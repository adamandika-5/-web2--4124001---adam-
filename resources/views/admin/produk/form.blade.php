@if(isset($produk))
    @include('admin.produk.edit')
@else
    @include('admin.produk.create')
@endif
