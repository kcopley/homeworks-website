function printContent(div_id){
	var DocumentContainer = document.getElementById(div_id);
	var html = '<html><head>'+'<style type="text/css">small {font-family: Arial, Helvetica, sans-serif; font-size:11px;} .left {width:45%; float:left; padding-right:10px;} #datepicker {display:none;}</style>'+'</head><body style="background:#ffffff;">'+DocumentContainer.innerHTML+'</body></html>';

	var WindowObject = window.open("","PrintWindow","width=800,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
	WindowObject.document.writeln(html);
	WindowObject.document.close();
	WindowObject.focus();
	WindowObject.print();
	document.getElementById('print_link').style.display='block';
}
