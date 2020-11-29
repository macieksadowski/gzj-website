<?php


function resetAllErrorFlags()
{
	if(isset($_SESSION['errors']))
	{
		foreach($_SESSION['errors'] as $key => $error)
		{
		$_SESSION['errors'][$key] = FALSE;
		}
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

function showModal()
{
	?>
	<!-- The Modal -->
<div class="modal" 

<?php
if(isset($_SESSION['success']))
{
	echo ' id="success" style="display:block;" ';
	$communicate = $_SESSION['success'];
	unset($_SESSION['success']);
}
else if(isset($_SESSION['errors']))
{
    $errors = $_SESSION['errors'] ;
    //If there is error in memory then print it
    $communicate = '';
    foreach($errors as $num => $error)
    {
        if($error == TRUE) 
        {
            if(@!$print)
            {
				$communicate = 'Wystąpił błąd: ';
                echo ' id="error" style="display:block;" ';
                $print = true;
            }
            
            $communicate .= ' '.$num;
        }
    }
    //After printing errors reset error memory
    resetAllErrorFlags();
}

?>
>

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-body">
      <span class="close">&times;</span>
     
      <p id="modal-text"><?=$communicate;?></p>
    </div>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementsByClassName("modal")[0];

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<?php
}
?>