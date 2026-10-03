
@if ($paginator->hasPages())
    <nav>
        <div style="margin-bottom: 10px;">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} data mahasiswa
        </div>

        <div style="display: flex; gap: 5px; align-items: center;">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <span style="padding: 6px 10px; color: #999;">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   style="padding: 6px 10px;">Previous</a>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="padding: 6px 10px;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding: 6px 10px; background: #2563eb; color: white;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="padding: 6px 10px;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   style="padding: 6px 10px;">Next</a>
            @else
                <span style="padding: 6px 10px; color: #999;">Next</span>
            @endif
        </div>
    </nav>
@endif
