<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.pay-method-information.create')
    @include('livewire.pay-method-information.update')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de métodos de pago</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nuevo método de pago" data-toggle="modal" wire:click.prevent="newReg()"><i class="fa fa-plus"></i> Nuevo método de pago</button>
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
                <livewire:pay-method-information.pay-methods-information-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>

<script>
    window.addEventListener('payMethodInformation_modal_show', event => {
        $('#payMethodInformationModal').modal('show');
    });
</script>