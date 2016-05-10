@extends('layout.html')

@section('body')

<header class="intro-header" style="background: url('{{ asset('img/home-bg.jpg') }}') repeat;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>Menéame Responde</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <form id="main-search" method="GET">
                <div class="form-group">
                    <input type="search" name="q" value="" class="form-control input-lg" placeholder="Buscar..." />
                </div>
            </form>

            @foreach ($posts as $post)
                @include('pages.post.list')
            @endforeach

            @include('molecules.paginate', [
                'result' => $posts
            ])
        </div>
    </div>
</div>

@stop