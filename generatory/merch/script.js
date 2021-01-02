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
	const regex = /\((\d+)szt\.\)/m;
	var text = dropdown.options[dropdown.selectedIndex].text;
	var found = text.match(regex);
	var amount = 0;
	
	if(found != null)
	{
		var amount = found[1];
	}	
	var amountInput = document.getElementsByName('amount')[0];
	amountInput.value = null;
	amountInput.max = amount;
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

function ShowAddNewProduct() {
	var x = document.getElementById("newProduct");
	if (x.style.display === "none") {
	  x.style.display = "block";
	} else {
	  x.style.display = "none";
	}
  }

  function addNewSize(clickedButton)
  {

	  var table = document.getElementById("newProductTable");
	  var row = table.insertRow(-1);
	  var cell1 = row.insertCell(0);
	  var cell2 = row.insertCell(1);
	  var cell3 = row.insertCell(2);

	  var deleteButton = document.createElement("button");
	  deleteButton.className = "deleteButton";
	  deleteButton.setAttribute('onclick',"removeSize(this)");
	 
	  deleteButton.type = "button";
	  deleteButton.innerHTML = "x";
	  cell1.appendChild(deleteButton);
	  
	  
	  var selectList = document.createElement("select");
	  selectList.name = "newProduct[sizes][size][]";
	  var sizes = ['N','S','M','L','XL'];
	  sizes.forEach(element => {
		  var option = document.createElement("option");
		  option.value = element;
		  option.text = element;
		  selectList.add(option);
	  });
	  cell2.appendChild(selectList);

	  var amount = document.createElement("input");
	  amount.type = 'number';
	  amount.name = "newProduct[sizes][amount][]";
	  amount.min = '0';
	  amount.max = '999';
	  amount.size = '3';
	  amount.placeholder = 'Ilość';
	  cell2.appendChild(amount);


	  clickedButton.remove();
	  var newButton = document.createElement("button");
	  newButton.id = "newSizeBtn";
	  newButton.type = "button";
	  newButton.className = "button";
	  newButton.setAttribute('onclick', 'addNewSize(this)');

	  newButton.innerHTML = "Dodaj rozmiar";
	  cell3.appendChild(newButton);
	
	  	
  }

  function removeSize(button)
  {
	  var row = button.parentElement.parentElement;
	  row.remove();

	  
	  var table = document.getElementById("newProductTable");
	  var lastRow = table.rows[table.rows.length -1];
	  var lastCell = lastRow.cells[lastRow.cells.length -1];
	  if(lastCell.children.length == 0)
	  {
		var newButton = document.createElement("button");
		newButton.id = "newSizeBtn";
		newButton.type = "button";
		newButton.className = "button";
		newButton.setAttribute('onclick', 'addNewSize(this)');

		newButton.innerHTML = "Dodaj rozmiar";
		lastCell.appendChild(newButton);
	  }
	  
	  
  }