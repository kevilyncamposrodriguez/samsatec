<div>
    <div class="text-center m-t-40" style="background-color: #192743;">
        <h1 class="page-header mb-3 text-white">{{ Auth::user()->currentTeam->name }}</h1>
        <h1 class=" text-white"> Conexión Banco Ridivi </h1>
        <form class="form-horizontal form-bordered">
            <div class="form-group row">
                <div class="col-lg-3 col-xl-3">
                </div>
                <div class="col-lg-2 col-xl-2">
                    <h4 class="text-white m-b-20 m-t-10 col-xl-12">Usuario: </h4>
                    <h4 class="text-white m-b-10 m-t-10 col-xl-12">Contraseña: </h4>
                </div>

                <div class="col-lg-2 col-xl-2">
                    <input class="form-control m-b-10" type="text" name="ridivi_username" id="ridivi_username" placeholder="Usuario de conexión para ridivi" wire:model="ridivi_username" />
                    <input class="form-control m-b-10" type="password" name="ridivi_pass" id="ridivi_pass" placeholder="Usuario de conexión para ridivi" wire:model="ridivi_pass" />
                    @error('ridivi_pass') <span class="text-red-500">{{ $message }}</span>@enderror
                    @error('ridivi_username') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-2 col-xl-2">
                    <button type="button" wire:click.prevent="conection()" onclick="this.disabled=true;" class="btn btn-danger m-b-10 col-xl-12">Provar conexión</button>
                    <button type="button" wire:click.prevent="save()" onclick="this.disabled=true;" class="btn btn-blue m-b-10  col-xl-12">Guardar</button>
                </div>
                <div class="col-lg-3 col-xl-3">
                </div>
            </div>
        </form>
    </div>
    <br>
    <br>
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">Cuentas Asignadas</h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body" width="100%" style="font-size:14px">
            <!-- begin bienes-content -->
            <div class="invoice-content">
                <!-- begin table-responsive -->
                <div class="table-responsive ">
                    <table class="table table-invoice">
                        <thead>
                            <tr class="text-white" style="background-color:#192743; white-space: nowrap;">
                                <th class="text-center">Cuenta</th>
                                <th class="text-center">IBAN</th>
                                <th class="text-center">Moneda</th>
                                <th class="text-center">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allAccounts as $account)
                            <tr class="gradeU">
                                <td width="15" class="text-center" style="white-space: nowrap;">{{ $account["name"] }}</td>
                                <td width="15" class="text-center" style="white-space: nowrap;"> {{ $account["iban"] }}</td>
                                <td width="15" class="text-center" style="white-space: nowrap;">{{ $account["cur"] }}</td>
                                <td width="15" class="text-center" style="white-space: nowrap;"> {{ number_format( $account["bal"],2,'.',',') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
            <!-- end bienes-content -->
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->
</div>