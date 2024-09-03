$(function () {

    var markedToDelete = [];

    var dynamicallyCreated = [];

    $("#edit-contracts-form-new-entry").css({"display":"none"});

    $(".deleteDb").on("click", function () {
        $( this ).parent().css({"display": "none"});
        markedToDelete.push($( this ).parent());
    } );

    $("#edit-contracts-form-table").on("click",".deleteTmp" , function () {
        $( this ).parent().remove();
    } );
    

    var cloneCount= 0;
    
    $(".addNewBtn").on("click", function() {
        cloneCount++;
        var newEntry = $("#edit-contracts-form-new-entry")
            .clone()
            .attr("id", 'edit-contracts-form-new-entry-'+ cloneCount);
        newEntry.find("[id*='contract-']").each( function (i) {
            var name = this.name;
            $(this).attr("name", 'new-contract['+cloneCount+']'+name );
        });

        newEntry
            .css({"display" :"table-row"})
            .appendTo("#edit-contracts-form-table");

        dynamicallyCreated.push(newEntry);

        $("#edit-contracts-form-add-tr").appendTo("#edit-contracts-form-table");
    });

    $('#editContractsModal').on('hidden.bs.modal', function (e) {
        markedToDelete.forEach(element => {
            element .css({"display" :"table-row"});
        });
        markedToDelete.length = 0;

        dynamicallyCreated.forEach(element => {
            element.remove();
        })
        dynamicallyCreated.length = 0;
      })
});