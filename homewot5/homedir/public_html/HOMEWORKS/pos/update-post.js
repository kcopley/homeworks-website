// JavaScript Document
var theDiv='';
function ajaxRequest(){
 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
  for (var i=0; i<activexmodes.length; i++){
   try{
    return new ActiveXObject(activexmodes[i])
   }
   catch(e){
    //suppress error
   }
  }
 }
 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
  return new XMLHttpRequest()
 else
  return false
}
var mypostrequest=new ajaxRequest()
mypostrequest.onreadystatechange=function(){
 if (mypostrequest.readyState==4){
  if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1){
	  var results=mypostrequest.responseText;
   document.getElementById(theDiv).innerHTML=results;

  }
  else{
   alert("An error has occured making the request")
  }
 }
}
function updatePost(divId, value){
	theDiv=divId;
	var value=encodeURIComponent(value);
	var parameters="id="+value;
	mypostrequest.open("POST", "bar.php", true)
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	mypostrequest.send(parameters)
}
