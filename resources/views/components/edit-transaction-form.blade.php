<form method="POST" id="edit-transaction-form">
    @csrf
    <input type="hidden" id="transaction" name="transaction" value="{{ $transaction->tr_id }}">
    <div class="modal-body ">
        <div class="mb-3">
            <label for="transaction-date" class="form-label">Data</label>
            <input class="form-control" type="date" id="transaction-date" name="date" required
                value="{{ $transaction->date }}">
        </div>
        <div class="mb-3">
            <label for="transaction-amount" class="form-label">Kwota</label>
            <input class="form-control" type="text" id="transaction-amount" name="amount" required
                value="{{ old('amount', $transaction->amount ?? '0,00') }}"
                onchange="formatAmount(this)"
            >
        </div>
        <div class="mb-3">
            <label for="transaction-description" class="form-label">Nazwa</label>
            <input class="form-control" type="text" id="transaction-description" name="description" required
                value="{{ $transaction->description }}">
        </div>
        <div class="mb-3">
            <label for="transaction-category" class="form-label">Kategoria</label>
            <select class="form-select" id="transaction-category" name="category">
                @foreach ($financeCategories as $category)
                    <option value="{{ $category->id }}" @if ($category->id == $transaction->category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="transaction-event" class="form-label">Wydarzenie</label>
            <select class="form-select" id="transaction-event" name="event">
                    <option value="">Brak</option>
                @foreach ($events as $event)
                    @php
                        $isSelected = isset($transaction) && isset($transaction->event) && $transaction->event->id == $event->id;
                    @endphp
                    <option value="{{ $event->id }}" @if ($isSelected) selected @endif>
                        {{ $event->name }} - {{ date('Y', strtotime($event->date)) }} #{{ $event->id }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <input class="form-check-input" type="checkbox" id="transaction-cash" name="cash"><label
                class="form-check-label" for="transaction-cash">Gotówka</label>
        </div>
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton">Anuluj</button>
        <button type="submit" name="action" value="{{ $action }}" class="btn btn-success">Zapisz</button>
    </div>
</form>

@push('scripts')
    <script>
        // initilize select2 for event selection
        $('#transaction-event').select2({
            placeholder: '',
            allowClear: true
        });

        const categories = @json($financeCategories);


        $('#transaction-amount').on('change', function() {
            filterCategories();
        });

        function filterCategories() {
            var amount = amountToFloat($('#transaction-amount')[0]);
            if (amount > 0) {
                var filteredCategories = categories.filter(function(category) {
                    return category.enum_type_id == 13;
                });
            }
            else if (amount == 0) {
                // if the amount is 0, do not show any categories
                var filteredCategories = [];
            } else {
                var filteredCategories = categories.filter(function(category) {
                    return category.enum_type_id == 14;
                });
            }
            $('#transaction-category').empty();
            filteredCategories.forEach(function(category) {
                $('#transaction-category').append(
                    $('<option>', {
                        value: category.id,
                        text: category.name
                    })
                );
            });
        }

        // format amount input
        function formatAmount(input) {
            // Remove any non-numeric characters except minus and decimal point
            let value = input.value.replace(/[^\d.-]/g, '');
            
            // Convert invalid input to 0
            if (isNaN(parseFloat(value))) {
                value = '0';
            }

            // Parse the number and fix to 2 decimal places
            let number = parseFloat(value).toFixed(2);
            
            // Format with comma as decimal separator and add currency
            let formatted = number.replace('.', ',') + ' zł';
            
            // Update input value
            input.value = formatted;
        }

        // Format initial value
        $(document).ready(function() {
            formatAmount(document.getElementById('transaction-amount'));
            filterCategories();
        });

        //on submit the amount should be converted to a parseFloat
        $('#edit-transaction-form').submit(function() {
            let amount = $('#transaction-amount')[0];

            amount.value = amountToFloat(amount);
        });

        function amountToFloat(amount) {
            let cleanValue = amount.value.replace(' zł', '').trim();;
            cleanValue = cleanValue.replace(',', '.');
            return parseFloat(cleanValue);
        }
    </script>
@endpush
