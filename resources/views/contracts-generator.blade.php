@extends('layouts.master')
@section('title', 'Generator umów')

@section('content')

    <section>
        <div class="generator">

            <form method="get">
            @csrf
                <p class="form-row">Na kogo ma być wygenerowana umowa?
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
                <input type="hidden" name="member_id" value="{{$selected->id}}">
                <p class="form-row">Wybierz typ umowy:
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
                    <div class="form-row">
                        <label>
                            Imię:</label>
                        <input disabled class="data-field" type="text" id="first_name" name="data[first_name]"
                            value="{{$selected->first_name}}">
                        <label>
                                Nazwisko:</label>
                        <input disabled class="data-field" type="text" id="last_name" name="data[last_name]"
                            value="{{$selected->last_name}}">
                    </div>
                    <div class="form-row">
                        <label>
                            Ulica:
                        </label>
                        <input disabled class="data-field" type="text" id="street" name="data[street]"
                            value="{{$selected->street}}">
                        <label>
                            Nr domu:
                        </label>
                        <input disabled class="data-field" type="text" id="house_no" name="data[house_no]"
                            value="{{$selected->house_no}}">
                    </div>
                    <div class="form-row">
                        <label>
                            Kod pocztowy:</label>
                        <input disabled class="field" id="postal_code" name="data[postal_code]"
                            value="{{$selected->postal_code}}" autocomplete="off" maxlength="6" type="text" />
                        <label>
                            Miasto:
                        </label>
                        <input disabled class="data-field" type="text" id="town" name="data[town]"
                            value="{{$selected->town}}">
                    </div>
                    <div class="form-row"><label>PESEL:</label>
                        <input disabled class="data-field" type="text" id="pesel" name="data[pesel]"
                            value="{{$selected->pesel}}" inputmode="numeric" maxlength="11">
                    </div>
                    <div class="form-row">
                        <label>
                            Miejsce urodzenia:
                        </label>
                        <input disabled class="data-field" type="text" id="birth_place"
                            name="data[birth_place]" value="{{$selected->birth_place}}">
                    </div>

                    <div class="form-row">
                        <label>
                            Nr konta:
                        </label>
                        <input disabled class="data-field" type="text" id="account_no" name="data[account_no]"
                            value="{{$selected->account_no}}" inputmode="numeric" maxlength="32">
                    </div>
                    <div class="form-row">
                        <label>
                            Urząd skarbowy:
                        </label>
                        <input disabled class="data-field" type="text" id="tax_office" name="data[tax_office]"
                            value="{{$selected->tax_office}}">
                    </div>
                </div>
                <div class="form-footer">
                    <input name="fileName" type="text" placeholder="Nazwa pliku" onfocus="this.placeholder=''"
                        onblur="this.placeholder='Nazwa pliku'">
                    <input onclick="editdataFcn()" name="edit" type="button" value="Zmień dane">
                    <input type="button" onclick="copyFcn()" name="copy" value="Kopiuj dane">
                    <input formaction="{{ route('generateContract') }}" name="generate" type="submit" value="Generuj dokument">
                </div>
            </form>
        </div>
    </section>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript">

        function copyFcn() {
          /* Prepare text with data */
          var copyText = 'Imię i nazwisko: ' + document.getElementById("first_name").value + ' ' + document.getElementById("last_name").value +
                            '\r\nUlica i nr domu: ' + document.getElementById("street").value + ' ' + document.getElementById("house_no").value +
                            '\r\nKod pocztowy: ' + document.getElementById("postal_code").value +
                            '\r\nMiasto: ' + document.getElementById("town").value +
                            '\r\nPESEL: ' + document.getElementById("pesel").value +
                            '\r\nMiejsce urodzenia: ' + document.getElementById("birth_place").value +
                            '\r\nNr konta: ' + document.getElementById("account_no").value +
                            '\r\nUrząd skarbowy: ' + document.getElementById("tax_office").value;

          /*Create object for select fcn*/
          const el = document.createElement('textarea');
          el.value = copyText;
          document.body.appendChild(el);
          el.select();
          document.execCommand('copy');
          document.body.removeChild(el);

          //Show communicate

          var modal = document.getElementsByClassName('bottom-modal')[0];
          modal.id = "success";
          document.getElementById("bottom-modal-text").innerHTML = "Dane skopiowano do schowka!";
          modal.style.display = "block";

        }

        function editdataFcn() {
            var btn = document.getElementsByName("edit");
            btn = btn[0];
            if(btn.value == "Aktualizuj")
            {
                btn.type = "submit";
                //btn.formAction = "update-data.php";
            }
            btn.value = "Aktualizuj";
            var form = document.getElementById("personal-data").querySelectorAll("input");
            var i;
            for (i = 0; i < form.length; i++) {
              form[i].removeAttribute("disabled");
            }
        }

        </script>


@endsection
