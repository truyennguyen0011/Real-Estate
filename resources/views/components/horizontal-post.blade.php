<div class="row">
    <div class="card card-profile card-plain mt-0">
        <div class="row">
            <div class="col-md-5">
                <div class="card-image">
                    <a href="{{ route('post', $postSlug) }}" title="{{ $postTitle }}">
                        @if ($postImage != null)
                            <img class="img img-raised img-raised-home"
                                lazy-src="{{ asset($postImage->folder . $postImage->url) }}"
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkiIurBwACXwE9Al44DAAAAABJRU5ErkJggg=="
                                title="{{ $postTitle }}" alt="{{ $postTitle }}">
                        @else
                            <img class="img img-raised img-raised-home" src="{{ asset('images/no-image-360x200.png') }}"
                                title="{{ $postTitle }}" alt="{{ $postTitle }}">
                        @endif
                    </a>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card-content">
                    <h5 class="card-title text-left">
                        <a class="text-truncate-1" href="{{ route('post', $postSlug) }}"
                            title="{{ $postTitle }}">{{ $postTitle }}</a>
                    </h5>
                    <p class="text-muted text-left">
                        Giá: <span class="text-danger title">{{ $postPrice }}</span>
                    </p>
                    <p class="text-muted text-left">
                        Diện tích: <span class="text-success title">{{ $postArea }}</span>
                    </p>
                    <p class="text-muted text-truncate-1 text-left">
                        Địa chỉ: <span class="text-success title">{{ $postAddress }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>