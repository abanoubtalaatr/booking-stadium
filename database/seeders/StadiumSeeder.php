<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stadium;

class StadiumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stadiums = [
            [
                'name' => 'Al-Nadi Sports Complex',
                'address' => '123 Sports Avenue, Dubai, UAE',
                'description' => 'A premium sports complex featuring multiple football pitches with state-of-the-art facilities.',
                'capacity' => 500,
                'status' => 'active',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
                'phone' => '+971-4-123-4567',
                'email' => 'info@alnadi-sports.com',
            ],
            [
                'name' => 'Victory Stadium',
                'address' => '456 Championship Road, Abu Dhabi, UAE',
                'description' => 'Modern stadium complex with professional-grade football pitches and excellent amenities.',
                'capacity' => 750,
                'status' => 'active',
                'latitude' => 24.4539,
                'longitude' => 54.3773,
                'phone' => '+971-2-987-6543',
                'email' => 'bookings@victory-stadium.com',
            ],
        ];

        foreach ($stadiums as $stadium) {
            Stadium::create($stadium);
        }
    }
}
