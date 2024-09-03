var table = new DataTable('#financesTable', {
    ajax: 'dashboard/finances/source',
    dom: 'Plfrtip',
    columnDefs: [
        {
            target: 1,
            type: 'date',
            searchPanes: {
                show: false
            }  
        },
        {
            target: 2,
            render: DataTable.render.number(' ', null, 2, null, ' zł')
        },
        {
            target: 6,
            searchPanes: {
                show: false
            }  
        }
    ],
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pl.json',
        searchPanes: {
            clearMessage: 'Wyczyść filtry',
            collapseMessage: 'Zwiń filtry',
            showMessage: 'Pokaż filtry',
            title: 'Filtry aktywne - %d'
        }
    },
    autoWidth: true,
    searching: true,
    columns: [null, { type: 'date' }, { type: 'num-fmt' }, null, null, null, , null],
    order: [1, 'desc'],
    responsive: {
        details: false
    },
    layout: {
        top1: {
            searchPanes: {
                orderable: false,
                collapse: false
            }
        }
    },
});


$('#transaction-event').select2({
    theme: "bootstrap",
    selectionCssClass: 'form-select',
    language: 'pl',
});

$('#transaction-category').select2({
    theme: "bootstrap",
    selectionCssClass: 'form-select',
    language: 'pl',
});

$('#transaction-amount').on('change', function() {
    var amount = $(this).val();
    if (amount < 0) {
        $.ajax({
            url: 'dashboard/finances/})
    }