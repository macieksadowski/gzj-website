@extends('dashboard-sections.new-event')

@section('card-header')
Krok 2: Umowa
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
    <p>Dodaj szczegóły dotyczące umowy. Możesz też zrobić to później</p>
    <x-new-contract-form :formAction="{{ route('events.new.step.two.post') }}">
        <x-slot name="additionalContractTypes">
            <input type="radio" id="no-contract" name="contract" value="no" checked />
            <label for="no-contract">Brak</label>
        </x-slot>
        <x-slot name="submitButton">
            <button type="submit">Dalej</button>
        </x-slot>
    </x-new-contract-form>

@endsection
