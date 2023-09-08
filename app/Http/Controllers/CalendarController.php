<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Classe;
use DateTime;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $allReservations = Calendar::all();
        $classes = Classe::all();

        // Create an Empty Array
        $events = array();

        foreach ($allReservations as $reservation) {
            // if the reservation is not canceled
            if ($reservation->cancel == false) {

                // Fill the array with needed informations
                $events[] = [
                    "id" => $reservation->id,
                    "title" => $reservation->title,
                    "start" => $reservation->start_time,
                    "end" => $reservation->finish_time,
                ];
            }
        }
        return view("Frontend.pages.calendar", compact("classes", "events", "allReservations"));
    }


    // ^^ Simple Store with Conditions
    public function store(Request $request)
    {
        request()->validate([
            "title" => ["required"],
            "description" => ["required"],
            "classe_id" => ["required"],
            "start_time" => ["required"],
            "finish_time" => ["required"],
        ]);

        $allReservations = Calendar::all();

        // Convert starting and finishing time to the same format
        $userStartTime = new DateTime($request->start_time);
        $userEndTime = new DateTime($request->finish_time);

        // Check if the ending time is not before the starting time
        if ($userEndTime < $userStartTime) {
            return redirect()->back()->with("error", "the ending time was before the starting time");
        }

        foreach ($allReservations as $reservation) {
            // To ignore canceled Reservations
            if ($reservation->cancel === 0) {
                //* Only Make conditions when reserving the same class 
                if ($reservation->classe_id === (int)$request->classe_id) {

                    // Convert the times in database to same format
                    $baseStartTime = new DateTime($reservation->start_time);
                    $baseEndTime = new DateTime($reservation->finish_time);

                    // Check if the user's time period overlaps with any reservation
                    if ($userStartTime >= $baseStartTime && $userEndTime <= $baseEndTime) {
                        return redirect()->back()->with("error", "There's already a reservation in this time.");
                    }

                    // Check if the user's time period contains a reservation
                    if ($userStartTime <= $baseStartTime && $userEndTime >= $baseEndTime) {
                        return redirect()->back()->with("error", "This time coincides with a reservation.");
                    }

                    // Check if the user's starting or ending time is during a reservation
                    if (($userStartTime >= $baseStartTime && $userStartTime < $baseEndTime) ||
                        ($userEndTime > $baseStartTime && $userEndTime <= $baseEndTime)
                    ) {
                        return redirect()->back()->with("error", "Starting or ending time is during a reservation.");
                    }
                }
            }
        }

        Calendar::create([
            "title" => $request->title,
            "description" => $request->description,
            "classe_id" => $request->classe_id,
            "start_time" => $request->start_time,
            "finish_time" => $request->finish_time,
            // by default all new reservation are set to false
            "cancel" => false,
        ]);

        return redirect()->back()->with("success", "Reservation made successfully!!");
    }

    // ""delete""
    // Get the id of the reservation we want to cancel
    public function cancel($id)
    {
        $reservation = Calendar::find($id);
        // update the cancel column to true => it will be ignored in the index()
        $reservation->update(
            [
                "cancel" => true,
            ]
        );
        return $id;
    }

    // THE UPDATE FUNCTION-tion-tion
    public function update($id)
    {
        //todo Add Overlap Verification 
        // find the reservation with the same id
        $reservation = Calendar::find($id);

        // put all column that were validated in a variable
        $validatedData = request()->validate([
            "title" => "required",
            "start_time" => "required",
            "finish_time" => "required",
            "classe_id" => "required",
        ]);

        // update the reservation
        $reservation->update($validatedData);

        return $reservation;
    }
}
