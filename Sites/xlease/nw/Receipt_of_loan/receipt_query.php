<?php 
session_start();
include('../../config/config.php');

$id_user = $_SESSION["av_iduser"];
$id = $_POST['idre'];
$idcontract = $_POST['idcontract'];
$listdetail = $_POST['detaillist'];
$list1 = $listdetail[0];
$list2 = $listdetail[1];
$list3 = $listdetail[2];
$list4 = $listdetail[3];
$list5 = $listdetail[4];
$list6 = $listdetail[5];
$list7 = $listdetail[6];
$list8 = $listdetail[7];
$list9 = $listdetail[8];
$list10 = $listdetail[9];

$money = $_POST['moneylist'];
$money1 = $money[0];
$money2 = $money[1];
$money3 = $money[2];
$money4 = $money[3];
$money5 = $money[4];
$money6 = $money[5];
$money7 = $money[6];
$money8 = $money[7];
$money9 = $money[8];
$money10 = $money[9];


$date = $_POST['date'];
$cusname = $_POST['cusname'];
$address = $_POST['address'];
$status = 0;

if($address == ""){
	$address = "null";
}else{
	$address = "'".$address."'";
}
if($list1 == ""){
	$list1 = "null";
	$listdetail1 = "null";
}else{
	$list1 = "'".$list1."'";
	$queylist1 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list1");
	$relist1 = pg_fetch_array($queylist1);
	$listdetail1 = $relist1['list_detail'];
	$listdetail1 = "'".$listdetail1."'";

}
if($list2 == ""){
	$list2 = "null";
	$listdetail2 = "null";
}else{
	$list2 = "'".$list2."'";
	$queylist2 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list2");
	$relist2 = pg_fetch_array($queylist2);
	$listdetail2 = $relist2['list_detail'];
	$listdetail2 = "'".$listdetail2."'";
}

if($list3 == ""){
	$list3 = "null";
	$listdetail3 = "null";
}else{
	$list3 = "'".$list3."'";
	$queylist3 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list3");
	$relist3 = pg_fetch_array($queylist3);
	$listdetail3 = $relist3['list_detail'];
	$listdetail3 = "'".$listdetail3."'";
}
if($list4 == ""){
	$list4 = "null";
	$listdetail4 = "null";
}else{
	$list4 = "'".$list4."'";
	$queylist4 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list4");
	$relist4 = pg_fetch_array($queylist4);
	$listdetail4 = $relist4['list_detail'];
	$listdetail4 = "'".$listdetail4."'";
}
if($list5 == ""){
	$list5 = "null";
	$listdetail5 = "null";
}else{
	$list5 = "'".$list5."'";
	$queylist5 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list5");
	$relist5 = pg_fetch_array($queylist5);
	$listdetail5 = $relist5['list_detail'];
	$listdetail5 = "'".$listdetail5."'";
}
if($list6 == ""){
	$list6 = "null";
	$listdetail6 = "null";
}else{
	$list6 = "'".$list6."'";
	$queylist6 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list6");
	$relist6 = pg_fetch_array($queylist6);
	$listdetail6 = $relist6['list_detail'];
	$listdetail6 = "'".$listdetail6."'";
}
if($list7 == ""){
	$list7 = "null";
	$listdetail7 = "null";
}else{
	$list7 = "'".$list7."'";
	$queylist7 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list7");
	$relist7 = pg_fetch_array($queylist7);
	$listdetail7 = $relist7['list_detail'];
	$listdetail7 = "'".$listdetail7."'";
}
if($list8 == ""){
	$list8 = "null";
	$listdetail8 = "null";
}else{
	$list8 = "'".$list8."'";
	$queylist8 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list8");
	$relist8 = pg_fetch_array($queylist8);
	$listdetail8 = $relist8['list_detail'];
	$listdetail8 = "'".$listdetail8."'";
}
if($list9 == ""){
	$list9 = "null";
	$listdetail9 = "null";
}else{
	$list9 = "'".$list9."'";
	$queylist9 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list9");
	$relist9 = pg_fetch_array($queylist9);
	$listdetail9 = $relist9['list_detail'];
	$listdetail9 = "'".$listdetail9."'";
}
if($list10 == ""){
	$list10 = "null";
	$listdetail10 = "null";
}else{
	$list10 = "'".$list10."'";
	$queylist10 = pg_query("SELECT list_detail FROM list_of_mg_3dreceipt where \"listreID\" = $list10");
	$relist10 = pg_fetch_array($queylist10);
	$listdetail10 = $relist10['list_detail'];
	$listdetail10 = "'".$listdetail10."'";
}

if($money1 == ""){
	$money1 = "null";
}else{
	$money1 = "'".$money1."'";
}
if($money2 == ""){
	$money2 = "null";
}else{
	$money2 = "'".$money2."'";
}

if($money3 == ""){
	$money3 = "null";
}else{
	$money3 = "'".$money3."'";
}
if($money4 == ""){
	$money4 = "null";
}else{
	$money4 = "'".$money4."'";
}
if($money5 == ""){
	$money5 = "null";
}else{
	$money5 = "'".$money5."'";
}
if($money6 == ""){
	$money6 = "null";
}else{
	$money6 = "'".$money6."'";
}
if($money7 == ""){
	$money7 = "null";
}else{
	$money7 = "'".$money7."'";
}
if($money8 == ""){
	$money8 = "null";
}else{
	$money8 = "'".$money8."'";
}
if($money9 == ""){
	$money9 = "null";
}else{
	$money9 = "'".$money9."'";
}
if($money10 == ""){
	$money10 = "null";
}else{
	$money10 = "'".$money10."'";
}




pg_query('BEGIN');

	$sql = "INSERT INTO temp_thcap_mg_3dreceipt(
				\"threceiptID\", \"Date\", cusname, cusaddress, list1, list2, list3, 
				list4, list5, list6, list7, list8, list9, list10, money1, money2, 
				money3, money4, money5, money6, money7, money8, money9, money10, 
				id_user,listdetail1,listdetail2,listdetail3,listdetail4,listdetail5,listdetail6,listdetail7,
				listdetail8,listdetail9,listdetail10)
		VALUES ('$id', '$date', '$cusname', $address, $list1, $list2,$list3, 
				$list4, $list5, $list6, $list7, $list8, $list9, $list10, $money1, $money2, 
				$money3, $money4, $money5, $money6, $money7, $money8, $money9, $money10, 
				'$id_user',$listdetail1,$listdetail2,$listdetail3,$listdetail4,$listdetail5,$listdetail6,
				$listdetail7,$listdetail8,$listdetail9,$listdetail10)";
			
	$query = pg_query($sql);

		if($query){
		}else{
		$status++;
		}
		
			if($status == 0){
			
					$sqlapp = "INSERT INTO approve_thcap_mg_3dreceipt(date, status, \"threceiptID\") VALUES ('$date', 0, '$id')";
					$appquery = pg_query($sqlapp);
					
						if($appquery){
						}else{
						$status++;
						}
						
							if($status == 0){
								
								$sqlmatch = "INSERT INTO thcap_3dreceipt_contract(\"contractID\", \"threceiptID\") VALUES ('$idcontract', '$id')";
								$matchquery = pg_query($sqlmatch);
								
									if($matchquery){
									}else{
									$status++;
									}
										
										if($status == 0){
												
												pg_query('COMMIT');
												echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
												echo "<script type='text/javascript'>alert(' บันทึกข้อมูลเรียบร้อยแล้ว ')</script>";											
											
										
										}else{
											
												pg_query('ROLLBACK');
												echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
												echo "<script type='text/javascript'>alert(' ขออภัย ไม่สามารถบันทึกข้อมูลได้ ')</script>";
												echo $sqlmatch;
																									
										}
							}else{
								
									pg_query('ROLLBACK');
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
										echo "<script type='text/javascript'>alert(' ขออภัย ไม่สามารถบันทึกข้อมูลได้ ')</script>";
										echo $sqlapp;
								
							}
			}else{
			
				pg_query('ROLLBACK');
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
					echo "<script type='text/javascript'>alert(' ขออภัย ไม่สามารถบันทึกข้อมูลได้ ')</script>";
					echo $maxsql;
			
			}

		
?>