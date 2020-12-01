<?php
	
	session_start();
	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}

	//Add database access credentials
	require_once "../connectvars.php";
	

	//Preprare SQL query and get songs list from DB
	$query = "SELECT * FROM dane";
	$entriesArray = getFromDB($query);
	
	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	unset($_SESION['sukces']);
	
	
	if ($polaczenie->connect_errno!=0)
	{
		$_SESSION['blad'] ='Błąd: '.$polaczenie->connect_errno;
		header('location: ./index.php');	
	}
	else
	{
		if(isset($_POST['edytuj']))
		{
			$query1 = 'UPDATE stany_magazynowe SET ';
			$query2 = 'WHERE ';
			if($rezultat = @$polaczenie->query(sprintf("SELECT id,Zapas_magazynowy,Spakowano_na_koncert FROM stany_magazynowe",mysqli_real_escape_string($polaczenie,$login))))
			{
				$ile_wpisow = $rezultat->num_rows;
				if($ile_wpisow > 0)
				{
					$tabela = $rezultat->fetch_all(MYSQLI_ASSOC);
					$rezultat->free_result();
				}
				else
				{
					$_SESSION['blad'] ='Wystąpił błąd!';
					header('location: ./index.php');
				}	
			}
			
			foreach($_POST as $name=>$value)
			{
				if($name[0] == 's') 
					$updated2[] = $value;
				else if($name[0] == 'z')
					$updated1[] = $value;
			}
			foreach($tabela as $name=>$value)
			{
				$old2[] = $value['Spakowano_na_koncert'];
				$old1[] = $value['Zapas_magazynowy'];
			}
			foreach($updated1 as $i=>$value)
			{
				if($old1[$i] != $value)
					$to_query1[$i] = $value;
			}
			foreach($updated2 as $i=>$value)
			{
				if($old2[$i] != $value)
					$to_query2[$i] = $value;
			}
			print_r($old1);
			echo '<br>';
			print_r($updated1);
			echo '<br>';
			print_r($old2);
			echo '<br>';
			print_r($updated2);
			echo '<br>';
			print_r($to_query1);
			echo '<br>';
			print_r($to_query2);
			
			$query='';
			foreach($to_query1 as $i=>$value)
			{
			    $query.= 'UPDATE stany_magazynowe SET Sztuk_lacznie = '.$value.'+ Spakowano_na_koncert,  Zapas_magazynowy = '.$value.'  WHERE id = '.($i+1);    
			    $query.= ';';
			}
			foreach($to_query2 as $i=>$value)
			{
			    $query.= 'UPDATE stany_magazynowe SET Sztuk_lacznie = '.$value.'+ Zapas_magazynowy,  Spakowano_na_koncert = '.$value.' WHERE id = '.($i+1);
			    $query.=';';
			}
			
			print_r($query);
					if($rezultat = @$polaczenie->multi_query($query))
					{
				        	unset($_SESSION['blad']);
		                	$_SESSION['sukces'] = 'Poprawnie zaktualizowano dane!';
		                	print_r($_SESSION['sukces']);
			                $polaczenie->close();
			                //header('location: przeglad.php');
					}
					else
					{
							$_SESSION['blad'] ='Wystąpił błąd bazy danych!  '.$query;
							print_r($_SESSION['blad']);
							$polaczenie->close();
//header('location:  index.php');
					}
		}
		else
		{		
		    print_r($_POST);
			$id = $_POST['id'];	
			$query = 'UPDATE stany_magazynowe SET Sztuk_lacznie = Sztuk_lacznie - 1, Spakowano_na_koncert = Spakowano_na_koncert - 1 WHERE id = '.$id;

			if($rezultat = @$polaczenie->query($query))
			{
				$_SESSION['sukces'] = 'Poprawnie zaktualizowano dane!';
				$polaczenie->close();
				//header('location: nowa-sprzedaz.php');
				
				
			}
			else
			{
					$_SESSION['blad'] ='Wystąpił błąd bazy danych!  '.$query;
					$polaczenie->close();
					//header('location:  index.php');
			}	
		}
	}
			
	
?>