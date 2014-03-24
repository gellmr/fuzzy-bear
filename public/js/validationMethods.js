
		
/**
		Display a message about the failed validation of this field.

		Focus on and select this field.
		*/
function validationFailed( errField, messageString)
{
			alert(messageString);

			errField.focus();

			errField.select();
}








/**
		Checks if the given field contains a number greater than zero.
		
		Assumes the field has already passed validateIsNumber()
		*/
function validateMoreThanZero(field)
{
			if (field.value >= 1)
			{
						return true;
			}
			return false;
}

		
		
		
		
/**
		Checks if the given field contains a numeric value.

		Ignores all spaces.
		*/
function validateIsNumber(field)
{

			re = /^[0-9\ ]+$/; // Regular expression to match any numeric value. May contain spaces anywhere.

			if( re.test( field.value ) )
			{

						// alert("This field contains a number: " + field.id);
	
						return true; // This field contains a number.
			}
	
			//var message = "Field must contain a number " + (field.id);
	
			var message = "Field must contain a positive integer, or zero.";
	
			validationFailed(field, message);
	
			return false;
}







/**
		Check if the given Text Input Field contains text.

		If not, display an error message and focus on the field.
		*/
function validateTextInputField(field)
{
	
			re = /^.+$/; // Regular expression to match a string containing at least one character of any kind.

			if( re.test( field.value ) )
			{
						// Field contains at least one character.
		
						return true;
			}
			else
			{
						// Field is empty.

						validationFailed(field, "Field must contain a value.");
		
			}
			return false;
}








/*
	* Validate the given quantity.
	* 
	* Must be exactly zero. Note that 00000 is accepted.
	*/
function validate_isZero(val)
{
			var zeroRE = /^0+$/; // exactly zero

			var str = val.toString();

			if (zeroRE.test(str))
			{
						console.log("validate_isZero()  successful");

						return true;
			}

			console.log("validate_isZero()  failed");

			return false;
}




/*
	* Validate the given quantity.
	* 
	* Must be a positive integer
	*/
function validate_positiveInteger(val)
{
			//var re = /^[0-9]+(\.)?[0-9]*$/; // decimals

			var integerRE = /^[1-9]+[0-9]*$/; // integers

			var str = val.toString();

			if (integerRE.test(str))
			{
						console.log("validate_positiveInteger()  successful");

						return true;
			}

			console.log("validate_positiveInteger()  failed");

			return false;
}




/*
	* Validate the given quantity.
	* 
	* Must be a positive integer or zero
	*/
function validate_positiveIntegerOrZero(val)
{

			if (validate_positiveInteger(val) || validate_isZero(val))
			{
						console.log("Quantity is OK");

						return true;
			}

			console.log("Quantity is BAD");

			return false;
}