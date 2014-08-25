<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script> 
<title> ยกเลิกสัญญาเช่าซื้อ <?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<style type="text/css">

    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
    }
   


    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
    #color_hr
	{
	color:#999999;
	}  
</style>

	
</head>

<body style="background-color:#999999;">
<div style="width:auto; height:auto;">
<div id="warppage" style="width:720px; height:150px;">
<div style="width:auto; text-align:left; padding-left:15px; color:#666666; text-shadow: -1px -1px white, 0.5px 0.5px #333"><h2><?php echo $_SESSION["session_company_name"]; ?></h2></div>
  <div id="h2" style="height:20px; padding-left:15px; margin-top:20px;">
 
    <b>ยกเลิกสัญญาเช่าซื้อ</b> </div>
  <div id="contentpage" style="padding-left:15px; height:100px;"><hr style="color:#959596; height: 1px;"/> 
  <form method="post" action="frm_show_dtl.php">
  ตรวจสอบ 
    <input type="text" size="95" id="idno_names" name="idno_names" onKeyUp="findNames();" style="height:20;"/>
	<input name="h_id" type="hidden" id="h_id" value="" />
             <input type="submit" value="NEXT" />
             <input name="button" type="button" onclick="window.close()" value="CLOSE" />
            
<script type="text/javascript">
function make_autocom(autoObj,showObj){
	var mkAutoObj=autoObj; 
	var mkSerValObj=showObj; 
	new Autocomplete(mkAutoObj, function() {
		this.setValue = function(id) {		
			document.getElementById(mkSerValObj).value = id;
		}
		if ( this.isModified )
			this.setValue("");
		if ( this.value.length < 1 && this.isNotClick ) 
			return ;	
		return "listdata_cc.php?q=" + this.value;
    });	
}	

make_autocom("idno_names","h_id");

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
  </form>	
  </div>
  </div>
</div>
	<div style="margin-top:25px" align="center" ></div>	
	<table width="1000" border="0" cellspacing="0" cellpadding="0"  align="center">	
		<tr>	
			<td>
				<?php 
					$limitshow = "true";
					include("frm_history.php"); 
				?>	
			</td>			
		</tr>			
	</table>
  
</body>
</html>
