
@foreach($galeria as $g)
    <div class="gallery-item" data-id="{{ $g->id }}" onclick="openModal(this)">
        <img
            src="{{ url('storage/archivos/'.$g->imagen) }}"
            alt="{{ $g->altseo }}"
            data-caption="{{ $g->textoIdioma ?? '' }}"
            loading="lazy"
            decoding="async"
            itemprop="image">
        <div class="overlay"><i class="fa fa-eye"></i></div>
    </div>
@endforeach



