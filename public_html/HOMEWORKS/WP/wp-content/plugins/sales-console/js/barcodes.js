function printBarcode(div_id){
	var DocumentContainer = document.getElementById(div_id);
	var html = '<html><head>'+'<style type="text/css">table {width:756px;} td {width:252px; height:96px;} td img {max-width: 126px;} small {font-family: Arial, Helvetica, sans-serif; font-size:11px;} .left {width:53%; float:left; padding-right:5%; padding-left:2%;} .right{ width:40%; float:right;} .right img {width: 100%;} td.ltd div.left {}; td.ctd div.left {} td.rtd div.left {width:50%; float:left; padding-right:2%; padding-left:8%;}</style>'+'</head><body style="background:#ffffff;">'+DocumentContainer.innerHTML+'</body></html>';

	var WindowObject = window.open("","PrintWindow","width=800,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=no");
	WindowObject.document.writeln(html);
	WindowObject.document.close();
	WindowObject.focus();
	WindowObject.print();
	document.getElementById('print_link').style.display='block';
}
