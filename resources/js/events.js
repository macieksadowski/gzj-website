var table =  new  DataTable('#eventsTable', {
    dom: 'Plfrtip',
    columnDefs: [
        {
            target: 3,
            render: DataTable.render.number( ' ', null, 2, null, ' zł' )
        }
    ],
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pl.json',
        searchPanes: {
            collapseMessage: 'Zwiń filtry',
            showMessage: 'Pokaż filtry'
        }
    },
    "autoWidth": true,
    searching: true,
    columns: [null, null, { type: 'date' }, { type: 'num-fmt' }, null, null],
    "order": [ 2, 'desc' ],
    responsive: {
        details: false
    }
} );



$('#eventsTable').on('click', 'tbody tr', function() {
window.location.href = `${window.location.href}/${table.row(this).data()[0]}`;
});