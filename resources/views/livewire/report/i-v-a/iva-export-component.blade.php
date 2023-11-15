<div>
   


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
                        <th class="text-center"  style="background-color:#192743; color: white;">Clasificación</th>
                        <th class="text-center"  style="background-color:#192743; color: white;">Gravado</th>
                        <th class="text-center"  style="background-color:#192743; color: white;" colspan="2">Sin Impuesto</th>
                        <th class="text-center"  style="background-color:#192743; color: white;" colspan="2">Impuesto_1%</th>
                        <th class="text-center"  style="background-color:#192743; color: white;" colspan="2">Impuesto_2%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_3%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_8%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_13%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Total</th>
                     </tr>
                     <tr>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;"></td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;"></td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($categories as $category)
                     @if(count($Ventas->where("Categoria_MH",$category->name))>0)
                     @foreach($Ventas->where("Categoria_MH",$category->name) as $venta)
                     <tr class="gradeU">
                        <td  class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Gravado'],2,'.',',') }} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Monto_0'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Impuesto_0'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Monto_1'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Impuesto_1'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Monto_2'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Impuesto_2'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Monto_4'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Impuesto_4'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Monto_8'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Impuesto_8'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Monto_13'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['Impuesto_13'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['total_venta'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($venta['total_impuesto'],2,'.',',')}} </td>
                     </tr>
                     @endforeach
                     @else
                     <tr class="gradeU">
                        <td  class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
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
               <table class="table table-invoice" >
                  <thead>
                     <tr class="text-white" style="background-color:#192743; white-space: nowrap;">
                        <th class="text-center" style="background-color:#192743; color: white;">Clasificación</th>
                        <th class="text-center" style="background-color:#192743; color: white;">Gravado</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2" >Sin Impuesto</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_1%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_2%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_3%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_8%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Impuesto_13%</th>
                        <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Total</th>
                     </tr>
                     <tr>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;"></td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;"></td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;"> Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Monto</td>
                        <td class="text-center" style="background-color:aliceblue; color: #192743;">Impuesto</td>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($categories as $category)
                     @if(count($Gastos->where("Categoria_MH",$category->name))>0)
                     @foreach($Gastos->where("Categoria_MH",$category->name) as $gasto)
                     <tr class="gradeU">
                        <td  class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Gravado'],2,'.',',') }} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Monto_0'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Impuesto_0'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Monto_1'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Impuesto_1'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Monto_2'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Impuesto_2'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Monto_4'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Impuesto_4'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Monto_8'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Impuesto_8'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Monto_13'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['Impuesto_13'],2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['total_venta'],2,'.',',') }}</td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format($gasto['total_impuesto'],2,'.',',')}} </td>
                     </tr>
                     @endforeach
                     @else
                     <tr class="gradeU">
                        <td  class="text-center" style="white-space: nowrap;">{{ $category->name }} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
                        <td  class="text-center" style="white-space: nowrap;">{{ number_format(0,2,'.',',')}} </td>
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