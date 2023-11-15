<div>
<x-banner-buton></x-banner-buton>
    @include('livewire.system-pay.pay')
    @include('livewire.system-pay.view')
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
                <h4 class="panel-title">Historial de pagos</h4>
                <div class="panel-heading-btn">
                    <div class="btn-group m-r-5 m-b-1">

                    </div>
                </div>
            </div>
            
            <!-- begin panel-body -->
            <div class="panel-body">
                <livewire:system-pay.system-pays-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>
<script>
    window.addEventListener('viewSytemPay_modal_hide', event => {
        $('#viewSytemPayModal').modal('hide');
    });
    window.addEventListener('paySytemPay_modal_hide', event => {
        $('#paySytemPayModal').modal('hide');
    });
</script>