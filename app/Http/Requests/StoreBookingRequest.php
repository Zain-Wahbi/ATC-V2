<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_reference' => ['nullable', 'string', 'unique:bookings,booking_reference'],
            'customer_id' => ['required', 'exists:customers,id'],
            'flight_id' => ['required', 'exists:flights,id'],
            'seat_id' => [
                'required',
                'exists:seats,id',
                function ($attribute, $value, $fail) {
                    $seat = \App\Models\Seat::find($value);
                    if ($seat && $seat->is_booked) {
                        $fail('This seat is already booked.');
                    }
                },
            ],
            'overweight' => ['nullable', 'integer', 'min:0'],
            'booking_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'flight_id.required' => 'Please select a flight.',
            'seat_id.required' => 'Please select a seat.',
        ];
    }
}