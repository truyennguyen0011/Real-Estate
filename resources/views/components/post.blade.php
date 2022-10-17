<div class="col-md-4">
    <div class="card card-plain card-blog">
        <div class="card-image">
            <a href="{{ route('post', $postSlug) }}" title="{{ $postTitle }}">
                @if ($postImage != null)
                    <img class="img img-raised img-raised-home" lazy-src="{{ asset($postImage->folder . $postImage->url) }}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkiIurBwACXwE9Al44DAAAAABJRU5ErkJggg==" title="{{ $postTitle }}" alt="{{ $postTitle }}">
                @else
                    <img class="img img-raised img-raised-home" src="{{ asset('images/no-image-360x200.png') }}"  title="{{ $postTitle }}">
                @endif
            </a>
        </div>

        <div class="card-content">
            <h5 class="card-title h5-truncate">
                <a class="text-truncate-2" href="{{ route('post', $postSlug) }}" title="{{ $postTitle }}">{{ $postTitle }}</a>
            </h5>
            
            <div class="footer">
                <p class="text-muted">
                    Giá: <span class="text-danger title">{{ $postPrice }}</span>
                </p>
                <p class="text-muted">
                    Diện tích: <span class="text-success title">{{ $postArea }}</span>
                </p>
                <p class="text-muted text-truncate-1">
                    Địa chỉ: <span class="text-success title">{{ $postAddress }}</span>
                </p>
            </div>
        </div>
    </div>
</div>