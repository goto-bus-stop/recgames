@if ($paginator->hasPages())
  <nav class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
      <a class="button is-disabled" href="#">Previous</a>
    @else
      <a class="button" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <a class="button" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
    @else
      <a class="button is-disabled" href="#">Next</li>
    @endif
  </nav>
@endif
