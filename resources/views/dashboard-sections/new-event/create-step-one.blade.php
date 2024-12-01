@extends('dashboard-sections.new-event')

@section('card-header')
Krok 1: Podstawowe informacje
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

    <form action="{{ route('events.new.step.one.post') }}" method="POST">
        @csrf
        <label for="event-name">Nazwa wydarzenia:</label>
        <input class="data-field" type="text" id="event-name" name="event-name" value="{{ $event->name ?? '' }}">

        <label for="event-type">Typ:</label>
        <select id="event-type" name="event-type">
            @foreach ($eventTypes as $eventType)
                <option value="{{ $eventType->id }}" @if ( $loop->first) selected @endif>{{ $eventType->value }}</option>
            @endforeach
        </select>

        <label for="event-date">Data:</label>
        <input type="date" id="event-date" name="event-date" value="{{ $event->date ?? '' }}">

        <button type="submit">Dalej</button>

    </form>

@endsection
