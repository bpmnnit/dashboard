function load_data(fp, fromdate, todate)
{
	$.ajax({
		url:"fetch.php",
		method:"post",
		data:{fp, fromdate, todate},
		success:function(data)
		{
			$('#result').html(data);
		}
	});
}

$('#fetch').click(function(){
	alert("See the poop!");
	var fp = $('#fpselect').val();
	if(fp.length == 0) {
		alert("Please select a field party.");
		return false;
	}
	var fromdate = $('#fromdate').val();
	var todate = $('#todate').val();
	if(fromdate.length == 0 || todate.length == 0) {
		alert("Please select from date and to date");
		return false;
	}
	var fromdate = new Date(fromdate);
	var todate = new Date(todate);
	if(todate.getTime() < fromdate.getTime()) {
		alert("To date is less than from date");
		return false;
	}
	load_data(fp, fromdate, todate);			
});