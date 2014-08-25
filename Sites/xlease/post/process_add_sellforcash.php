<?php
include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

$get_userid = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$idno=$_POST['idno'];
$cusid=$_POST['names'];

$fnames=$_POST['fnames'];
$anames=$_POST['anames'];
$snames=$_POST['snames'];
$no=$_POST['no'];
$sno=$_POST['sno'];
$soi=$_POST['soi'];
$rd=$_POST['rd'];
$tam=$_POST['tam'];
$aum=$_POST['aum'];
$pro=$_POST['pro'];
$post=$_POST['post'];

$san=$_POST['san'];
$age=$_POST['age'];
$card=$_POST['card'];
$idcard=$_POST['idcard'];
$otdate=$_POST['otdate'];
$by=$_POST['by'];
$occ=$_POST['occ'];
$contact=$_POST['contact'];

$datepicker=$_POST['datepicker'];
$paytype=$_POST['paytype'];
$price=$_POST['price'];
$non_vat=$_POST['non_vat'];
$vat=$_POST['vat'];

$to_date = nowDate();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<table width="100%">
<tr>
    <td align="left">
<input type="button" value="ย้อนกลับ"onclick="window.location='sell_for_cash.php'">
    </td>
    <td align="right">
<input type="button" value="ปิดหน้านี้" onclick="javascript:window.close();">
    </td>
</tr>
</table>

<fieldset><legend><B>ขายสด รถยึด</B></legend>

<div align="center">

<?php
if( empty($idno) || empty($cusid) || empty($paytype) || empty($price) || empty($non_vat) || empty($vat) ){
    echo "กรุณากรอกข้อมูลให้ครบถ้วน !";
}else{

    pg_query("BEGIN WORK");
    
    //$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($datepicker)));
    
    $qry_tran_id=pg_query("select generate_cash_id('$datepicker','1','1');");
    $tran_id=pg_fetch_result($qry_tran_id,0);
    
    $update_fp="Update \"Fp\" SET 
    \"P_TransferIDNO\"='$tran_id', 
    \"P_StopVat\"='TRUE', 
    \"P_StopVatDate\"='$datepicker', 
    \"P_ACCLOSE\"='TRUE', 
    \"P_CLDATE\"='$datepicker' 
    WHERE \"IDNO\"='$idno';";
    if(pg_query($update_fp)){
        $status = 0;
    }else{
        $status = 1;
    }
    

    if($cusid == "ไม่พบข้อมูล"){ //Insert Fa1 Fn
        //------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
			$cus_sn = GenCus();
		//----------------------
		
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$anames' and \"A_SIRNAME\" = '$snames' ");
		$row_check_name = pg_num_rows($sql_check_name);
		if($row_check_name > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}

        $in_sql="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\") values 
        ('$cus_sn','$fnames','$anames','$snames',DEFAULT,'$no','$sno','$soi','$rd','$tam','$aum','$pro','$post');";
        if(pg_query($in_sql)){
            $status = 0;
        }else{
            $status = 1;
        }
		
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		$check_card = str_replace(" ","",$idcard);
		$check_card = str_replace("-","",$check_card);
		$sql_check=pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
		$row_check = pg_num_rows($sql_check);
		if($row_check > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}

        $in_sql="insert into \"Fn\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\") values 
        ('$cus_sn','0','$san','$age','$card','$idcard','$otdate','$by','$occ','$contact');";
        if(pg_query($in_sql)){
            $status = 0;
        }else{
            $status = 1;
        }
    }

//Qry Fp
$qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$idno';");
if( $res_fp=pg_fetch_array($qry_fp) ){
    $asset_id=$res_fp["asset_id"];
}

//Insert Fp
if($cus_sn == ""){
    $add_cusid = $cusid;
}else{
    $add_cusid = $cus_sn;
}

list($n_year,$n_month,$n_day) = split('-',$datepicker);
$pcusbyyear = $n_year;

$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"asset_type\",\"asset_id\",\"P_StopVat\",\"P_StopVatDate\",\"P_ACCLOSE\",\"P_CLDATE\",\"P_CustByYear\",\"P_FDATE\",\"P_STDATE\") values
('$tran_id','$add_cusid','1','$asset_id','TRUE','$datepicker','TRUE','$datepicker','$pcusbyyear','$datepicker','$datepicker');";
if($result=pg_query($ins_fp)){
    $status = 0;
}else{
    $status = 1;
}


$gen_pos_no=pg_query("select gen_pos_no('$to_date');");
$gen_pos_no_id=pg_fetch_result($gen_pos_no,0);

$ins_pl="insert into \"PostLog\" (\"PostID\",\"UserIDPost\",\"UserIDAccept\",\"PostDate\",\"paytype\",\"AcceptPost\") values
('$gen_pos_no_id','$get_userid','$get_userid','$to_date','$paytype','TRUE');";
if($result=pg_query($ins_pl)){
    $status = 0;
}else{
    $status = 1;
}

$ins_fc="insert into \"FCash\" (\"PostID\",\"CusID\",\"IDNO\",\"TypePay\",\"AmtPay\",\"refreceipt\") values
('$gen_pos_no_id','$add_cusid','$tran_id','99','$price',DEFAULT);";
if($result=pg_query($ins_fc)){
    $status = 0;
}else{
    $status = 1;
}

$accept_acc_cash=pg_query("select accept_acc_cash('$gen_pos_no_id','$datepicker','$get_userid');");
$accept_acc_cash_id=pg_fetch_result($accept_acc_cash,0);
if($accept_acc_cash_id){
    $status = 0;
}else{
    $status = 1;
}

if($status == 1){
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้"."<br>"; 
	echo $error_check;
}else{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) ขายรถยึดให้คนใหม่', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว<br /><br /><input type=\"button\" id=\"btn2\" class=\"ui-button\" value=\"พิมพ์ใบเสร็จ\"/>";
}

}

?>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>