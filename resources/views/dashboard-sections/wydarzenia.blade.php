@extends('layouts.dashboard')
@section('title', 'Wydarzenia')

@section('inner-content')

<section>
    <div class="dashboard__generator">
        (Kliknij, aby wyświetlić szczegóły wydarzenia)

        <br/><br/>
        <table id="eventsTable" class="hover responsive nowrap" >
            <thead>
                <th>Id</th>
                <th data-priority="1">Nazwa Wydarzenia</th>
                <th data-priority="2">Data</th>
                <th data-priority="3">Saldo</th>
                <th data-priority="4">Liczba umów</th>
                <th data-priority="5">Typ</th>
            </thead>
            <tbody>
        @foreach ($pageVariable as $event)

            <tr>
                <td>{{ $event->id}}</td>
                <td>{{ $event->name}}</td>
                <td>{{ $event->date}}</td>
                <td class="cash-amount @if ($event->saldo < 0) cash-amount--negative @endif">{{ $event->saldo}}</td>
                <td class="number-cell">{{ $event->contracts_amount}}</td>
                <td>{{ $event->type->value}}</td>
            </tr>


            @endforeach
            </tbody>
        </table>
    </div>

</section>

@endsection

@section('scripts')
    @parent

    <script src="{{ asset('/js/events.js') }}"></script>


@endsection
