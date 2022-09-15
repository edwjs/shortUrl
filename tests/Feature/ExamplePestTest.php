<?php

use App\Models\ShortUrl;
use Illuminate\Support\Str;

use function Pest\Laravel\deleteJson;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function() {
    $this->randomCode = Str::random(6);    
    ds($this->randomCode);
});

it('has example page', function () {
    $shortUlr = ShortUrl::factory()->create(['code' => 'xpto']);

    expect($this->randomCode)->toBeString();
    ds($this->randomCode . ':: first test');

    // high order test
    expect($shortUlr)
        ->code
        ->toBeString()
        ->toBe('xpto')
        ->toHaveLength(4)
        ->url
        ->toBeString();
    
    expect([1,2,3])
        ->toBeArray()
        ->toHaveCount(3);
});

it('should be able to delete a short url', function () {
    $shortUlr = ShortUrl::factory()->create();    
    $route = route('api.short-url.destroy', $shortUlr->code);

    expect($this->randomCode)->toBeString();
    ds($this->randomCode . ':: second test');
    
    expect(deleteJson($route))
        ->assertStatus(Response::HTTP_NO_CONTENT);
    
    $this->assertDatabaseMissing('short_urls', [
        'id' => $shortUlr->id,
    ]);
});

// Using dataset
it('should return a no-content status when deleting a short url', function($shortUlr) {    
    deleteJson(route('api.short-url.destroy', $shortUlr->code))
        ->assertStatus(Response::HTTP_NO_CONTENT);
})->with([
    'with abcd' => fn () => ShortUrl::factory()->create(['code' => 'abcd']),
    'with efgh' => fn () => ShortUrl::factory()->create(['code' => 'efgh']),
]);
