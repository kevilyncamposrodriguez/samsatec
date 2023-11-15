<div>
    <div class="content-wrapper ">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Lista de Referidos</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">Lista de Referidos</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.col -->
        <div class="col-md-12">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user ">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header text-center bg-blue">
                    <h3 class="text-white">{{ Auth::user()->currentTeam->name }}</h3>
                    <h5 class="widget-user-desc">{{ Auth::user()->currentTeam->email_company }}</h5>
                </div>
                <div class="" style="margin: auto;">
                    @if(Auth::user()->currentTeam->logo_url != '')
                    <img class="img elevation-2" src="{{ Auth::user()->currentTeam->logo_url }}" width="100px" alt="{{ Auth::user()->currentTeam->name }}">
                    @endif
                </div>
                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-sm-6 border-right">
                            <div class="description-block">
                                <h5 class="description-header">₡{{ number_format($total_n1,2,',',' ') }}</h5>
                                <span class="description-text">NIVEL 1</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <div class="description-block">
                                <h5 class="description-header">₡{{ number_format($total_n2,2,',',' ') }}</h5>
                                <span class="description-text">NIVEL 2</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <div class="text-center bg-blue">
                        <h3 class="widget-user-username text-white"><strong>Ganacias Mensuales</strong></h3>
                        <h5 class="widget-user-username">₡{{ number_format($totalNs,2,',',' ') }}</h5>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <!-- /.col -->
            <!-- Main content -->
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title"><strong>Nivel {{$level}}</strong> -
                                    @foreach($references as $reference)
                                    <a href="#" wire:click="backLevel({{ $reference['id'] }} , {{ $reference['level'] }})">{{ $reference['name'] }} </a>
                                    @endforeach
                                </h5>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-header">

                                <div class="row justify-content-center">
                                    <div class="input-group input-group-sm" style="width: 50%;">
                                        <input type="text" name="search" class="form-control float-right" placeholder="Buscar" wire:model="search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Referidos</th>
                                            <th class="text-center">Plan</th>
                                            <th class="text-center">Sub-Referencias</th>
                                            <th class="text-center">Ingreso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allReferrals as $referred)

                                        <tr class="gradeU">
                                            <td width="1%" class="text-center">{{ $referred->id }}</td>
                                            <td width="10%" class="text-center">{{ $referred->name }}</td>
                                            <td width="10%" class="text-center">{{ $referred->mount_refered }}</td>
                                            <td width="15%" class="text-center">@if($referred->referes)
                                                @if($level<'2') <a wire:click="nextLevel({{ $referred->id }}, {{ $level+1 }})" class="btn btn-blue text-white">{{ $referred->referes }} </a>
                                                    @else{{ $referred->referes }}
                                                    @endif
                                                    @else -
                                                    @endif</td>
                                            <td width="15%" class="text-center">{{ $this->levelCalculate($referred->id) }}</td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <!-- /.card-body -->
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{ $allReferrals->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- Main Footer -->
            <footer class="main-footer">
                <strong>Link de referencia: <a href="javascript:getlink();" id="copy">{{ $link = asset('membership/1/'.Auth::user()->currentTeam->referral_code) }}</a>.</strong>
                <span id="copiado" style="visibility: hidden;"> Copiado</span>
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b> 1.0
                </div>
            </footer>
            <script>
                function getlink() {
                    var aux = document.createElement('input');
                    aux.setAttribute('value', document.getElementById('copy').innerText);
                    document.body.appendChild(aux);
                    aux.select();
                    document.execCommand('copy');
                    document.body.removeChild(aux);
                    document.getElementById('copiado').style.visibility = 'visible';
                    setTimeout(function() {
                        // Declaramos la capa mediante una clase para ocultarlo
                        document.getElementById('copiado').style.visibility = 'hidden';
                    }, 2000);
                }
            </script>
        </div>