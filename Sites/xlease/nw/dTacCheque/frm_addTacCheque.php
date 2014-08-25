<?php
include("../../config/config.php");

$ChequeNo2 = $_POST["ChequeNo2"];
$BankID2 = $_POST["BankID2"];
$BankBranch2 = $_POST["BankBranch2"];
$DateReceive2 = $_POST["DateReceive2"];
$DateOnChq2 = $_POST["DateOnChq2"];
$DateEntBank2 = $_POST["DateEntBank2"];
$BAccount2 = $_POST["BAccount2"];
$Amount2 = $_POST["Amount2"];
$RecNo2 = $_POST["RecNo2"];
$status2 = $_POST["status2"];
?>
<head>
<title>เพิ่มเช็ค TAC</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

	<!-- <META HTTP-EQUIV="Pragma" CONTENT="no-cache"> -->

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
<script language=javascript>

$(document).ready(function(){
    $("#DateReceive").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
		//dateFormat: 'dd-mm-yy'
    });
	$("#DateOnChq").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
		//dateFormat: 'dd-mm-yy'
    });
	$("#DateEntBank").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
		//dateFormat: 'dd-mm-yy'
    });
});


function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.ChequeNo.value=="") {
    theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.BankBranch.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.DateReceive.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.DateOnChq.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.DateEntBank.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.Amount.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.RecNo.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">เพิ่มเช็ค TAC<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<form method="post" name="form1" action="process_addTacCheque.php">
			<table width="600" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">
			<tr><th colspan="2"><?php echo $showtext;?></th></tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th width="50%" height="25" align="right">เลขที่เช็ค :</th>
				<?php echo "<td><input type=\"text\" name=\"ChequeNo\" value=\"$ChequeNo2\"></td></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right">ชื่อธนาคาร :</th>
				<?php
					echo "<td><select name=\"BankID\">";
					$query_BankID=pg_query("select * from public.\"BankProfile\" order by \"bankID\" ");
					while($result_BankID=pg_fetch_array($query_BankID))
					{
						$bankID=$result_BankID["bankID"];
						$bankName=$result_BankID["bankName"];
						echo "<option value=\"$bankID\" "; ?><?php if($bankID2==$bankID){echo "selected=\"selected\" ";}?><?php echo ">$bankID, $bankName</option>";
					}
					echo "</select></td>";
				?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right" valign="top">สาขาธนาคาร :</th>
				<?php echo "<td><input type=\"text\" name=\"BankBranch\" value=\"$BankBranch2\"></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th width="50%" height="25" align="right">วันที่ัรับเช็ค :</th>
				<?php echo "<td><input type=\"text\" id=\"DateReceive\" name=\"DateReceive\" value=\"$DateReceive2\" readonly></td></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right">วันที่บนเช็ค :</th>
				<?php echo "<td><input type=\"text\" id=\"DateOnChq\" name=\"DateOnChq\" value=\"$DateOnChq2\" readonly></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right" valign="top">วันที่นำเช็คเข้า :</th>
				<?php echo "<td><input type=\"text\" id=\"DateEntBank\" name=\"DateEntBank\" value=\"$DateEntBank2\" readonly></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th width="50%" height="25" align="right">บัญชีที่นำเข้า :</th>
				<?php
					echo "<td><select name=\"BAccount\">";
					$query_BAccount=pg_query("select * from public.\"BankInt\" order by \"BAccount\" ");
					while($result_BAccount=pg_fetch_array($query_BAccount))
					{
						$BAccount=$result_BAccount["BAccount"];
						$BCompany=$result_BAccount["BCompany"];
						echo "<option value=\"$BAccount\" "; ?><?php if($BAccount2==$BAccount){echo "selected=\"selected\" ";}?><?php echo ">$BAccount, $BCompany</option>";
					}
					echo "</select></td>";
				?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right">จำนวนเงิน :</th>
				<?php echo "<td><input type=\"text\" name=\"Amount\" value=\"$Amount2\"></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right" valign="top">ใบเสร็จรับเช็ค :</th>
				<?php echo "<td><input type=\"text\" name=\"RecNo\" value=\"$RecNo2\"></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right" valign="top">สถานะเช็ค :</th>
				<?php
					echo "<td><select name=\"status\">";
					echo "<option value=1 "; ?><?php if($status2==1){echo "selected=\"selected\" ";}?><?php echo ">เช็คผ่าน</option>";
					echo "<option value=2 "; ?><?php if($status2==2){echo "selected=\"selected\" ";}?><?php echo ">เช็คตีคืน</option>";
					echo "</select></td>";
				?>
			</tr>
			<tr>
				<td colspan="2" align="center" height="50"><input type="submit" value="    เพิ่ม    " onclick="return validate()"><input type="button" value="    ปิด    " onclick="window.close();"></td>
			</tr>
			</table>
			</form>
			
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
			return "listdata_customer.php?q=" + this.value;
		});
	}

	make_autocom("idno_names","h_id");
	</script>
			<!-- </form> -->
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>