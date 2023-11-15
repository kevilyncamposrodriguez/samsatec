<x-app-layout>
    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Auth::user()->isMemberOfATeam())
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
    @else
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acepte la Invitación') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-jet-form-section submit="createTeam">
                <x-slot name="title">
                    {{ __('Usuario Creado') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Este usuario ha sido creado bajo una invitacion.') }}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-jet-label value="{{ __('Acepte la invitacion enviada al correo ').Auth::user()->email.' para obtener acceso a la compañia anfitrión.'  }}" />

                    </div>
                </x-slot>
            </x-jet-form-section>
        </div>
    </div>
    @endif
</x-app-layout>