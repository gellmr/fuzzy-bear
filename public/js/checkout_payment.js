


$( document ).ready(function(){
    
	var date = new Date;
	
	var seconds = date.getSeconds();
	var minutes = date.getMinutes();
	var hour = date.getHours();

	console.log("Checkout page (payment) loaded. Time of Day == " + hour + ':' + minutes + ' and ' + seconds + " seconds");
	
});
