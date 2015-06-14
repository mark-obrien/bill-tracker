var morris = {

    drawArea : function drawAreaChart() {
        var billId = $('#morris-area-chart').data('bill');
        $.getJSON("/bill/json/payment/" + billId, function (json) {
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
                labels: ['Balance'],
                pointSize: 2,
                hideHover: 'auto'
            });
        });
    },

    drawDonut:function drawDonutChart() {
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


}
