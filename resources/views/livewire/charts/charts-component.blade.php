<div>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    <!-- panel principal se mantiene igual -->
    <div class="row">@livewire('charts.chart-costs-by-month-component')
        @livewire('charts.chart-sales-by-month-component')
    </div>
</div>
@push('scripts')
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="/assets/plugins/apexcharts/dist/apexcharts.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        $('#monthsSales').select2();
        $('#monthsSales').on('change', function(e) {
            livewire.emit('updatedMonthsSales', $('#monthsSales').select2("val"))
        });
        $('#monthsSales').val(@this.get('monthsSales')).trigger('change');
        renderChartSales(@json($chartDataCosts));


        //====================================================//

        $('#monthsCosts').select2();
        $('#monthsCosts').on('change', function(e) {
            livewire.emit('updatedMonthsCosts', $('#monthsCosts').select2("val"))
        });
        $('#monthsCosts').val(@this.get('monthsCosts')).trigger('change');
        renderChartCosts(@json($chartDataSales));
    });


    window.addEventListener('renderChartSales', ({
        detail
    }) => {

        renderChartSales(detail.data);
    }, false);

    const renderChartSales = chartDataSales => {

        const ventas = chartDataSales.map(item => item.amount.toFixed(0));
        const labels = chartDataSales.map(item => item.month);
        //este es el nombre de la grafica que esta en el id de html
        const chart = new ApexCharts(document.querySelector("#chartBySales"), {
            chart: {
                height: 250,
                type: 'line',
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: [COLOR_DARK],
            series: [{
                name: 'Ventas',
                data: ventas
            }],
            xaxis: {
                categories: labels,
                axisBorder: {
                    show: true,
                    color: COLOR_SILVER_TRANSPARENT_5,
                    height: 1,
                    width: '100%',
                    offsetX: 0,
                    offsetY: -1
                },
                axisTicks: {
                    show: true,
                    borderType: 'solid',
                    color: COLOR_SILVER,
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                }
            }
        });

        chart.render();
    }
//============================Aqui comienza el otro mae//
    window.addEventListener('renderChartCosts', ({
        detail
    }) => {

        renderChartCosts(detail.data);
    }, false);

    const renderChartCosts = chartDataCosts => {

        const ventas = chartDataCosts.map(item => item.amount.toFixed(0));
        const labels = chartDataCosts.map(item => item.month);
        //este es el nombre de la grafica que esta en el id de html
        const chart = new ApexCharts(document.querySelector("#chartByCost"), {
            chart: {
                height: 250,
                type: 'line',
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: [COLOR_DARK],
            series: [{
                name: 'Ventas',
                data: ventas
            }],
            xaxis: {
                categories: labels,
                axisBorder: {
                    show: true,
                    color: COLOR_SILVER_TRANSPARENT_5,
                    height: 1,
                    width: '100%',
                    offsetX: 0,
                    offsetY: -1
                },
                axisTicks: {
                    show: true,
                    borderType: 'solid',
                    color: COLOR_SILVER,
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                }
            }
        });

        chart.render();
    }
</script>

@endpush