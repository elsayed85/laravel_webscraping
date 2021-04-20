<?php

use App\Services\EgyBest;
use App\Services\GenLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('egybest/search/{title}', function ($title) {
    // try {
    //     $data = EgyBest::search($title, request('page'));
    // } catch (\Throwable $th) {
    //     return response()->json(['error' => $th->getMessage()], 500);
    // }
    $data = EgyBest::search($title, request('page'));
    return response()->json(['data' => $data]);
});

Route::get('egybest/movie/{slug}', function ($slug) {
    // try {
    //     $data = EgyBest::show($slug);
    // } catch (\Throwable $th) {
    //     return response()->json(['error' => $th->getMessage()], 500);
    // }
    $data = EgyBest::show($slug);

    if (is_string($data)) {
        return response()->json(['error' => $th->getMessage()] , 404);
    }

    return response()->json(['data' => $data]);
});

Route::get('genlib/search/{query}', function ($query) {
    try {
        $data = GenLib::search($query, request('page'), request('per_page'), request('sort'), request('sort_by'));
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
    return response()->json(['data' => $data]);
});

Route::get('genlib/download/{md5}', function ($md5) {
    try {
        $data = GenLib::download($md5);
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
    return response()->json(['data' => $data]);
});
