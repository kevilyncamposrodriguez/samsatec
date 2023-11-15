<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3 {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'text-center' }} m-t-20">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
    <!-- begin row -->
    <div class="row">

        <!-- begin col-6 -->
        <div class="col-xl-8">
            <!-- begin card -->
            <div class="card border-0 text-white mb-2 overflow-hidden" style="background-color: #192743;">
                <!-- begin card-body -->
                <div class="card-body">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-7 -->
                        <div class="col-xl-4 col-lg-8">
                            <!-- begin title -->
                            <div class="mb-2 f-s-20">
                                <b>MES ACTUAL</b>
                            </div>
                            <!-- end title -->
                            <!-- begin total-sales -->
                            <div class="d-flex mb-1">
                                <div class="f-s-20 m-b-5 f-w-600 p-b-1 mb-3">₡ <span data-animation="number" data-value="{{ number_format($curMonth_sales-$gastos-$compras, 2, '.', ',') }}">{{ number_format($curMonth_sales-$gastos-$compras, 2, '.', ',') }}</span></div>
                            </div>
                            <!-- end total-sales -->

                        </div>
                        <!-- end col-7 -->
                        <!-- begin col-5 -->
                        <div class="col-xl-8 col-lg-8 align-items-center ">
                            <!-- begin row -->
                            <div class="row text-truncate">
                                <!-- begin col-6 -->
                                <div class="col-4 mb-2">
                                    <div class="f-s-14 mb-2">
                                        <h4>Ventas Totales</h4>
                                    </div>
                                    <div class="f-s-18 m-b-5 f-w-600 p-b-1 mb-2">₡ <span data-animation="number" data-value="{{ number_format($curMonth_sales, 2, '.', ',') }}">{{ number_format($curMonth_sales, 2, '.', ',') }}</span></div>
                                    <div wire:ignore class="progress progress-xs rounded-lg bg-dark-darker m-b-5 mb-2">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-green-600" data-animation="width" data-value="100%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4 mb-2">
                                    <div class="f-s-14 mb-2">
                                        <h4>Gastos Totales</h4>
                                    </div>
                                    <div class="f-s-18 m-b-5 f-w-600 p-b-1 mb-2">₡ <span data-animation="number" data-value="{{ number_format($gastos, 2, '.', ',') }}">{{ number_format($gastos, 2, '.', ',') }}</span></div>
                                    <div wire:ignore class="progress progress-xs rounded-lg bg-dark-darker m-b-5 mb-2">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-red" data-animation="width" data-value="100%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4 mb-2">
                                    <div class="f-s-14 mb-2">
                                        <h4>Compras Totales</h4>
                                    </div>
                                    <div class="f-s-18 m-b-5 f-w-600 p-b-1 mb-2">₡ <span data-animation="number" data-value="{{ number_format($compras, 2, '.', ',') }}">{{ number_format($compras, 2, '.', ',') }}</span></div>
                                    <div wire:ignore class="progress progress-xs rounded-lg bg-dark-darker m-b-5 mb-2">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-orange-darker" data-animation="width" data-value="100%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                            </div>
                        </div>
                        <!-- end col-5 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-6 -->
        <!-- begin col-3 -->
        <div class="col-xl-2 col-md-6">
            <div class="widget widget-stats bg-red">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h4>COMPRA</h4>
                    </div>
                    <div class="stats-number">₡ {{ session('sale') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-2 col-md-6">
            <div class="widget widget-stats" style="background-color: #1B7940;">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h4> VENTA <h4>
                    </div>
                    <div class="stats-number">₡ {{ session('purchase') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
    @if(Auth::user()->currentTeam->fe)
    <!-- begin row -->
    <div class="row">
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-analytics"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Balance Tributado del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($curMonth_sales_trib-$compras_trib-$gastos_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-add-circle-outline"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Ventas tributadas del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($curMonth_sales_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-remove-circle-outline"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Gastos tributados del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($gastos_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-remove-circle-outline"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Compras tributados del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($compras_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
    @endif
    @if(Auth::user()->currentTeam->plan_id > 1)
    <!-- begin row -->
    <div class="row">
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-analytics"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Balance Interno del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($curMonth_sales_int-$compras_no_trib-$gastos_no_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-add-circle-outline"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Ventas internas del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($curMonth_sales_int, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-remove-circle-outline"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Gastos internos del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($gastos_no_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-remove-circle-outline"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Compras internas del mes</div>
                    <div class="stats-number f-s-18">₡ {{ number_format($compras_no_trib, 2, '.', ',') }}</div>
                    <div class="stats-progress progress">
                        <div wire:ignore class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="{{ $class = ($interno)?'col-xl-6':'col-xl-12' }}">
            <!-- begin card -->
            <div class="card border-0 text-white mb-2 overflow-hidden" style="background-color: #001760;">
                <!-- begin card-body -->
                <div class="card-body">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-7 -->
                        <div class="{{ $class = ($interno)?'col-xl-9 col-lg-8':'col-xl-7 col-lg-12' }}">
                            <!-- begin title -->
                            <div class="mb-2 text-grey f-s-18">
                                <b>Balance General</b>
                                <span>
                                    <i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Balance General" data-placement="top" data-content="Resultado de las ventas menos los gastos correspondientes a la compañia"></i>
                                </span>
                            </div>
                            <!-- end title -->
                            <!-- begin total-sales -->
                            <div class="d-flex mb-1 f-w-800">
                                <div class="f-s-18 m-b-5 mt-1 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_sales-$t_compras-$t_gastos, 2, '.', ',') }}">{{ number_format($t_sales-$t_compras-$t_gastos, 2, '.', ',') }}</span></div>
                                <div class="row ml-4 m-b-5 col-md-6">
                                    <label class="col-md-4 col-form-label">Año: </label>
                                    <select class="form-control col-md-8" name="ano" id="ano" wire:model="ano">
                                        <option style="color: black;" value="">Todos...</option>
                                        @foreach($anos as $ano)
                                        <option style="color: black;" value="{{ $ano['ANO'] }}">{{ $ano['ANO'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- end total-sales -->
                            <hr class="bg-white-transparent-2" />
                            <!-- begin row -->
                            <div class="row text-truncate">
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de ventas</div>
                                    <div class="f-s-12 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_sales, 2, '.', ',') }}">{{ number_format($t_sales, 2, '.', ',') }}</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-green-600" data-animation="width" data-value="100%" style="width: 100%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de Gastos</div>
                                    <div class="f-s-12 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_gastos, 2, '.', ',') }}">{{ number_format($t_gastos, 2, '.', ',') }}</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-red" data-animation="width" data-value="100%" style="width: 100%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de Compras</div>
                                    <div class="f-s-12 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_compras, 2, '.', ',') }}">{{ number_format($t_compras, 2, '.', ',') }}</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-orange" data-animation="width" data-value="100%" style="width: 100%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end col-7 -->
                        <!-- begin col-5 -->
                        <div class="col-xl-3 col-lg-3 align-items-center d-flex justify-content-center text-center">
                            <img src="../assets/img/svg/img-1.svg" width="200px" class="d-none d-lg-block" />
                        </div>
                        <!-- end col-5 -->
                    </div>
                    <!-- end row -->

                </div>
                <!-- end card-body -->

            </div>
            <!-- end card -->
        </div>
        <!-- end col-6 -->
        <!-- begin col-6 -->
        <div class="col-xl-6">
            <!-- begin row -->
            <div class="row">
                @if(Auth::user()->currentTeam->fe)
                <!-- begin col-6 -->
                <div class="col-sm-6">
                    <!-- begin card -->
                    <div class="card border-0 text-white text-truncate mb-3" style="background-color: #001760;">
                        <!-- begin card-body -->
                        <div class="card-body">
                            <!-- begin title -->
                            <div class="mb-3 text-grey f-s-18">
                                <b class="mb-3">Situacion tributaria</b>
                                <span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Conversion Rate" data-placement="top" data-content="Documentos emitidos de forma electrónica y verificados por el ministerio de hacienda." data-original-title="" title=""></i></span>
                            </div>
                            <!-- end title -->
                            <!-- begin conversion-rate -->
                            <div class="d-flex align-items-center mb-2">
                                <div class="f-s-18 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_sales_trib-$t_compras_trib-$t_gastos_trib, 2, '.', ',') }}">{{ number_format($t_sales_trib-$t_compras_trib-$t_gastos_trib, 2, '.', ',') }}</span></div>
                            </div>
                            <!-- end conversion-rate -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-1">
                                <div class="d-flex align-items-center f-s-14">
                                    <i class="fa fa-circle text-green f-s-8 mr-2"></i>
                                    Ventas
                                </div>
                                <div class="d-flex align-items-center f-s-14">
                                    <div class="width-50 text-right pl-2 f-w-600 ">₡ <span data-animation="number" data-value="{{ number_format($t_sales_trib, 2, '.', ',') }}">{{ number_format($t_sales_trib, 2, '.', ',') }}</span></div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-1">
                                <div class="d-flex align-items-center f-s-14">
                                    <i class="fa fa-circle text-red f-s-8 mr-2"></i>
                                    Gastos
                                </div>
                                <div class="d-flex align-items-center f-s-14">
                                    <div class="width-50 text-right pl-2 f-w-600">₡ <span data-animation="number" data-value="{{ number_format($t_gastos_trib, 2, '.', ',') }}">{{ number_format($t_gastos_trib, 2, '.', ',') }}</span></div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-1">
                                <div class="d-flex align-items-center f-s-14">
                                    <i class="fa fa-circle text-orange f-s-8 mr-2"></i>
                                    Compras
                                </div>
                                <div class="d-flex align-items-center f-s-14">
                                    <div class="width-50 text-right pl-2 f-w-600">₡ <span data-animation="number" data-value="{{ number_format($t_compras_trib, 2, '.', ',') }}">{{ number_format($t_compras_trib, 2, '.', ',') }}</span></div>
                                </div>
                            </div>
                            <!-- end info-row -->
                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col-6 -->
                @endif
                <!-- begin col-6 -->
                <div class="{{ Auth::user()->currentTeam->fe ? 'col-sm-6' : 'col-sm-12' }}">
                    <!-- begin card -->
                    <div class="card border-0 text-white text-truncate mb-3" style="background-color: #001760;">
                        <!-- begin card-body -->
                        <div class="card-body">
                            <!-- begin title -->
                            <div class="mb-3 text-grey f-s-18">
                                <b class="mb-3">Situacion Interna</b>
                                <span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Conversion Rate" data-placement="top" data-content="Documentos emitidos de forma electrónica y verificados por el ministerio de hacienda." data-original-title="" title=""></i></span>
                            </div>
                            <!-- end title -->
                            <!-- begin conversion-rate -->
                            <div class="d-flex align-items-center mb-2">
                                <div class="f-s-18 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_sales_no_trib-$t_compras_no_trib-$t_gastos_no_trib, 2, '.', ',') }}">{{ number_format($t_sales_no_trib-$t_compras_no_trib-$t_gastos_no_trib, 2, '.', ',') }}</span></div>

                            </div>
                            <!-- end conversion-rate -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-1">
                                <div class="d-flex align-items-center f-s-14">
                                    <i class="fa fa-circle text-green f-s-8 mr-2"></i>
                                    Ventas
                                </div>
                                <div class="d-flex align-items-center f-s-14">
                                    <div class="width-50 text-right pl-2 f-w-600">₡ <span data-animation="number" data-value="{{ number_format($t_sales_no_trib, 2, '.', ',') }}">{{ number_format($t_sales_no_trib, 2, '.', ',') }}</span></div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-1">
                                <div class="d-flex align-items-center f-s-14">
                                    <i class="fa fa-circle text-red f-s-8 mr-2"></i>
                                    Gastos
                                </div>
                                <div class="d-flex align-items-center f-s-14">
                                    <div class="width-50 text-right pl-2 f-w-600">₡ <span data-animation="number" data-value="{{ number_format($t_gastos_no_trib, 2, '.', ',') }}">{{ number_format($t_gastos_no_trib, 2, '.', ',') }}</span></div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-1">
                                <div class="d-flex align-items-center f-s-14">
                                    <i class="fa fa-circle text-orange f-s-8 mr-2"></i>
                                    Compras
                                </div>
                                <div class="d-flex align-items-center f-s-14">
                                    <div class="width-50 text-right pl-2 f-w-600">₡ <span data-animation="number" data-value="{{ number_format($t_compras_no_trib, 2, '.', ',') }}">{{ number_format($t_compras_no_trib, 2, '.', ',') }}</span></div>
                                </div>
                            </div>
                            <!-- end info-row -->
                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col-6 -->
            </div>
            <!-- end row -->

        </div>
        <!-- end col-6 -->
    </div>
    <!-- end row -->
    @else
    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="{{ $class = ($interno)?'col-xl-12':'col-xl-12' }}">
            <!-- begin card -->
            <div class="card border-0 text-white mb-3 overflow-hidden" style="background-color: #001760;">
                <!-- begin card-body -->
                <div class="card-body">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-7 -->
                        <div class="{{ $class = ($interno)?'col-xl-7 col-lg-8':'col-xl-7 col-lg-12' }}">
                            <!-- begin title -->
                            <div class="mb-3 text-grey f-s-18">
                                <b>Balance General</b>
                                <span class="ml-2">
                                    <i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Balance General" data-placement="top" data-content="Resultado de las ventas menos los gastos correspondientes a la compañia"></i>
                                </span>
                            </div>
                            <!-- end title -->
                            <!-- begin total-sales -->
                            <div class="d-flex mb-1 f-w-800">
                                <div class="f-s-22 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_sales-$t_compras-$t_gastos, 2, '.', ',') }}">{{ number_format($t_sales-$t_compras-$t_gastos, 2, '.', ',') }}</span></div>
                                <div class="ml-auto mt-n1 mb-n1">
                                    <div id="total-sales-sparkline"></div>
                                </div>
                            </div>
                            <!-- end total-sales -->
                            <hr class="bg-white-transparent-2" />
                            <!-- begin row -->
                            <div class="row text-truncate">
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de ventas</div>
                                    <div class="f-s-22 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_sales, 2, '.', ',') }}">{{ number_format($t_sales, 2, '.', ',') }}</span></div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de Gastos</div>
                                    <div class="f-s-20 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_gastos, 2, '.', ',') }}">{{ number_format($t_gastos, 2, '.', ',') }}</span></div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de Compras</div>
                                    <div class="f-s-20 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="{{ number_format($t_compras, 2, '.', ',') }}">{{ number_format($t_compras, 2, '.', ',') }}</span></div>
                                </div>
                                <!-- end col-6 -->
                            </div>
                            <!-- end row -->

                        </div>
                        <!-- end col-7 -->
                        <!-- begin col-5 -->
                        <div class="col-xl-5 col-lg-4 align-items-center d-flex justify-content-center">
                            <img src="../assets/img/svg/img-1.svg" height="150px" class="d-none d-lg-block" />
                        </div>
                        <!-- end col-5 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card-body -->

            </div>
            <!-- end card -->
        </div>
        <!-- end col-6 -->
    </div>
    @endif

    @else
    <div class="text-center" style="margin-top: 4em;">
        <strong>
            <h1> Bienvenido a</h1>
        </strong>
        <img class="logo" style="margin: auto;" src="/assets/img/logo_.png" width="200px" alt="Grupo Samsa" />
        <br>
        <div style="font-size: 30px;"><strong>¡{{ Auth::user()->name }}!</strong></div>

    </div>

    @endif

</div>