@extends('layout.html')

@section('body')

<header class="intro-header" style="background-image: url('{{ asset('img/home-bg.jpg') }}')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>Menéame Responde</h1>
                    <hr class="small">
                    <span class="subheading">Aclara todas tus dudas</span>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
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