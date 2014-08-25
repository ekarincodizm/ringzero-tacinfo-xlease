<?php
$excel = $_REQUEST[excel];

set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$num_add = 0;
?>
<?php if($excel==1)header("Content-Type: application/vnd.ms-excel");
if($excel==1)header('Content-Disposition: attachment; filename="join_main_ck.xls"'); ?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=utf-8" />

</HEAD><BODY>
<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>


<br><button onClick="window.open('mg_join_main.php?excel=1')" style="width:160px">Excel</button>


<table border="0" cellpadding="0" cellspacing="0">

  <tr>
    <td style="vertical-align:top"><center>
    <fieldset><legend>
    <h3>ค้นหาทะเบียนรถใน Join Main ระบบเก่า ไม่เจอใน Fc </h3>
    </legend>  
<TABLE BORDER="0" class="t2" cellpadding="1" cellspacing="1" style="vertical-align:top"  x:str>
<Tr bgcolor="#33CCFF">
<Th bgcolor="#66CC33"><b>ลำดับ</b></Th>
<Th bgcolor="#66CC33"><b>เลขที่สัญญา</b></Th>
<Th bgcolor="#66CC33"><b>ทะเบียนรถยนต์</b></Th>
<Th bgcolor="#66CC33"><b>เลขตัวถัง</b></Th>
</Tr>
<?php  
$test_sql=pg_query("Truncate Table public.ta_join_main_bin "); //ลบข้อมูลในตารางเดิม
$rowtest=pg_num_rows($test_sql);

//การตรวจสอบ เลขทะเบียนรถว่ามี รหัสรถยนต์เป็นอะไร
$test_sql=pg_query("select ta_join_pm_id, car_license, contract_id,cpro_name,id_body,deleted  from ta_tal_1r4_mg.\"ta_join_main_bin\" ");
$rowtest=pg_num_rows($test_sql);
$seq=1;
while($result=pg_fetch_array($test_sql))
{
	$car_license=trim($result["car_license"]); //ตัดช่องว่าง ข้างหน้าและข้างหลังออก
	$contract_id=trim($result["contract_id"]);
list($car_license1,$car_license2)=explode("/",$car_license,2); //แยกช่องว่าง เพื่อตัดชื่อ กับนามสกุล  แยกเป็น 2 คำ เท่านั้น ถึงแม้จะมีช่องว่างมากกว่า 1 
$car_license1  =trim($car_license1);
$id_body=trim($result["id_body"]);
$deleted =$result["deleted"];
	
	
	
	$car_license_seq = trim($car_license2);
	
	if($id_body!="")
	$con2 = " and C_CARNUM = '$id_body' ";
	
	$test_sql2=pg_query("select \"CarID\" from public.\"Fc\" where \"C_REGIS\" = '$car_license1' ");
	$rowtest2=pg_num_rows($test_sql2);
	
	if($rowtest2==0){//ถ้ายังไม่เจอ ใช้ or
	if($id_body!=""){
	$con2 = " or C_CARNUM = '$id_body' ";
	$test_sql2=pg_query("select \"CarID\" from public.\"Fc\" where \"C_REGIS\" = '$car_license1' ");
	$rowtest2=pg_num_rows($test_sql2);
	}
	}
	
	if($rowtest2==0 && $deleted!='1'){
		
				
                    
                            if($seq%2==0){
            echo "<TR bgcolor=\"#A2FDA4\">";
        }else{
            echo "<TR bgcolor=\"#CDFCD5\">";
        }
?>

<TD ><?php echo $seq; ?></TD>
<TD ><?php echo $contract_id; ?></TD>
<TD ><?php echo $car_license; ?></TD>
<TD ><?php echo $id_body; ?></TD>
</TR>
		<?php			
		$seq++;		
		
		
		
	}else{
		
		
				while($result2=pg_fetch_array($test_sql2))
{
	$CarID=$result2["CarID"]; //รหัสรถยนต์ใน Fc
}

			

	}
	

	} ?> 
    
</TABLE></fieldset></center></td>
    <td style="vertical-align:top">
    
    <fieldset><legend>
    <h3> ค้นหาชื่อ-นามสกุล และรหัสบัตรลูกค้าใน Join Main ระบบเก่าไม่เจอใน Fa1 </h3>
    </legend>   
    <TABLE BORDER="0" class="t2" cellpadding="1" cellspacing="1" style="vertical-align:top;" x:str>
<Tr bgcolor="#33CCFF">
<Th><b>ลำดับ</b></Th>
<Th><b>เลขที่สัญญา</b></Th>
<Th><b>ทะเบียนรถยนต์</b></Th>
<Th><b>ชื่อ</b></Th>
<Th><b>นามสกุล</b></Th>
<Th><b>รหัสบัตร</b></Th>
</Tr>
<?php			

$test_sql=pg_query("select id,ta_join_pm_id, car_license, contract_id,cpro_name,id_card,start_pay_date, staff_check,
 cancel, cancel_datetime, note, datetime, update_datetime, users, deleted from ta_tal_1r4_mg.\"ta_join_main_bin\"  ");
$rowtest=pg_num_rows($test_sql);
$seq2=1;
while($result=pg_fetch_array($test_sql))
{
	$id=trim($result["id"]);
	$car_license=trim($result["car_license"]); //ตัดช่องว่าง ข้างหน้าและข้างหลังออก
	$contract_id=trim($result["contract_id"]);
	$cpro_name=trim($result["cpro_name"]); //ชื่อ-นามสกุล
	list($A_NAME,$A_SIRNAME)=explode(" ",$cpro_name,2); //แยกช่องว่าง เพื่อตัดชื่อ กับนามสกุล  แยกเป็น 2 คำ เท่านั้น ถึงแม้จะมีช่องว่างมากกว่า 1 
	$A_SIRNAME = trim($A_SIRNAME);
	
	$A_NAME = str_replace("นาย",'',$A_NAME) ;
	$A_NAME = str_replace("นางสาว",'',$A_NAME) ;
	$A_NAME = str_replace("นาง",'',$A_NAME) ;
	$id_card=trim($result["id_card"]);
	$start_pay_date=$result["start_pay_date"];
	$ta_join_pm_id=$result["ta_join_pm_id"];
										//$car_month=$result["car_month"];
										//$start_contract_date=$result["start_contract_date"];
										$staff_check=$result["staff_check"];
										$cancel=$result["cancel"];
										$cancel_datetime=$result["cancel_datetime"];
										$note=$result["note"];
										
										$update_datetime=$result["update_datetime"];
										$datetime=$result["datetime"];
										$users =$result["users"];
										$deleted =$result["deleted"];
	//แปลง user			
	list($users_id,$users_n) = explode("[",$users);	
	
	list($users_f,$users_l) = explode(" ",$users_n,2);	
	//$users_f = str_replace("[",'',$users_f) ;
	
	$users_l = str_replace("]",'',$users_l) ;								
	//echo $users."-".$users_l."<br>";		
			$test_sql5=pg_query("select \"id_user\" from public.\"fuser\" where (\"fname\" like '%$users_f%' and \"lname\" like '%$users_l%') or (\"fname\" like '%$users_f $users_l%') ");	
	$rowtest5=pg_num_rows($test_sql5);	
	if($rowtest5==0){
		
		if($users_f=="นฤกร")
	$id_user = '034';
	else if($users_f=="Admin")
	$id_user = '001';
	else {
		echo $users_f." ".$users_l."<br><br>";
	$id_user = '001';	
	}
	
	
	}else{
				while($result5=pg_fetch_array($test_sql5))
{
	$id_user =$result5["id_user"];
}
		
	}										
list($car_license1,$car_license2)=explode("/",$car_license,2); //แยกช่องว่าง เพื่อตัดชื่อ กับนามสกุล  แยกเป็น 2 คำ เท่านั้น ถึงแม้จะมีช่องว่างมากกว่า 1 
$car_license1  =trim($car_license1);
$id_body=trim($result["id_body"]);

	$car_license_seq = trim($car_license2); // ลำดับการโอน ซื้อคืน ขาย..
	if($car_license_seq	=="")$car_license_seq=0;
	
	if($id_body!="")
	$con2 = " and C_CARNUM = '$id_body' ";
	
	$test_sql2=pg_query("select \"CarID\" from public.\"Fc\" where trim(\"C_REGIS\") = '$car_license1' ");
	
	$rowtest2=pg_num_rows($test_sql2);
	
	if($rowtest2==0){//ถ้ายังไม่เจอ ใช้ or
	if($id_body!=""){
	$con2 = " or C_CARNUM = '$id_body' ";
	$test_sql2=pg_query("select \"CarID\" from public.\"Fc\" where trim(\"C_REGIS\") = '$car_license1' ");
	$rowtest2=pg_num_rows($test_sql2);
		}
	}				

	//echo "select \"CarID\" from public.\"Fc\" where \"C_REGIS\" = '$car_license1' <br><br>";

if($id_card=="" || $id_card=="-" || $id_card=="- " || $id_card=="--" || strlen ($id_card)!=13){
$test_sql3=pg_query("select \"CusID\" from public.\"Fa1\" where \"A_NAME\" = '$A_NAME' and  \"A_SIRNAME\" = '$A_SIRNAME' ");	

}
else{
	
	$id_card2 = $id_card[0]." ".$id_card[1].$id_card[2].$id_card[3].$id_card[4]." ".$id_card[5].$id_card[6].$id_card[7].$id_card[8].$id_card[9]." ".$id_card[10].$id_card[11]." ".$id_card[12];
$test_sql3=pg_query("select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where a.\"A_NAME\" = '$A_NAME' and  a.\"A_SIRNAME\" = '$A_SIRNAME' and b.\"N_IDCARD\"='$id_card2' ");
//echo "select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where a.\"A_NAME\" = '$A_NAME' and  a.\"A_SIRNAME\" = '$A_SIRNAME' and b.\"N_IDCARD\"='$id_card2' ";
$rowtest3=pg_num_rows($test_sql3);
	if($rowtest3==0) //ถ้ายังไม่เจออีกให้ค้นหาเฉพาะ เลขบัตร
	$test_sql3=pg_query("select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where b.\"N_IDCARD\"='$id_card2' ");

}
//echo "select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where b.\"N_IDCARD\"='$id_card2'<br><br>";
		//if($A_NAME=="บุญสุข")
		//echo "select \"CusID\" from public.\"Fa1\" where \"A_NAME\" = '$A_NAME' and  \"A_SIRNAME\" = '$A_SIRNAME' <br>";
	$rowtest3=pg_num_rows($test_sql3);
	if($rowtest3==0 && $deleted!='1'){

        if($seq2%2==0){
            echo "<TR bgcolor=\"#EDF8FE\">";
        }else{
            echo "<TR bgcolor=\"#D5EFFD\">";
        }
?>
<TD><?php echo $seq2; ?></TD>
<TD><?php echo $contract_id; ?></TD>
<TD><?php echo $car_license; ?></TD>
<TD><?php echo $A_NAME ?></TD>
<TD><?php echo $A_SIRNAME; ?></TD>
<TD><?php echo $id_card2; ?></TD>
</TR>
		<?php		
		
		$seq2++;		
	}else{
		
		while($result3=pg_fetch_array($test_sql3))
{
	$CusID=$result3["CusID"]; //รหัสลูกค้าใน Fa1
}
	}
	$id_card2=null;	
	$id_card=null;
if($rowtest2!=0 ){
				while($result2=pg_fetch_array($test_sql2))
{
	$CarID=$result2["CarID"]; //รหัสรถยนต์ใน Fc
}	
}
	if($cancel_datetime=="")$cancel_datetime_sql="NULL";
	else $cancel_datetime_sql = "'$cancel_datetime'";
	
if($start_pay_date=="")$start_pay_date_sql="NULL";
	else $start_pay_date_sql = "'$start_pay_date'";
	



if($note=="")$note_sql="NULL";
	else $note_sql = "'$note'";	
if($CusID=="")$CusID_sql="NULL";
	else $CusID_sql = "'$CusID'";
	
	if($CarID=="")$CarID_sql="NULL";
	else $CarID_sql = "'$CarID'";	
	
	$test_sql4="INSERT INTO public.ta_join_main_bin(
            id,ta_join_pm_id, car_license,CarID,car_license_seq,IDNO,CusID,cpro_name,
            start_pay_date, staff_check, 
            cancel, cancel_datetime, note, create_datetime, update_datetime, update_by, deleted)
    VALUES  ('$id','$ta_join_pm_id','$car_license1',$CarID_sql,'$car_license_seq', '$contract_id' ,$CusID_sql,'$cpro_name',
            $start_pay_date_sql,'$staff_check', '$cancel', $cancel_datetime_sql, $note_sql, '$datetime', '$update_datetime', '$id_user', '$deleted')";
		//	echo $test_sql4."<br><br>";
			if($rowtest4=pg_query($test_sql4))
			{
				$num_add ++;
				}
			else
			{
				echo $test_sql4."<br><br>";
				$status++;
			}
$CarID=null;
$CusID=null;	
//}
			
		
	//}
		
	} ?>

 </table></fieldset>
</td>
      </tr>
    </table>
</BODY>

</HTML>
<?php
//}
$test_sql=pg_query("delete from public.ta_join_main_bin where deleted='1' "); //ลบข้อมูลในตารางเดิม
$rowtest=pg_num_rows($test_sql);
if($status == 0){
   pg_query("COMMIT");
   echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>
