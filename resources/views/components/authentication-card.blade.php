<!-- begin login-cover -->
<div class="login-cover ">
    <div class="login-cover-image" style="background-image: url(/assets/img/fond.jpg)" data-id="login-cover-image"></div>
    <div class="login-cover-bg"></div>
</div>
<!-- end login-cover -->
<!-- begin login -->
<div class="login login-v2" data-pageload-addclass="animated fadeIn">
    <!-- begin brand -->
    <div class="login-header" style=" margin-top: 55px;">
        <div class="text-center">
            <span class="" ><img src="/assets/img/logo-auth.png" style="margin: auto;"  width="100" alt="Samsa App"/></span>   
            <h2 class=""></h2>
            <h5 class="text-center">Sistema de administración de inventario con facturación electrónica.</h5>
        </div>   
    </div>
    <!-- end brand -->

    <!-- begin login-content -->
    <div class="login-content">
        {{ $slot }}
    </div>
    <!-- end login-content -->
</div>
<!-- end login -->

