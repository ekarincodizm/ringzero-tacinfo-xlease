<?php
session_start();
include("../../config/config.php");

$abh_autoid = pg_escape_string($_POST["abh_autoid"]);
$abh_autoid_old = $abh_autoid;

// หาข้อมูลเดิม
$qry_m_old = pg_query("select \"abh_id\", \"abh_stamp\"::date from account.\"all_accBookHead\" where  \"abh_autoid\" = '$abh_autoid'");
$a_id_old = pg_fetch_result($qry_m_old,0); // รหัสบัญชีเดิม
$as_date_old = pg_fetch_result($qry_m_old,1); // วันที่เดิม

$date_add = pg_escape_string($_POST['datepicker']);
$text_add = pg_escape_string($_POST['text_add']);
$check_1999 = in_array(1999,$_POST['acid']);

$add_date = nowDateTime();
$user_id = $_SESSION["av_iduser"];

$GJtype = pg_escape_string($_POST['GJtype']); // ประเภทสมุดรายวันทั่วไป

$buyfrom = pg_escape_string($_POST['buyfrom']);
$buyreceiptno = pg_escape_string($_POST['buyreceiptno']);
$chkbuy = pg_escape_string($_POST['chkbuy']);
$paybuy = pg_escape_string($_POST['paybuy']);
$tohpid = pg_escape_string($_POST['tohpid']);
$hidchk = pg_escape_string($_POST['hidchk']);
?>
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
	
<script>
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>บันทึกเอง</B></legend>

<div align="center">

<?php
$c_dr=0;
$c_cr=0;

for($i=0;$i<count($_POST["acid"]);$i++){

    if($_POST['actype'][$i] == 1){
        $dr += $_POST['text_money'][$i];
        $c_dr += 1;
    }else{
        $cr += $_POST['text_money'][$i];
        $c_cr += 1;
    }

}

$dr = round($dr,2);
$cr = round($cr,2);

if($c_dr<1 or $c_cr<1){
    echo "ต้องมี Dr และ Cr อย่างน้อย 1 รายการ";
}elseif($dr != $cr){
    echo "ยอดเงิน Dr และ Cr ไม่ตรงกัน [$dr ~ $cr]";
}else{
    
    pg_query("BEGIN WORK");
    $status = 0;
	
	$gj_id=pg_query("select account.thcap_gen_addressbook('$date_add','$GJtype');");
	$res_gj_id=pg_fetch_result($gj_id,0);
    if(empty($res_gj_id)){
        $status++;
		echo "select account.thcap_gen_addressbook('$date_add','$GJtype');";
    }

    if($chkbuy == 1){
        $txtstr = "เงินสด";
    }else{
        $txtstr = "เช็ค เลขที่ $paybuy";
    }
    
    if($hidchk == 1){
        /*
        if(empty($text_add)){
            $text_add = "$buyreceiptno\n$buyfrom\n$tohpid\n$txtstr\n$text_add";
        }else{
            $text_add = "$buyreceiptno\n$buyfrom\n$tohpid\n$txtstr\n$text_add";
        }
        */
        $text_add = "$buyreceiptno\n$buyfrom\n$tohpid\n$txtstr\n$text_add";
    }else{
        $text_add = "$text_add";
    }
	
	if($check_1999)
	{
		$abh_refid = "'VATB'";
	}
	else
	{
		$abh_refid = "NULL";
	}
	
	// หา editTime
	$qry_maxEditTime = pg_query("select max(\"editTime\") from account.\"all_accBookHead_temp\" where \"abh_autoid\" = '$abh_autoid' ");
	$maxEditTime = pg_fetch_result($qry_maxEditTime,0);
	if($maxEditTime == "")
	{
		$editTime = 0;
	}
	else
	{
		$editTime = $maxEditTime+1;
	}
    
	// เริ่มบันทึกหัวบัญชี
	// ขอยกเลิกรายการพร้อมอนุมัติทันที
	$del_h_temp = "insert into account.\"all_accBookHead_temp\"(\"abh_autoid\",\"doerID\",\"doerStamp\",\"abh_transaction_type\",\"appvID\",\"appvStamp\",\"appvStatus\",\"editTime\",\"addStatus\",\"auditorID\",\"auditorStamp\")
					values('$abh_autoid','$user_id','$add_date','3','000','$add_date','1','$editTime','2','$user_id','$add_date')";
	if(!$res_del_h_temp = pg_query($del_h_temp)){$status++;}

	// ยกเลิกรายการเก่าก่อน
	$del_h = "update account.\"all_accBookHead\" set \"abh_status\" = '0' where \"abh_autoid\" = '$abh_autoid' ";
	if(!$res_del_h = pg_query($del_h)){$status++;}
	
	// เพิ่มรายการใหม่
	$up_or_in_sql = "insert into account.\"all_accBookHead\"(\"abh_type\",\"abh_id\",\"abh_stamp\",\"abh_detail\",\"abh_refid\",\"GJ_typeID\",\"addStatus\",\"abh_transaction_stamp\") values('GJ','$res_gj_id','$date_add','$text_add',$abh_refid,'$GJtype','2','$add_date') RETURNING \"abh_autoid\" ";
    
	$result = pg_query($up_or_in_sql);
	if($result)
	{
		$abh_autoid = pg_fetch_result($result,0);
		
		// update การแก้ไขเดิม
		$up_h = "update account.\"all_accBookHead\" set \"editTo\" = '$abh_autoid' where \"abh_autoid\" = '$abh_autoid_old' ";
		if(!$res_up_h = pg_query($up_h)){$status++;}
		
		$abh_transaction_type = 1; // สถานะเพิ่มรายการ
		$editTime = 0;
		
		$in_sql_temp = "insert into account.\"all_accBookHead_temp\"(\"abh_autoid\",\"abh_type\",\"abh_id\",\"abh_stamp\",\"abh_detail\",\"abh_refid\",\"doerID\",\"doerStamp\",\"abh_transaction_type\",\"appvID\",\"appvStamp\",\"appvStatus\",\"editTime\",\"GJ_typeID\",\"addStatus\",\"auditorID\",\"auditorStamp\",\"abh_transaction_stamp\")
						values('$abh_autoid','GJ','$res_gj_id','$date_add','$text_add',$abh_refid,'$user_id','$add_date','$abh_transaction_type','000','$add_date','1','$editTime','$GJtype','2','$user_id','$add_date','$add_date') RETURNING \"abh_autoid_temp\" ";
		$result_temp = pg_query($in_sql_temp);
		if($result_temp)
		{
			$abh_autoid_temp = pg_fetch_result($result_temp,0);
		}
		else
		{
			$status++;
		}
	}
	else
	{
		$status++;
	}
	
	// ลบรายละเอียดเก่าออกก่อน
	$del_sql = "delete from account.\"all_accBookDetail\" where \"abd_autoidabh\" = '$abh_autoid_old' ";
	if(!$result_del = pg_query($del_sql)){$status++;}
    
    for($i=0;$i<count($_POST["acid"]);$i++)
	{
        $adds_serial = pg_escape_string($_POST['acid'][$i]); // รหัสบัญชี
        $adds_money = pg_escape_string($_POST['text_money'][$i]);
		$abd_bookType = pg_escape_string($_POST['actype'][$i]);
		
		// หาเลขที่บัญชี
		$qry_acid = pg_query("select \"accBookID\" from account.\"all_accBook\" where \"accBookserial\" = '$adds_serial' ");
		$adds_acid = pg_fetch_result($qry_acid,0);
		
		$in_sql = "insert into account.\"all_accBookDetail\"(\"abd_autoidabh\",\"abd_accBookID\",\"abd_bookType\",\"abd_amount\",\"accBookserial\") values('$abh_autoid','$adds_acid','$abd_bookType','$adds_money','$adds_serial');";
		if(!$result = pg_query($in_sql)){$status++; echo $in_sql;}
		
		$in_sql_temp = "insert into account.\"all_accBookDetail_temp\"(\"abd_autoidabh_temp\",\"abd_autoidabh\",\"abd_accBookID\",\"abd_bookType\",\"abd_amount\",\"accBookserial\") values('$abh_autoid_temp','$abh_autoid','$adds_acid','$abd_bookType','$adds_money','$adds_serial');";
		if(!$result_temp = pg_query($in_sql_temp)){$status++;}
    }
    
    if($hidchk == 1){
        $in_sql="insert into account.\"BookBuy\" (\"bh_id\",\"buy_from\",\"buy_receiptno\",\"pay_buy\",\"to_hp_id\") values ('$abh_autoid','$buyfrom','$buyreceiptno','$txtstr','$tohpid');";
        if(!$result=pg_query($in_sql)){
            $status++;
        }
    }
    
    if($status==0)
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกบัญชี', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        //pg_query("ROLLBACK");
		
		?>
		
		<script>
			opener.location.reload(true); // reload หน้าหลัก
		</script>

		<?php
		
		$sql1="select \"abh_autoid\" from account.\"all_accBookHead\" order by \"abh_autoid\" DESC limit 1";
        $ree=pg_query($sql1);
		$resul=pg_fetch_array($ree);
		//echo $abhid = $resul['abh_autoid'];
        echo "แก้ไขข้อมูลเรียบร้อยแล้ว";
		//echo "<meta http-equiv=\"refresh\" content=\"0;URL=pdf_print_fo_one.php?aid=$abhid\">";
		
		echo "<br><br><br>";
		
		// หาข้อมูลหัวบัญชี
		$qry_m = pg_query("select \"abh_id\", \"abh_stamp\" from account.\"all_accBookHead\" where  \"abh_autoid\" = '$abh_autoid'");
		
		$a_id = pg_fetch_result($qry_m,0);
		$as_date = pg_fetch_result($qry_m,1);
			
		$trn_date=pg_query("select * from c_date_number('$as_date')");
		$a_date=pg_fetch_result($trn_date,0);
		
		?>
			<table>
				<tr>
					<td>
						<table width="100%">
							<tr>
								<td align="left">วันที่ <?php echo $a_date; ?></td>
								<td align="center">ใบสำคัญจ่าย</td>
								<td align="right">เลขที่ <?php echo $a_id; ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#000000" width="100%">
							<tr bgcolor="#DDFFAA">
								<th align="center">รายการ</th>
								<th align="center">Dr</th>
								<th align="center">Cr</th>
							</tr>
							<?php
								// หารายละเอียดบัญชี
								$qry_vacc=pg_query("select *  from account.\"V_all_AccountBook\" where abh_id='$a_id' ");
								$v_dr_sum = 0; // ผลรวม debit
								$v_cr_sum = 0; // ผลรวม Credit
								
								while($res_vacc=pg_fetch_array($qry_vacc))
								{
									$v_acname=$res_vacc["accBookName"];
									$v_acid=" [ ".$res_vacc["abd_accBookID"]." ]"." ".$v_acname;
									$vs_dt=$res_vacc["abh_detail"];
									$abd_bookType = $res_vacc["abd_bookType"]; // ประเภท 1 Dr 2 Cr
									$abd_amount = $res_vacc["abd_amount"];
								   
									if($abd_bookType == 1)
									{
										$v_dr = number_format($abd_amount,2);
										$v_cr = "0.00";
										
										$v_dr_sum += $abd_amount;
									}
									elseif($abd_bookType == 2)
									{
										$v_dr = "0.00";
										$v_cr = number_format($abd_amount,2);
										
										$v_cr_sum += $abd_amount;
									}
									else
									{
										$v_dr = "";
										$v_cr = "";
									}
								   
								   
									$exp_dtl=str_replace("\n","#",$vs_dt);
									$sep_dtl=explode("#",$exp_dtl);
									
									$sp_dtl=str_replace("\n"," ",$vs_dt);
									
									echo "<tr bgcolor=\"#DDFFAA\">";
									echo "<td align=\"left\">$v_acid</td>";
									echo "<td align=\"right\">$v_dr</td>";
									echo "<td align=\"right\">$v_cr</td>";
									echo "</tr>";
								}
							?>
							<tr bgcolor="#DDFFAA">
								<td align="right"><b>รวม</b></td>
								<td align="right"><b><?php echo number_format($v_dr_sum,2); ?></b></td>
								<td align="right"><b><?php echo number_format($v_cr_sum,2); ?></b></td>
							</tr>
							<tr bgcolor="#DDFFAA">
								<td colspan="3" align="left"><?php echo $sp_dtl; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<?php
    }
	else
	{
        pg_query("ROLLBACK");
        echo "ไม่สามารถแก้ไขข้อมูลได้";
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