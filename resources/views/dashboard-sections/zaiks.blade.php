@extends('layouts.dashboard')
@section('title', 'Generator ZAiKS')

@section('inner-content')
    <section>
        <div class="dashboard__generator">

            <form action={{ route('generateZAiKS') }} method="post">
                @csrf
                <p>Wybierz utwory, które mają zostać wpisane do tabelki ZAiKS:</p>
                <select name='songs[]' class="songSelect" multiple>
                    @foreach ($pageVariable as $item)
                        <option value={{ $item->id }}>{{ $item->title }}</option>
                    @endforeach
                </select>
                <div class="dashboard__generator__footer">
                    <input name="eventName" type="text" placeholder="Nazwa wydarzenia" onfocus="this.placeholder=''"
                        onblur="this.placeholder='Nazwa wydarzenia'">
                    <input name="generate" type="submit" value="Generuj dokument">
                </div>
            </form>
        </div>

    </section>
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
            showRemoveAllButton: true

        });
    </script>
@endsection
