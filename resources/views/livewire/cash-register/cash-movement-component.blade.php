<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif

    <div class="panel panel-inverse">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h1 class="panel-title font-medium uppercase">Registro de Cajas</h1>
            <a class="btn btn-blue m-r-5 uppercase" title="Abrir caja" data-toggle="modal" data-target="#OCRModal">Abrir Caja</a>
        </div>
        <!-- begin panel-body -->
        <div class="panel-body">
            <livewire:cash-register.cash-movement-table />
        </div>
        <!-- end panel-body -->
    </div>
</div>