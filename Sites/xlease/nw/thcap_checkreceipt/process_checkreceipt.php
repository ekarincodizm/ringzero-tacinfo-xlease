<?php
session_start();
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
$doerID = $_SESSION["av_iduser"];
$doerStamp = nowDateTime();


pg_query("BEGIN WORK");
$status=0;

//$receipt = json_decode(stripcslashes($_POST["receipt"]));
$receiptall=$_POST["receipt"];
$recid = explode("#",$receiptall);//แยก  receiptid
$condate=$_POST["condate"];
$month=$_POST["month"];
$year=$_POST["year"];
$bankint=$_POST["bankint"];

//ตรวจสอบ level ของผู้ใช้
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];
$receivdoerID="nosame";	
/*foreach($receipt as $key => $value){
	$receiptID = $value->receiptid;	*/
for($i=0;$i<count($recid)-1;$i++)
{	
	$receiptID = $recid[$i];	
	$qryreceipt=pg_query("select * from \"thcap_checkReceiptID\" where \"receiptID\"='$receiptID' ");
	$resultreceipt = pg_fetch_array($qryreceipt);
	$numrows=pg_num_rows($qryreceipt);
	if($numrows>0){
		$checkstatus=$resultreceipt["checkstatus"];
		$receiveUser=$resultreceipt["receiveUser"];//iduser ของผู้ทำรายการ
		$doerIDUser=$resultreceipt["doerID"];//iduser ของผู้ตรวจสอบคนแรก
		if(($checkstatus=='0') and ($doerIDUser=="")){
				if($emplevel<=1){}
				else{				    
				    if($receiveUser==$doerID){
						$receivdoerID="same";//เป็นคนเดียวกัน
						break;
					}
				}
				if($receivdoerID=="nosame"){ 
					$up="UPDATE \"thcap_checkReceiptID\"
					SET  \"doerID\"='$doerID', \"doerStamp\"='$doerStamp', checkstatus='1'
					WHERE \"receiptID\"='$receiptID'";
					if($resup=pg_query($up)){	
					}else{
						$status++;
					}
				}
		}
		else{//$checkstatus=1 
				$doerID2=$resultreceipt["doerID2"];
		        if($doerID2!=""){//แสดงว่า มี การการตรวจสอบครั้งที่ 2 แล้ว
					$receivdoerID="Checktrue";
				}
				else{
					if($emplevel<=1){}
					else{ 
						//ตรวจสอบว่า เป็นคนเดียวกันกับผู้ตรวจสอบคนแรก  หรือไม่						
						if($doerIDUser==$doerID){
							$receivdoerID="same";//เป็นคนเดียวกัน
							break;
						}
						//ตรวจสอบว่า เป็นคนเดียวกันกับผู้ทำรายการหรือไม่ 
						if($receivdoerID=="nosame"){
							if($doerID==$receiveUser){
								$receivdoerID="same";//เป็นคนเดียวกัน
								break;
							}
						}
					}
					if($receivdoerID=="nosame"){ 
						$up="UPDATE \"thcap_checkReceiptID\"
						SET  \"doerID2\"='$doerID', \"doerStamp2\"='$doerStamp'
						WHERE \"receiptID\"='$receiptID'";
						if($resup=pg_query($up)){	
						}else{
							$status++;
						}
					}
				}
		    }
	}
	/*
	if($numrows>0){ //แสดงว่ายังพบข้อมูลอยู่สามารถทำรายการได้
		$up="UPDATE \"thcap_checkReceiptID\"
		SET  \"doerID\"='$doerID', \"doerStamp\"='$doerStamp', checkstatus='1'
		WHERE \"receiptID\"='$receiptID'";
		if($resup=pg_query($up)){	
		}else{
			$status++;
		}
	}else{
		$status=-1;
		break;
	}*/
}
$script= '<script language=javascript>';
if($receivdoerID=="same"){ //กรณีที่ คนที่ตรวจสอบ เก็นคนเดียวกัน หรือ คนทำรายการ คนเดียวกัน 
	pg_query("ROLLBACK"); 
	$script.= " alert('ผิดผลาด เนื่องจากผู้ที่ทำรายการรับเงิน ไม่สามารถทำการตรวจสอบรายการชำระเงินได้  หรือ ผู้ที่ทำการตรวจสอบรายการชำระเงินครั้งที่ 1 แล้วไม่สามารถทำการตรวจสอบรายการชำระเงินครั้งที่  2 ได้ ');";
	//echo 4;
	}
else if($receivdoerID=="Checktrue"){ //กรณีมีการตรวจสอบมากกว่า 2 ครั้ง
	pg_query("ROLLBACK");
	//echo 5;
	$script.= " alert('ผิดผลาด เนื่องจากการตรวจสอบรายการชำระเงินทำได้สูงสุด 2 ครั้งเท่านั้น');";
}
else if($receivdoerID=="nosame"){
	if($status==-1){ //กรณีไม่สามารถตรวจสอบได้เนื่องจากมีการตรวจสอบก่อนหน้านี้แล้ว
		pg_query("ROLLBACK");
		//echo 1;
		$script.= " alert('มีบางรายการตรวจสอบก่อนหน้านี้แล้ว กรุณาตรวจสอบ');";
	}else if($status == 0){ //กรณีสามารถบันทึกได้ให้ส่ง 2 กลับไป
		pg_query("COMMIT");		
		//echo 2;
		$script.= " alert('บันทึกรายการเรียบร้อย');";
	}else{ //กรณีบันทึกข้อมูลไม่สำเร็จให้ส่งค่า 3 กลับไป
		pg_query("ROLLBACK");
		//echo 3;
		$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้ กรุณาตรวจสอบ');";
	}
}
$script.= '</script>';
echo $script;
if(true){
?>
<script type="text/javascript">
location.href = "frm_Index.php?val=1&condate="+'<?php echo $condate;?>'+"&month="+'<?php echo $month;?>'+"&year="+'<?php echo $year;?>'+"&bankint="+'<?php echo $bankint;?>';//refresh เป็นหน้าแรก
</script>
<?php } ?>