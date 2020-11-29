<!-- The Modal -->
<div id="error" class="modal" 

<?php
if(isset($_SESSION['errors']))
{
    $errors = $_SESSION['errors'] ;
    //If there is error in memory then print it
    $errorStr = '';
    foreach($errors as $num => $error)
    {
        if($error == TRUE) 
        {
            if(@!$print)
            {
                echo ' style="display:block;" ';
                $print = true;
            }
            
            $errorStr .= ' '.$num;
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
     
      <p>Wystąpił błąd: <?=$errorStr;?></p>
    </div>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("error");

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