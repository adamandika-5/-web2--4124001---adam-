<!DOCTYPE html>
<html>
<head>
    <title>Katalog Produk</title>
</head>
<body>

<h1>Katalog Produk</h1>

<table border="1">
<tr>
<th>ID</th>
<th>Nama Produk</th>
<th>Harga</th>
</tr>

@foreach($produk as $item)
<tr>
<td>{{ $item['id'] }}</td>
<td>{{ $item['nama'] }}</td>
<td>{{ $item['harga'] }}</td>
</tr>
@endforeach

</table>

</body>
</html>