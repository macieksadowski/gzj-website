<script type="text/javascript">

function copyFcn() {
  /* Prepare text with data */
  var copyText = <?='\'Imię i nazwisko: '.$person['imie'].' '.$person['nazwisko'].
					'\r\nUlica i nr domu: '.$person['ulica'].' '.$person['nr_domu'].
					'\r\nKod pocztowy: '.$person['kod_pocztowy'].
					'\r\nMiasto: '.$person['miasto'].
					'\r\nPESEL: '.$person['PESEL'].
					'\r\nMiejsce urodzenia: '.$person['miejsce_urodzenia'].
					'\r\nNr konta: '.$person['nr_konta'].
					'\r\nUrząd skarbowy: '.$person['urzad_skarbowy'].'\'';?>;
  
  /*Create object for select fcn*/
  const el = document.createElement('textarea');
  el.value = copyText;
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);

	//Show communicate
  
  var modal = document.getElementsByClassName('modal')[0];
  modal.id = "success";
  document.getElementById("modal-text").innerHTML = "Dane skopiowano do schowka!";
  modal.style.display = "block";
  
}

function editdataFcn() {
	var btn = document.getElementsByName("edit");
	btn = btn[0];
	if(btn.value == "Aktualizuj")
	{
		btn.type = "submit";
		btn.formAction = "update-data.php";
	}
	btn.value = "Aktualizuj";
	var form = document.getElementById("personal-data").querySelectorAll("input"); 
	var i;
	for (i = 0; i < form.length; i++) {
	  form[i].removeAttribute("disabled");
	}
}







</script>