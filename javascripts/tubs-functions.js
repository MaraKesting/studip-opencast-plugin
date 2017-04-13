/*
 *
 *
 *
 *
 *
 */
 
 
 
/*
 *
 */
function delTitle() 
{
	if ( $("#titleField").css('color') == 'rgb(160, 160, 160)' ) {
		$("#titleField").val('');
		$("#titleField").css('color', '#000');
	}
}


/*
 *
 */
function fillTitle() 
{
	if ( $("#titleField").val() == '' 
			|| $("#titleField").val().substr(0,16) == 'Mein neues Video' ) 
				// Die 2. if-Bedingung soll greifen, wenn
				//  +  das Aufnahme-Datum ODER 
				//  +  die Startzeit-Dropdowns (Stunde : Minute) 
				// verändert werden. 
				// Damit soll der Default-Titel (sofern kein eigener angegeben) 
				// im Datum konsistent zu den Einstellungen gehalten werden.
	{
		var startTimeHour = $("#startTimeHour").val();
		if (startTimeHour.length == 1)
			startTimeHour = '0' + startTimeHour;
		var startTimeMin = $("#startTimeMin").val();
		if (startTimeMin.length == 1)
			startTimeMin = '0' + startTimeMin;
		$("#titleField").val('Mein neues Video ' + 
			$("#recordDate").val() + ' ' + 
			startTimeHour + ':' + startTimeMin );
		$("#titleField").css('color', '#a0a0a0');
	}
}
