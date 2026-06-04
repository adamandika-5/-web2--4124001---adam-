{{--
    Partial: partials/produk-img.blade.php
    Variables:
      $produk  — instance App\Models\Produk (required)
      $style   — inline CSS string for the <img> (optional, default: width:100%;height:100%;object-fit:cover)
      $alt     — alt text override (optional, defaults to $produk->nama)
      $class   — CSS class for the <img> (optional)
--}}
@php
    $imgUrl  = $produk->gambar_url ?? asset('gambar/placeholder.svg');
    $imgAlt  = $alt ?? $produk->nama ?? '';
    $imgStyle = $style ?? 'width:100%;height:100%;object-fit:cover';
    $imgClass = $class ?? '';
@endphp
<img src="{{ $imgUrl }}"
     alt="{{ $imgAlt }}"
     style="{{ $imgStyle }}"
     class="{{ $imgClass }}"
     onerror="this.onerror=null; this.src='{{ asset('gambar/placeholder.svg') }}';">
