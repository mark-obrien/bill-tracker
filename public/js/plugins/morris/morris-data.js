$(function() {

    var data=[];
    alert("begin loop");
    $.getJSON('/bill/bill-json',function(data){
        var i=0;
        for(i=0;i<data.length;i++){
            data.push({
                x: data.id,
                y: data.id,
                z: data.id
            });
        }
        // The data in haikus is available here becuase
        //    this alert() doesn't run until the response is received.
        console.log(data);
    });



    var nReloads = 0;
    function data(offset) {
        var ret = [];
        for (var x = 0; x <= 360; x += 10) {
            var v = (offset + x) % 360;
            ret.push({
                x: x,
                y: Math.sin(Math.PI * v / 180).toFixed(4),
                z: Math.cos(Math.PI * v / 180).toFixed(4)
            });
        }
        return ret;
    }
    var graph = Morris.Line({
        element: 'morris-area-chart',
        data: data(0),
        xkey: 'x',
        ykeys: ['y', 'z'],
        labels: ['sin()', 'cos()'],
        parseTime: false,
        ymin: -1.0,
        ymax: 1.0,
        hideHover: true
    });
    function update() {
        nReloads++;
        graph.setData(data(5 * nReloads));
        $('#reloadStatus').text(nReloads + ' reloads');
    }
    setInterval(update, 100);

    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Download Sales",
            value: 12
        }, {
            label: "In-Store Sales",
            value: 30
        }, {
            label: "Mail-Order Sales",
            value: 20
        }],
        resize: true
    });

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90
        }, {
            y: '2007',
            a: 75,
            b: 65
        }, {
            y: '2008',
            a: 50,
            b: 40
        }, {
            y: '2009',
            a: 75,
            b: 65
        }, {
            y: '2010',
            a: 50,
            b: 40
        }, {
            y: '2011',
            a: 75,
            b: 65
        }, {
            y: '2012',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        hideHover: 'auto',
        resize: true
    });

});
