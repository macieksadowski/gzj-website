@extends('layouts.dashboard')
@section('title', 'Generator ZAiKS')

@section('inner-content')
    <section>
        <div class="dashboard__generator">
            <p>Wybierz utwory, które mają zostać wpisane do tabelki ZAiKS:</p>
            <form action={{ route('generateZAiKS') }} method="post">
                @csrf
                
                <select name='songs[]' class="songSelect" multiple>
                    @foreach ($pageVariable as $item)
                        <option value={{ $item->id }}>{{ $item->title }}</option>
                    @endforeach
                </select>
                <div class="dashboard__generator__footer">
                    <input name="eventName" type="text" placeholder="Nazwa wydarzenia" onfocus="this.placeholder=''"
                        onblur="this.placeholder='Nazwa wydarzenia'" class="form-control">
                    <input name="generate" type="submit" value="Generuj dokument" class="btn btn-primary">
                </div>
            </form>
        </div>

    </section>
@endsection

@section('scripts')
    @parent
    <script>
        let listbox = new DualListbox('.songSelect', {
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

        listbox.addEventListener('added', function(event){
            listbox.search_left.value = '';
        });

        listbox.add_all_button.classList.add('btn', 'btn-secondary');
        listbox.remove_all_button.classList.add('btn', 'btn-secondary');

        listbox.add_all_button.parentNode.classList.add('listboxFirstButtons');
        listbox.selectedList.parentNode.classList.add('listboxSelectedList');
        listbox.availableList.parentNode.classList.add('listboxAvailableList');

    </script>
@endsection
