<?php

namespace Tests\Feature\ShortUrl;

use App\Models\ShortUrl;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StatsTest extends TestCase
{
   /** @test */
   public function it_should_return_the_last_visit_on_the_short_url()
   {
        // $this->withoutExceptionHandling();

        $shortUrl = ShortUrl::factory()->createOne();
        $this->get($shortUrl->code);

        $this->getJson(route('api.short-urls.stats.last-visit', $shortUrl->code))
                ->assertSuccessful()
                ->assertJson([
                    'last_visit' => $shortUrl->last_visit?->toIso8601String(),
                ]);

        $this->assertDatabaseHas('visits', [
            'short_url_id' => $shortUrl->id,
            'created_at' => Carbon::now(),
        ]);
   }

   /** @test */
   public function it_should_return_the_amount_per_day_of_visits_with_a_total()
   {
        // $this->withoutExceptionHandling();

        $shortUrl = ShortUrl::factory()->createOne();

        Visit::factory()
        ->count(12)
        ->state(new Sequence(
            ['created_at' => Carbon::now()->subDays(3)],
            ['created_at' => Carbon::now()->subDays(2)],
            ['created_at' => Carbon::now()->subDay()],
            ['created_at' => Carbon::now()],
        ))
        ->create([
            'short_url_id' => $shortUrl->id,            
        ]);
                
        $this->getJson(route('api.short-urls.stats.visits', $shortUrl->code))
            ->assertSuccessful()
            ->assertJson([
                'total' => 12,
                'visits' => [
                    [
                        'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                        'count' => 3,
                    ],
                    [
                        'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                        'count' => 3,
                    ],
                    [
                        'date' => Carbon::now()->subDay()->format('Y-m-d'),
                        'count' => 3,
                    ],
                    [
                        'date' => Carbon::now()->format('Y-m-d'),
                        'count' => 3,
                    ],
                ]
            ]);

            $this->assertDatabaseCount('visits', 12);
   }
   

   
}
