<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Stadium;
use App\Models\Pitch;
use App\Models\Booking;
use Carbon\Carbon;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    protected Stadium $stadium;
    protected Pitch $pitch60;
    protected Pitch $pitch90;

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
    }

    private function getValidBookingData($pitch = null)
    {
        $pitch = $pitch ?? $this->pitch60;
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        
        return [
            'pitch_id' => $pitch->id,
            'user_name' => 'Test User',
            'user_email' => 'test@example.com',
            'user_phone' => '+1234567890',
            'booking_date' => $tomorrow,
            'start_time' => '10:00',
            'end_time' => $pitch->slot_type === '60' ? '11:00' : '11:30',
            'duration_minutes' => (int)$pitch->slot_type,
        ];
    }

    /** @test */
    public function it_can_create_a_valid_booking()
    {
        $bookingData = $this->getValidBookingData();
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'pitch_id',
                         'user_name',
                         'user_email',
                         'user_phone',
                         'booking_date',
                         'start_time',
                         'end_time',
                         'duration_minutes',
                         'total_price',
                         'status',
                         'created_at',
                         'updated_at'
                     ],
                     'message'
                 ]);

        // Check that booking was created in database
        $this->assertDatabaseHas('bookings', [
            'pitch_id' => $bookingData['pitch_id'],
            'user_email' => $bookingData['user_email'],
            'start_time' => $bookingData['start_time'],
            'status' => 'confirmed',
        ]);
        
        // Verify the date is stored correctly (can be either date or datetime format)
        $booking = Booking::where('user_email', $bookingData['user_email'])->first();
        $this->assertEquals($bookingData['booking_date'], $booking->booking_date->format('Y-m-d'));
    }

    /** @test */
    public function it_prevents_overbooking_same_slot()
    {
        $bookingData = $this->getValidBookingData();
        
        // Create first booking
        $this->postJson('/api/bookings', $bookingData)
             ->assertStatus(200);
        
        // Try to book the same slot again
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slot']);
                 
        // Verify the error message is present
        $responseData = $response->json();
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('slot', $responseData['errors']);
        $this->assertContains('This time slot is already booked.', $responseData['errors']['slot']);
    }

    /** @test */
    public function it_prevents_overlapping_bookings()
    {
        $bookingData = $this->getValidBookingData();
        
        // Create first booking: 10:00-11:00
        $this->postJson('/api/bookings', $bookingData)
             ->assertStatus(200);
        
        // Try to book overlapping slot: 10:30-11:30
        $overlappingData = $bookingData;
        $overlappingData['start_time'] = '10:30';
        $overlappingData['end_time'] = '11:30';
        
        $response = $this->postJson('/api/bookings', $overlappingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slot']);
                 
        // Verify the error message is present
        $responseData = $response->json();
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('slot', $responseData['errors']);
        $this->assertContains('This time slot is already booked.', $responseData['errors']['slot']);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/bookings', []);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'pitch_id',
                     'user_name',
                     'user_email',
                     'user_phone',
                     'booking_date',
                     'start_time',
                     'end_time',
                     'duration_minutes'
                 ]);
    }

    /** @test */
    public function it_validates_pitch_exists()
    {
        $bookingData = $this->getValidBookingData();
        $bookingData['pitch_id'] = 999; // Non-existent pitch
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['pitch_id']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $bookingData = $this->getValidBookingData();
        $bookingData['user_email'] = 'invalid-email';
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_email']);
    }

    /** @test */
    public function it_validates_future_date()
    {
        $bookingData = $this->getValidBookingData();
        $bookingData['booking_date'] = Carbon::yesterday()->format('Y-m-d');
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['booking_date']);
    }

    /** @test */
    public function it_validates_time_format()
    {
        $bookingData = $this->getValidBookingData();
        $bookingData['start_time'] = 'invalid-time';
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['start_time']);
                 
        // Verify the error message contains time format information
        $responseData = $response->json();
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('start_time', $responseData['errors']);
    }

    /** @test */
    public function it_validates_end_time_after_start_time()
    {
        $bookingData = $this->getValidBookingData();
        $bookingData['start_time'] = '11:00';
        $bookingData['end_time'] = '10:00'; // End before start
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['end_time']);
    }

    /** @test */
    public function it_validates_duration_matches_slot_type_for_60_minute_pitch()
    {
        $bookingData = $this->getValidBookingData($this->pitch60);
        $bookingData['duration_minutes'] = 90; // Wrong duration for 60-minute pitch
        $bookingData['end_time'] = '11:30';
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['duration_minutes']);
    }

    /** @test */
    public function it_validates_duration_matches_slot_type_for_90_minute_pitch()
    {
        $bookingData = $this->getValidBookingData($this->pitch90);
        $bookingData['duration_minutes'] = 60; // Wrong duration for 90-minute pitch
        $bookingData['end_time'] = '11:00';
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['duration_minutes']);
    }


    /** @test */
    public function it_validates_booking_within_operating_hours()
    {
        $bookingData = $this->getValidBookingData();
        $bookingData['start_time'] = '07:00'; // Before operating hours (08:00)
        $bookingData['end_time'] = '08:00';
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['start_time']);
    }

    /** @test */
    public function it_validates_booking_on_operating_days()
    {
        // Create a pitch that only operates on weekdays
        $weekdayPitch = Pitch::create([
            'stadium_id' => $this->stadium->id,
            'name' => 'Weekday Pitch',
            'type' => 'football',
            'surface' => 'grass',
            'hourly_rate_60' => 100.00,
            'hourly_rate_90' => 150.00,
            'status' => 'available',
            'capacity' => 22,
            'operating_start_time' => '08:00:00',
            'operating_end_time' => '22:00:00',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'slot_type' => '60',
        ]);

        $bookingData = $this->getValidBookingData($weekdayPitch);
        $bookingData['booking_date'] = Carbon::now()->next(Carbon::SATURDAY)->format('Y-m-d');
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['booking_date']);
    }

    /** @test */
    public function it_prevents_booking_unavailable_pitch()
    {
        // Set pitch as unavailable
        $this->pitch60->update(['status' => 'unavailable']);
        
        $bookingData = $this->getValidBookingData($this->pitch60);
        
        $response = $this->postJson('/api/bookings', $bookingData);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['pitch_id']);
    }
}
