<?php

namespace App\Rules;

use App\Models\Calendar;
use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;

class ReservationClasseOverlap implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Convert starting and finishing time to the same format
        $userStartTime = new DateTime($this->request->start_time);
        $userEndTime = new DateTime($this->request->finish_time);

        // Check if the ending time is not before the starting time
        if ($userEndTime < $userStartTime) {
            $fail("invalid Reservation Time");
        }

        $allReservations = Calendar::all();

        foreach ($allReservations as $reservation) {
            // Ignore canceled reservations
            if ($reservation->cancel === false) {
                // Only check for reservations of the same class
                if ($reservation->classe_id === (int)$this->request->classe_id) {
                    $baseStartTime = new DateTime($reservation->start_time);
                    $baseEndTime = new DateTime($reservation->finish_time);

                    // Check if the user's time period overlaps with any reservation
                    if ($userStartTime >= $baseStartTime && $userEndTime <= $baseEndTime) {
                        $fail("invalid Reservation Time");
                    }

                    // Check if the user's time period contains a reservation
                    if ($userStartTime <= $baseStartTime && $userEndTime >= $baseEndTime) {
                        $fail("invalid Reservation Time");
                    }

                    // Check if the user's starting or ending time is during a reservation
                    if (($userStartTime >= $baseStartTime && $userStartTime < $baseEndTime) ||
                        ($userEndTime > $baseStartTime && $userEndTime <= $baseEndTime)
                    ) {
                        $fail("invalid Reservation Time");
                    }
                }
            }
        }

        // return true;
    }
}
