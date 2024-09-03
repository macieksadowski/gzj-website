var table =  new  DataTable('#contractsTable', {
    dom: 'lfrtip',
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pl.json'
        },
        columnDefs: [
            {
                target: 1,
                visible: false,
                searchable: false
            },
        ],
        "autoWidth": false,
        "order": [ 2, 'desc' ],
        "columns": [
            null,
            null,
            null,
            { "width": "60%" },
            null,
            null
        ],
        responsive: {
            details: false
        }
});

$('#contractsTable').on('click', 'tbody tr', function() {
    window.location.href = `${window.location.origin}/dashboard/events/${table.row(this).data()[1]}`;
    });

var summaryTable =  new DataTable('#contractsSummaryTable', {
    searching: true,
    paging: false,
    info: false,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pl.json'
    },
    initComplete: function() {
        $("#contractsSummaryTable_filter.dataTables_filter").empty();
        $("#contractsSummaryTable_filter.dataTables_filter").append($("#summaryYearWrapper"));
        var yearColIndex = 0;
        $("#contractsSummaryTable th").each(function (i) {
            if ($($(this)).html().includes("Rok")) {
              yearColIndex = i; return false;
            }
        });
        DataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== "contractsSummaryTable") {
                return true;
            }
            var selectedYear = $("#summaryYear").val()
            var year = data[yearColIndex];
            if (selectedYear === "" || year.includes(selectedYear)) {
                return true;
            }
            return false;
        });
        $("#summaryYear").on('click', function() {
            summaryTable.draw();
        });
    
        summaryTable.draw();

    }
});    

    
