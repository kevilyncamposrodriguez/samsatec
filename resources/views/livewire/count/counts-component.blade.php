<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.count.create')
    @include('livewire.count.update')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Catálogo de cuentas contables</h4>
                <div class="panel-heading-btn">
                </div>
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
            <!-- begin panel-body -->
            <div class="panel-body">
                <link href="/assets/css/tree.css" rel="stylesheet" />
                <?php

                use Illuminate\Support\Facades\Auth;

                $cont = 0; ?>
                @foreach($allCountTypes as $type)
                <details class="tree-nav__item is-expandable" wire:ignore.self>
                    <summary class="tree-nav__item-title">
                        <div>
                            <h4>
                                <strong>{{ (++$cont).'. '.$type->name }}</strong>
                            </h4>
                            <span></span>
                        </div>
                    </summary>
                    <?php $cont2 = 0; ?>
                    @foreach($allCountCategories as $category)
                    @if($category->id_count_type == $type->id)
                    <details class="tree-nav__item is-expandable" wire:ignore.self>
                        <summary class="tree-nav__item-title">
                            <div>
                                <h5>
                                    <strong>{{ ($cont).'.'.(++$cont2).'. '.$category->name }}</strong>
                                </h5>
                                <span></span>
                            </div>
                        </summary>
                        <?php $cont3 = 0; ?>
                        @foreach($allPrimaryCounts as $primary)
                        @if(($primary->id_count_category == $category->id) && ($primary->id_count_primary == ''))
                        <details class="tree-nav__item is-expandable" wire:ignore.self>
                            <summary class="tree-nav__item-title">
                                <div>
                                    <h6>
                                        <strong>{{ ($cont).'.'.($cont2).'.'.(++$cont3).'. '.$primary->name }}</strong>
                                    </h6>
                                    <span>
                                        @if($primary->name =="INVENTARIOS")
                                        @if(Auth::user()->currentTeam->bo_inventory)
                                        <button class="btn btn-md  btn-blue" title="Nueva Cuenta" data-toggle="modal" wire:click="newAccount(null, {{ $primary->id }})" data-target="#countModal"><i class="fa fa-plus"></i></button>
                                        @endif
                                        @else
                                        <button class="btn btn-md  btn-blue" title="Nueva Cuenta" data-toggle="modal" wire:click="newAccount(null, {{ $primary->id }})" data-target="#countModal"><i class="fa fa-plus"></i></button>
                                        @endif
                                    </span>
                                </div>
                            </summary>
                            <?php $cont4 = 0; ?>
                            @foreach($allCounts as $count)
                            @if(($count->id_count_primary == $primary->id) && ($count->id_count == ''))

                            {{ getAccounts(1, $cont, $allCounts, $count,$primary) }}

                            @endif
                            @endforeach
                            <?php $cont4 = 0; ?>
                            @foreach($allPrimaryCounts as $secondary)
                            @if(($secondary->id_count_primary == $primary->id))
                            <details class="tree-nav__item is-expandable" wire:ignore.self>
                                <summary class="tree-nav__item-title">
                                    <div>
                                        <h6>
                                            {{ ($cont).'.'.($cont2).'.'.($cont3).'.'.(++$cont4).'. -'.$secondary->name }}
                                        </h6>
                                        <span> <button class="btn btn-md  btn-blue" title="Nueva Cuenta" data-toggle="modal" wire:click="newAccount(null, {{ $secondary->id }})" data-target="#countModal"><i class="fa fa-plus"></i></button></span>
                                    </div>
                                </summary>
                                <?php $cont5 = 0; ?>
                                @foreach($allCounts as $count)
                                @if(($count->id_count_primary == $secondary->id) && ($count->id_count == ''))

                                {{ getAccounts(1, $cont, $allCounts, $count,$secondary) }}

                                @endif
                                @endforeach
                            </details>
                            @endif
                            @endforeach

                        </details>
                        @endif
                        @endforeach
                    </details>
                    @endif
                    @endforeach
                </details>
                @endforeach
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>

<?php
function getAccounts($code = 1, $cont = 1, $allCounts, $count, $primary)
{
    if (count($allCounts->where("id_count", $count->id)) > 0) {
        echo "<details class='tree-nav__item is-expandable' wire:ignore.self>
        <summary class='tree-nav__item-title'>
        <div>
            <h6>" .
            $count->name
            . "</h6>
            <span>";
        if ($count->is_deleted) {
            echo  "<button class='btn btn-md btn-red m-r-10' title='Eliminar Cuenta'wire:click='delete(" . $count->id . ")' ><i class='fa fa-trash'></i></button>";
        }
        echo "<button class='btn btn-md btn-blue' title='Nueva Cuenta' data-toggle='modal' wire:click='newAccount(" . $count->id . ", " . $primary->id . ")' data-target='#countModal'><i class='fa fa-plus'></i></button></span>
        </div>
    </summary>";
        foreach ($allCounts->where("id_count", $count->id) as $c) {
            getAccounts($code, $cont, $allCounts, $c, $primary);
        }
        echo "</details>";
    } else {
        echo "<details class='tree-nav__item is-expandable' wire:ignore.self>
        <summary class='tree-nav__item-title'>
            <div>
                <h6>
                   " . $count->name . "
                </h6>
                <span>";
        if ($count->is_deleted) {
            echo  "<button class='btn btn-md btn-red m-r-20' title='Eliminar Cuenta'wire:click='delete(" . $count->id . ")' ><i class='fa fa-trash'></i></button>";
        }
        if ($primary->name == "INVENTARIOS") {
            if (Auth::user()->currentTeam->bo_inventory) {
                echo "  <button class='btn btn-md  btn-blue' title='Nueva Cuenta' data-toggle='modal' wire:click='newAccount(" . $count->id . ", " . $primary->id . ")' data-target='#countModal'><i class='fa fa-plus'></i></button>";
            }
        } else {
            echo "  <button class='btn btn-md  btn-blue' title='Nueva Cuenta' data-toggle='modal' wire:click='newAccount(" . $count->id . ", " . $primary->id . ")' data-target='#countModal'><i class='fa fa-plus'></i></button>";
        }
        echo "</span>            
        </div>
        </summary>
    </details>";
    }
}
?>