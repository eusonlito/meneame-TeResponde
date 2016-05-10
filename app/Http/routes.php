<?php
$app->get('/', ['as' => 'site.index', 'uses' => 'Site@index']);
$app->get('/list', ['as' => 'site.list', 'uses' => 'Site@list']);
$app->get('/post/{id:[0-9]+}/{slug}', ['as' => 'site.post', 'uses' => 'Site@post']);
$app->get('/about', ['as' => 'site.about', 'uses' => 'Site@about']);