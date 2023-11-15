<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="providerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Creación de proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-bordered">
                        @csrf
                        <!-- begin form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Codigo: <span class="text-danger"></span></label>
                            <div class="col-lg-4 col-xl-4">
                                <input class="form-control" type="text" name="code" id="code" wire:model="code" />
                                @error('code') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->
                        <!-- begin form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Nombre Proveedor: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="text" name="name_provider" data-parsley-required="true" class="form-control" placeholder="Nombre Proveedor" wire:model="name_provider" />
                                @error('name_provider') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Identifiacion y tipo de identificacion: <span class="text-danger"></span></label>
                            <div class="col-lg-9">
                                <div class="row row-space-10">
                                    <div class="col-xs-6 mb-2 mb-sm-0">
                                        <input data-toggle="number" data-placement="after" class="form-control" type="number" name="id_card" id="id_card" placeholder="Numero Identificación" minlength="9" maxlength="12" wire:model="id_card" />
                                        @error('id_card') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-xs-6">
                                        <select class="form-control" data-parsley-required="true" name="type_id_card" id="type_id_card" wire:model="type_id_card">
                                            @foreach($type_id_cards as $type_id_card)
                                            <option style="color: black;" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_id_card') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Direccion: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <div class="row ">
                                    <div class="col-4">
                                        <select class="form-control" name="id_province" id="id_province" wire:model="id_province">
                                            <option style="color: black;" value="0">Provincia</option>
                                            @foreach($provinces as $province)
                                            <option style="color: black;" value="{{ $province->id }}">{{ $province->province }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_province') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-4">
                                        <select class="form-control" data-parsley-required="true" name="id_canton" id="id_canton" wire:model="id_canton">
                                            <option style="color: black;" value="0">Canton</option>
                                            @foreach($cantons as $canton)
                                            <option style="color: black;" value="{{ $canton->id }}">{{ $canton->canton }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_canton') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-4">
                                        <select class="form-control" data-parsley-required="true" name="id_district" id="id_district" wire:model="id_district">
                                            <option style="color: black;" value="0">Distrito</option>
                                            @foreach($districts as $district)
                                            <option style="color: black;" value="{{ $district->id }}">{{ $district->district }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_district') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Otras señas: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="text" name="other_signs" data-parsley-required="true" class="form-control" wire:model="other_signs" placeholder="DIreccion exacta" />
                                @error('other_signs') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- begin form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="email" name="emails" class="form-control" data-parsley-type="email" wire:model="emails" placeholder="ejemplo@dominio.com" />
                                @error('emails') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->

                        <!-- begin form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Codigo de pais y Telefono: </label>
                            <div class="col-lg-9 col-xl-9">
                                <div class="row row-space-6">
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="name_country_code" list="countryCodes" placeholder="Ninguno" wire:model="name_country_code">
                                        <datalist id="countryCodes">
                                            @foreach($country_codes as $cc)
                                            <option style="color: black;" value="{{ $cc->phone_code }}" label="{{ $cc->name }}">
                                                @endforeach
                                        </datalist>
                                        <input type="text" name="id_country_code" wire:model="id_country_code" hidden>
                                        @error('id_country_code') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-6">
                                        <input data-toggle="number" data-placement="after" class="form-control" type="number" name="phone" minlength="8" maxlength="8" wire:model="phone" placeholder="88888888" />
                                        @error('phone') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end form-group -->
                        <!-- end form-group -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Condicion de venta, Plazo y Monto: <span class="text-danger"></span></label>
                            <div class="col-lg-9">
                                <div class="row row-space-10">
                                    <div class="col-xs-4">
                                        <select class="form-control" data-parsley-required="true" name="id_sale_condition" id="id_sale_condition" wire:model="id_sale_condition">
                                            @foreach($sale_conditions as $sale_condition)
                                            <option style="color: black;" value="{{ $sale_condition->id }}">{{ $sale_condition->sale_condition }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_sale_condition') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="input-group col-xs-4 mb-2 mb-sm-0">
                                        <div class="input-group-prepend"><span class="input-group-text">dias</span></div>
                                        <input class="form-control" type="number" name="time" id="time" value="1" min="1" max="99" wire:model="time" />
                                        @error('time') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="input-group col-xs-4 mb-2 mb-sm-0">
                                        <div class="input-group-prepend"><span class="input-group-text">₡</span></div>
                                        <input class="form-control" type="number" {{ ($id_sale_condition != 2)?'disabled':'' }} name="total_credit" id="total_credit" value="0" wire:model="total_credit" />
                                        @error('total_credit') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end form-group -->
                        <!-- end form-group -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Moneda y metodo de pago: <span class="text-danger"></span></label>
                            <div class="col-lg-9">
                                <div class="row row-space-10">
                                    <div class="col-xs-6">
                                        <input class="form-control" type="text" name="name_currency" list="currencies" placeholder="Ninguno" wire:model="name_currency">
                                        <datalist id="currencies">
                                            @foreach($currencies as $currency)
                                            <option style="color: black;" value="{{ $currency->code }}" label="{{ $currency->currency.'-'.$currency->code }}">
                                                @endforeach
                                        </datalist>
                                        <input type="text" name="id_currency" wire:model="id_currency" hidden>
                                        @error('id_currency') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-xs-6">
                                        <select class="form-control" data-parsley-required="true" name="id_payment_method" id="id_payment_method" wire:model="id_payment_method">
                                            @foreach($payment_methods as $payment_method)
                                            <option style="color: black;" value="{{ $payment_method->id }}">{{ $payment_method->payment_method }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_payment_method') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end form-group -->
                        @if(Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2 && Auth::user()->currentTeam->plan_id != 5)
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                            <div class="panel panel-inverse">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <h4 class="panel-title">Cuentas de proveedor</h4>
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
                                <div class="form-group row m-b-15">
                                    <label class="col-md-3 col-form-label">Descripción de la cuenta</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="description_provider_account" placeholder="Descripción o nombre para identificar" wire:model="description_provider_account" />
                                        @error('description_provider_account') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <label class="col-md-3 col-form-label">Número de cuenta</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="account_provider_account" placeholder="Cuenta bancaria IBAN" wire:model="account_provider_account" />
                                        @error('account_provider_account') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <label class="col-md-3 col-form-label">Número de sinpe</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="account_sinpe" placeholder="Numero para tranferencia sinpe" wire:model="account_sinpe" />
                                        @error('account_sinpe') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-md  btn-blue" wire:click.prevent="addAccounts()" onclick="this.disabled=true;" title="Agregar cuenta">Agregar</button>
                                    </div>
                                </div>
                                @if(count($allProviderAccounts)>0)
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <table id="data-table-pay" class="table table-striped table-bordered table-td-valign-middle">
                                        <thead>
                                            <tr>
                                                <th data-orderable="false" width="10%">Eliminar</th>
                                                <th class="text-nowrap" width="10%">Nombre</th>
                                                <th width="15%">Cuenta Bancaria</th>
                                                <th width="15%">SINPE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allProviderAccounts as $index => $accounts)
                                            <tr class="gradeU">
                                                <td width="15%" class="text-center">
                                                    <div class="btn-group btn-group-justified">
                                                        <button wire:click.prevent="deleteAccounts('{{ $index }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $accounts['description'] }}</td>
                                                <td class="text-center">{{ $accounts['account'] }}</td>
                                                <td class="text-center">{{ $accounts['sinpe'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- end panel-body -->
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.modal').on('shown.bs.modal', function() {});
        });
        window.addEventListener('provider_modal_hide', event => {
            $('#providerModal').modal('hide');
        });
    </script>
</div>