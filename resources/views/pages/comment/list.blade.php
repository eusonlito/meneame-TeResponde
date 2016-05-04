<div class="post-preview">
    <div>{!! $comment->text !!}</div>

    <a href="{{ $comment->link }}" class="post-meta" target="_blank">
        Enviado por <strong>{{ $comment->user }}</strong>
        el {{ strftime('%e de %B de %Y', strtotime($comment->created_at)) }}
    </a>
</div>

<hr />