<!-- begin #header -->
<div wire:ignore.self id="header" class="header" style="background-color: #010E2C; border-bottom: 3px solid #001760;">

    <!-- begin navbar-header -->
    <div class="navbar-header">
        <a href="{{ route('dashboard') }}" class="navbar-brand"><span class="navbar-logo"><img class="logo" src="/assets/img/Logo-banner.png" alt="Grupo Samsa" /></span></a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- end navbar-header -->

    <!-- begin header-nav -->
    <ul class="navbar-nav navbar-right">
        <!-- end panel-heading -->
        @if (session()->has('mensaje'))
        <h6 class="mt-3 mr-4 text-red-500 justify-center">{{ session('mensaje') }}</h6>
        @endif
        <x-notification-component></x-notification-component>
        <div class="btn-group btn-group-justified uppercase"></div>
        <li class="dropdown navbar-user m-l-10">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                <span class="d-none d-md-inline text-white"><strong> {{ Auth::user()->name }} </strong></span> <b class="caret"></b>
                @else
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> <b class="caret"></b>
                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-jet-responsive-nav-link>
                <div class="dropdown-divider"></div>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-jet-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>
                @if(Auth::user()->isMemberOfATeam())
                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                <div class="border-t border-gray-200"></div>

                <!-- Team Switcher -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Switch Teams') }}
                </div>

                @foreach (Auth::user()->allTeams() as $team)
                <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                @endforeach
                @endif
                @endif

            </div>
        </li>
    </ul>
    <!-- end header navigation right -->
</div>

<!-- end #header -->