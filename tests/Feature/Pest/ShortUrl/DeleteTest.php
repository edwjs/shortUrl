<?php

use App\Models\ShortUrl;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\deleteJson;

it('can delete a short url', function () {
    $shortUlr = ShortUrl::factory()->create();
        
    expect(deleteJson(route('api.short-urls.destroy', $shortUlr->code)))
        ->assertStatus(Response::HTTP_NO_CONTENT);
    
    $this->assertDatabaseMissing('short_urls', [
        'id' => $shortUlr->id,
    ]);
});
