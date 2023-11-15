<td>
    @if(file_exists ($path.'/'.$key.'.pdf'))
    <a href="{{ '/'.$path.'/'.$key.'.pdf' }}" target="_blank" class="dropdown-item text-blue" class="btn btn-dark btn-sm">{{$consecutivo}}</a>
    @endif
</td>