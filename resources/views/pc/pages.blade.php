@if ($paginator->hasPages())
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span>@lang('pagination.previous')</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
    @else
        <span>@lang('pagination.next')</span>
    @endif
@endif