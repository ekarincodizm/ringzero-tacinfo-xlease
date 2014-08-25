<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$add_date = nowDateTime(); //วันเวลาปัจจุบันจาก server
$date_transaction = nowDate();//วันที่ทำรายการ
$now_time = nowTime(); //เวลาปัจจุบันจาก server
$user_id = $_SESSION["av_iduser"]; 

$date_add = pg_escape_string($_POST['datepicker']);//วันที่ : ที่  user คีย์ข้อมูล
$text_add = pg_escape_string($_POST['text_add']);
$text_add =checknull($text_add);
$made = pg_escape_string($_POST['made']);
$chk_to= pg_escape_string($_POST['to']);//0=บุคคลภายนอก,1=ลูกค้าบุคคล,2=ลูกค้านิติบุคคล,3=พนักงานบริษัท 
$topayfullin= pg_escape_string($_POST['topayfullin']);
list($f_id,$f_name) = explode("#",$topayfullin);//id,name
$topayfullout= pg_escape_string($_POST['topayfullout']);
$voucherPurpose= pg_escape_string($_POST['voucherPurpose']); //จุดประสงค์
$chk_insert_channel= pg_escape_string($_POST['chk_insert_channel']);

$voucherPurpose = checknull($voucherPurpose);


pg_query("BEGIN WORK");
$status=0;
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">    
    <link type="text/css" rel="stylesheet" href="act.css"></link>    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"><input type="button" value=" กลับ " onclick="javascript:window.location='frm_Index_v2.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ทำรายการใบสำคัญรายวันทั่วไป</B></legend>

<div align="center">

<?php
	//วันที่ทำรายการเป็นวันที่ เป็นวันเดียวกัน ไม่ต้อง fix เวลา 23:59:59.999
	if($date_add==$date_transaction){
		$this_time= $now_time;	    //ให้ใช้เวลาจริง	เช่น  "10:10:47"
	}
	else{
		$this_time='23:59:59.999'; //fix เวลา 23:59:59.999
	}
	if($chk_to=='0'){
		$f_id=null;
		$f_name=$topayfullout;
	}
	$f_id =checknull($f_id);
    $arrayaccbookserial='{';
	$arraybooktype='{';
	$arrayabd_amount='{';
	if($made=='1'){
		for($i=0;$i<count($_POST["acid"]);$i++)
		{
			$adds_serial = pg_escape_string($_POST['acid'][$i]); // รหัสบัญชี
			$adds_money = pg_escape_string($_POST['text_money'][$i]);
			$abd_bookType = pg_escape_string($_POST['actype'][$i]);			
			
			if($abd_bookType=='1'){//รายการเดบิต
				$abd_bookType_dr .='1,';
				$adds_money_dr.=$adds_money.',';
				$adds_serial_dr.=$adds_serial.',';
			}
			else{//รายการเครดิต
				$adds_money_cr .=$adds_money.',';
				$abd_bookType_cr .='2,';				
				$adds_serial_cr.=$adds_serial.',';
			}		
			
		}
	}
	else{
	//ใช้สูตร
		for($i=0;$i<count($_POST["text_accno1"]);$i++)
		{
			$adds_serial = pg_escape_string($_POST['text_accno1'][$i]); // รหัสบัญชี
			$adds_money = pg_escape_string($_POST['text_money1'][$i]);
			if($abd_bookType=='1'){//รายการเดบิต
				$abd_bookType_dr .='1,';
				$adds_money_dr.=$adds_money.',';
				$adds_serial_dr.=$adds_serial.',';
			}
			else{//รายการเครดิต
				$adds_money_cr .=$adds_money.',';
				$abd_bookType_cr .='2,';				
				$adds_serial_cr.=$adds_serial.',';
			}

		}
		//คีย์ข้อมูลเอง
		for($i=0;$i<count($_POST["text_accno"]);$i++)
		{
			$adds_serial = pg_escape_string($_POST['text_accno'][$i]); // รหัสบัญชี
			$adds_money = pg_escape_string($_POST['text_money2'][$i]);
			$abd_bookType = pg_escape_string($_POST['text_drcr'][$i]);

			if($abd_bookType=='1'){			//รายการเดบิต
				$abd_bookType_dr .='1,';
				$adds_money_dr.=$adds_money.',';
				$adds_serial_dr.=$adds_serial.',';
			}
			else{ 							//รายการเครดิต
				$adds_money_cr .=$adds_money.',';
				$abd_bookType_cr .='2,';				
				$adds_serial_cr.=$adds_serial.',';
			}

		}
	}
	//บันทึกรายการเดบิตต้องขึ้นก่อนเครดิตเสมอ
	$arrayaccbookserial='{'.$adds_serial_dr.$adds_serial_cr;
	$arraybooktype='{'.$abd_bookType_dr.$abd_bookType_cr;
	$arrayabd_amount='{'.$adds_money_dr.$adds_money_cr;
		
	$arrayaccbookserial =substr($arrayaccbookserial,0,-1);
	$arraybooktype=substr($arraybooktype,0,-1);
	$arrayabd_amount=substr($arrayabd_amount,0,-1);
	$arrayaccbookserial .='}';
	$arraybooktype.='}';
	$arrayabd_amount.='}';	
	
	if($chk_insert_channel!="on"){
		$in_sql="insert into \"thcap_temp_voucher_pre_details\"(\"voucherDate\",\"voucherTime\",\"doerID\",\"doerStamp\",\"payID\",\"payFull\",\"voucherRemark\",
		\"voucherType\",\"appvStatus\",\"arrayaccbookserial\",\"arrayabd_booktype\", \"arrayabd_amount\",\"voucherPurpose\",\"payIDType\")  	
		values ('$date_add','$this_time','$user_id','$add_date',null,'$f_name',$text_add,'3','9','$arrayaccbookserial','$arraybooktype','$arrayabd_amount',
		$voucherPurpose,'0')
		RETURNING \"prevoucherdetailsid\"  ";
		if(!$result=pg_query($in_sql)){
			$status++;
		}
	}
	
	if($status==0)
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP)ทำรายการใบสำคัญรายวันทั่วไป V.2', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");		
        echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
		echo "<br><br><br>";
    }
	else
	{
        pg_query("ROLLBACK");
        echo "ไม่สามารถเพิ่มข้อมูลได้";
    }
?>
</div>
</fieldset>
        </td>
    </tr>
</table>
</body>
</html>