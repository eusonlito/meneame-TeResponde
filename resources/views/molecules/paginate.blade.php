<ul class="pager">
    @if ($url = $result->previousPageUrl())
    <li class="previous">
        <a href="{{ $url }}">&larr; Recientes</a>
    </li>
    @endif

    @if ($url = $result->nextPageUrl())
    <li class="next">
        <a href="{{ $url }}">Anteriores &rarr;</a>
    </li>
    @endif
</ul>