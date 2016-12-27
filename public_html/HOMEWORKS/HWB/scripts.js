// JavaScript Document
function udpateFields(checkbox){
	var checkIt=document.getElementById('same').checked;
	if(checkIt==true){
		document.getElementById('first_name_s').value=document.getElementById('first_name').value;
		document.getElementById('last_name_s').value=document.getElementById('last_name').value;
		document.getElementById('address_s').value=document.getElementById('address').value;
		document.getElementById('address2_s').value=document.getElementById('address2').value;
		document.getElementById('city_s').value=document.getElementById('city').value;
		document.getElementById('state_s').value=document.getElementById('state').value;
		document.getElementById('zip_s').value=document.getElementById('zip').value;
		}
		
	else{
		document.getElementById('first_name_s').value='';
		document.getElementById('last_name_s').value='';
		document.getElementById('address_s').value='';
		document.getElementById('address2_s').value='';
		document.getElementById('city_s').value='';
		document.getElementById('state_s').value='';
		document.getElementById('zip_s').value='';
		}
	}