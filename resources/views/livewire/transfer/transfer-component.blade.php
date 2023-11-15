<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.transfer.create')
    @include('livewire.transfer.update')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Transferencias</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nuevo Transferencia" wire:click.prevent="resetInputFields()" data-toggle="modal" data-target="#transferModal"><i class="fa fa-plus"> Transferencia</i></button>
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
            <div class="panel-body" wire:ignore>
                <link rel="stylesheet" href="{{ mix('css/app.css') }}">
                <livewire:transfer.transfers-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>
</div>