<?php
session_start();
include("../../config/config.php");
$date_add = pg_escape_string($_POST['date_add']);
$text_add = pg_escape_string($_POST['text_add']);
$text_money = $_POST['text_money'];
$text_drcr = $_POST['text_drcr'];
$text_accno = $_POST['text_accno'];
$booktype = pg_escape_string($_POST['booktype']);

$add_date = nowDateTime();
$user_id = $_SESSION["av_iduser"];

$status = 0;
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

</head>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"><input type="button" value=" กลับ " onclick="javascript:window.location='add_acc_formula.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ใช้สูตรทางบัญชี</B></legend>

<div align="center">

<?php
foreach($text_money as $key_money => $value_money){
    if($text_drcr[$key_money] == 1){
        $dr += $value_money;
        $c_dr += 1;
    }else{
        $cr += $value_money;
        $c_cr += 1;
    }
}

if($c_dr<1 or $c_cr<1){
    echo "ต้องมี Dr และ Cr อย่างน้อย 1 รายการ";
}elseif($dr!=$cr){
    echo "ยอดเงิน Dr และ Cr ไม่ตรงกัน";
}else{
    
    pg_query("BEGIN WORK");
    
    $gj_id=@pg_query("select account.thcap_gen_addressbook('$date_add','$booktype')");
    $res_gj_id=@pg_fetch_result($gj_id,0);
	
	// หาชื่อเอกสาร
	$qry_documentName = pg_query("select \"documentName\" from account.\"General_Journal_Type\" where \"GJ_typeID\" = '$booktype' ");
	$documentName = pg_fetch_result($qry_documentName,0); // ชื่อเอกสาร
    
    $in_sql="insert into account.\"all_accBookHead\" (\"abh_type\",\"abh_id\",\"abh_stamp\",\"abh_detail\",\"GJ_typeID\",\"addStatus\",\"abh_transaction_stamp\") values ('GJ','$res_gj_id','$date_add','$text_add','$booktype','2','$add_date') RETURNING \"abh_autoid\" ";
    $result = pg_query($in_sql);
	
	if($result)
	{
        $abh_autoid = pg_fetch_result($result,0);
		
		$in_sql_temp = "insert into account.\"all_accBookHead_temp\"(\"abh_autoid\",\"abh_type\",\"abh_id\",\"abh_stamp\",\"abh_detail\",\"doerID\",\"doerStamp\",\"abh_transaction_type\",\"appvID\",\"appvStamp\",\"appvStatus\",\"editTime\",\"GJ_typeID\",\"addStatus\",\"abh_transaction_stamp\")
						values('$abh_autoid','GJ','$res_gj_id','$date_add','$text_add','$user_id','$add_date','1','000','$add_date','1','0','$booktype','2','$add_date') RETURNING \"abh_autoid_temp\" ";
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
    
    foreach($text_money as $key_money2 => $value_money2)
	{
		$abd_bookType = $text_drcr[$key_money2];
		
		// หาเลขที่บัญชี
		$qry_acid = pg_query("select \"accBookID\" from account.\"all_accBook\" where \"accBookserial\" = '$text_accno[$key_money2]' ");
		$adds_acid = pg_fetch_result($qry_acid,0);
		
        $in_sql = "insert into account.\"all_accBookDetail\"(\"abd_autoidabh\",\"abd_accBookID\",\"abd_bookType\",\"abd_amount\",\"abd_RefID\",\"accBookserial\") values('$abh_autoid','$adds_acid','$abd_bookType','$value_money2',null,'$text_accno[$key_money2]') ";
		if(!$result = pg_query($in_sql)){$status++;}
		
		$in_sql_temp = "insert into account.\"all_accBookDetail_temp\"(\"abd_autoidabh_temp\",\"abd_autoidabh\",\"abd_accBookID\",\"abd_bookType\",\"abd_amount\",\"accBookserial\") values('$abh_autoid_temp','$abh_autoid','$adds_acid','$abd_bookType','$value_money2','$text_accno[$key_money2]') ";
		if(!$result_temp = pg_query($in_sql_temp)){$status++;}
    }
    
    if($status==0)
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกบัญชี', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
		
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
								<td align="center"><?php echo $documentName; ?></td>
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
									}
									elseif($abd_bookType == 2)
									{
										$v_dr = "0.00";
										$v_cr = number_format($abd_amount,2);
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
								<td colspan="3" align="left"><?php echo $sp_dtl; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			
			<table>
				<tr align="right">
					<td align="right">
						<a align="right" href="javascript:popU('pdf_print_fo_one.php?aid=<?php echo $abh_autoid;?>&booktype=<?php echo $documentName;?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
					</td>
				</tr>
			</table>
		<?php
    }
	else
	{
        pg_query("ROLLBACK");
        echo "ไม่สามารถเพิ่มข้อมูลได้";
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