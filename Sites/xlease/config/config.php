<?php
session_start();
@ini_set('display_errors', '1');

if(empty($_SESSION["session_company_server"]) || empty($_SESSION["session_company_dbname"]) || empty($_SESSION["session_company_dbuser"]) || empty($_SESSION["session_company_dbpass"])){
    echo "<div style=\"padding: 30px; color:#ff0000; font-size:13px; text-align: center;\"><a href=\"index.php\"><u>Please login.</u></a></div>";
    exit;
}

// Database Connection (Postgres)
$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");
//date_default_timezone_set('Asia/Bangkok');

// Database Connection (MSSQL - NW)
// ตรวจสอบว่า Server ที่ Run PHP นี้อยู่ใช้ 172.16.2.11 หรือไม่ ถ้าใช่จึงทำการเชื่อมต่อ MSSQL ถ้าไม่ใช่ไม่ต้องเชื่อมต่อ
//if ($_SERVER['SERVER_ADDR'] == "172.16.2.11" || $_SERVER['SERVER_ADDR'] == "10.0.1.11"){
	$dbserver=$_SESSION['mssql_dbserver'];
	$db_name=$_SESSION['mssql_db_name'];
	$dbusername=$_SESSION['mssql_dbusername'];
	$dbpassword=$_SESSION['mssql_dbpassword'];
	$conn=mssql_connect($dbserver,$dbusername,$dbpassword) or die("can not connect db");
	$s=mssql_select_db($db_name) or die("Can't select database");
//}

// Database Connection (mySQL - THCAP)
$connect_db = mysql_connect("172.16.2.251","ta_auto","ta_auto")or die ("Cannot connect to MySQL Database"); 
mysql_query("SET NAMES 'UTF8'");

//ฟังค์ชั่น ตั้งเวลาปิด session
function setSessionTime($_timeSecond){
    if(!isset($_SESSION['ses_time_life'])){
        $_SESSION['ses_time_life']=time();
    }
    if(isset($_SESSION['ses_time_life']) && time()-$_SESSION['ses_time_life']>$_timeSecond){
        if(count($_SESSION)>0){
            foreach($_SESSION as $key=>$value){
                unset($$key);
                unset($_SESSION[$key]);
            }
        }
    }else{
        $_SESSION['ses_time_life']=time();
    }
}
//จบฟังค์ชั่น ตั้งเวลาปิด session


//แปลงเครื่องหมาย ' ก่อนลงฐานข้อมูล
function str_replacein($stringin){
			$a = str_replace("'","#$@!%",$stringin);	
			$d = addslashes($a);				
			return $d;
}
//จบการแปลงเครื่องหมาย ' ลงฐานข้อมูล
//แปลงเครื่องหมาย ' กลับมาเมื่อต้องการดูข้อมูล
function str_replaceout($stringout){		
			$b = str_replace("#$@!%","'",$stringout);
			$c = stripslashes($b);			
			return $c;
}
//จบการแปลงเครื่องหมาย ' กลับมา

//ย้อนกลับไปเริ่มต้นการเรียกไฟล์ที่ Root
	 // * นำเข้า path ปัจจุบันโดยใช้คำสั่ง  $_SERVER['PHP_SELF'] ในหน้าที่ตนเรียกใช้และระบุ Path ใหม่โดยเริ่มจาก Xlease เช่น   post/frm_av_editidno.php *//
	 //** ตัวอย่างการใช้  $realpath = redirect($_SERVER['PHP_SELF'],'post/frm_av_editidno.php'); **//
function redirect($pathself,$folder){
$pathselfs = str_replace("xlease-nw/","",$pathself);
   $tempvarrelpathdir = explode("/",$pathselfs); //แบ่ง string  ที่อยู่ของไฟล์
	$countdirect = sizeof($tempvarrelpathdir); //นับจำนวนที่จะต้องย้อนกลับ
        for($i=$countdirect; $i>0; $i--){  // วนการย้อนกลับของไฟล์ โดยจะต้องดักว่าหากเป็นชื่อไฟล์ โฟเดอร์และค่าว่างจะไม่ต้องใส่ ../ ซึ่งจะอยู่ที่ตำแหน่งที่สุดท้ายไล่มา อีก -2 ตำแหน่ง 
                if($i == $countdirect || $i == $countdirect-1 || $i == $countdirect-2){ }else{ 
                        $relpath .= "../";
				}
        }
		 
		if($folder != ""){
			return 	$relpath.$folder;	
		}else{
			return 	$relpath;
		}	
}	
//////



// function สำหรับแตก array ของ postgresql
function pg_array_parse($array, $asText = true){
     $s = $array;
     if ($asText) {
         $s = str_replace("{", "array('", $s);
         $s = str_replace("}", "')", $s);    
        $s = str_replace(",", "','", $s);    
    } else {
         $s = str_replace("{", "array(", $s);
         $s = str_replace("}", ")", $s);
     }
     $s = "\$retval = $s;";
     eval($s);
     return $retval;
}

function pg_gen_numdaysinmonth($month , $year) // function ในการจำนวนวันในเดือนนั้นๆ
{
	$select_day = pg_query("SELECT \"gen_numDaysInMonth\"('$month' , '$year')");
	list($ans_day) = pg_fetch_array($select_day);	
	return $ans_day;
}

function pg_getminpaytype($cid = '') // function สำหรับ return ค่า code minpay
{
	$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$cid')");
	list($ans) = pg_fetch_array($select);
	return $ans;
}

function pg_getprincipletype($cid = '') // function สำหรับ return ค่า code principle
{
	$select = pg_query("SELECT account.\"thcap_mg_getPrincipleType\"('$cid')");
	list($ans) = pg_fetch_array($select);
	return $ans;
}

function pg_getinteresttype($cid = '') // function สำหรับ return ค่า code interest
{
	$select = pg_query("SELECT account.\"thcap_mg_getInterestType\"('$cid')");
	list($ans) = pg_fetch_array($select);
	return $ans;
}

function pg_getinsurelivetype() // function สำหรับ return ประเภทของประกันภัยคุ้มครองหนี้
{
	$select = pg_query("SELECT insure.get_insurelive_type()");
	list($ans) = pg_fetch_array($select);
	return $ans;
}

function pg_creditType($cid = '') // function สำหรับ return ประเภทสินเชื่อ
{
	$select = pg_query("SELECT \"thcap_get_creditType\"('$cid')");
	list($ans) = pg_fetch_array($select);
	return $ans;
}

function pg_getsecuremoneytype($cid = '', $sid = '') // function get Secure Money Channel Type
{
	if ($sid == '')
		$select = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$cid')");
	else
		$select = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$cid','$sid')");

	list($ans) = pg_fetch_array($select);
	return $ans;
}

function pg_getholdmoneytype($cid = '', $sid = '') // function get Hold Money Channel Type
{
	if ($sid == '')
		$select = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$cid')");
	else
		$select = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$cid','$sid')");

	list($ans) = pg_fetch_array($select);
	return $ans;
}

function insertZeroByConfig($inputValue , $digit ) // function เติมเลข 0 ให้ครบจำนวนหลักที่ต้องการ
{
	$str = "" . $inputValue;
	while (strlen($str) < $digit){
		$str = "0" . $str;
	}
	return $str;
}

function gen_receiptID($receiptDate, $contractID) // function หาเลขที่ใบเสร็จ
{
	// ค่าที่รับคือ
	// $receiptDate = วันที่ชำระ เช่น 2012-01-01
	// $contractID = เลขที่สัญญา เช่น MG-BK01-5400001

	//หาประเภทของสัญญา
	if($sql_search_type = pg_query("select * from \"thcap_contract\" where \"contractID\" = '$contractID'")); else $newreceiptID = "error";
	while($searchType = pg_fetch_array($sql_search_type))
	{
		$type = trim($searchType["conType"]);
	}
	if($type == ""){$type = substr($contractID,0,2);} // ประเภทสินเชื่อ

	// หาว่า ในวันนั้น และ รหัสประเภทนั้นเคยมีแล้วหรือยัง แล้วทำการ insert หรือ update ในตาราง thcap_running_receipt
	if($sql_check_date = pg_query("select * from \"thcap_running_receipt\" where \"receiptDate\" = '$receiptDate' and \"receiptType\" = '$type' ")); else $newreceiptID = "error";
	$numrowcheck = pg_num_rows($sql_check_date);
	if($numrowcheck > 0) // ถ้ามีข้อมูลอยู่แล้ว
	{
		while($resultDate=pg_fetch_array($sql_check_date))
		{
			$maxnumber = $resultDate["receiptRunning"]; // เลขล่าสุด
		}
		$maxnumber++; // เลขที่จะนำไปใช้ต่อไป	
		
		$up_sqldate = "update public.\"thcap_running_receipt\" set \"receiptRunning\" = '$maxnumber' where \"receiptDate\" = '$receiptDate' and \"receiptType\" = '$type' ";
		if($resultUpdate = pg_query($up_sqldate)); else $newreceiptID = "error";
	}
	else // ถ้ายังไม่มีข้อมูล
	{
		$maxnumber = 1;
		
		$in_sql_date = "insert into public.\"thcap_running_receipt\" (\"receiptDate\",\"receiptType\",\"receiptRunning\") values ('$receiptDate','$type','$maxnumber')";
		if($resultinto = pg_query($in_sql_date)); else $newreceiptID = "error";
	}
	// จบการ insert หรือ update ในตาราง thcap_running_receipt

	$year = substr($receiptDate,2,2); // ปี
	$year = insertZeroByConfig($year,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$month = substr($receiptDate,5,2); // เดือน
	$month = insertZeroByConfig($month,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$day = substr($receiptDate,8,2); // วัน
	$day = insertZeroByConfig($day,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$number_running = insertZeroByConfig($maxnumber,5); // เลข running ประจำวัน ในประเภทนั้นๆ
	
	if($newreceiptID != "error") // ถ้าก่อนหน้านี้ไม่มี error อะไรเกิดขึ้น
	{
		$newreceiptID = $year.$month.$day.$type."-".$number_running; // เลขที่ใบเสร็จที่ถูก gen ขึ้นมาใหม่
	}
	
	return $newreceiptID; // return เลขที่ใบเสร็จกลับไป แต่ถ้า return เป็นคำว่า error แสดงว่าเกิดข้อผิดพลาด
}

function gen_receiptIDForTax($receiptDate, $contractID) // function หาเลขที่ใบเสร็จ สำหรับ TAX
{
	// ค่าที่รับคือ
	// $receiptDate = วันที่ชำระ เช่น 2012-01-01
	// $contractID = เลขที่สัญญา เช่น MG-BK01-5400001
	
	$type_tax = substr($contractID,0,2); // ประเภทสินเชื่อ
	$type_tax = "V".$type_tax; // ประเภท tax

	// หาว่า ในวันนั้น และ รหัสประเภทนั้นเคยมีแล้วหรือยัง แล้วทำการ insert หรือ update ในตาราง thcap_running_receipt
	if($sql_check_date = pg_query("select * from \"thcap_running_receipt\" where \"receiptDate\" = '$receiptDate' and \"receiptType\" = '$type_tax' ")); else $newreceipt_tax = "error";
	$numrowcheck = pg_num_rows($sql_check_date);
	if($numrowcheck > 0) // ถ้ามีข้อมูลอยู่แล้ว
	{
		while($resultDate=pg_fetch_array($sql_check_date))
		{
			$maxnumber = $resultDate["receiptRunning"]; // เลขล่าสุด
		}
		$maxnumber++; // เลขที่จะนำไปใช้ต่อไป	
		
		$up_sqldate = "update public.\"thcap_running_receipt\" set \"receiptRunning\" = '$maxnumber' where \"receiptDate\" = '$receiptDate' and \"receiptType\" = '$type_tax' ";
		if($resultUpdate = pg_query($up_sqldate)); else $newreceipt_tax = "error";
	}
	else // ถ้ายังไม่มีข้อมูล
	{
		$maxnumber = 1;
		
		$in_sql_date = "insert into public.\"thcap_running_receipt\" (\"receiptDate\",\"receiptType\",\"receiptRunning\") values ('$receiptDate','$type_tax','$maxnumber')";
		if($resultinto = pg_query($in_sql_date)); else $newreceipt_tax = "error";
	}
	// จบการ insert หรือ update ในตาราง thcap_running_receipt

	$year = substr($receiptDate,2,2); // ปี
	$year = insertZeroByConfig($year,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$month = substr($receiptDate,5,2); // เดือน
	$month = insertZeroByConfig($month,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$day = substr($receiptDate,8,2); // วัน
	$day = insertZeroByConfig($day,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$number_running = insertZero($maxnumber,5); // เลข running ประจำวัน ในประเภทนั้นๆ
	
	if($newreceipt_tax != "error") // ถ้าก่อนหน้านี้ไม่มี error อะไรเกิดขึ้น
	{
		$newreceipt_tax = $year.$month.$day.$type_tax."-".$number_running; // เลขที่ใบเสร็จที่ถูก gen ขึ้นมาใหม่
	}
	
	return $newreceipt_tax; // return เลขที่ใบเสร็จ สำหรับ TAX กลับไป แต่ถ้า return เป็นคำว่า error แสดงว่าเกิดข้อผิดพลาด
}
function gen_debtinvoiceID($debtDate, $contractID) // function หาเลขที่ใบแจ้งหนี้
{
	// ค่าที่รับคือ
	// $receiptDate = วันที่ตั้งหนี้ เช่น 2012-01-01
	// $contractID = เลขที่สัญญา เช่น MG-BK01-5400001

	//หาประเภทของสัญญา
	if($sql_search_type = pg_query("select * from \"thcap_contract\" where \"contractID\" = '$contractID'")); else $newdebtinvoiceID = "error";
	while($searchType = pg_fetch_array($sql_search_type))
	{
		$type = trim($searchType["conType"]);
	}
	if($type == ""){$type = substr($contractID,0,2);} // ประเภทสินเชื่อ

	
	$type = "I".$type; // เพิ่ม I เข้าไปด้านหน้าเพื่อบอกให้รู้ว่าเป็นประเภทใบแจ้งหนี้
	
	// หาว่า ในวันนั้น และ รหัสประเภทนั้นเคยมีแล้วหรือยัง แล้วทำการ insert หรือ update ในตาราง thcap_running_receipt
	if($sql_check_date = pg_query("select * from \"thcap_running_receipt\" where \"receiptDate\" = '$debtDate' and \"receiptType\" = '$type' ")); else $newdebtinvoiceID = "error";
	$numrowcheck = pg_num_rows($sql_check_date);
	if($numrowcheck > 0) // ถ้ามีข้อมูลอยู่แล้ว
	{
		while($resultDate=pg_fetch_array($sql_check_date))
		{
			$maxnumber = $resultDate["receiptRunning"]; // เลขล่าสุด
		}
		$maxnumber++; // เลขที่จะนำไปใช้ต่อไป	
		
		 $up_sqldate = "update public.\"thcap_running_receipt\" set \"receiptRunning\" = '$maxnumber' where \"receiptDate\" = '$debtDate' and \"receiptType\" = '$type' ";
		 if($resultUpdate = pg_query($up_sqldate)); else $newdebtinvoiceID = "error";
	}
	else // ถ้ายังไม่มีข้อมูล
	{
		$maxnumber = 1;
		
		 $in_sql_date = "insert into public.\"thcap_running_receipt\" (\"receiptDate\",\"receiptType\",\"receiptRunning\") values ('$debtDate','$type','$maxnumber')";
		 if($resultinto = pg_query($in_sql_date)); else $newdebtinvoiceID = "error";
	}
	// จบการ insert หรือ update ในตาราง thcap_running_receipt
	
	
	
	$year = substr($debtDate,2,2); // ปี
	$year = insertZeroByConfig($year,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$month = substr($debtDate,5,2); // เดือน
	$month = insertZeroByConfig($month,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$day = substr($debtDate,8,2); // วัน
	$day = insertZeroByConfig($day,2); // เช็คให้มั่นใจว่ามี 2 หลัก
	$number_running = insertZeroByConfig($maxnumber,5); // เลข running ประจำวัน ในประเภทนั้นๆ
	
	if($newdebtinvoiceID != "error") // ถ้าก่อนหน้านี้ไม่มี error อะไรเกิดขึ้น
	{
		$newdebtinvoiceID = $year.$month.$day.$type."-".$number_running; // เลขที่ใบเสร็จที่ถูก gen ขึ้นมาใหม่
	}
	return $newdebtinvoiceID; // return เลขที่ใบเสร็จกลับไป แต่ถ้า return เป็นคำว่า error แสดงว่าเกิดข้อผิดพลาด
}

function nowDate() // ใช้วันที่ปัจจุบันจาก postgres 
{
	$qryDate = pg_query("select current_date");
	$nowDate = pg_result($qryDate,0);
	return $nowDate;
}

function nowDateTime() // วันเวลาปัจจุบัน
{
	$qryDateTime = pg_query("select \"nowDateTime\"() ");
	$nowDateTime = pg_result($qryDateTime,0);
	return $nowDateTime;
}

function nowTime() // เวลาปัจจุบัน  เช่น  "10:10:47"
{
	$qryTime = pg_query("select \"nowTime\"() ");
	$nowTime = pg_result($qryTime,0);
	return $nowTime;
}

function GetAndUnsetSession($str) // get ค่าจาก Session และ clear ทิ้ง parameter คือ ชื่อ session
{
	$value=$_SESSION[$str]; // กำหนดค่าให้ตัวแปล
	unset($_SESSION[$str]); // clear ค่าทิ้ง
	return $value; // ส่งค่ากลับ
}

function GetAdminMenu()
{
	$sqr = pg_query("select id_menu from f_menu where \"isAlert\"='1' order by id_menu");
	for($i=1;$i<=$res_admin=pg_fetch_array($sqr);$i++){
		$adminMenu[$i]=$res_admin["id_menu"];
	}
	return $adminMenu;
}
function DateDiff_2P($strDate1,$strDate2)
{
	$Qry = pg_query("select '$strDate2'::date - '$strDate1'::date");
	return pg_fetch_result($Qry,0);
	//return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
}
function SetFavoriteToTable($column,$id_user,$data){
	//ตรวจสอบว่ามี User คนนี้อยู่ในตาราง Favorite หรือยัง
	$Qry_user = pg_query("select id_user from thcap_favorite where id_user = '$id_user'");
	$check_user = pg_num_rows($Qry_user);
	if($check_user>0){
		$qrytype = "update"; //ถ้ามีให้ update
	} else {
		$qrytype = "insert"; //ไม่มีให้ insert
	}
	//process การ insert
	if (trim($qrytype)=="insert"){
	
		$dataSet = "{".$data."}";
		$qry = "insert into thcap_favorite(id_user,\"$column\") values ('$id_user','$dataSet')";
		pg_query($qry);
	}
	//process การ update
	if(trim($qrytype)=="update"){
	
		$select_data = pg_query("select \"$column\" from thcap_favorite where id_user = '$id_user'");
		$res_favor = pg_fetch_result($select_data,0);
		
		//ในกรณีมีการเพิ่ม column favorite ใหม่
		if($res_favor==null){
			$dataSet = "{".$data."}";
		} else {
			$count_array = pg_query("select ta_array1d_count('$res_favor')");
			$res_count = pg_fetch_result($count_array,0);
		
			$get_array = pg_query("select ta_array1d_get('$res_favor',0,$res_count) as favorite");
		
			if($res_count<10){
				$i=0;
				while($res_fav = pg_fetch_array($get_array)){
					$i++;
					$favorite = $res_fav["favorite"];
					if($i==1){
						$favoriteSet = $favorite;
					} else {
						$favoriteSet = $favoriteSet.",".$favorite;
					}
				}
				$dataSet = "{".$favoriteSet.",".$data."}";
			} else {
				$i=0;
				while($res_fav = pg_fetch_array($get_array)){
					$i++;
					$favorite = $res_fav["favorite"];
					
					if($i==1){
						$favoriteSet = "";
					} else if ($i==2) {
						$favoriteSet = $favorite;
					} else {
						$favoriteSet = $favoriteSet.",".$favorite;
					}
				}
				$dataSet = "{".$favoriteSet.",".$data."}";
			}
		}
		$qry = "update thcap_favorite set \"$column\" = '$dataSet' where id_user = '$id_user'";
		pg_query($qry);
	}
}
setSessionTime(30*60); //เรียกใช้งาน การตั้งเวลาปิด session ไว้ 10 นาที หากไม่มีการใช้งาน ระบบจะปิดการใช้งาน ต้อง Login ใหม่


$digit_cusid=6;
$digit_carid=8;
?>
