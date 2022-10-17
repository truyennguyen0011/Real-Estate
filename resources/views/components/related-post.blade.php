<div class="col-xs-6">
    <div class="card card-plain card-related">
        <div class="card-image">
            <a href="{{ route('post', $postSlug) }}" title="{{ $postTitle }}">
                @if ($postImage != null)
                    <img width="100%" height="203px" class="img img-raised img-raised-related" lazy-src="{{ asset($postImage->folder . $postImage->url) }}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkiIurBwACXwE9Al44DAAAAABJRU5ErkJggg==" title="{{ $postTitle }}" alt="{{ $postTitle }}">
                @else
                    <img width="100%" height="203px" class="img img-raised img-raised-related" src="{{ asset('images/no-image-360x200.png') }}"  title="{{ $postTitle }}">
                @endif
            </a>
        </div>

        <div class="card-content">
            <h6 class="card-title">
                <a class="text-truncate-1" href="{{ route('post', $postSlug) }}" title="{{ $postTitle }}">{{ $postTitle }}</a>
            </h6>
            
            <div class="footer footer-custom">
                <p class="text-muted text-left">
                    Giá: <span class="text-danger title">{{ $postPrice }}</span>
                </p>
                <p class="text-muted text-left">
                    Diện tích: <span class="text-success title">{{ $postArea }}</span>
                </p>
                <p class="text-muted text-left text-truncate-1">
                    Địa chỉ: <span class="text-success title">{{ $postAddress }}</span>
                </p>
            </div>
        </div>
    </div>
</div>