<td width="20%" class="text-center">
    <div class="btn-group btn-group-justified">
        @if(file_exists( $ruta.'/'.$key.'.pdf' ))
        <a href="{{ $ruta.'/'.$key.'.pdf' }}" target="_blank" class="btn btn-md text-white" style="background-color: #192743;"><i title="Vista del Documento" class="fa fa-search"></i></a>
        @endif
    </div>
</td>