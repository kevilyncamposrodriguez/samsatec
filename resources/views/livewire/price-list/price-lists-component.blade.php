<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.price-list.update-price')
    @include('livewire.price-list.create')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Lista de precios</h4>
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
                <div class="form-group row ">
                    <label class="col-lg-1 col-form-label right">Lista: </label>
                    <div class="col-6">
                        <div>
                            <select class="form-control" name="price_list_id" id="price_list_id" wire:model="price_list_id">
                                <option style="color: black;" value="">Seleccionar lista...</option>
                                @foreach($allPriceList as $pList)
                                <option style="color: black;" wire value="{{ $pList->id }}">{{ $pList->name. ' - '. $pList->description }}</option>
                                @endforeach
                            </select>
                            @error('price_list_id') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="btn-group btn-group-justified">
                            <button class="btn btn-md  btn-blue" title="Nueva Lista de precios" data-toggle="modal" data-target="#priceListModal"><i class="fa fa-plus"></i></button>
                            @if($price_list_id != "")
                            <button class="btn btn-md text-white" title="Modificar Lista de precios" data-toggle="modal" data-target="#priceListModalU" style="background-color: #192743;" wire:click="edit({{ $price_list_id }})"><i class="fa fa-pen"></i></button>
                            <button class="btn btn-danger btn-sm" title="Eliminar Lista de precios" wire:click="delete({{ $price_list_id }})"><i class="fa fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- end panel-body -->
        </div>
    </div>

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de precios por producto</h4>
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
                <livewire:price-list.price-lits-table />
            </div>
            <!-- end panel-body -->

            <!-- end panel-body -->
        </div>
    </div>

</div>