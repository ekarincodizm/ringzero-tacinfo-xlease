<?php
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="autocomplete.js"></script>
	<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">
$(document).ready(function(){
    
    $("#showdivcash").show();
    $("#showdivcq").hide();
    
    $(".static_class1").click(function(){
        if($(this).val()==="2"){
            $("#showdivcq").show();
            $("#showdivcash").hide();
        }else{
            $("#showdivcq").hide();
            $("#showdivcash").show();
        }
    });

    
    $('#cash_amt').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
    });
    
    $('#cq_id').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57)) ? false : true ;
    });
    
    $('#cq_amt').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
    });
    
    $("#cq_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    
    $('#frm1').submit(function(){
		if($('#vtid option:selected').attr('value')==''){
			alert('กรุณาเลือกประเภทค่าใช้จ่าย');
			return false;
		}else if( $('#detail').val() == '' ){
            alert('กรุณากรอก รายละเอียด');
            $('#detail').focus();
            return false;
        }else if( $('input[id=chkbuy]:checked').val() == 1 ){
            if( $('#cash_amt').val() == '' ){
                alert('กรุณากรอก จำนวน เงินสด');
                $('#cash_amt').focus();
                return false;
            }
        }else if( $('input[id=chkbuy]:checked').val() == 2 ){
            if( $('#cq_type').val() == '' ){
                alert('กรุณาเลือก AcTable');
                $('#cq_type').focus();
                return false;
            }else if( $('#acid_bank').val() == '' ){
                alert('กรุณาเลือก ธนาคาร');
                $('#acid_bank').focus();
                return false;
            }else if( $('#cq_id').val() == '' ){
                alert('กรุณากรอก เลขที่เช็ค');
                $('#cq_id').focus();
                return false;
            }else if( $('#cq_date').val() == '' ){
                alert('กรุณากรอก วันที่บนเช็ค');
                $('#cq_date').focus();
                return false;
            }else if( $('#cq_amt').val() == '' ){
                alert('กรุณากรอก ยอดเงินในเช็ค');
                $('#cq_amt').focus();
                return false;
            }
        }
        
        return true;
    });
});

</script>

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:13px;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
.BoxYellow {
    margin: 0 auto;
    padding:5px 5px 5px 5px;
    font-size: 12px;
    font-weight: bold;
    color: #666666;
    text-align: center;
    line-height: 20px;
    BORDER-RIGHT: #FCC403 1px solid; BORDER-TOP: #FCC403 1px solid; BORDER-LEFT: #FCC403 1px solid; WIDTH: 500px; BORDER-BOTTOM: #FCC403 1px solid; HEIGHT: auto; BACKGROUND-COLOR: #FFFFD5
}
</style>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<div class="ui-widget">

<fieldset><legend><B>Voucher Receipt</B></legend>

<form name="frm1" id="frm1" action="fvoucher_receipt_insert.php" method="post">

<table cellpadding="3" cellspacing="3" border="0" width="100%">
<tr>
    <td><b>วันที่ทำรายการ</b></td>
    <td><?php echo "$now_date"; ?></td>
</tr>
<tr>
    <td><b>ประเภทค่าใช้จ่าย</b></td>
    <td>
		<select name="vtid" id="vtid">
			<option value="">---เลือก---</option>
			<?php
			$qry_paytype=pg_query("select * from account.\"nw_voucher_type\" where voucher_type_status='TRUE'");
			while($res=pg_fetch_array($qry_paytype)){
			?>
			  <option value="<?php echo $res["vtid"]; ?>"><?php echo $res["voucher_type_name"]; ?></option>
			<?php
			 }
			?>  
		</select>
	</td>
</tr>
<tr>
    <td valign="top"><b>รายละเอียด</b></td>
    <td><textarea name="detail" id="detail" rows="7" cols="80"></textarea></td>
</tr>
<tr>
    <td><b>รูปแบบ</b></td>
    <td>
<input type="radio" class="static_class1" name="chkbuy" id="chkbuy" value="1" checked> เงินสด
<span id="showdivcash"> <b>จำนวน</b> <input id="cash_amt" name="cash_amt" size="15" /> บาท.</span>
    </td>
</tr>

<tr>
    <td>&nbsp;</td><td>
<input type="radio" class="static_class1" name="chkbuy" id="chkbuy" value="2"> เช็ค
<div id="showdivcq">
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
    <td width="25%"><b>เลือก AcID</b></td>
    <td>
<select name="cq_acid" id="cq_acid">
<?php
$qry_name=pg_query("SELECT * FROM account.\"ChequeAcc\" ORDER BY \"AcID\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $BankName = $res_name["BankName"];
    $BankBranch = $res_name["BankBranch"];
    echo "<option value=\"$AcID\">$AcID:$BankName ($BankBranch)</option>";
}
?>
</select>
    </td>
</tr>
<tr>
    <td><b>เลือกประเภท</b></td>
    <td>
<select name="cq_type" id="cq_type">
    <option value="0">ไม่เฉพาะ</option>
    <option value="1">payee only</option>
    <option value="2">account payee</option>
</select>
    </td>
</tr>
<tr>
    <td><b>เลขที่เช็ค</b></td>
    <td><input id="cq_id" name="cq_id" size="20" /></td>
</tr>
<tr>
    <td><b>วันที่บนเช็ค</b></td>
    <td><input id="cq_date" name="cq_date" type="text" size="10" value="<?php echo $now_date; ?>" style="text-align: center;" readonly></td>
</tr>
<tr>
    <td><b>ชำระให้</b></td>
    <td><input id="payto" name="payto" size="50" /></td>
</tr>
<tr>
    <td><b>ยอดเงินในเช็ค</b></td>
    <td><input id="cq_amt" name="cq_amt" size="20" /> บาท.</td>
</tr>
</table>
</div>

    </td>
</tr>

<tr>
    <td>&nbsp;</td><td align="right"><input type="submit" id="btnsubmit" class="ui-button" value="บันทึก"/></td>
</tr>
</table>
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
				return "s_user.php?q=" + this.value;
		});	
	}	
	make_autocom("userid","h_id");
</script>
</form>

</fieldset>

</div>

        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" frame="1" width="80%" align="center">	
	<tr>
		<td>
			<?php include("Data_wait_app.php"); ?>
		</td>	
	</tr>
</table>
</body>
</html>