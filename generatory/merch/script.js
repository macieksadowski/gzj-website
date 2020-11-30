function disableSell(numInput) 
{
	val = numInput.value;	
	max = numInput.max;
	var btn = document.getElementById('sellBtn');
	if(max < 1 || val < 1 || val == null)
	{
		btn.disabled = true;

	}	
	else
	{
		btn.disabled = false;
	}
}

function setMaxAmount(dropdown)
{
	dropdown.value.substr(-5,1) 
}