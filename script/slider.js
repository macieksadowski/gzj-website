


	var iloscSlajdow = 5;

	var numer = Math.floor(Math.random()*iloscSlajdow)+1;
	var timer1 = 0;
	var timer2 = 0;
	
	function ustawslajd(nrslajdu)
	{
		clearTimeout(timer1);
		clearTimeout(timer2);
		numer = nrslajdu -1;
		schowaj();
		setTimeout("zmienSlajd()",500);
	}
	
	function schowaj()
	{
	 $("#slider").fadeOut(500);
	}
	function zmienSlajd()
	{
		
		numer++;if (numer>iloscSlajdow) numer = 1;
		var plik = "<img src=\"./img/slider/slajd"+numer+".jpg\"/>";
		//document.getElementById("number").innerHTML = numer;
		document.getElementById("slider").innerHTML = plik;
		$("#slider").fadeIn(500);
		timer1 = setTimeout("zmienSlajd()",5000);
		timer2 = setTimeout("schowaj()",4500);
	}
