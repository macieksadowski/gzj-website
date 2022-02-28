@extends('layouts.master')
@section('title', 'Wydarzenia')

@section('content')

<section>
    <div class="generator">
        (Kliknij, aby wyświetlić szczegóły wydarzenia)<br/><br/>
        <table id="eventsTable" class="hover responsive nowrap" >
            <thead>
                <th>Id</th>
                <th data-priority="1">Nazwa Wydarzenia</th>
                <th data-priority="2">Data</th>
                <th>Typ</th>
            </thead>

        @foreach ($pageVariable as $event)

            <tr>
                <td>{{ $event->ev_id}}</td>
                <td>{{ $event->name}}</td>
                <td>{{ $event->date}}</td>
                <td>{{ $event->type->name}}</td>
            </tr>


            @endforeach
        </table>
    </div>

</section>

@endsection

@section('scripts')
    @parent

    <script>


        $(document).ready(function() {
            var table =  $('#eventsTable').DataTable( {
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



            $('#eventsTable').on('click', 'tbody tr', function() {
            window.location.href = `{{URL::route('eventy')}}/${table.row(this).data()[0]}`;
            });


        } );


    </script>


@endsection
