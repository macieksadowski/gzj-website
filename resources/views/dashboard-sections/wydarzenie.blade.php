@extends('layouts.dashboard')


@php
    $event_year = date('Y', strtotime($event->date));
@endphp
@section('title', $event->name . ' - ' . $event_year)

@section('inner-content')

    <section>
        <div class="container eventSummary">
            <div class="section-title">
                <p>{{ $event->name . ' - ' . $event_year }}</p>
                <h2>#{{ $event->id }}</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-4">
                    <h2>Szczegóły</h2>
                    <table class="table eventSummary__detailstable">
                        <tr>
                            <td>Data</td>
                            <td>{{ $event->date }}</td>
                        </tr>
                        <tr>
                            <td>Typ</td>
                            <td>{{ $event->type->value }}</td>
                        </tr>
                        <tr>
                            <td>Saldo</td>
                            <td @if ($sum < 0) style="color:red" @endif>
                                {{ $sum }} zł
                            </td>
                        </tr>
                        <tr>
                            <td>Setlista</td>
                            <td><i class="bi bi-box-arrow-up-right"></i></td>
                        </tr>
                        <tr>
                            <td>Lista ZAiKS</td>
                            <td><i class="bi bi-box-arrow-up-right"></i></td>
                        </tr>
                    </table>
                    <table class="table">
                        <thead>
                            <th colspan="3">Umowy</th>
                        </thead>
                        <thead>
                            <th>Kto</th>
                            <th>Kwota</th>
                            <th>Rodzaj</th>
                        </thead>

                        @foreach ($event->contracts as $contract)
                            <tr>
                                <td>{{ $contract->member->display_name }}</td>
                                <td>{{ $contract->contract_amount }} zł</td>
                                <td>{{ $contract->type->value }}</td>
                            </tr>
                        @endforeach


                    </table>
                </div>

                <div class="col-4">
                    <h2>Lista wydatków</h2>
                    <table class="table" id="eventTransactions">
                        <thead>
                            <th>Kwota</th>
                            <th>Nazwa</th>
                            <th>Kategoria</th>
                        </thead>


                        @foreach ($transactions as $transaction)
                            <tr>
                                <td @if ($transaction->amount < 0) style="color:red" @endif>{{ $transaction->amount }} zł
                                </td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->category->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-4">
                    <h2>Setlista</h2>
                    <table class="table" id="eventSetlist">
                        <thead>
                            <th>Kolejność</th>
                            <th>Utwór</th>
                        </thead>

                        @foreach ($setlist as $entry)
                            <tr>
                                <td>{{ $entry->order }}</td>
                                <td>{{ $entry->song->title }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
            <div class="row justify-content-center eventSummary__actionBar">
                <div class="col-2"><button type="button" data-bs-toggle="modal"
                        data-bs-target="#editEventSummaryModal">Edytuj szczegóły</button></div>
                <div class="col-2"><button type="button" data-bs-toggle="modal"
                        data-bs-target="#editContractsModal">Edytuj umowy</button></div>
                <div class="col-2"><button disabled>Edytuj setlistę</button></div>
                <div class="col-2"><button disabled>Generuj tabelkę ZAiKS</button></div>
                <div class="col-2"><button type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">Usuń</button></div>
            </div>
        </div>
    </section>

    <x-edit-contracts-form :event=$event />

    <x-edit-event-summary-form :event=$event />

    <x-delete-modal :id="$event->id" :name="$event->name" />



@endsection

@section('scripts')
    @parent

    <script src="{{ asset('/js/edit-contracts-form.js') }}"></script>
@endsection
