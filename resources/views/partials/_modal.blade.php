<div id="modal"
    style="display:{{ $activo ? 'block' : 'none' }};opacity:{{ $activo ? '1' : '0' }};"
    class="{{ $typeModal }}"
    onclick="event.stopPropagation()">
    <div class="flexc" id="modalPregunta" @if($typeModal !=='question' ) hidden @endif>
        <div>
            {{ $typeModal === 'question' ? '?' : '!' }}
        </div>
        <h3>{{ $h3 }}</h3>
        <hr />
        <p onclick="event.stopPropagation()">
            {!! $info !!}
        </p>
        <div class="flexr">
            @if ($typeModal === 'question')
            <button onclick="window.ModalesService.aceptar()">Sí</button>
            <button onclick="window.ModalesService.cerrar()">No</button>
            @else
            <button onclick="window.ModalesService.cerrar()">Aceptar</button>
            @endif
        </div>
    </div>
</div>