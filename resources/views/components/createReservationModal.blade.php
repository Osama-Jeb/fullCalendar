<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRes">
    Create Reservation
</button>

<!-- Modal -->
<div class="modal fade" id="createRes" tabindex="-1" aria-labelledby="createResLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createResLabel">Reserve</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action={{route("calendar.store")}} method="POST">
                    @csrf
                    <div>
                        <label for="title">Title of Reservation</label>
                        <input type="text" name="title" id="title" required>
                    </div>

                    <div>
                        <label for="description">description of Reservation</label>
                        <textarea name="description" id="description" cols="30" rows="5"></textarea>
                    </div>

                    <div>
                        <label for="classe_id">classe: </label>
                        <select name="classe_id" id="classe_id">Choose classe
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="start_time">Starting Time: </label>
                        <input type="datetime-local" step="3600" name="start_time" id="start_time"
                            min="{{ date('Y-m-d') }}T09:00" required>
                    </div>

                    <div>
                        <label for="finish_time">Starting Time: </label>
                        <input type="datetime-local" step="3600" name="finish_time" id="finish_time"
                            min="{{ date('Y-m-d') }}T09:00" required>
                    </div>

                    <button class="btn btn-primary" type="submit">Create Reservation</button>
                </form>
            </div>

        </div>
    </div>
</div>
