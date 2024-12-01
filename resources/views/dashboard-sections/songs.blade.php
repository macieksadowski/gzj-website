@extends('layouts.dashboard')
@section('title', 'Baza utworów')

@section('inner-content')

<section>
    <div class="dashboard__generator">
        <table id="songsTable" class="hover responsive nowrap" >
            <thead>
                <th>Id</th>
                <th data-priority="1">Tytuł</th>
                <th data-priority="2">Wykonawca</th>
                <th data-priority="3">Kompozytor</th>
                <th data-priority="4">Autor tekstu</th>
                <th data-priority="6">Tempo</th>
                <th data-priority="7">Adnotacje bębny</th>
                <th data-priority="8">Adnotacje zapowiedź 1</th>
                <th data-priority="9">Adnotacje zapowiedź 2</th>
            </thead>
            <tbody>
            @foreach ($pageVariable as $song)
                <tr>
                    <td>{{ $song->id }}</td>
                    <td>{{ $song->title }}</td>
                    <td>{{ $song->performer }}</td>
                    <td>{{ $song->composer }}</td>
                    <td>{{ $song->text_author }}</td>
                    <td>{{ $song->tempo }}</td>
                    <td>{{ $song->drums_annotations }}</td>
                    <td>{{ $song->announcer_annotations_1 }}</td>
                    <td>{{ $song->announcer_annotations_2 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

            
    </div>
</section>

@endsection

@section('scripts')
    @parent

    <script src="{{ asset('/js/songs.js') }}"></script>

@endsection