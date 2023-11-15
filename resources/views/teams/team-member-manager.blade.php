<div>
    @if (Gate::check('addTeamMember', $team) || Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))


    <!-- Add Team Member -->
    <div class="mt-10 sm:mt-0">
        <x-form-section submit="addTeamMember">
            <x-slot name="title">
                {{ __('Agregar miembro a su compañia') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Agregue un nuevo miembro a su compañia, permitiéndoles colaborar con usted.') }}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <div class="max-w-xl text-sm text-gray-600">
                        {{ __('Proporcione la dirección de correo electrónico de la persona que le gustaría agregar a este equipo. La dirección de correo electrónico debe estar asociada a una cuenta existente.') }}
                    </div>
                </div>
                <!-- Member Email -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" type="text" class="mt-1 block w-full" wire:model.defer="addTeamMemberForm.email" />
                    <x-input-error for="email" class="mt-2" />
                </div>
                <!-- Role -->
                @if (count($this->roles) > 0)
                <div class="col-span-6 lg:col-span-4">
                    <x-label for="role" value="{{ __('Role') }}" />
                    <x-input-error for="role" class="mt-2" />

                    <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        @foreach ($this->roles as $index => $role)
                        <div clas <div class="px-4 py-3 {{ $index > 0 ? 'border-t border-gray-200' : '' }}" wire:click="$set('addTeamMemberForm.role', '{{ $role->key }}')">
                            <div class="{{ isset($addTeamMemberForm['role']) && $addTeamMemberForm['role'] !== $role->key ? 'opacity-50' : '' }}">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 ">
                                        {{ $role->name }}
                                    </div>

                                    @if ($addTeamMemberForm['role'] == $role->key)
                                    <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @endif
                                </div>

                                <!-- Role Description -->
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ $role->description }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <!--bo -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="bo" value="{{ __('Asignar Sucursal') }}" />
                    <select class="form-control" id="bo" wire:model="addTeamMemberForm.bo" {{ ($addTeamMemberForm['role'] == 'admin' ||  $addTeamMemberForm['role'] == '') ? 'disabled' : '' }}>
                        <option style="color: black;" value="">Todas las Sucursales</option>
                        @foreach($allBranchOffices as $bo)
                        <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="bo" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="terminal" value="{{ __('Asignar Terminal') }}" />
                    <select class="form-control" id="terminal" wire:model.defer="addTeamMemberForm.terminal" {{ $addTeamMemberForm['bo'] == '' ? 'disabled' : '' }}>
                        <option style="color: black;" value="">Todas las Terminales</option>
                        @foreach($allTerminals as $terminal)
                        <option style="color: black;" value="{{ $terminal->id }}">{{ $terminal->number }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="terminal" class="mt-2" />
                </div>
            </x-slot>
            <x-slot name="actions">
                <x-action-message class="mr-3" on="saved">
                    {{ __('Añadido.') }}
                </x-action-message>

                <x-button2>
                    {{ __('Añadir') }}
                </x-button2>
            </x-slot>
        </x-form-section>
    </div>


    @endif
    @if ($team->teamInvitations->isNotEmpty() && Gate::check('addTeamMember', $team))
    <!-- Team Member Invitations -->
    <div class="mt-10 sm:mt-0">
        <x-action-section>
            <x-slot name="title">
                {{ __('Pending Team Invitations') }}
            </x-slot>

            <x-slot name="description">
                {{ __('These people have been invited to your company and have been sent an invitation email. They may join the company by accepting the email invitation.') }}
            </x-slot>

            <x-slot name="content">
                <div class="space-y-6">
                    @foreach ($team->teamInvitations as $invitation)
                    <div class="flex items-center justify-between">
                        <div class="text-gray-600">{{ $invitation->email }}</div>

                        <div class="flex items-center">
                            @if (Gate::check('removeTeamMember', $team))
                            <!-- Cancel Team Invitation -->
                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="cancelTeamInvitation({{ $invitation->id }})">
                                {{ __('Cancel') }}
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-slot>
        </x-action-section>
    </div>
    @endif
    @if ($team->users->isNotEmpty())
    <x-section-border />
    <!-- Manage Team Members -->
    <div class="mt-10 sm:mt-0">
        <x-action-section>
            <x-slot name="title">
                {{ __('Miembros de la compañia') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Todas las personas que forman parte de esta compañia.') }}
            </x-slot>

            <!-- Team Member List -->
            <x-slot name="content">
                <div class="space-y-6">
                    @foreach ($team->users->sortBy('name') as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                            <div class="ml-4">{{ $user->name.' ('.$user->teams->find($team->id)->membership->role.')-'.(App\Models\TeamUser::getUserTeamTerminal($user->id, $team->id)) }}</div>
                        </div>

                        <div class="flex items-center"> <!-- Manage Team Member Role -->
                            <button class="ml-2 text-sm text-gray-400" wire:click="manageRole('{{ $user->id }}')">
                                Modificar
                            </button>

                            <!-- Leave Team -->
                            @if ($this->user->id === $user->id)
                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="$toggle('confirmingLeavingTeam')">
                                {{ __('Abandonar') }}
                            </button>

                            <!-- Remove Team Member -->
                            @elseif ((count(Auth::user()->teams)>0 && Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) || Auth::user()->id == Auth::user()->currentTeam->user_id)
                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmTeamMemberRemoval('{{ $user->id }}')">
                                {{ __('Eliminar') }}
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-slot>
        </x-action-section>
    </div>
    @endif

    <!-- Role Management Modal -->
    <x-dialog-modal wire:model="currentlyManagingRole">
        <x-slot name="title">
            {{ __('Modificar permisos') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                @foreach ($this->roles as $index => $role)
                <div class="px-4 py-3 {{ $index > 0 ? 'border-t border-gray-200' : '' }}" wire:click="$set('currentRole', '{{ $role->key }}')">
                    <div class="{{ $currentRole !== $role->key ? 'opacity-50' : '' }}">
                        <!-- Role Name -->
                        <div class="flex items-center">
                            <div class="text-sm text-gray-600 {{ $currentRole == $role->key ? 'font-semibold' : '' }}">
                                {{ $role->name }}
                            </div>

                            @if ($currentRole == $role->key)
                            <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>

                        <!-- Role Description -->
                        <div class="mt-2 text-xs text-gray-600">
                            {{ $role->description }}
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label for="currentBo" value="{{ __('Asignar Sucursal') }}" />
                <select class="form-control" id="currentBo" wire:change="changeCurrentBo()" wire:model="currentBo" {{ ($currentRole == 'admin' || $currentRole == '') ? 'disabled' : '' }}>
                    <option style="color: black;" value=''>Todas las Sucursales</option>
                    @foreach($allBranchOffices as $bo)
                    <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                    @endforeach
                </select>
                <x-input-error for="currentBo" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label for="currentTerminal" value="{{ __('Asignar Terminal') }}" />
                <select class="form-control" id="currentTerminal" wire:model="currentTerminal" {{ $currentBo == '' ? 'disabled' : '' }}>
                    <option style="color: black;" value=''>Todas las Terminales</option>
                    @foreach($allCurrentTerminals as $terminal)
                    <option style="color: black;" value="{{ $terminal->id }}">{{ $terminal->number }}</option>
                    @endforeach
                </select>
                <x-input-error for="currentTerminal" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="stopManagingRole" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-button2 class="ml-2" wire:click="updateRole" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button2>
        </x-slot>
    </x-dialog-modal>

    <!-- Leave Team Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmingLeavingTeam">
        <x-slot name="title">
            {{ __('Dejar comapañia') }}
        </x-slot>

        <x-slot name="content">
            {{ __('¿Estás seguro de que te gustaría dejar esta compañia?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingLeavingTeam')" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="leaveTeam" wire:loading.attr="disabled">
                {{ __('Dejar') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Remove Team Member Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmingTeamMemberRemoval">
        <x-slot name="title">
            {{ __('Eliminar miembro de la compañia') }}
        </x-slot>

        <x-slot name="content">
            {{ __('¿Estás seguro de que deseas eliminar a esta persona dela compañia?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingTeamMemberRemoval')" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="removeTeamMember" wire:loading.attr="disabled">
                {{ __('Remover') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>