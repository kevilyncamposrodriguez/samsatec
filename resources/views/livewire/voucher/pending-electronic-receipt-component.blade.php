<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    <!-- begin panel -->
    <div class="col-xl-12">
        <!-- begin panel -->
        <div class="panel panel-inverse">
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Pendientes de Comprobante Electrónico</h4>
                    <div class="btn-group m-r-5 m-b-1">
                        <div class="dropdown-menu dropdown-menu-right uppercase">
                        </div>
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
                    <livewire:voucher.pending-electronic-receipt-table />
                </div>
                <!-- end panel-body -->
            </div>
        </div>
    </div>
</div>