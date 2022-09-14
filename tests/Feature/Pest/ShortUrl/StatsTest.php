<?php

use Carbon\Carbon;
use App\Models\Visit;

use App\Models\ShortUrl;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('should return the last visit on the short url', function () {
    $shortUrl = ShortUrl::factory()->createOne();
    
    get($shortUrl->code);

    expect(getJson(route('api.short-urls.stats.last-visit', $shortUrl->code)))
            ->assertSuccessful()
            ->assertJson([
                'last_visit' => $shortUrl->last_visit?->toIso8601String(),
            ]);

    $this->assertDatabaseHas('visits', [
        'short_url_id' => $shortUrl->id,
        'created_at' => Carbon::now(),
    ]);
});

it('should return the amount per day of visits with a total', function () {
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
                
    expect(getJson(route('api.short-urls.stats.visits', $shortUrl->code)))
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
});

