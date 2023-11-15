<div>
    <x-banner-buton></x-banner-buton>
    @livewire('buyer.create-buyer-component')
    @include('livewire.buyer.update')
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Compradores</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nuevo Comprador" data-toggle="modal" data-target="#buyerModal"><i class="fa fa-plus"> Nuevo Comprador</i></button>
                </div>
            </div>
            <!-- end panel-heading -->
            @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <!-- begin panel-body -->
            <div class="panel-body">
                <livewire:buyer.buyers-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>