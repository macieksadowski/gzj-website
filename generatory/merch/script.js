<script type="text/javascript">



function editFcn() {
	var btn = document.getElementsByName("edytuj");
	btn = btn[0];
	if(btn.value == "Zapisz")
	{
		btn.type = "submit";
	}
	btn.value = "Zapisz";
	var form = document.getElementById("items").querySelectorAll("input"); 
	var i;
	for (i = 0; i < form.length; i++) {
	  form[i].removeAttribute("disabled");
	}
}


function blockFcn() {
	var x = document.getElementsByName("id");
	var form = document.getElementById("new-sale");
	form.submit();
	for (const element of x)
	{
	  //  element.disabled = true;
	}
	
}





</script>