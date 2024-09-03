@extends('layouts.dashboard')
@section('title', 'Generator umów')

@section('inner-content')
    <section>
        <div class="dashboard__generator">

            <form method="get" class="col-12">
                @csrf
                <div class="row">
                    <div class="form-group col-12">
                        <label for="contract-person-select">Na kogo ma być wygenerowana umowa?</label>
                        <select onchange="this.form.submit()" id="contract-person-select" name="id" class="form-select">
                            @foreach ($members as $member)
                                <option @if ($member->id == $selected->id) selected @endif value={{ $member->id }}>
                                    {{ $member->first_name }} {{ $member->last_name }}</option>';
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <form method="post" class="col-12">
                @csrf
                <input type="hidden" name="member_id" value="{{ $selected->id }}">
                <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Typ umowy</p>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input type="radio" id="zlecenie" name="contractType" value="zlecenie" checked
                                    class="form-check-input">
                                <label for="zlecenie" class="form-check-label">Zlecenie</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="dzielo" name="contractType" value="dzielo"
                                    class="form-check-input">
                                <label class="item" for="dzielo" class="form-check-label">O dzieło</label>
                            </div>
                        </div>
                </fieldset>
                <div class="row g-3" id="personal-data">
                    <div class="col-6">
                        <label for="first_name" class="form-label">Imię</label>
                        <input disabled class="form-control" type="text" id="first_name" name="data[first_name]"
                            value="{{ $selected->first_name }}">
                    </div>
                    <div class="col-6">
                        <label for="last_name" class="form-label">Nazwisko</label>
                        <input disabled class="form-control" type="text" id="last_name" name="data[last_name]"
                            value="{{ $selected->last_name }}">
                    </div>
                    <div class="col-9">
                        <label for="street" class="form-label">Ulica</label>
                        <input disabled class="form-control" type="text" id="street" name="data[street]"
                            value="{{ $selected->street }}">
                    </div>
                    <div class="col-3">
                        <label for="house_no" class="form-label">Nr domu</label>
                        <input disabled class="form-control" type="text" id="house_no" name="data[house_no]"
                            value="{{ $selected->house_no }}">
                    </div>
                    <div class="col-3">
                        <label for="postal_code" class="form-label">Kod pocztowy</label>
                        <input disabled class="form-control" id="postal_code" name="data[postal_code]"
                            value="{{ $selected->postal_code }}" autocomplete="off" maxlength="6" type="text" />
                    </div>
                    <div class="col-9">
                        <label for="town" class="form-label">Miasto</label>
                        <input disabled class="form-control" type="text" id="town" name="data[town]"
                            value="{{ $selected->town }}">
                    </div>
                    <div class="col-4">
                        <label for="pesel" class="form-label">PESEL:</label>
                        <input disabled class="form-control" type="text" id="pesel" name="data[pesel]"
                            value="{{ $selected->pesel }}" inputmode="numeric" maxlength="11">
                    </div>
                    <div class="col-8">
                        <label for="birth_place" class="form-label">Miejsce urodzenia</label>
                        <input disabled class="form-control" type="text" id="birth_place" name="data[birth_place]"
                            value="{{ $selected->birth_place }}">
                    </div>
                    <div class="col-12">
                        <label for="account_no" class="form-label">Numer konta</label>
                        <input disabled class="form-control" type="text" id="account_no" name="data[account_no]"
                            value="{{ $selected->account_no }}" inputmode="numeric" maxlength="32">
                    </div>
                    <div class="col-12">
                        <label for="tax_office" class="form-label">Urząd skarbowy</label>
                        <input disabled class="form-control" type="text" id="tax_office" name="data[tax_office]"
                            value="{{ $selected->tax_office }}">
                    </div>
                </div>
                <div class="dashboard__generator__footer row">
                    <div class="col-12 col-lg-6">
                        <input class="form-control" name="fileName" type="text" placeholder="Nazwa pliku"
                            onfocus="this.placeholder=''" onblur="this.placeholder='Nazwa pliku'">
                    </div>
                    <div class="col-12 col-lg-auto">
                        <input formaction="{{ route('generateContract') }}" name="generate" type="submit"
                            value="Generuj dokument" class="btn btn-primary">
                    </div>
                    <div class="col-12 col-lg-auto">
                        <input name="edit" type="button" id="button-edit" value="Zmień dane"
                            class="btn btn-primary">
                        <input type="button" name="copy" id="button-copy" value="Kopiuj dane"
                            class="btn btn-primary">
                        
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" id="clipboardModal" aria-labelledby="clipboardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kopiuj dane</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                </div>
                <div class="modal-body" id="modal-text">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal-copy-button">Kopiuj</button>
                </div>
            </div>
        </div>
    </div>

@endsection
