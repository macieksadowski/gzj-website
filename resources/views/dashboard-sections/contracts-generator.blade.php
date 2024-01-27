@extends('layouts.dashboard')
@section('title', 'Generator umów')

@section('inner-content')
    <section>
        <div class="dashboard__generator">

            <form method="get">
                @csrf
                <p class="dashboard__generator__formrow">Na kogo ma być wygenerowana umowa?
                    <select onchange="this.form.submit()" id="osoba" name="id">

                        @foreach ($members as $member)
                            <option @if ($member->id == $selected->id) selected @endif value={{ $member->id }}>
                                {{ $member->first_name }} {{ $member->last_name }}</option>';
                        @endforeach
                    </select>
                </p>

            </form>
            <form method="post">
                @csrf
                <input type="hidden" name="member_id" value="{{ $selected->id }}">
                <p class="dashboard__generator__formrow">Wybierz typ umowy:
                    <input type="radio" id="zlecenie" name="contractType" value="zlecenie" checked>
                    <label class="item" for="zlecenie">
                        Zlecenie
                    </label>
                    <input type="radio" id="dzielo" name="contractType" value="dzielo">
                    <label class="item" for="dzielo">
                        O dzieło
                    </label>
                </p>
                <div id="personal-data">
                    <div class="dashboard__generator__formrow">
                        <label>
                            Imię:</label>
                        <input disabled class="data-field" type="text" id="first_name" name="data[first_name]"
                            value="{{ $selected->first_name }}">
                        <label>
                            Nazwisko:</label>
                        <input disabled class="data-field" type="text" id="last_name" name="data[last_name]"
                            value="{{ $selected->last_name }}">
                    </div>
                    <div class="dashboard__generator__formrow">
                        <label>
                            Ulica:
                        </label>
                        <input disabled class="data-field" type="text" id="street" name="data[street]"
                            value="{{ $selected->street }}">
                        <label>
                            Nr domu:
                        </label>
                        <input disabled class="data-field" type="text" id="house_no" name="data[house_no]"
                            value="{{ $selected->house_no }}">
                    </div>
                    <div class="dashboard__generator__formrow">
                        <label>
                            Kod pocztowy:</label>
                        <input disabled class="field" id="postal_code" name="data[postal_code]"
                            value="{{ $selected->postal_code }}" autocomplete="off" maxlength="6" type="text" />
                        <label>
                            Miasto:
                        </label>
                        <input disabled class="data-field" type="text" id="town" name="data[town]"
                            value="{{ $selected->town }}">
                    </div>
                    <div class="dashboard__generator__formrow"><label>PESEL:</label>
                        <input disabled class="data-field" type="text" id="pesel" name="data[pesel]"
                            value="{{ $selected->pesel }}" inputmode="numeric" maxlength="11">
                    </div>
                    <div class="dashboard__generator__formrow">
                        <label>
                            Miejsce urodzenia:
                        </label>
                        <input disabled class="data-field" type="text" id="birth_place" name="data[birth_place]"
                            value="{{ $selected->birth_place }}">
                    </div>

                    <div class="dashboard__generator__formrow">
                        <label>
                            Nr konta:
                        </label>
                        <input disabled class="data-field" type="text" id="account_no" name="data[account_no]"
                            value="{{ $selected->account_no }}" inputmode="numeric" maxlength="32">
                    </div>
                    <div class="dashboard__generator__formrow">
                        <label>
                            Urząd skarbowy:
                        </label>
                        <input disabled class="data-field" type="text" id="tax_office" name="data[tax_office]"
                            value="{{ $selected->tax_office }}">
                    </div>
                </div>
                <div class="dashboard__generator__footer">
                    <input name="fileName" type="text" placeholder="Nazwa pliku" onfocus="this.placeholder=''"
                        onblur="this.placeholder='Nazwa pliku'">
                    <input name="edit" type="button"  id="button-edit" value="Zmień dane">
                    <input type="button" name="copy" id="button-copy" value="Kopiuj dane">
                    <input formaction="{{ route('generateContract') }}" name="generate" type="submit"
                        value="Generuj dokument">
                </div>
            </form>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="clipboardModal" tabindex="-1" role="dialog" aria-labelledby="clipboardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body" id="modal-text">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-copy-button">Kopiuj</button>
                </div>
            </div>
        </div>
    </div>
@endsection
