<div>
    <div class="form-group row relative">
        <label class="col-lg-3 col-form-label">CAByS</label>
        <div class="col-lg-9 col-xl-9">
            <input type="text" name="query" data-parsley-required="true" class="form-control" placeholder="Buscar codigo..." wire:model="query" />
            <div class="absolute z-10 list-group bg-white w-full rounded-t-none shodow-lg">
                @foreach($allCabys as $cabys)
                <button type="button" class="list-item" wire:model="query">{{ $cabys->codigo.'-'.$cabys->descripcion}}</button>
                @endforeach
            </div>
            @error('cabys') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
    </div>
</div>