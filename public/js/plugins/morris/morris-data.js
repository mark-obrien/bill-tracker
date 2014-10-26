$(function() {

    function drawAreaChart() {
        $.getJSON("/bill/json/payment", function (json) {
            var billAreaChart = [];
            var billJson = json;

            for (var i = 0; i < billJson.length; i++) {
                var areaItem = {
                    "x": billJson[i].date_json,
                    "y":billJson[i].running_balance
                };

                billAreaChart.push(areaItem);
            }

            Morris.Area({
                element: 'morris-area-chart',
                data: billAreaChart,
                xkey: 'x',
                ykeys: ['y'],
                pointSize: 2
            });
        });
    }

    function drawDonutChart() {
        $.getJSON("/bill/json/bill", function (json) {
            var billDonut = [];
            var billJson = json;

            for (var i = 0; i < billJson.length; i++) {
                var item = {
                    "label": billJson[i].creditor,
                    "value":billJson[i].balance
                };

                billDonut.push(item);
                console.log(billDonut);
            }

            Morris.Donut({
                element: 'morris-donut-chart',
                data: billDonut,
                resize: true
            });
        });
    }

    drawAreaChart();
    drawDonutChart();

    //Morris.Donut({
    //    element: 'morris-donut-chart',
    //    data: [{
    //        label: "Download Sales",
    //        value: 12
    //    }, {
    //        label: "In-Store Sales",
    //        value: 30
    //    }, {
    //        label: "Mail-Order Sales",
    //        value: 20
    //    }],
    //    resize: true
    //});

    //Morris.Bar({
    //    element: 'morris-bar-chart',
    //    data: [{
    //        y: '2006',
    //        a: 100,
    //        b: 90
    //    }, {
    //        y: '2007',
    //        a: 75,
    //        b: 65
    //    }, {
    //        y: '2008',
    //        a: 50,
    //        b: 40
    //    }, {
    //        y: '2009',
    //        a: 75,
    //        b: 65
    //    }, {
    //        y: '2010',
    //        a: 50,
    //        b: 40
    //    }, {
    //        y: '2011',
    //        a: 75,
    //        b: 65
    //    }, {
    //        y: '2012',
    //        a: 100,
    //        b: 90
    //    }],
    //    xkey: 'y',
    //    ykeys: ['a', 'b'],
    //    labels: ['Series A', 'Series B'],
    //    hideHover: 'auto',
    //    resize: true
    //});

});
