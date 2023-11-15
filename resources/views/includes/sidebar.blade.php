<!-- begin #sidebar -->
<div id="sidebar" class="sidebar" style="background-color: #010E2C;">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%" >
        <br>
        <br>
        <!-- begin sidebar nav -->
        <ul class="nav">
            @if(Auth::user()->isMemberOfATeam())
            <li>
                <a href="/dashboard">
                    <i class="ion-ios-home" style="background-color: #cc0000;"></i>
                    <span class="text-white">Inicio</span>
                </a>
            </li>
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-md-archive bg-blue"></i>
                    <span class="text-white">Almacen</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('products') }}" class="text-white">Productos o servicios</a></li>
                    @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
                    <li><a href="{{ route('providers') }}" class="text-white">Proveedores</a></li>
                    @if(Auth::user()->currentTeam->plan_id > 1)
                    <li><a href="{{ route('inventory') }}" class="text-white">Inventario</a></li>
                    @if(Auth::user()->currentTeam->plan_id > 3)
                    <li><a href="{{ route('inventoryAjustment') }}" class="text-white">Ajustes de Inventario</a></li>
                    <li><a href="{{ route('categories') }}" class="text-white">Categorias</a></li>
                    <li><a href="{{ route('families') }}" class="text-white">Familias</a></li>
                    <li><a href="{{ route('classproducts') }}" class="text-white">Clases</a></li>
                    <li><a href="{{ route('zones') }}" class="text-white">Zonas</a></li>
                    <li><a href="{{ route('lots') }}" class="text-white">Lotes</a></li>
                    @endif
                    @endif
                    @endif

                </ul>
            </li>
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-cash bg-blue"></i>
                    <span class="text-white">Ventas</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('documents') }}" class="text-white">Todas las ventas</a></li>
                    <li><a href="{{ route('clients') }}" class="text-white">Clientes</a></li>
                    @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
                    @if(Auth::user()->currentTeam->plan_id > 1)
                    @if(Auth::user()->currentTeam->plan_id > 2)
                    <li><a href="{{ route('pricelists') }}" class="text-white">Listados de precios</a></li>
                    @endif
                    <li><a href="{{ route('paymentsInvoices') }}" class="text-white">Pagos</a></li>
                    @endif
                    <li><a href="{{ route('documentsResume') }}" class="text-white">Resumen ventas</a></li>
                    <li><a href="{{ route('documentsDetail') }}" class="text-white">Detalle ventas</a></li>
                    @endif
                </ul>
            </li>
            @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-cart bg-blue"></i>
                    <span class="text-white">Compras y Gastos</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('vouchers') }}" class="text-white">Todos los comprobantes</a></li>
                    @if(Auth::user()->currentTeam->fe)
                    @if (!session()->has('npay'))
                    <li><a href="{{ route('expenses') }}" class="text-white">Comprobación</a></li>
                    @endif
                    @if(Auth::user()->currentTeam->plan_id > 1)
                    <li><a href="{{ route('pendingElectronicReceipt') }}" class="text-white" title="Pendientes de Comprobante Electrónico">Pendientes de CE</a></li>
                    @endif
                    @endif
                    @if(Auth::user()->currentTeam->plan_id > 2)
                    <li><a href="{{ route('payments') }}" class="text-white">Pagos</a></li>
                    @endif
                    <li><a href="{{ route('expensesResume') }}" class="text-white">Resumen compras-gastos</a></li>
                    <li><a href="{{ route('expensesDetail') }}" class="text-white">Detalle compras-gastos</a></li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->currentTeam->plan_id > 1)
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-md-paper bg-blue"></i>
                    <span class="text-white">Caja y Bancos</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('cashRegister') }}" class="text-white">Cajas</a></li>
                    @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
                    <li><a href="{{ route('cashMovement') }}" class="text-white">Reporte Caja y Bancos</a></li>
                    @if(Auth::user()->currentTeam->plan_id > 4)
                    <li><a href="{{ route('credits') }}" class="text-white">Créditos</a></li>
                    <li><a href="{{ route('paybills') }}" class="text-white">Vales</a></li>
                    <li><a href="{{ route('transfers') }}" class="text-white">Traslados</a></li>
                    @endif
                    @endif
                </ul>
            </li>
            @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
            @if(Auth::user()->currentTeam->plan_id > 3)
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-md-paper bg-blue"></i>
                    <span class="text-white">CXC - CXP</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('cxc') }}" class="text-white">Cuentas por Cobrar</a></li>
                    <li><a href="{{ route('cxp') }}" class="text-white">Cuentas por Pagar</a></li>
                </ul>
            </li>
            @endif
            @endif
            @endif
            @if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-md-paper bg-blue"></i>
                    <span class="text-white">Reportes</span>
                </a>
                <ul class="sub-menu">
                    @if(Auth::user()->currentTeam->plan_id > 2 )
                    <li><a href="{{ route('iva') }}" class="text-white">Prorrateo</a></li>
                    @if(Auth::user()->currentTeam->plan_id > 3 )
                    <li><a href="{{ route('ivaDetail') }}" class="text-white">Registro anual de IVA</a></li>
                    @if(Auth::user()->currentTeam->plan_id > 4 )
                    <li><a href="{{ route('diaryBook') }}" class="text-white">Libro Diario</a></li>
                    @endif
                    @endif
                    @endif
                </ul>
            </li>
            @endif
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-md-finger-print bg-blue"></i>
                    <span class="text-white">Administrar</span>
                </a>
                <ul class="sub-menu">

                    <li><a href="{{ route('taxes') }}" class="text-white">Impuestos</a></li>
                    <li><a href="{{ route('exonerations') }}" class="text-white">Exoneraciones</a></li>
                    <li><a href="{{ route('discounts') }}" class="text-white">Descuentos</a></li>
                    @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
                    @if(Auth::user()->currentTeam->plan_id > 2)
                    <li><a href="{{ route('counts') }}" class="text-white">Cuentas contables</a></li>
                    @endif
                    @if(Auth::user()->currentTeam->plan_id > 1)
                    <li><a href="{{ route('sellers') }}" class="text-white">vendeedores</a></li>
                    <li><a href="{{ route('buyers') }}" class="text-white">Compradores</a></li>
                    @endif
                    @endif
                </ul>
            </li>
            @if(Auth::user()->teamRole(Auth::user()->currentTeam)->key != 'vende')
            @if(Auth::user()->currentTeam->plan_id > 3)
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-archive bg-blue"></i>
                    <span class="text-white">Bodega</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('cellarUnprocessed') }}" class="text-white">Ordenes de Venta pendientes</a></li>
                    <li><a href="{{ route('cellarProcessed') }}" class="text-white">Ordenes de Venta procesadas</a></li>
                </ul>
            </li>
            @endif
            @if(false)
            <li>
                <a href="">
                    <i class="ion-ios-exit bg-blue"></i>
                    <span class="text-white">Salidas</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="ion-md-git-compare bg-blue"></i>
                    <span class="text-white">Conciliación</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="ion-md-calculator bg-blue"></i>
                    <span class="text-white">Conversiones</span>
                </a>
            </li>
            <li>
                <a href="{{ route('charts') }}" class="text-white">
                    <i class="ion-md-pencil bg-blue"></i>
                    <span class="text-white">Graficas</span>
                </a>
            </li>
            @endif
            @endif
            @if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-cog bg-gray-300"></i>
                    <span class="text-white">Configuraciones</span>
                </a>
                <ul class="sub-menu">
                    <li title="Datos necesarios para la conexión con el sistema ATV del Ministerio de Hacienda"><a href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" class="text-white">Configuración Principal </a></li>
                    @if(false)
                    <li><a href="{{ route('bankSettings') }}" class="text-white">Banco Ridivi</a></li>
                    @endif
                    <li><a href="{{ route('listPays') }}" class="text-white">Facturación</a></li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
            <li><a href="{{ route('teams.showUser', Auth::user()->currentTeam->id) }}" class="text-white"><i class="ion-md-contacts bg-gray-300"></i><span class="text-white">Usuarios</span></a></li>
            @endif
            @if(Auth::user()->id == Auth::user()->currentTeam->user_id)
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="nav-icon fas fa-sitemap"></i>
                    <span class="text-white">Referidos</span>
                </a>
                <ul class="sub-menu">
                    @if (Auth::user()->currentTeam->referral_code)
                    <li><a href="{{ route('referrals') }}" class="text-white">Mis Referidos</a></li>
                    <li><a href="{{ route('myPays') }}" class="text-white">Pagos</a></li>
                    @else
                    <li><a href="{{ route('referrals') }}" class="text-white">Unirme</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if (Auth::user()->currentTeam->id == 1)
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-cog bg-gray-300"></i>
                    <span class="text-white">Sistema</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('adminPays') }}" class="text-white">Pagos de Sistema</a></li>
                    <li><a href="{{ route('clientSystem') }}" class="text-white">Clientes del Sistema</a></li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->currentTeam->id == 1 || Auth::user()->currentTeam->id == 2)
            <li><a href="{{ route('categoriesTutorials') }}"><i class="ion-md-bulb bg-green"></i> <span class="text-white ">Tutoriales</span></a></li>
            @endif
            <!-- begin sidebar minify button -->
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="ion-ios-arrow-back"></i> <span class="text-white">Minimizar</span></a></li>
            <!-- end sidebar minify button -->
            @endif
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->