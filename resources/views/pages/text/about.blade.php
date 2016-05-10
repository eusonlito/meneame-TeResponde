@extends('layout.html')

@section('body')

<header class="intro-header" style="background-image: url('{{ asset('img/contact-bg.jpg') }}')">
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
            <ul>
                <li>El código fuente lo tienes disponible en <a href="https://github.com/eusonlito/meneame-responde" target="_blank">GitHub</a></li>
                <li>Las imágenes utilizadas en las cabeceras son obras Creative Commons obtenidas de <a href="http://www.uhdwallpapers.org/p/creative-commons-images.html" target="_blank">http://www.uhdwallpapers.org/p/creative-commons-images.html</a> y <a href="http://christmasstockimages.com/free/Stars/slides/blue_star_backdrop.htm" target="_blank">http://christmasstockimages.com/free/Stars/slides/blue_star_backdrop.htm</a></li>
                <li>El tema utilizado es el distribuido bajo licencia MIT por <a href="https://github.com/BlackrockDigital/startbootstrap-clean-blog" target="_blank">https://github.com/BlackrockDigital/startbootstrap-clean-blog</a></li>
                <li>El framework de desarrollo es <a href="https://lumen.laravel.com/" target="_blank">Lumen</a></li>
                <li>El servidor usa Ubuntu 16.04 + nginx + PHP7-FPM + MySQL 5.7</li>
                <li>Aquí tienes las <a href="https://meneame-responde.lito.com.es/stats/report.html" target="_blank">estadísticas del proyecto.</li>
                <li>Yo soy <a href="https://about.me/lito" target="_blank">Lito</a></li>
                <li>Este proyecto me ha llevado unas <a href="https://github.com/eusonlito/meneame-responde" target="_blank">10 horas</a></li>
            </ul>
        </div>
    </div>
</div>

@stop