<?php

namespace Tests\Feature\ShortUrl;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_short_url()
    {
        $this->withoutExceptionHandling();

        $this->postJson(
            route('api.short-urls.store'), 
            ['url' => 'https://www.google.com.br'])
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJson([            
            'short_url' => config('app.url') . '/YH12',
        ]);

        $this->assertDatabaseHas('short_urls', [
            'url' => 'https://www.google.com.br',
            'short_url' => config('app.url') . '/YH12',
            'code' => 'YH12',
        ]);
    }
    
}
