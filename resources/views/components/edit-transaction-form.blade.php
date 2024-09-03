<form method="POST">
    @csrf
    <input type="hidden" id="transaction" name="transaction" value="{{ $transaction->id }}">
    <div class="modal-body ">
        <div class="mb-3">
            <label for="transaction-date" class="form-label">Data</label>
            <input class="form-control" type="date" id="transaction-date" name="transaction-date"
                value="{{ $transaction->date }}">
        </div>
        <div class="mb-3">
            <label for="transaction-amount" class="form-label">Kwota</label>
            <input class="form-control" type="currency" id="transaction-amount" name="transaction-amount"
                value="@if (isset($transaction)) {{ $transaction->amount }} @else 0,00 zł @endif" />
        </div>
        <div class="mb-3">
            <label for="transaction-date" class="form-label">Nazwa</label>
            <input class="form-control" type="text" id="transaction-name" name="transaction-name"
                value="{{ $transaction->name }}">
        </div>
        <div class="mb-3">
            <label for="transaction-category" class="form-label">Kategoria</label>
            <select class="form-select" id="transaction-category" name="transaction-category">
                @foreach ($financeCategories as $category)
                    <option value="{{ $category->id }}" @if ($category->id == $transaction->category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="transaction-event" class="form-label">Wydarzenie</label>
            <select class="form-select" id="transaction-event" name="transaction-event">
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" @if (isset($transaction) && isset($transaction->event) && $transaction->event->id == $event->id) selected @endif>
                        {{ $event->name }} - {{ date('Y', strtotime($event->date)) }} #{{ $event->id }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <input class="form-check-input" type="checkbox" id="transaction-cash" name="transaction-cash"><label
                class="form-check-label" for="transaction-cash">Gotówka</label>
        </div>
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton">Anuluj</button>
        <button type="submit" name="action" value="editSummary" class="btn btn-success">Zapisz</button>
    </div>
</form>
