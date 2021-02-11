@if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-angle-left"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        @endif

        <li class="page-item page-of disabled">
            <span class="page-link">
                {{ $paginator->currentPage() }} {{ Lang::get('Page.De') }} {{ $paginator->lastPage() }}
            </span>
        </li>

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-angle-right"></i>
                </span>
            </li>
        @endif
    </ul>
@endif
