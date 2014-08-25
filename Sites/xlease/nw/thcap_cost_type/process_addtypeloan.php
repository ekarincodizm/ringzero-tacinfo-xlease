<?php
include("../../config/config.php");
include("../../nw/function/checknull.php");
$doerID = $_SESSION["av_iduser"];
$appvStamp = nowDateTime();
pg_query("BEGIN WORK");
$status=0;
$data= array();
$data1=array();
$loantypeall = json_decode(stripcslashes(pg_escape_string($_POST["type"])));
$name=pg_escape_string($_POST["name"]);
$note=pg_escape_string($_POST["note"]);
$autoid=pg_escape_string($_POST["autoid"]);
$statuscosttype=pg_escape_string($_POST["status"]);
$note=checknull($note);
foreach($loantypeall as $key => $value){
	$type = $value->loantype;
	$data[]="\"".$type."\"";
	$data1[]=$type;
}
$i=0;
while($i< count($data))
{	
	if($i==((count($data))-1))
	{	$loantype.=$data[$i];
		$loantype1.=$data1[$i];
	}
	else{ 
		$loantype.=$data[$i].",";
		$loantype1.=$data1[$i].",";
	}
	$i++;
}
if($loantype=='"all"'){ $loantype="null";}
else{
$loantype="'{".$loantype."}'";
$loantype1="'{".$loantype1."}'";
}
//เพิ่มข้อมูลในตาราง
if($autoid=='0'){
$in_sql="insert into \"thcap_cost_type_temp\" ( \"costtype\",\"costname\",\"doerid\",\"doerstamp\",\"typeloansuse\",\"note\"
		,\"approved\",\"edit_last_autoid\",\"status_costtype\") 
		values ('0','$name','$doerID','$appvStamp',$loantype,$note,'9','0','$statuscosttype')";
        if(!$result=pg_query($in_sql)){
            $status++;
        }
}
else{
	/*$sql_edit_last = pg_query("select \"autoid\" as \"edit_last\"  from \"thcap_cost_type_temp\"  where \"appvstamp\" 
				in (select max(\"appvstamp\") from \"thcap_cost_type_temp\" where \"approved\"!='9' and \"costtype\"='$autoid')");
	$re_loantype  = pg_fetch_array($sql_edit_last);
	$edit_lastautoid=$re_loantype ["edit_last"];*/
	/*$sql_edit_last = pg_query("select \"costtype\"  from \"thcap_cost_type_temp\" where \"autoid\"='$autoid'");
	$re_loantype  = pg_fetch_array($sql_edit_last);
	$costtype=$re_loantype ["costtype"];*/
	//ตรวจสอบว่าเคยแก้ไขมาจากจากเลขที่ไหน thcap_cost_type_temp
	$sql_edit_last = pg_query("select *  from \"thcap_cost_type\" where \"costtype\"='$autoid'");
	$re_loantype  = pg_fetch_array($sql_edit_last);
	$lasttype=$re_loantype["costtype"];
	$lastcostname=$re_loantype["costname"];
	$lasttypeloansuse=checknull($re_loantype["typeloansuse"]);	
	$lastnote=checknull($re_loantype["note"]);
	
	$laststatus_costtype=$re_loantype["status_costtype"];
	//ตรวจสอบว่ามีการเปลี่ยนแปลงข้อมูลหรือไม่
	$check="yes";
	if($lasttypeloansuse=="null"){$lasttypeloansuse="all";}	
	
	if(($lastcostname==$name)and($lasttypeloansuse==$loantype1)and($lastnote==$note)and($laststatus_costtype==$statuscosttype)){
		$check="no"; 
	}
	if($check=="yes"){
	$sql_edit = pg_query("select max(\"autoid\") as \"autoid\"  from \"thcap_cost_type_temp\"  where \"costtype\"='$lasttype' ");
	$re_edit  = pg_fetch_array($sql_edit);
	$autoidedit=$re_edit["autoid"];	
	
	//ตรวจสอบว่า มีสถานะอย่างไร
	$sql_edit_chk = pg_query("select \"approved\"  from \"thcap_cost_type_temp\"  where \"autoid\"='$autoidedit'");
	$re_edit_chk  = pg_fetch_array($sql_edit_chk);
	$approved_chk=$re_edit_chk["approved"];	
	$costtype=$autoid;
	if($approved_chk =='1'){ //อนุมัติแล้ว
		$in_sql="insert into \"thcap_cost_type_temp\" ( \"costtype\",\"costname\",\"doerid\",\"doerstamp\",\"typeloansuse\",\"note\"
		,\"approved\",\"edit_last_autoid\",\"status_costtype\")
		values ('$costtype','$name','$doerID','$appvStamp',$loantype,$note,'9','$autoidedit','$statuscosttype')";
        if(!$result=pg_query($in_sql)){
            $status++;			
        }
	}else if($approved_chk =='9'){//อยู่ระหว่างรออนุมัติ จะต้อง ไม่สามารถ แก้ไขได้ 
		$status++;	
	}
	else if($approved_chk =='0'){//ไม่อนุมัติ  จะต้องหาว่า ที่อนุมัติล่าสคดเป็นอะไร
		$sql_edit = pg_query("select max(\"autoid\") as \"autoid\"  from \"thcap_cost_type_temp\"  where \"costtype\"='$lasttype' and \"approved\"='1' ");
		$re_edit  = pg_fetch_array($sql_edit);
		$autoidedit=$re_edit["autoid"];	
		$in_sql="insert into \"thcap_cost_type_temp\" ( \"costtype\",\"costname\",\"doerid\",\"doerstamp\",\"typeloansuse\",\"note\"
		,\"approved\",\"edit_last_autoid\",\"status_costtype\")
		values ('$costtype','$name','$doerID','$appvStamp',$loantype,$note,'9','$autoidedit','$statuscosttype')";
        if(!$result=pg_query($in_sql)){
            $status++;			
        }
	}	
}
}
if($check=="no"){
	pg_query("ROLLBACK");    
	echo 3;
}
else{
	if($status==0){
		pg_query("COMMIT");
		echo 1;
	}
	else{
		pg_query("ROLLBACK");
		if($approved_chk =='9'){
		echo 4;}
		else{
		echo 2;
		}
	}
}
?>