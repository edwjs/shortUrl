<?php

namespace Tests\Feature\ShortUrl;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /**
     * @dataProvider urls 
     * @test
     * */
    public function it_can_delete_a_short_url($url)
    {
        $shortUlr = ShortUrl::factory()->create(['code' => $url]);
        
        $this->deleteJson(route('api.short-urls.destroy', $shortUlr->code))
            ->assertStatus(Response::HTTP_NO_CONTENT);
        
        $this->assertDatabaseMissing('short_urls', [
            'id' => $shortUlr->id,
        ]);
    }

    // dataset/dataprovider in phpunit
    public function urls(): array
    {
        return [
            'with abcd' => ['abcd'],
            'with efgh' => ['efgh'],
        ];
    }    
}
