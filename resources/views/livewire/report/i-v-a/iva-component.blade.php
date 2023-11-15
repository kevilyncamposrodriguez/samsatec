<div>
   <div class="text-center mb-3">
      <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
      <h2 class=""> Reporte Mensual de IVA </h2>
      <form class="form-horizontal form-bordered">
         <div class="form-group row">
            <label class="col-lg-1 col-form-label">Año: <span class="text-danger"></span></label>
            <div class="col-lg-2 col-xl-2">
               <select class="form-control" name="year" id="year" wire:model="year">
                  @for($i = ($year-5);$i<($year+6);$i++) <option style="color: black;" value="{{ $i }}">{{ $i }}</option>
                     @endfor
               </select>
               @error('year') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>
            <label class="col-lg-1 col-form-label">Mes: <span class="text-danger"></span></label>
            <div class="col-lg-2 col-xl-2">
               <select class="form-control" name="month" id="month" wire:model="month">
                  <option style="color: black;" value="1">Enero</option>
                  <option style="color: black;" value="2">Febrero</option>
                  <option style="color: black;" value="3">Marzo</option>
                  <option style="color: black;" value="4">Abril</option>
                  <option style="color: black;" value="5">Mayo</option>
                  <option style="color: black;" value="6">Junio</option>
                  <option style="color: black;" value="7">Julio</option>
                  <option style="color: black;" value="8">Agosto</option>
                  <option style="color: black;" value="9">Setiembre</option>
                  <option style="color: black;" value="10">Octubre</option>
                  <option style="color: black;" value="11">Noviembre</option>
                  <option style="color: black;" value="12">Diciembre</option>
               </select>
               @error('month') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>
            <label class="col-lg-1 col-form-label">Actividad Economica: <span class="text-danger"></span></label>
            <div class="col-lg-2 col-xl-5">
               <select class="form-control" name="number_ea" id="number_ea" wire:model="number_ea">
                  <option style="color: black;" value="">Todas</option>
                  @foreach($allEconomicActivities as $ea)
                  <option style="color: black;" value="{{ $ea->number }}">{{ $ea->name_ea }}</option>
                  @endforeach
               </select>
               @error('number_ea') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>
         </div>
      </form>

      <button type="button" wire:click.prevent="exportExcel()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Exportar a Excel</button>
   </div>


   <!-- begin panel -->
   <div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
      <!-- begin panel-heading -->
      <div class="panel-heading">
         <h4 class="panel-title">Ventas</h4>
      </div>
      <!-- end panel-heading -->
      <!-- begin panel-body -->
      <div class="panel-body" width="100%" style="font-size:14px">
         <!-- begin bienes-content -->
         <div class="invoice-content">
            <!-- begin table-responsive -->
            <div class="table-responsive bg-gray-400">
               <table class="table table-invoice" id="ventas" name="ventas">
                  <thead>
                     <tr class="text-white" style="background-color:#192743; white-space: nowrap;">
                        <th class="text-center">Clasificación</th>
                        <th class="text-center">Gravado</th>
                        <th class="text-center" colspan="2">Sin Impuesto</th>
                        <th class="text-center" colspan="2">Impuesto_1%</th>
                        <th class="text-center" colspan="2">Impuesto_2%</th>
                        <th class="text-center" colspan="2">Impuesto_3%</th>
                        <th class="text-center" colspan="2">Impuesto_8%</th>
                        <th class="text-center" colspan="2">Impuesto_13%</th>
                        <th class="text-center" colspan="2">Total</th>
                     </tr>
                     <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($categories as $category)
                     @if(count($Ventas->where("Categoria_MH",$category->name))>0)
                     @foreach($Ventas->where("Categoria_MH",$category->name) as $venta)
                     <tr class="gradeU">
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Gravado,2,'.',',') }} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Monto_0,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Impuesto_0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Monto_1,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Impuesto_1,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Monto_2,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Impuesto_2,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Monto_4,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Impuesto_4,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Monto_8,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Impuesto_8,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Monto_13,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->Impuesto_13,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->total_venta,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($venta->total_impuesto,2,'.',',')}} </td>
                     </tr>
                     @endforeach
                     @else
                     <tr class="gradeU">
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                     </tr>
                     @endif

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
   <!-- begin panel -->
   <div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
      <!-- begin panel-heading -->
      <div class="panel-heading">
         <h4 class="panel-title">Gastos</h4>
      </div>
      <!-- end panel-heading -->
      <!-- begin panel-body -->
      <div class="panel-body" width="100%" style="font-size:14px">
         <!-- begin bienes-content -->
         <div class="invoice-content">
            <!-- begin table-responsive -->
            <div class="table-responsive bg-gray-400">
               <table class="table table-invoice" id="ventas" name="ventas">
                  <thead>
                     <tr class="text-white" style="background-color:#192743; white-space: nowrap;">
                        <th class="text-center">Clasificación</th>
                        <th class="text-center">Gravado</th>
                        <th class="text-center" colspan="2">Sin Impuesto</th>
                        <th class="text-center" colspan="2">Impuesto_1%</th>
                        <th class="text-center" colspan="2">Impuesto_2%</th>
                        <th class="text-center" colspan="2">Impuesto_3%</th>
                        <th class="text-center" colspan="2">Impuesto_8%</th>
                        <th class="text-center" colspan="2">Impuesto_13%</th>
                        <th class="text-center" colspan="2">Total</th>
                     </tr>
                     <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                        <td class="text-center">Monto</td>
                        <td class="text-center">Impuesto</td>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($categories as $category)
                     @if(count($Gastos->where("Categoria_MH",$category->name))>0)
                     @foreach($Gastos->where("Categoria_MH",$category->name) as $gasto)
                     <tr class="gradeU">
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Gravado,2,'.',',') }} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Monto_0,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Impuesto_0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Monto_1,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Impuesto_1,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Monto_2,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Impuesto_2,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Monto_4,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Impuesto_4,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Monto_8,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Impuesto_8,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Monto_13,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->Impuesto_13,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->total_venta,2,'.',',') }}</td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($gasto->total_impuesto,2,'.',',')}} </td>
                     </tr>
                     @endforeach
                     @else
                     <tr class="gradeU">
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                     </tr>
                     @endif

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
</div>