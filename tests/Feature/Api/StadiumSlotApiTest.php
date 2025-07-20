<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Stadium;
use App\Models\Pitch;
use App\Models\Booking;
use Carbon\Carbon;

class StadiumSlotApiTest extends TestCase
{
    use RefreshDatabase;

    protected Stadium $stadium;
    protected Pitch $pitch60;
    protected Pitch $pitch90;
    protected Pitch $pitchWeekdaysOnly;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create a stadium
        $this->stadium = Stadium::create([
            'name' => 'Test Stadium',
            'address' => '123 Test Street',
            'capacity' => 50000,
            'status' => 'active',
        ]);

        // Create a pitch with 60-minute slots
        $this->pitch60 = Pitch::create([
            'stadium_id' => $this->stadium->id,
            'name' => 'Pitch 60min',
            'type' => 'football',
            'surface' => 'grass',
            'hourly_rate_60' => 100.00,
            'hourly_rate_90' => 150.00,
            'status' => 'available',
            'capacity' => 22,
            'operating_start_time' => '08:00:00',
            'operating_end_time' => '22:00:00',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            'slot_type' => '60',
        ]);

        // Create a pitch with 90-minute slots
        $this->pitch90 = Pitch::create([
            'stadium_id' => $this->stadium->id,
            'name' => 'Pitch 90min',
            'type' => 'football',
            'surface' => 'artificial',
            'hourly_rate_60' => 120.00,
            'hourly_rate_90' => 180.00,
            'status' => 'available',
            'capacity' => 18,
            'operating_start_time' => '09:00:00',
            'operating_end_time' => '21:00:00',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            'slot_type' => '90',
        ]);

        // Create a pitch that doesn't operate on weekends
        $this->pitchWeekdaysOnly = Pitch::create([
            'stadium_id' => $this->stadium->id,
            'name' => 'Weekdays Only Pitch',
            'type' => 'football',
            'surface' => 'grass',
            'hourly_rate_60' => 80.00,
            'hourly_rate_90' => 120.00,
            'status' => 'available',
            'capacity' => 22,
            'operating_start_time' => '06:00:00',
            'operating_end_time' => '18:00:00',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'slot_type' => '60',
        ]);
    }
    /** @test */
    public function it_returns_error_for_invalid_stadium()
    {
        $date = Carbon::tomorrow()->format('Y-m-d');
        
        $response = $this->getJson("/api/stadiums/999/available-slots?date={$date}");
        
        $response->assertStatus(404);
    }
}
