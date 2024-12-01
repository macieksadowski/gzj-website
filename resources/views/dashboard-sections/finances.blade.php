@extends('layouts.dashboard')
@section('title', 'Finanse')

@section('inner-content')

    <section>
        <div class="dashboard__generator">
            <div class="dashboard__balance d-flex  form-group mb-5">
                <label for="balance">Saldo:</label>
                <input type="text" id="balance" class="form-control" value="{{ $totalSaldo }} zł" readonly>
            </div>
            <div class="financesTableDateSelector">
                <form method="GET" class="d-flex align-items-center justify-content-between pb-5">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <label class="form-label">Pokaż transakcje z zakresu dat:</label>
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <label for="startDate" class="form-label me-2">Od:</label>
                            <input type="date" id="startDate" name="start-date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <label for="endDate" class="form-label me-2">Do:</label>
                            <input type="date" id="endDate" name="end-date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <button type="submit" class="btn btn-primary">Zmień zakres dat</button>
                        </div>
                    </div>
                    <div>
                        <div class="btn btn-primary"><button type="button" data-bs-toggle="modal"
                            data-bs-target="#newTransactionModal">Dodaj transakcję</button></div>
                    </div>
                </form>
            </div>
            @csrf
            <table id="financesTable" class="hover responsive nowrap">
                <thead>
                    <th>Id</th>
                    <th data-priority="1">Data</th>
                    <th data-priority="2">Kwota</th>
                    <th data-priority="5">Opis</th>
                    <th data-priority="3">Kategoria</th>
                    <th data-priority="4">Wydarzenie</th>
                    <th data-priority="6">Płatne gotówką</th>
                    <th>Akcje</th>
                </thead>
            </table>

        </div>
    </section>

    <div class="modal fade" id="newTransactionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nowa transakcja</h5>
                </div>
                <x-edit-transaction-form :transaction="$newTransactionContainer" :action="'new'" />
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent
    <script>
        var table = new DataTable('#financesTable', {
            // Server-side processing configuration
            processing: true,
            serverSide: true,
            ajax: '{{ route('finances') }}',

            // SearchPanes configuration
            searchPanes: {
                orderable: false,
                cascadePanes: true,
                layout: 'columns-2',
                columns: [4, 5], // Category and Event columns
                dtOpts: {
                    select: { style: 'multi'},
                    order: [[ 1, "desc" ]]
                },
                collapse: false,
            },

            // DOM layout configuration
            dom: 'P<"clear">lfrtip', // SearchPanes (P), length (l), filter (f), table (rt), info (i), pagination (p)

            // Column definitions
            columns: [
                { "data": "tr_id" },
                { "data": "date", type: 'date' },
                { "data": "amount", type: 'num-fmt' },
                { "data": "description" },
                { "data": "cat_id" },
                { "data": "ev_id" },
                { "data": "cash_transaction" },
                { "data": "action" }
            ],

            // Column-specific configurations
            columnDefs: [
                {
                    targets: [2], // Amount column
                    render: function(data, type, row) {
                        if (data < 0) {
                            return '<span class="cash-amount cash-amount--negative">' + data + ' zł</span>';
                        }
                        return '<span class="cash-amount">' + data + ' zł</span>';
                    },
                },
                {
                    target: 4, // Category column
                    searchPanes : { 
                        show : true, 
                        hideCount : true ,
                        orderable: false,
                        preSelect: true // Preserve server-side order
                    }
                },
                {
                    target: 5, // Event column
                    render: function(data, type, row) {
                        if (data == null) {
                            return 'Brak';
                        }
                        return '<a href="' + data.link + '">' + data.name + '</a>'; 
                    },
                    searchPanes : { 
                        show : true, 
                        hideCount : true ,
                        orderable: false,
                        preSelect: true // Preserve server-side order
                    }
                },
                {
                    target: 7, // Actions column
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<a href="' + data.edit + '" class="btn btn-secondary dashboard__table-button"><i class="bi bi-pencil-fill"></i></a>' +
                                '<a href="' + data.delete + '" class="btn btn-secondary btn-danger dashboard__table-button" onclick="return confirm(\'Czy na pewno chcesz usunąć ten element?\');"><i class="bi bi-trash"></i></a>';
                    } 
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pl.json',
                searchPanes: {
                    clearMessage: 'Wyczyść filtry',
                    
                    title: 'Filtry aktywne - %d'
                }
            },
            order: [1, 'desc'], // Sort by date descending by default
        });


        $('#transaction-event').select2({
            theme: "bootstrap",
            selectionCssClass: 'form-select',
            language: 'pl',
        });
    </script>

@endsection
