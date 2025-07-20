<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PitchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'stadium_id' => 'required|exists:stadiums,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:football,basketball,tennis,volleyball,other',
            'surface' => 'required|in:grass,artificial,clay,concrete',
            'hourly_rate_60' => 'required|numeric|min:0',
            'hourly_rate_90' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable,maintenance',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'operating_start_time' => 'required|date_format:H:i',
            'operating_end_time' => 'required|date_format:H:i|after:operating_start_time',
            'operating_days' => 'required|array|min:1',
            'operating_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'slot_type' => 'required|in:60,90',
        ];
    }
}
