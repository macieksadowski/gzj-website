$(function () {
    var modal = document.getElementsByClassName("modal-bottom")[0];
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    $("#modal-bottom-close").on("click", function () {
        // When the user clicks on <span> (x), close the modal
        modal.style.display = "none";
    });

    $("#button-copy").on("click", function () {
        /* Prepare text with data */
        var copyText =
            "Imię i nazwisko: " +
            $("#first_name").val() +
            " " +
            $("#last_name").val() +
            "\r\nUlica i nr domu: " +
            $("#street").val() +
            " " +
            $("#house_no").val() +
            "\r\nKod pocztowy: " +
            $("#postal_code").val() +
            "\r\nMiasto: " +
            $("#town").val() +
            "\r\nPESEL: " +
            $("#pesel").val() +
            "\r\nMiejsce urodzenia: " +
            $("#birth_place").val() +
            "\r\nNr konta: " +
            $("#account_no").val() +
            "\r\nUrząd skarbowy: " +
            $("#tax_office").val();

        //Show communicate
        $("#clipboardModalLabel").text("Kopiuj dane");
        $("#modal-text").html(copyText.replaceAll("\r\n", "<br />"));
        $("#modal-copy-button").on("click", function () {
            if (!navigator.clipboard) {
                /*Create object for select fcn*/
                const el = document.createElement("textarea");
                el.value = copyText;
                el.select();
                document.body.appendChild(el);
                document.execCommand("copy");
                document.body.removeChild(el);
            } else {
                navigator.clipboard.writeText(copyText);
            }
        });
        $("#clipboardModal").modal("show");
    });

    $("#button-edit").on("click", function () {
        var btn = $("#button-edit").get(0);
        if (btn.value == "Aktualizuj") {
            btn.type = "submit";
            //btn.formAction = "update-data.php";
        }
        btn.value = "Aktualizuj";
        var form = document
            .getElementById("personal-data")
            .querySelectorAll("input");
        var i;
        for (i = 0; i < form.length; i++) {
            form[i].removeAttribute("disabled");
        }
    });
});
