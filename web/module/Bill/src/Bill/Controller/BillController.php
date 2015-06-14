<?php
 
namespace Bill\Controller;
 
use Application\Controller\AbstractBillController;
use Zend\View\Model\ViewModel;
use Application\Entity\Bill;
use Bill\Form\BillForm;
use Application\Entity\Payment;
use Bill\Form\PaymentForm;
use Doctrine\ORM\Query;

class BillController extends AbstractBillController
{
    protected $_role = self::ADMIN_ROLE;

    protected $em;
 
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
 
    public function indexAction()
    {
        return new ViewModel(array(
            'bills' => $this->getRepo('Application\Entity\Bill')->getActiveBills(),
        ));
    }
 
    public function addAction()
    {
        $form = new BillForm();
        $form->get('submit')->setValue('Add');
 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $bill = new Bill();
            $form->setInputFilter($bill->getInputFilter());
            $form->setData($request->getPost());
 
            if ($form->isValid()) {
                $bill->exchangeArray($form->getData());
                $this->getEntityManager()->persist($bill);
                $this->getEntityManager()->flush();
 
                // Redirect to list of bills
                return $this->redirect()->toRoute('bill');
            }
        }
        return array(
            'form' => $form
        );
    }
 
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('bill', array(
                'action' => 'add'
            ));
        }
 
        $bill = $this->getEntityManager()->find('Application\Entity\Bill', $id);
        if (!$bill) {
            return $this->redirect()->toRoute('bill', array(
                'action' => 'index'
            ));
        }
 
        $form  = new BillForm();
        $form->bind($bill);
        $form->get('submit')->setAttribute('value', 'Edit');
 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($bill->getInputFilter());
            $form->setData($request->getPost());
 
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
 
                // Redirect to list of bills
                return $this->redirect()->toRoute('bill');
            }
        }
 
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
 
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('bill');
        }
 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
 
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $bill = $this->getEntityManager()->find('Application\Entity\Bill', $id);
                if ($bill) {
                    $this->getEntityManager()->remove($bill);
                    $this->getEntityManager()->flush();
                }
            }
 
            return $this->redirect()->toRoute('bill');
        }
 
        return array(
            'id'    => $id,
            'bill' => $this->getEntityManager()->find('Application\Entity\Bill', $id)
        );
    }

    public function paymentAction()
    {

        $bill_id = (int) $this->params()->fromRoute('id');

        $form = new PaymentForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $payment = new Payment();
            $bill_obj = $this->getEntityManager()->find('Application\Entity\Bill', $bill_id);

            $payment->addBill($bill_obj);
            $form->setInputFilter($payment->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $payment->exchangeArray($form->getData());
                $this->getEntityManager()->persist($payment);
                $this->getEntityManager()->flush();

                // Redirect to list of bills
                return $this->redirect()->toRoute('bill');
            }
        }
        return array(
            'form' => $form,
            'id' => $bill_id
        );
    }

    public function paymentHistoryAction()
    {

        $bill_id = (int) $this->params()->fromRoute('id');

        $em = $this->getEntityManager();
        $repo = $em->getRepository('\Application\Entity\Bill');
        $query = $repo->createQueryBuilder('bill')
            ->leftJoin('bill.payments', 'payments')
            ->where('bill.id = payments.bill')
            ->andWhere('bill.id = :bill_id')
            ->setParameter('bill_id', $bill_id);

        $bill = $query->getQuery()->getOneOrNullResult();

        if(empty($bill)){
            $this->flashMessenger()->addErrorMessage("No Payments Recorded");
            return $this->redirect()->toRoute('bill');
        }

        return array(
            'bill' => $bill,
        );
    }
}
