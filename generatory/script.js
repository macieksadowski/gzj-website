<script type="text/javascript">

	$('#kod_pocztowy').on('keypress change', function() {
  $(this).val(function(index, value) {
    return value.replace(/\W/gi, '').replace(/(.{2})/, '$1-');
  });
});

$('#kod_pocztowy').on('paste', function() {
  $(this).val(function(index, value) {
    return value.replace(/\W/gi, '').replace(/(.{2})/, '$1-');
  });
  setTimeout(function() {
    $('#kod_pocztowy').trigger("change");
  });
});



function copyFcn() {
  /* Prepare text with data */
  var copyText = <?='\'Imię i nazwisko: '.$osoba['imie'].' '.$osoba['nazwisko'].
					'\r\nUlica i nr domu: '.$osoba['ulica'].' '.$osoba['nr_domu'].
					'\r\nKod pocztowy: '.$osoba['kod_pocztowy'].
					'\r\nMiasto: '.$osoba['miasto'].
					'\r\nPESEL: '.$osoba['PESEL'].
					'\r\nMiejsce urodzenia: '.$osoba['miejsce_urodzenia'].
					'\r\nNr konta: '.$osoba['nr_konta'].
					'\r\nUrząd skarbowy: '.$osoba['urzad_skarbowy'].'\'';?>;
  
  /*Create object for select fcn*/
  const el = document.createElement('textarea');
  el.value = copyText;
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);
  
  /* Alert the copied text */
  alert("Skopiowano dane!");
}

function editdataFcn() {
	var btn = document.getElementsByName("zmien");
	btn = btn[0];
	if(btn.value == "Aktualizuj")
	{
		btn.type = "submit";
		btn.formAction = "aktualizuj-dane.php";
	}
	btn.value = "Aktualizuj";
	var form = document.getElementById("personal-data").querySelectorAll("input"); 
	var i;
	for (i = 0; i < form.length; i++) {
	  form[i].removeAttribute("disabled");
	}
}







</script>