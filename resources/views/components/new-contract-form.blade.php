<form action="{{ $formAction }}" method="POST" id="new-contract-form">
    @csrf

    <div class="form-group">
    <label for="contract-flag"></label>
    <fieldset>
        <legend>Rodzaj umowy</legend>
        
        
        @isset($additionalContractTypes)
            {{ $additionalContractTypes }}
        @endisset

        <input type="radio" id="dzielo-contract" name="contract" value="dzielo" />
        <label for="dzielo-contract">Umowa o dzieło</label>
        
        <input type="radio" id="zlecenie-contract" name="contract" value="zlecenie"  />
        <label for="zlecenie-contract">Umowa zlecenie</label>
        
        <input type="radio" id="other-contract" name="contract" value="other"  />
        <label for="other-contract">Inna</label>
        
    </fieldset>
    </div>

    @if( $eventSelection )
        <div class="form-group">
        <label for="events">Wydarzenie:</label>
        <select name="contract-event" id="contract-event">
            @foreach ($events as $event)
                <option value="{{$event->id}}">{{ $event->name}} - {{ date('m.Y', strtotime($event->date)) }}</option>
            @endforeach
        </select>
        </div>
    @endif
    <div class="form-group">
    <label for="contract-person">Na kogo: (możesz wybrać kilka osób)</label>
    <select id="contract-person" name="contract-person" multiple>
        @foreach ($members as $member)
            <option value="{{ $member->id }}" @if ( $loop->first) selected @endif>{{ $member->first_name }} {{ $member->last_name }}</option>
        @endforeach
    </select>
    </div>
    <div class="form-group">
    <label for="contract-amount">Kwota (łączna):</label>
    <input type="currency" id="contract-amount" name="contract-amount" value="0,00 zł"/>
    </div>
    {{ $submitButton }}

</form>