@extends('layout.html')

@section('body')

<header class="intro-header" style="background: url('{!! $background !!}') repeat;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="post-heading">
                    <h1>{{ $post->title }}</h1>

                    <span class="meta">
                        Enviado por <strong>{{ $post->user }}</strong>
                        el {{ $post->dateHuman }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</header>

<article>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="well well-lg">
                    {!! $post->text !!}
                </div>

                <hr />

                <ul class="pager">
                    <li class="previous">
                        <a href="{{ back('site.index') }}">&larr; Volver</a>
                    </li>

                    <li class="next">
                        <a href="{{ $post->link }}" target="_blank">Ver en meneame.net &rarr;</a>
                    </li>
                </ul>

                <hr />

                <div class="interview">
                    @foreach ($interview as $turn)
                        @include('pages.comment.interview', $turn)
                    @endforeach
                </div>

                <ul class="pager">
                    <li class="previous">
                        <a href="{{ back('site.index') }}">&larr; Volver</a>
                    </li>

                    <li class="next">
                        <a href="{{ $post->link }}" target="_blank">Ver en meneame.net &rarr;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</article>

@stop