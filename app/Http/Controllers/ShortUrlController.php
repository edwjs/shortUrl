<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlController extends Controller
{
    public function store()
    {
        $url = request('url');

        $shortUrl = ShortUrl::query()->create([
            'url' => $url,
            'short_url' => config('app.url') . '/YH12',
            'code' => 'YH12'
        ]);

        return response()->json([
            'short_url' => $shortUrl->short_url
        ], Response::HTTP_CREATED);
    }
}
