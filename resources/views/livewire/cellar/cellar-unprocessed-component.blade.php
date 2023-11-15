<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.cellar.update')

    <div class="panel panel-inverse">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h1 class="panel-title font-medium uppercase">Documentos Sin Procesar</h1>
        </div>
        <!-- begin panel-body -->
        <div class="panel-body">
            <livewire:cellar.cellar-unprocesseds-table />
        </div>
        <!-- end panel-body -->
    </div>
</div>