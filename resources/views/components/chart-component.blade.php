<div id="chart"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: {!! json_encode($chartData) !!},
            chart: {
                type: 'line',
                height: 350
            },
            // Add additional chart options as needed...
        };

        var chart = new ApexCharts(document.querySelector('#chart'), options);
        chart.render();
    });
</script>
