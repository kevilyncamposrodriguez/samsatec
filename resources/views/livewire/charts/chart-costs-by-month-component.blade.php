<div class="col-xl-4">
    <div class="panel panel-inverse" data-sortable-id="chart-js-1">
        <div class="panel-heading">
            <!-- Cambiar titulo segun la grafica -->
            <h4 class="panel-title">Costos por mes</h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <!-- dentro de esta clase agregan los filtros -->
            <div class="col-sm-4 mb-4" wire:ignore>
                <select multiple class="form-control" id="monthsCosts">
                    @foreach($allMonths as $month)
                    <option style="color: black;" value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <!-- aqui va la grafica, el id es el nombre de la grafica -->
                <div id="chartBySales"></div>
            </div>
        </div>
    </div>
</div>
<!-- end panel -->

<<<<<<< HEAD
=======
@push('scripts')
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="/assets/plugins/apexcharts/dist/apexcharts.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        $('#monthsCosts').select2();
        $('#monthsCosts').on('change', function(e) {
            livewire.emit('updatedMonths', $('#monthsCosts').select2("val"))
        });
        $('#monthsCosts').val(@this.get('monthsCosts')).trigger('change');
        renderChart(@json($chartData));
    });


    window.addEventListener('renderChartCosts', ({
        detail
    }) => {

        renderChart(detail.data);
    }, false);

    const renderChart = chartData => {

        const ventas = chartData.map(item => item.amount.toFixed(0));
        const labels = chartData.map(item => item.month);
        //este es el nombre de la grafica que esta en el id de html
        const chart = new ApexCharts(document.querySelector("#chartByCost"), {
            chart: {
                height: 350,
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
>>>>>>> origin/Kevin
