<?php
	
	
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
	require_once "polaczenie.php";
	
	
	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		$_SESSION['blad'] ='Błąd: '.$polaczenie->connect_errno;
		header('location: ./umowy.php');
		
	}
	else
	{
		
		$osoba = $_POST['dane'];
		$dane = array_slice($osoba, 2); 
		print_r($osoba);
		echo $osoba['adres'];
		$adres = $osoba['adres'];
		echo $adres;
		
		$dane['ulica']  = (implode(' ',explode(' ',$adres,-1)));
		$dane['nr_domu'] =  end(explode(' ', $adres));
		
		
		$query = "UPDATE dane SET ";
		$i=0;
		foreach($dane as $key => $pole)
		{
			$i++;
			echo  $pole;
			if($i == count($dane))
				$query .= $key.' = \''.$pole.'\' ';
			else
				$query .= $key.' = \''.$pole.'\', ';
			
		}
		
		$query .= 'WHERE id = '.$_POST['osoba'] ;
		
	
		
		if($rezultat = @$polaczenie->query($query))
		{
			$_SESSION['sukces'] = 'Poprawnie zaktualizowano dane!';
			header('location: umowy.php');
			
			
		}
		else
		{
				$_SESSION['blad'] ='Wystąpił błąd bazy danych!  '.$query;
				header('location: umowy.php');
		}	
		$polaczenie->close();
	}

	
?>