<div class="post-preview">
    <a href="{{ route('site.post', ['id' => $post->id, 'slug' => $post->slug]) }}">
        <h2 class="post-title">{{ $post->title }}</h2>
    </a>

    <p class="post-meta">
        Enviado por <strong>{{ $post->user }}</strong>
        el {{ $post->dateHuman }}
    </p>
</div>

<hr />