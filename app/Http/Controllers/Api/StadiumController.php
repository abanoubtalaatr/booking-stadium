<?php

namespace App\Http\Controllers\Api;

use App\Models\Stadium;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StadiumResource;

class StadiumController extends Controller
{
    protected BookingService $bookingService;
    use ApiResponse;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of all active stadiums.
     */
    public function index(): JsonResponse
    {
        $stadiums = Stadium::active()
            ->with(['pitches' => function ($query) {
                $query->where('status', 'available');
            }])
            ->get();

        $stadiums= StadiumResource::collection($stadiums);
            
        return $this->success($stadiums, 'Stadiums retrieved successfully');
    
    }

    /**
     * Display the specified stadium with its pitches.
     */
    public function show(int $id): JsonResponse
    {
        $stadium = Stadium::with(['pitches' => function ($query) {
            $query->where('status', 'available');
        }])->findOrFail($id);

        return $this->success(new StadiumResource($stadium), 'Stadium retrieved successfully');
    }
}
