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

function disablePlusMinus(input)
{
	var inStore = input.parentElement.parentElement.getElementsByClassName('plusMinusInput')[0];
	var plusBtn = inStore.getElementsByClassName("plus")[0];
	if(input.value > 0)
	{
		plusBtn.classList.remove("disabled");
	}
	else
	{
		plusBtn.classList.add("disabled");
	}
}

function changeVal(button)
{
	
	
	var input = button.parentElement.children[0];
	var inWarehouse = button.parentElement.parentElement.parentElement.getElementsByClassName('inWarehouse')[0];
	var inWarehouseOldVal = Number(inWarehouse.value);
	var oldVal = Number(input.value);
	if(button.classList.contains("plus"))
	{
		var minusBtn = button.parentElement.getElementsByClassName('minus')[0];
		if(!button.classList.contains("disabled"))
		{
			input.value =  oldVal + 1;
			minusBtn.classList.remove("disabled");
			inWarehouse.value = inWarehouseOldVal - 1;
			if(inWarehouse.value < 1) button.classList.add("disabled");
		}
	}
	else if(button.classList.contains("minus"))
	{
		var plusBtn = button.parentElement.getElementsByClassName('plus')[0];
		if(!button.classList.contains("disabled"))
		{
			input.value = oldVal - 1;
			plusBtn.classList.remove("disabled");
			if(input.value < 1) button.classList.add("disabled");
			inWarehouse.value = inWarehouseOldVal + 1;
		}
	}	
	

	
}