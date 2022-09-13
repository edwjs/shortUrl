<?php

namespace Tests\Feature\ShortUrl;

use App\Facades\Actions\CodeGenerator;
use App\Models\ShortUrl;
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

    /** @test */
    public function test_url_should_be_valid_url()
    {
        $this->postJson(
            route('api.short-urls.store'), 
            ['url' => 'not-valid-url'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([                
                'url' => __('validation.url', ['attribute' => 'url']),                
            ]);
    }
    
    /** @test */
    public function test_if_should_return_the_existed_code_if_the_url_is_the_same()
    {
        ShortUrl::factory()->create([
            'url' => 'https://www.google.com.br',
            'short_url' => config('app.url') . '/123456',
            'code' => '123456'
        ]);

        $this->postJson(
            route('api.short-urls.store'), 
            ['url' => 'https://www.google.com.br'])        
        ->assertJson([            
            'short_url' => config('app.url') . '/123456',
        ]);

        $this->assertDatabaseCount('short_urls', 1);
    }
}
