@extends('layouts.dashboard')

@php
    if (isset($transaction)) {
        $header = 'Edytuj transakcję';
        $title = $header . ' #' . $transaction->tr_id;
    } else {
        $header = 'Nowa transakcja';
        $title = $header;
    }
@endphp

@section('title', $title)

@section('inner-content')
    <section>
        <div class="container">
            <div class="section-title">
                <p>{{ $header }}</p>
                <h2>#{{ $transaction->tr_id }}</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-4">
                    <x-edit-transaction-form :transaction="$transaction" :action="'edit'" />
                </div>
            </div>
        </div>

    </section>
@endsection


@section('scripts')
    @parent
@endsection