<div class="col-xl-4">
    <div class="panel panel-inverse" data-sortable-id="chart-js-1">
        <div class="panel-heading">
            <!-- Cambiar titulo segun la grafica -->
            <h4 class="panel-title">Ventas por mes 2</h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <!-- dentro de esta clase agregan los filtros -->
            <div class="col-sm-4 mb-4" wire:ignore>
                <select multiple class="form-control" id="monthsSales">
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

