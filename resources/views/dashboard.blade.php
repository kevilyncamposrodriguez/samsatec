<?php $interno = true ?>
<x-app-layout>
    @push('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">@endpush
    @section('title', 'Inicio')

    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-xl-8">
            <!-- begin card -->
            <div class="card border-0 text-white mb-3 overflow-hidden" style="background-color: #192743;">
                <!-- begin card-body -->
                <div class="card-body">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-7 -->
                        <div class="col-xl-4 col-lg-8">
                            <!-- begin title -->
                            <div class="mb-3 f-s-20">
                                <b>MES ACTUAL</b>
                            </div>
                            <!-- end title -->
                            <!-- begin total-sales -->
                            <div class="d-flex mb-1">
                                <div class="f-s-20 m-b-5 f-w-600 p-b-1 mb-3">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
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
                                    <div class="f-s-14 mb-3">
                                        <h4>Ventas Totales</h4>
                                    </div>
                                    <div class="f-s-18 m-b-5 f-w-600 p-b-1 mb-2">₡ <span data-animation="number" data-value="0.00">{{ $curMonth_sales }}</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5 mb-2">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-green-600" data-animation="width" data-value="55%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4 mb-2">
                                    <div class="f-s-14 mb-3">
                                        <h4>Gastos Totales</h4>
                                    </div>
                                    <div class="f-s-18 m-b-5 f-w-600 p-b-1 mb-2">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5 mb-2">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-red" data-animation="width" data-value="55%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4 mb-2">
                                    <div class="f-s-14 mb-3">
                                        <h4>Compras Totales</h4>
                                    </div>
                                    <div class="f-s-18 m-b-5 f-w-600 p-b-1 mb-2">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5 mb-2">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-orange-darker" data-animation="width" data-value="55%" style="width: 0%"></div>
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
            <div class="widget widget-stats bg-red-600">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h4>COMPRA</h4>
                    </div>
                    <div class="stats-number">₡ {{ session('sale') }}</div>
                    <div class="stats-progress progress">
                        <div class="progress-bar" style="width: 40.5%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-xl-2 col-md-6">
            <div class="widget widget-stats bg-green-600">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h4> VENTA <h4>
                    </div>
                    <div class="stats-number">₡ {{ session('purchase') }}</div>
                    <div class="stats-progress progress">
                        <div class="progress-bar" style="width: 50.5%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
    <!-- begin row -->
    <div class="row" {{ Auth::user()->currentTeam->fe ?'':'hidden' }}>
        <!-- begin col-3 -->
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-white text-inverse">
                <div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-analytics"></i></div>
                <div class="stats-content">
                    <div class="stats-title text-inverse-lighter">Balance Tributado del mes</div>
                    <div class="stats-number">₡ 0.00</div>
                    <div class="stats-progress progress">
                        <div class="progress-bar" style="width: 0%;"></div>
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
                    <div class="stats-number">₡ 0.00</div>
                    <div class="stats-progress progress">
                        <div class="progress-bar" style="width: 0%;"></div>
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
                    <div class="stats-number">₡ 0.00</div>
                    <div class="stats-progress progress">
                        <div class="progress-bar" style="width: 0%;"></div>
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
                    <div class="stats-number">₡ 0.00</div>
                    <div class="stats-progress progress">
                        <div class="progress-bar" style="width: 0%;"></div>
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
                                <div class="f-s-22 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
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
                                    <div class="f-s-22 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-teal" data-animation="width" data-value="55%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de Gastos</div>
                                    <div class="f-s-20 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-red" data-animation="width" data-value="55%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <!-- end col-6 -->
                                <!-- begin col-6 -->
                                <div class="col-4">
                                    <div class="f-s-14 text-grey">Total de Compras</div>
                                    <div class="f-s-20 m-b-5 f-w-600 p-b-1">₡ <span data-animation="number" data-value="0.00">0.00</span></div>
                                    <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-orange" data-animation="width" data-value="55%" style="width: 0%"></div>
                                    </div>
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
    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-xl-12">
            <!-- begin row -->
            <div class="row">
                <!-- begin col-6 -->
                <div class="col-sm-12">
                    <!-- begin card -->
                    <div class="card border-0 text-white text-truncate mb-3" style="background-color: #192743;">
                        <!-- begin card-body -->
                        <div class="card-body">
                            <!-- begin title -->
                            <div class="mb-3 text-grey">
                                <b class="mb-3">Documentos electrónicos emitidos</b>
                                <span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Conversion Rate" data-placement="top" data-content="Percentage of sessions that resulted in orders from total number of sessions." data-original-title="" title=""></i></span>
                            </div>
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-red f-s-8 mr-2"></i>
                                    Documentos rechazados
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="262">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="3.79">0.00</span>%</div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-warning f-s-8 mr-2"></i>
                                    Documentos sin respuesta
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="11">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="3.85">0.00</span>%</div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-lime f-s-8 mr-2"></i>
                                    Documentos aceptados
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="57">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="2.19">0.00</span>%</div>
                                </div>
                            </div>
                            <!-- end percentage -->
                            <hr class="bg-white-transparent-2" />
                            <!-- begin row -->
                            <!-- begin title -->
                            <div class="mb-3 text-grey">
                                <b class="mb-3">Comprobantes electrónicos recibidos</b>
                                <span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Conversion Rate" data-placement="top" data-content="Percentage of sessions that resulted in orders from total number of sessions." data-original-title="" title=""></i></span>
                            </div>
                            <!-- begin info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-red f-s-8 mr-2"></i>
                                    Documentos rechazados
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="262">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="3.79">0.00</span>%</div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-warning f-s-8 mr-2"></i>
                                    Documentos sin procesar
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="11">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="3.85">0.00</span>%</div>
                                </div>
                            </div>
                            <!-- end info-row -->
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-lime f-s-8 mr-2"></i>
                                    Documentos aceptados
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="57">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="2.19">0.00</span>%</div>
                                </div>
                            </div>
                            <!-- begin info-row -->
                            <div class="d-flex mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-circle text-blue f-s-8 mr-2"></i>
                                    Documentos guardados
                                </div>
                                <div class="d-flex align-items-center ml-auto">
                                    <div class="text-grey f-s-11"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="57">0</span>%</div>
                                    <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" data-value="2.19">0.00</span>%</div>
                                </div>
                            </div>
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
    @push('scripts')
    <script src="/assets/plugins/d3/d3.min.js"></script>
    <script src="/assets/plugins/nvd3/build/nv.d3.js"></script>
    <script src="/assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
    <script src="/assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
    <script src="/assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="/assets/js/demo/dashboard-v2.js"></script>
    <script src="/assets/plugins/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/assets/plugins/moment/moment.js"></script>
    <script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/assets/js/demo/dashboard-v3.js"></script>
    @endpush
</x-app-layout>