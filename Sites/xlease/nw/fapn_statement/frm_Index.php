<?php
include("../../config/config.php");
$contractID = $_GET["idno"];
if($contractID == ""){$contractID = $_POST["idno_text"];}

if(empty($_POST["signDate"])){
    $ssdate = nowDate();
}else{
    $ssdate=$_POST["signDate"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แสดงวงเงินและหนี้</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#idno_text").autocomplete({
        source: "s_idno.php",
        minLength:1
    });
	
	$("#signDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	$("#showBT").click(function(){
		var conID = $("#conID").val();
		$("#TB_Close").load("Data_table_contractref_closed.php?contractID="+conID);
	});
	
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
	
function chkdate()
{	
	if(document.getElementById("chk_conFreeDate").value != '')
	{	
		if(document.getElementById("signDate").value > document.getElementById("chk_conFreeDate").value)
		{
			document.getElementById("damage").disabled = true;
			document.getElementById("damage").checked = false;
		}
		else
		{
			document.getElementById("damage").disabled = false;
		}
	}
	else
	{
		document.getElementById("damage").disabled = false;
	}
}
</script>
    
</head>
<body>

<input type="hidden" name="test" id="test">

<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<?php
include "menu.php"; // tab menubar
?>

<fieldset>
	<legend><B>ค้นหา</B></legend>
	<center>
	<div align="center" style="width:850px;">
		<div style="float:center; width:850px;">
		<form method="post" name="form1" action="frm_Index.php">
			เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว : &nbsp
			<input type="text" name="idno_text" id="idno_text" value="<?php echo $contractID; ?>" size="70"> &nbsp;
			<input type="submit" id="btnsearch" value="ค้นหา">
		</form>
		</div>
		<div style="float:left; width:50px;">
		<form method="post" name="form2" action="frm_pdf.php" target="_blank">
			<input type="hidden" name="idno_text" value="<?php echo $contractID; ?>">
			<?php
				if($contractID != "")
				{
			
					echo "<input type=\"button\" id=\"btnprint\" value=\"พิมพ์\" onclick=\"javascript:popU('frm_pdf.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')\">";
			
				}
			?>
		</form>
		</div>
		<div style="clear:both;"></div>
		<div id="panel" align="left" style="margin-top:10px"></div>
	</div>
	</center>
</fieldset>


<?php
if($contractID != ""){
?>
<div style="margin-top:0px;"><?php include('./Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
<div style="margin-top:0px;"><?php  include('./Data_other_debt_allline.php'); //หนี้อื่นๆที่ค้างชำระ ?></div>
<div style="margin-top:0px;"><?php  include('./Data_table_contractref.php'); //ตารางแสดงการจ่าย ?></div>
<div style="margin-top:10px;">

	<input type="button" id="showBT" value="แสดงสัญญาที่เกี่ยวข้อง (สัญญาที่ปิดบัญชีไปแล้ว )" />
	<input type="hidden" id="show" value="0"/>
	<input type="hidden" id="conID" value="<?php echo $contractID;?>"/>
	<div id="TB_Close" style="text-align:center"></div>

</div>
<?php }?>    

</body>
</html>