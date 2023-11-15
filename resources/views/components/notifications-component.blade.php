@if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
<li class="dropdown">
    <a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-4">
        <i class="fa fa-bell text-white"></i>
        <span class="label">{{$qty}}</span>
    </a>
    <div class="dropdown-menu media-list dropdown-menu-right">
        <div class="dropdown-header">Notificaciones ({{$qty}})</div>
        @foreach($allNotifications as $notification)
        <a href="{{ route($notification['href']) }}" class="dropdown-item media">
            <div class="media-left">
                <i class="fa fa-bug media-object bg-silver-darker"></i>
            </div>
            <div class="media-body">
                <h6 class="media-heading">{{$notification['msg']}} <i class="fa fa-exclamation-circle text-danger"></i></h6>
                <div class="text-muted f-s-10">{{$notification['sub']}}</div>
            </div>
        </a>
        @endforeach
        <div class="dropdown-footer text-center"></div>
    </div>
</li>
@endif