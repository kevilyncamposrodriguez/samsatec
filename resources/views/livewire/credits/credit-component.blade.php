<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.credits.create')
    @include('livewire.credits.update')
    @include('livewire.credits.payCredit')

    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Créditos Financieros</h4>
                <div class="btn-group m-r-5 m-b-1">
                    <button class="btn btn-md  btn-blue" title="Nuevo Credito" data-toggle="modal" data-target="#creditModal"><i class="fa fa-plus"> Nuevo credito</i></button>
                </div>
            </div>
            <div class="panel-body">
                <livewire:credits.credits-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>
</div>