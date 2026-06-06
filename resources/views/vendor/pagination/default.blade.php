@if ($paginator->hasPages())
<nav class="ongkir-pagination" aria-label="pagination">
    <ul>
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&#8249; Prev</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&#8249; Prev</a></li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span aria-current="page">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Next &#8250;</a></li>
        @else
            <li class="disabled"><span>Next &#8250;</span></li>
        @endif
    </ul>
</nav>
@endif
