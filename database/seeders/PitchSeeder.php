<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stadium;
use App\Models\Pitch;

class PitchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stadiums = Stadium::all();

        foreach ($stadiums as $stadium) {
            // Create 3 pitches per stadium with different slot types and operating hours
            for ($i = 1; $i <= 3; $i++) {
                // Alternate between 60 and 90 minute slots
                $slotType = $i % 2 === 1 ? '60' : '90';
                
                // Different operating hours for each pitch
                $operatingHours = [
                    1 => ['start' => '06:00:00', 'end' => '22:00:00'],
                    2 => ['start' => '08:00:00', 'end' => '23:00:00'],
                    3 => ['start' => '10:00:00', 'end' => '24:00:00'],
                ];

                Pitch::create([
                    'stadium_id' => $stadium->id,
                    'name' => "Pitch $i",
                    'type' => 'football',
                    'surface' => ['grass', 'artificial', 'grass'][$i - 1],
                    'hourly_rate_60' => [170, 190, 220][$i - 1],
                    'hourly_rate_90' => [240, 270, 310][$i - 1],
                    'status' => 'available',
                    'capacity' => [22, 18, 14][$i - 1],
                    'description' => "Professional football pitch with high-quality " . ['grass', 'artificial', 'grass'][$i - 1] . " surface.",
                    'amenities' => [
                        ['Floodlights', 'Changing rooms', 'Parking'],
                        ['Floodlights', 'Changing rooms', 'Parking', 'Restaurant'],
                        ['Floodlights', 'Changing rooms', 'Parking', 'Restaurant', 'Pro Shop']
                    ][$i - 1],
                    'operating_start_time' => $operatingHours[$i]['start'],
                    'operating_end_time' => $operatingHours[$i]['end'],
                    'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    'slot_type' => $slotType,
                ]);
            }
        }
    }
}
