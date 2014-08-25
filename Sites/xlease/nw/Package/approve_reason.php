<?php

$appfpackID = $_POST['checkdown'];
$type = $_GET['type'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- เหตุผลที่ไม่อนุมัติ  -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
<style type="text/css">
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
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body bgcolor="#EEF2F7">

<form name="frm" action="approve_query_no.php" method="post">

<!--Hidden value-->
	
<?php for($z=0;$z<sizeof($appfpackID);$z++){ ?>
	<input type="hidden" name="appfpackID[]" value="<?php echo $appfpackID[$z]; ?>">
<?php } ?>	
	<input type="hidden" name="type" value="<?php echo $type;?>">

	<table  border="0" cellspacing="0" cellpadding="0"  align="center" >
		<tr>
			<td>
			<center><legend><h3><B>-- เหตุผลที่ไม่อนุมัติ --</B></h3></legend></center>
				<div align="center">
				<div class="style5" style="width:auto; height:40px; padding-left:10px;">
					<table  frame="BORDER" cellSpacing="1" cellPadding="2">
						<tr >
							<td colspan="2">
								<textarea rows="10" cols="80" name="reason"></textarea>
							</td>
						</tr>
						<tr bgcolor="#C2CFDF">
							<td align="center">
								<input type="submit" value="ยืนยัน" style="width:100px;height:35px">
								
							</td>
							<td  align="center">
								<input type="button" value="ยกเลิก" style="width:100px;height:35px" onclick="parent.location.href='approve_index.php'">
								
							</td>
						</tr>
					</table>
				</div>
				</div>
			</td>
		</tr>
	</table>
</form>	
</body>

	
				
				
				
