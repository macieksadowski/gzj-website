@extends('layouts.master')
@section('title', 'Generator ZAiKS')

@section('content')

<section>
    <div class="generator">

        <form action={{ route('generateZAiKS') }} method="post" >
        @csrf
            Wybierz utwory, które mają zostać wpisane do tabelki ZAiKS:
            <select name='songs[]' class="songSelect" multiple >
            @foreach ($pageVariable as $item)
                <option value={{ $item->id}}>{{ $item->title}}</option>

            @endforeach


            </select>



            <div class="form-footer">
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

    <script type="text/javascript">
        new DualListbox('.songSelect',{
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
