<x-app-layout>

    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    @if (session()->has('message'))
    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
        <div class="flex">
            <div>
                <p class="text-sm">{!! session('message') !!}</p>
            </div>
        </div>
    </div>
    @endif
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('team.update-team-component', ['team' => $team])
            @livewire('team.update-team-key-component', ['team' => $team])
            @livewire('branch-office.branch-office-component')
            @livewire('terminal.terminals-component')
            @livewire('companies-economic-activities.companies-economic-activities-component', ['team' => $team])
            @livewire('team.other-configuration-component', ['team' => $team])
        </div>
    </div>

</x-app-layout>