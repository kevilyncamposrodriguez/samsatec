<td>
    @if(file_exists ($path.'/'.$key.'.pdf'))
    <a href="{{ '/'.$path.'/'.$key.'.pdf' }}" target="_blank" class="dropdown-item text-blue" class="btn btn-dark btn-sm">{{$consecutivo}}</a>
    @else
    <a href="" class="dropdown-item text-blue" data-toggle="modal" data-target="#documentUModal" data-toggle="modal" wire:click="$emit('editDocument',{{ $id }})" class="btn btn-dark btn-sm">{{$consecutivo}}</a>
    @endif
</td>