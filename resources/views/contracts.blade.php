@extends('layouts.master')
@section('title', 'Umowy')

@section('content')

<section>
    <div class="generator">
        <div class="form-footer"><p>(Kliknij, aby wyświetlić lub edytować umowę)</p><a href="#ex1" rel="modal:open"><button>Dodaj nową</button></a></div>
        <table id="contractsTable" class="hover responsive nowrap" >
            <thead>
                <th>Id</th>
                <th data-priority="1">Nazwa Wydarzenia</th>
                <th data-priority="2">Osoba</th>
                <th data-priority="3">Kwota</th>
            </thead>

        @foreach ($contracts as $contract)

            <tr>
                <td>{{ $contract->id}}</td>
                <td>{{ $contract->event->name}}</td>
                <td>{{ $contract->member->first_name}} {{ $contract->member->last_name}}</td>
                <td>{{ $contract->amount}}</td>
            </tr>


            @endforeach
        </table>
    </div>

</section>

<div id="ex1" class="modal">


    <form method="post" >
        @csrf
            <label for="member">Na kogo?</label>
            <select name="member" id="members" >
                @foreach ($members as $member)
                    <option value={{ $member->id }}>{{ $member->first_name}} {{$member->last_name}}</option>
                @endforeach
            </select>
            <label for="event">Wydarzenie</label>
            <select name="event" id="events">

                @foreach ($events as $event)
                    <option value={{$event->ev_id}}>{{$event->name}} - {{$event->date}}</option>
                @endforeach
            </select>
            <label for="amount">Kwota</label>
            <input type="number" name="amount" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" data-type="currency" />
            <input name="save" type="submit" value="Zapisz">

        </form>
</div>

@endsection

@section('scripts')
    @parent

    <script>


        $(document).ready(function() {
            var table =  $('#contractsTable').DataTable( {
                dom: 'lfrtip',
                buttons: [
                    {
                    text: 'Reload',
                    ction: function ( e, dt, node, config ) {
                    dt.ajax.reload();
                    }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pl.json'
                },
                "autoWidth": false,
                "columns": [
                    null,
                    { "width": "60%" },
                    null,
                    null

                ],
                responsive: {
                    details: false
                }


            } );



            $('#contractsTable').on('click', 'tbody tr', function() {
            window.location.href = `{{URL::route('contracts')}}/${table.row(this).data()[0]}`;
            });

            $('#events').select2();


        } );


    </script>


@endsection
