/*
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
*/
function create_fp_area() {
	var fp = document.getElementById('fpselect').value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("areaselect").innerHTML = '';
			document.getElementById("areaselect").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "getareas.php?fp=" + fp, true);
	xmlhttp.send();
}

function drop_down_acq_type() {
	var si_id = document.getElementById('areaselect').value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("acqtypeselect").innerHTML = '';
			document.getElementById("acqtypeselect").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "getacqtype.php?si_id=" + si_id, true);
	xmlhttp.send();
}

function create_dates_limit() {
	var si = document.getElementById('acqtypeselect').value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			dates = this.responseText;
			dates = dates.split('|');
			max_date = dates[1]; min_date = dates[0];
			document.getElementById("fromdate").value = min_date;
			document.getElementById("todate").value = max_date;
		}
	};
	xmlhttp.open("GET", "getdates.php?si=" + si, true);
	xmlhttp.send();
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}