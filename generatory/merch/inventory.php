<?php
	require_once "../polaczenie.php";
	
	session_start();
	
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: ../index.php');
		exit();
	}
	
		
	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno. "<br />";
	}
	else
	{
		if($rezultat = @$polaczenie->query(sprintf("SELECT * FROM stany_magazynowe",mysqli_real_escape_string($polaczenie,$login))))
		
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
		
		$polaczenie->close();
	}

?>




<!DOCTYPE HTML>
<html lang="pl">
<head>
<?php 
	require_once "../../head.php"; 
?>

<meta name="robots" content="noindex">

<link href="../style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<div class="page-container">
	<header>
	<div class="header">

		<a href="#"><div class="logo">
			<img src="/img/logo-square.png">
		</div></a>
		
		
		<input id="menu-toggle" type="checkbox" />
		<label class='menu-button-container' for="menu-toggle">
			<div class='menu-button'></div>
		</label>
		
		
		<ol class="menu" itemscope itemtype="https://schema.org/BreadcrumbList">
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="#"><span itemprop="name">Gadżety</span></a> <meta itemprop="position" content="1" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="../zaiks.php"><span itemprop="name">Generator ZAiKS</span></a> <meta itemprop="position" content="21" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="../umowy.php"><span itemprop="name">Generator umów</span></a> <meta itemprop="position" content="3" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="https://1drv.ms/x/s!AtuZqE8lQUVWjwAMtnCFEo1DvIkV?e=9fchsY" target="_blank"><span itemprop="name">Finanse</span></a> <meta itemprop="position" content="4" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="../logout.php"><span itemprop="name">Wyloguj się</span></a> <meta itemprop="position" content="5" /></li>
		</ol>
		
		
	</div>
	
	<div class="title"><h1>Sprzedaż gadżetów</h1></div>
	</header>
	<main>	
	<form method="post" action="aktualizuj.php">
	<div class="generator sprzedaz">
	
		<div class="formtitle">
		<h1>Przegląd zapasów</h1>
		<div>
			<label><div class="button"><a href="./">Wróć</a></div></label>
			<input class="button" onclick="editFcn()" name="edytuj" type="button" value="Edytuj">
		</div>
		
		</div>
		
		<table id="items">
		<tr>
			<th>Lp. </th>
			<th>Nazwa</th>
			<th>Łącznie</th>
			<th>Zapas</th>
			<th>Spakowano</th>
			<th></th>
			
		</tr>
		<?php
			foreach($tabela as $key=>$wiersz)
			{
				echo '<tr>';
				
				echo '<td>'.$wiersz['id'].'</td><td>'.$wiersz['Nazwa'].'</td><td>'.$wiersz['Sztuk_lacznie'].'</td><td><input disabled type="text" name="z'.$wiersz['id'].'" value="'.$wiersz['Zapas_magazynowy'].'"  inputmode="numeric" maxlength="3"></td><td><input disabled type="text" name="s'.$wiersz['id'].'" value="'.$wiersz['Spakowano_na_koncert'].'"  inputmode="numeric" maxlength="2"></td>';
				
				echo '</tr>';
			}
				
		?>
		

			
		</table>
		
	</div>
	</form>	
	</main>
	<footer>
	<div class="footer">
			<a href="#">Główny Zawór Jazzu</a> &copy;&nbsp;2020&nbsp;Maciej&nbsp;Sadowski
	</div>
	</footer>
</div>	

<?php

	require_once "script.js";



if(isset($_SESSION['sukces']))
{
	echo '<script>';
	echo 'alert("Pomyślnie zaktualizowano dane!")';
	echo '</script>';
	unset($_SESSION['sukces']);
}

?>
</body>
</html>