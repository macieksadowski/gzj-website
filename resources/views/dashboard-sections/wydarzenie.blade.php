@extends('layouts.dashboard')


@php
    $event_year = date('Y', strtotime($event->date));
@endphp
@section('title', $event->name.' - '.$event_year)

@section('inner-content')

<section>
    <div class="generator">


        <table id="eventSummary">
            <thead>
                <th></th>
                <th></th>

            </thead>


            <tr>
                <td >Id</td>
                <td>{{ $event->ev_id}}</td>
            </tr>
            <tr>
                <td >Data</td>
                <td>{{ $event->date}}</td>
            </tr>
            <tr>
                <td >Typ</td>
                <td>{{ $event->type->name}}</td>
            </tr>
            <tr>
                <td rowspan="2">Umowa</td>
                <td>
                    <!--<a href="#ex1" rel="modal:open">Dodaj</a>-->
                    @foreach ($event->contracts as $contract)
                        {{$contract->member->first_name}}

                    @endforeach
                  </td>
            </tr>
            <tr>
                <td>LINK</td>
            </tr>
            <tr>
                <td >Lista ZAiKS</td>
                <td> </td>
            </tr>

        </table>

        <br /><br />
        <h1>Lista wydatków</h1>
        <table id="eventTransactions">
            <thead>
                <th>Kwota</th>
                <th>Nazwa</th>
                <th>Kategoria</th>
            </thead>


            @foreach ($transactions as $transaction)
            <tr>
                <td @if ( $transaction->amount <0)
                  style="color:red"
                @endif
                >{{ $transaction->amount}} zł</td>
                <td>{{ $transaction->description}}</td>
                <td>{{ $transaction->category->name}}</td>
            </tr>
            @endforeach

            <tfoot>

                    <th>
                        {{$sum}} zł
                    </th>
                    <th colspan="2">
                        SALDO
                    </th>


            </tfoot>

        </table>



    </div>

</section>


<div id="ex1" class="modal">


    <form method="post" >
        @csrf
            Na kogo była umowa?
            <select name='songs[]' class="songSelect" >
                @foreach ($members as $member)
                    <option value={{ $member->id }}>{{ $member->first_name}} {{$member->last_name}}</option>
                @endforeach
            </select>
            <input name="save" type="submit" value="Zapisz">

        </form>
</div>


@endsection


