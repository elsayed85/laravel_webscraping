<?php

use App\Services\EgyBest;
use App\Services\GenLib;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;


Route::get('/', function () {
    return view('search');
});


Route::get('test/{title}', function ($title) {
    dd(EgyBest::search($title));
});

Route::get('show/{slug}', function ($slug) {
    dd(EgyBest::show($slug));
});
