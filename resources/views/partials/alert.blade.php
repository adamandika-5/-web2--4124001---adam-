@if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 text-center">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-700 p-4 text-center">
        {{ session('error') }}
    </div>
@endif