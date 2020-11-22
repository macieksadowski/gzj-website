<?php

//Add database access credentials
require_once "../connectvars-local.php";

function getFromDB($query,$reload = false,$reloadLocation = '')
{
    //Estabilish new connection with DB
	$DBconnection = @new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	if ($DBconnection->connect_errno!=0)
	{
        $_SESSION['error'] ='Błąd bazy danych ('.$DBconnection->connect_errno.')';
        if($reload) header('location: '.$reloadLocation);
	}
	else
	{
		//Get data from Database and save as array
		if($results = @$DBconnection->query($query))
		{
			$entriesNum = $results->num_rows;
			if($entriesNum > 0)
			{
				
				$entriesArray = $results->fetch_all(MYSQLI_ASSOC);
				$results->free_result();
				unset($_SESSION['error']);
			}
			else
			{
				$_SESSION['error'] ='Błąd bazy danych';
			}	
			
		}
		
        $DBconnection->close();
        if(isset($entriesArray)) return $entriesArray;
	}
}

//Function to convert polish PESEL number (personal ID number) to date of birth in single string format
//Attention: works for dates between years 1900 and 2099
function PESELtoDate($pesel)
{
						
	$dateOfBirth = substr($pesel,4,2);
	$dateOfBirth .= '.';
	$month = (int)substr($pesel,2,2);
	
	if($month > 20)
	{
		$month = $month - 20;
		
		$dateOfBirth .= $month;
		$dateOfBirth .= '.20';
		$dateOfBirth .= substr($pesel,0,2);
	}
	else
	{
		$dateOfBirth .= $month;
		$dateOfBirth .= '.19';
		$dateOfBirth .= substr($pesel,0,2);
	}
}


function generateDocumentFromTemplate( $template,$parametersToReplace,$outputLocation,$outputFilename)
{
	if(!empty($parametersToReplace))
	{
		//Make new MS Word file
		$zip = new ZipArchive();
		
		//Make a copy of template file
		if(!copy($template, $outputLocation.$outputFilename))
		{
			return 6;
		}
		else
		{
			// Open copied file
			if ($zip->open($outputLocation.$outputFilename, ZipArchive::CREATE)!==TRUE) 
			{
				return 6;
			}
			else
			{
				// Open XML document file
				$xml = $zip->getFromName('word/document.xml');
				
				//Replace keywords in document with prepared values from array
				foreach($parametersToReplace as $key=>$value)
				{
					$xml = str_replace($key,$value,$xml);
				}
				
				// Save and close file
				if ($zip->addFromString('word/document.xml', $xml)) 
				{ 
					$zip->close();
					return 0;
				}
				else 
				{ 
					return 7;
				}
			}
		}
	}
}

?>