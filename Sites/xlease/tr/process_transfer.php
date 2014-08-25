<?php
session_start();
$id_user=$_SESSION["av_iduser"];
$idno = pg_escape_string($_POST["p_idno"]);
$rf1 = pg_escape_string($_POST["ref1"]);
$rf2 = pg_escape_string($_POST["ref2"]);
$sid = pg_escape_string($_POST["id_tpay"]);
$p_log = pg_escape_string($_POST["h_plog"]);
include("../config/config.php");
$datenow=date("Y-m-d");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
pg_query("BEGIN WORK");
$status = 0;

//sum all amt
for($i=0;$i<count($_POST["typepayment"]);$i++)
{
	if($_POST["typepayment"][$i] != "")
	{	   
		//echo $_POST['typepayment'][$i]." amt = ".$_POST['amt'][$i]."<br>";
		$res_amt=$res_amt+$_POST['amt'][$i];
	}		
}
 
$amt_fr=$_POST["rescal"];
$total_other=$res_amt; //sum otherpay
 
$sumtotal=$amt_fr+$total_other;
 
$samts=$_POST["s_amt"];
if($sumtotal > $samts)
{
	echo "ยอดรวมรายการมากกว่ายอดโอน กรุณากลับไปทำรายการใหม่";
?>
	<button onclick="javascript:window.location='frm_transpaydate.php'">BACK</button>
<?php 
}
else if($sumtotal < $samts)
{ 
	echo "ยอดรวมรายการน้อยกว่ายอดโอน กรุณากลับไปทำรายการใหม่";
?>
	<button onclick="javascript:window.location='frm_transpaydate.php'">BACK</button>
<?php 
}
else
{
	// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
	$qry_chk_tr = pg_query("SELECT \"post_on_date\" FROM \"TranPay\" WHERE \"PostID\" = '$p_log' AND \"id_tranpay\" = '$sid' ");
	$post_on_date = pg_result($qry_chk_tr,0);
	if($post_on_date != "")
	{ // ถ้ามีการทำรายการไปก่อนหน้านี้แล้ว
		$status++;
		echo "<center><br><b>ไม่สามารถบันทึกข้อมูลได้ เนื่องจากมีการทำรายการไปก่อนหน้านี้แล้ว</b></center>";
	}
	else
	{ // ถ้ายังไม่มีการทำรายการ
		//end sum all amt
		if(($_POST["count_fr"]=="0") AND (empty($_POST["typepayment"])))
		{
			//echo "ไม่มีการจ่ายใด ๆ" ;
		}
		else
		{
			//Update _ TranPay
			$sql_up_tr = "update \"TranPay\" set post_on_date='$datenow' WHERE (\"PostID\"='$p_log') AND (id_tranpay='$sid') AND (post_on_date is null) RETURNING \"PostID\" ";
			$res_uptr = pg_query($sql_up_tr);
			if($res_uptr){
				$chk_res_uptr = pg_fetch_result($res_uptr,0);
				if($chk_res_uptr == ""){$status++;} // ถ้าไม่สามารถ update ข้อมูลได้
			}else{
				$status++;
			}			   
		  
			// end Update _ TranPay
			//end Postlog

			if($_POST["count_fr"]=="0")
			{
				//echo "ไม่มีค่างวด"; 
			}
			else
			{ 
				//echo $_POST["count_fr"];
			
				$amt_tr=$_POST["rescal"];
				$sql_idtltr="insert into \"DetailTranpay\"
						(\"PostID\",\"IDNO\",\"TypePay\",\"Amount\" ) 
					values 
						('$p_log','$idno',1,'$amt_tr')";
				if($result_fr=pg_query($sql_idtltr)){
				}else{
					$status++;
				}			   	
			}

			if(empty($_POST["typepayment"]))
			{
				echo "ไม่มีค่าอื่น ๆ";
			}
			else
			{
				for($is=0;$is<count($_POST["typepayment"]);$is++)
				{
					if($_POST["typepayment"][$is] != "")
					{	   
						$tpay=$_POST['typepayment'][$is];
						$amtpay=$_POST['amt'][$is];				
						
						$sql_idtotr="insert into \"DetailTranpay\"
								(\"PostID\",\"IDNO\",\"TypePay\",\"Amount\" ) 
							values 
								('$p_log','$idno','$tpay','$amtpay')";
						if($result_fr=pg_query($sql_idtotr)){
						}else{
							$status++;
						}			   	
					}
				}
			}
			
			if($status == 0){
				pg_query("COMMIT");
				echo "<center><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></center>";
				echo "<meta http-equiv=\"refresh\" content=\"2;URL=pass_tr.php?trdate=$datenow\" >";
			}else{
				pg_query("ROLLBACK");
				echo "<center><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></center>";
			}	
		}
	}
}
?>