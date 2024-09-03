<div class="modal fade" id="editEventSummaryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edytuj szczegóły - {{ $event->name }} - {{ date('Y', strtotime($event->date)) }}</h5>
            </div>
            <form method="POST">
                @csrf
                <input type="hidden" id="event" name="event" value="{{ $event->id }}">
                <div class="modal-body ">
                    <div class="form-group">
                        <label for="event-date">Nazwa</label>
                        <input type="text" id="event-name" name="event-name" value="{{ $event->name}}">
                    </div>
                    <div class="form-group">
                        <label for="event-date">Data</label>
                        <input type="date" id="event-date" name="event-date" value="{{ $event->date}}">
                    </div>
                    <div class="form-group">
                        <label for="event-type">Typ</label>
                        <select id="event-type" name="event-type">
                            @foreach ($eventTypes as $type)
                                <option value="{{ $type->id }}"
                                    @if ($type->id == $event->type->id) selected @endif>{{ $type->value }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="editSummary" class="btn btn-primary">Zapisz</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="cancelButton">Anuluj</button>
                </div>
            </form>
        </div>
    </div>
</div>
