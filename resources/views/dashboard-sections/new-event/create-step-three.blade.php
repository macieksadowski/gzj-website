@extends('dashboard-sections.new-event')

@section('card-header')
Krok 3: Setlista
@endsection

@section('card-content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h4>{{ $event->name }} {{ $event->date}}</h4>
    <p>Dodaj setlistę. Możesz też zrobić to później</p>
    <form action="{{ route('events.new.step.three.post') }}" method="POST" id="event-setlist-form">
        @csrf
        <label for="setlist-flag">Dodaj utwory do setlisty z uwzględnieniem ich kolejności</label>
        <select id="setlist-list" name='songs[]' class="songSelect" multiple>
            @foreach ($songs as $song)
                <option value={{ $song->id }}>{{ $song->title }}</option>
            @endforeach
        </select>


        <button type="submit">Dalej</button>

    </form>

@endsection

@section('scripts')
    @parent
    <script>
        new DualListbox('.songSelect', {
            searchPlaceholder: 'Wyszukaj',
            availableTitle: 'Wszystkie utwory',
            selectedTitle: 'Wybrane',
            addButtonText: '>',
            removeButtonText: '<',
            addAllButtonText: 'Dodaj wszystkie',
            removeAllButtonText: 'Usuń wszystkie',
            showAddButton: false,
            showAddAllButton: true,
            showRemoveButton: false,
            showRemoveAllButton: true,

            showSortButtons: true,
            upButtonText: "ᐱ",
            downButtonText: "ᐯ",
            draggable: true,

        });

    </script>
@endsection

