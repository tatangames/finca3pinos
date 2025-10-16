
@foreach($galeria as $dato)
    <div class="gallery-item" onclick="openModal(this)">
        <img
            src="{{ url('storage/archivos/'.$dato->imagen) }}"
            alt="{{ $dato->alt_seo }}"
            data-caption="{{ $dato->nombre }}"
            loading="lazy"
            decoding="async"
            itemprop="image">
        <div class="overlay"><i class="fa fa-eye"></i></div>
    </div>
@endforeach
