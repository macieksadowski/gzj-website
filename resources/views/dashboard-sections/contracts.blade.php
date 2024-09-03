@extends('layouts.dashboard')
@section('title', 'Umowy')

@section('inner-content')

    <section>
        <div class="container">
            <div class="section-title">
                <p>Podsumowanie umów na daną osobę</p>
            </div>
            <p>Dodać, edytować i usunąć umowę można z poziomu widoku szczegółów wydarzenia</p>
            <div id="summaryYearWrapper" class="dataTables_length">
                <label for="summaryYear">Rok</label>
                <select id="summaryYear" name="summaryYear">
                    @foreach ($summaryYears as $year)
                        <option value="{{ $year }}" @if ($loop->first) selected @endif>
                            {{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <table id="contractsSummaryTable" class="hover responsive nowrap">
                <thead>
                    <th data-priority="1">Osoba</th>
                    <th data-priority="2">Liczba umów</th>
                    <th data-priority="3">Łączna kwota</th>
                    <th data-priority="4">Rok</th>
                </thead>

                @foreach ($contractsSummary as $contractSummary)
                    <tr>
                        <td>{{ $contractSummary->first_name }} {{ $contractSummary->last_name }}</td>
                        <td>{{ $contractSummary->count }}</td>
                        <td>{{ $contractSummary->sum }}</td>
                        <td>{{ $contractSummary->year }}</td>
                    </tr>
                @endforeach
            </table>

            <div class="section-title">
                <p>Lista wszystkich umów</p>
            </div>
            <table id="contractsTable" class="hover responsive nowrap">
                <thead>
                    <th data-priority="1">Id</th>
                    <th data-priority="1">Ev_Id</th>
                    <th data-priority="2">Data</th>
                    <th data-priority="3">Nazwa Wydarzenia</th>
                    <th data-priority="4">Osoba</th>
                    <th data-priority="5">Kwota</th>
                </thead>

                @foreach ($contracts as $contract)
                    <tr>
                        <td>{{ $contract->id }}</td>
                        <td>{{ $contract->event->id }}</td>
                        <td>{{ $contract->event->date }}</td>
                        <td>{{ $contract->event->name }}</td>
                        <td>{{ $contract->member->first_name }} {{ $contract->member->last_name }}</td>
                        <td>{{ $contract->contract_amount }}</td>
                    </tr>
                @endforeach
            </table>

        </div>

    </section>

@endsection

@section('scripts')
    @parent

    <script src="{{ asset('/js/contracts.js') }}"></script>

@endsection
