<?php

namespace Tests\Feature\ShortUrl;

use App\Facades\Actions\CodeGenerator;
use Tests\TestCase;
use Illuminate\Support\Str;

use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_short_url()
    {
        // $this->withoutExceptionHandling();

        $randomCode = Str::random(5);
        CodeGenerator::shouldReceive('run')->once()->andReturn($randomCode);

        $this->postJson(
            route('api.short-urls.store'), 
            ['url' => 'https://www.google.com.br'])
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJson([            
            'short_url' => config('app.url') . '/' . $randomCode,
        ]);

        $this->assertDatabaseHas('short_urls', [
            'url' => 'https://www.google.com.br',
            'short_url' => config('app.url') . '/' . $randomCode,
            'code' => $randomCode,
        ]);
    }
    
}
