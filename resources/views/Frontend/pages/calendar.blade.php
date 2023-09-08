@extends('layouts.index')

@section('content')
    <h1>Calendar</h1>
    @include('components.createReservationModal')

    @include('layouts.flash')
    <div id="calendar"></div>

    <div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">

                    <input type="text" name="inputTitle" id="inputTitle">

                    <input type="text" name="inputClasse" id="inputClasse">

                    <input type="datetime-local" step="3600" name="inputStart" id="inputStart"
                        min="{{ date('Y-m-d') }}T09:00" required>

                    <input type="datetime-local" step="3600" name="inputEnd" id="inputEnd"
                        min="{{ date('Y-m-d') }}T09:00" required>

                    <button id="deleteEvent" class="btn btn-danger">Cancel Reservation</button>

                    <button class="btn btn-success" id="updateEvent">Update event</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // will be updated every time we click on a new event
        var selectedEvent;
        // it says we need to do this to convert php collection to jQuery array ?? not sure
        var reservations = @json($allReservations);

        // the ""delete" button
        $("#deleteEvent").click(function() {
            // if an event is selected
            if (selectedEvent) {
                // get the id
                var id = selectedEvent.id;

                // ajax request to the route we defined in web.php
                $.ajax({
                    url: "/calendar/update/cancel/" + id,
                    // PUT because we modify a value. Not delete it
                    type: "PUT",
                    dataType: "json",
                    success: function(response) {
                        // Dghanich bach t refresha l page o tban l modifcation
                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    },
                });
                // this is to hide the modal
                $("#eventDetailModal").modal("hide");
            }
        });

        // THE UPDATE BUTTON-ton-on-n
        $("#updateEvent").click(function() {
            if (selectedEvent) {
                var id = selectedEvent.id;

                // Create the data we want to send by filling it with the values in the input li kibano when we click 3la l event
                var data = {
                    id: id,
                    title: $("#inputTitle").val(),
                    start_time: $("#inputStart").val(),
                    finish_time: $("#inputEnd").val(),
                    classe_id: $("#inputClasse").val(),
                    cancel: $("#inputCancel").val(),
                };

                $.ajax({
                    url: "/calendar/update/" + id,
                    type: "PUT",
                    dataType: "json",
                    // Send the data to the Update Function in the Controller
                    data: data,
                    success: function(response) {
                        // Dghanich Magic
                        location.reload();
                    },
                    error: function(error) {
                        alert("-_- Try Again -_-");
                        console.log(error);
                    },
                });

                $("#eventDetailModal").modal("hide");
            }
        });

        $(document).ready(function() {
            //CSRF Token for Ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Launch the Calendar
            var calendar = $("#calendar").fullCalendar({
                // Header Details
                header: {
                    left: 'prev,next today,month,agendaWeek,agendaDay,',
                    center: 'title',
                    right: ' listDay, listWeek, listMonth'
                },

                defaultView: "agendaWeek",

                //!!! The Array we made in the index() is converted to json as value for FullCalendar events property
                events: @json($events),

                // Function for when clicking on an event on the calendar
                eventClick: function(event, jsEvent, view) {
                    $('#eventDetailModal').modal('show');
                    // this is the value we update each time we click on an event
                    selectedEvent = event;

                    // Loop through each reservations until we find the one that has the same ID as our event
                    reservations.forEach(reservation => {
                        if (reservation.id === event.id) {
                            // Fill the inputs with values from the chosen reservation
                            $("#inputTitle").val(reservation.title);
                            $("#inputClasse").val(reservation.classe_id);
                            $("#inputStart").val(reservation.start_time);
                            $("#inputEnd").val(reservation.finish_time);

                        }
                    });

                },
                // Kaynin bzf dial properties f FullCalendar if u want to know let me know

            })
        });
    </script>
@endsection
