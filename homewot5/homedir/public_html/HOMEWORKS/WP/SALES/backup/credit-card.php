<?php
require_once('security.php');
$poNum='';
$tax='';$stamp='';
$total='';
if(isset($_GET['tax'])){$tax=$_GET['tax'];}
if(isset($_GET['stamp'])){$stamp=$_GET['stamp'];}
if(isset($_GET['poNum'])){$poNum=$_GET['poNum'];}
$saleType='sale';
if(isset($_GET['total'])){$total=$_GET['total'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml">  
<head> <link rel="stylesheet" type="text/css" href="/main.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script> 
<script> 
$(document).ready(function() {    
    $('a[name=modal]').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('href');
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();
        $('#mask').css({'width':maskWidth,'height':maskHeight});
        $('#mask').fadeIn(1000);    
        $('#mask').fadeTo("slow",0.8);    
        var winH = $(window).height();
        var winW = $(window).width();
        $(id).css('top',  winH/2-$(id).height()/2);
        $(id).css('left', winW/2-$(id).width()/2);
        $(id).fadeIn(2000);
        $("#textbox").focus();
    });
    
    $("#textbox").blur(function (e) {
        e.preventDefault();
        $('#mask').hide();
        $('.window').hide();
    }).keyup(function (e) {
        if($(this).val().substr($(this).val().length-1)=="?") {
            $('#mask').hide();
            $('.window').hide();
            setFromCCS($("#textbox").val());
        }
    });    
    $('.window .close').click(function (e) {
        e.preventDefault();
        $('#mask').hide();
        $('.window').hide();
        setFromCCS($("#textbox").val());
    });        
    
    $('#mask').click(function () {
        $(this).hide();
        $('.window').hide();
    });            
    
}); 

        function setFromCCS(ccs) {
				document.getElementById('TrackData').value=ccs;
                var index1 = ccs.indexOf("%B") + 2;
                var index2 = ccs.indexOf("^") + 1;
                var index3 = ccs.indexOf("^", index2 + 1) + 1;
                
                var cardNumber = ccs.substring( index1, index2 - 1);
                var expMonth = ccs.substr(index3, 2);
                var expYear = ccs.substr(index3 + 2, 2);
                var holderName = ccs.substring(index2, index3 - 1);
                var index4=holderName.indexOf("/");
				var temp1=holderName.substring(0,index4); 
				var temp2=holderName.substring(index4+1);
				holderName=temp2+' '+temp1;
                $("#cn").val(cardNumber);
                $("#my").val(expYear+expMonth);
                $("#hn").val(holderName);
        }

</script> 
<style> 
body {
font-family:verdana;
font-size:15px;
}
 
a {color:#333; text-decoration:none}
a:hover {color:#ccc; text-decoration:none}
 
#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
}
  
#boxes .window {
  position:absolute;
  left:0;
  top:0;
  width:160px;
  height:32px;
  display:none;
  z-index:9999;
  padding:20px;
}
 
#boxes #dialog {
  width:160px; 
  height:32px;
  padding:10px;
  background-color:#ffffff;
}
</style>
</head> 
<body> 
<script>
function updateAmt(val){
	var origAmount=<?php echo $total;?>;
	var newAmt=origAmount-val;
	window.parent.document.getElementById('payment_amount').value=newAmt;
	}
</script>
<a href="#dialog" name="modal" style="padding:4px; border:1px solid #ccc;">SWIPE CARD</a><br /><br />
<div id="boxes"> 
    <div id="dialog" class="window"><input id="textbox" type="text" value="" /></div>
    <div id="mask"></div> 
</div>
<form method="post" action="cc-process.php">
  <h4><table  border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><input type="hidden" name="MerchantID" value="60078"/>
      <input type="hidden" name="RegKey" value="YBWH7TSWFTGXTYER"/>
Amount: </td>
    <td><input type="text" name="Amount" value="<?php echo $total;?>" onchange="updateAmt(this.value)"/></td>
  </tr>
  <tr>
    <td>
     <input type="hidden" name="stamp" id="stamp"  value="<?php echo $stamp;?>"/>
      <input type="hidden" name="TrackData" id="TrackData"/>
      <input type="hidden" name="SaleTaxAmount" value="<?php echo $tax;?>"/>
      <input type="hidden" name="PONumber" value="<?php echo $poNum;?>"/>
      <input type="hidden" name="REFID" value="<?php echo $saleType.$poNum;?>"/>
            <input type="hidden" name="CCRURL" value="http://cman/ccreturn.php?poNum=<?php echo $poNum;?>&saleType=<?php echo $saleType;?>"/>
Card number:</td>
    <td><input type="text" name="CardNumber" id="cn" /></td>
  </tr>
  <tr>
    <td>Card exp Date:</td>
    <td><input type="text" name="Expiration" id="my" /></td>
  </tr>
  <tr>
    <td>Card holder name:</td>
    <td><input type="text" name="CardHolderName" id="hn" /></td>
  </tr>
  <tr>
    <td>CCV: </td>
    <td><input type="text" name="CVV2" value=""/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" /></td>
  </tr>
</table>



  </h4>
</form>
</body> 
</html>