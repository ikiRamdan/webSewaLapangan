<?php

namespace App\Services;

use App\Models\TransactionDetail;
use Carbon\Carbon;

class BookingService
{
    private $openHour = '08:00';
    private $closeHour = '22:00';

    public function validateOperatingHours($start, $end)
    {
        $startTime = Carbon::parse($start)->format('H:i');
        $endTime = Carbon::parse($end)->format('H:i');

        if ($startTime < $this->openHour || $endTime > $this->closeHour) {
            return false;
        }

        return true;
    }

    public function calculateDuration($start, $end)
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        return $startTime->diffInHours($endTime);
    }

    public function calculateSubtotal($duration, $price)
    {
        return $duration * $price;
    }

    public function isFieldAvailable($fieldId, $start, $end)
    {
        $conflict = TransactionDetail::where('field_id', $fieldId)
            ->where(function ($query) use ($start, $end) {

                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);

            })
            ->exists();

        return !$conflict;
    }
}