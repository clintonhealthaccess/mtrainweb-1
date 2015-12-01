<script type="text/javascript">
    $(function () {
      $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Stacked bar chart'
        },
        xAxis: {
            categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total fruit consumption'
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        series: [{
            name: 'John',
            data: [5, 3, 4, 7, 2]
        }, {
            name: 'Jane',
            data: [2, 2, 3, 2, 1]
        }, {
            name: 'Joe',
            data: [3, 4, 4, 2, 5]
        },{
            name: 'Jack',
            data: [3, 3, 6, 2, 4]
        }
        ]
    });
});
	</script>


<div id="container" style="min-width: 10px; height: 350px;max-width: 150px;margin: 0 auto;border:1px solid #000;"></div>