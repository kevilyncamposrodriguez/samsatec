<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.discount.create')
    @include('livewire.discount.update')
    <!-- begin panel -->
    <div class="col-xl-12" wire:ignore data-sortable>
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Descuentos</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nueva Descuento" data-toggle="modal" data-target="#discountModal"><i class="fa fa-plus"> Nuevo descuento</i></button>
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
                <livewire:discount.discounts-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>
</div>