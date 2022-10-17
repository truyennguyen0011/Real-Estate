<div class="col-md-4">
    <div class="card card-plain card-blog">
        <div class="card-image">
            <a href="{{ route('new-detail', $newSlug) }}" title="{{ $newTitle }}">
                @if ($newImage != null)
                    <img class="img img-raised img-raised-home" lazy-src="{{ asset(config('app.image_new_thumb_direction') . $newImage) }}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkiIurBwACXwE9Al44DAAAAABJRU5ErkJggg==" title="{{ $newTitle }}" alt="{{ $newTitle }}">
                @else
                    <img class="img img-raised img-raised-home" src="{{ asset('images/no-image-360x200.png') }}"  title="{{ $newTitle }}">
                @endif
            </a>
        </div>

        <div class="card-content">
            <h5 class="card-title h5-truncate">
                {{--  --}}
                <a class="text-truncate-2" href="{{ route('new-detail', $newSlug) }}" title="{{ $newTitle }}">{{ $newTitle }}</a>
            </h5>
        </div>
    </div>
</div>