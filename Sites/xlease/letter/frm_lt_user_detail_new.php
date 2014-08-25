<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$CusID = pg_escape_string($_GET['CusID']);
$CusState = pg_escape_string($_GET['CusState']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function checkdata(){
	if(document.getElementById('sentto').value == "3"){
		if(document.getElementById('txt_ads3').value == ""){
			alert("กรุณาค้นหาที่อยู่");
			return false;
		}
	}
	
	if(document.getElementById('type_send').value == "A"){
		if(document.getElementById('regis_back').value == ""){
			alert("กรุณากรอกเลขทะเบียน");
			document.getElementById('regis_back').focus();
			return false;
		}
	}
}
$(document).ready(function(){
    $("#s1").hide();
    $("#s3").hide();
	$("#s4").hide();
	$("#s5").hide();
    
	if($("#coname2").val()==""){
		$("#coname2").hide();
		$("#coname1").show();
		$("#s6").hide();
		$("#s2").show();
	}else{
		$("#coname2").show();
		$("#coname1").hide();
		$("#s6").show();
		$("#s2").hide();
	}
	
    $("#sentto").change(function(){
        var src = $('#sentto option:selected').attr('value');
        if ( src == "1" ){
            $("#s1").show();
            $("#s2").hide();
			$("#s3").hide();
			$("#s4").hide();
			$("#s5").hide();
			$("#s6").hide();
			$("#coname1").show();
			$("#coname2").hide();
			$("#address_old").hide();
			$("#editaddress").hide();
			$("#oldsend").hide();
        }else if( src == "2" ){
            $("#s1").hide();
            $("#s2").show();
			$("#s3").hide();
			$("#s4").hide();
			$("#s5").hide();
			$("#s6").hide();
			$("#coname1").show();
			$("#coname2").hide();
			$("#address_old").hide();
			$("#editaddress").hide();
			$("#oldsend").hide();
		}else if( src == "3" ){
            $("#s1").hide();
            $("#s2").hide();
			$("#s3").show();
			$("#s4").hide();
			$("#s5").hide();
			$("#s6").hide();
			$("#coname1").show();
			$("#coname2").hide();
			$("#address_old").show();
			$("#editaddress").hide();
			$("#oldsend").hide();
		}else if( src == "4" ){
            $("#s1").hide();
            $("#s2").hide();
			$("#s3").hide();
			$("#s4").show();
			$("#s5").hide();
			$("#s6").hide();
			$("#coname1").show();
			$("#coname2").hide();
			$("#address_old").hide();
			$("#editaddress").show();
			$("#oldsend").show();
		}else if( src == "5" ){
            $("#s1").hide();
            $("#s2").hide();
			$("#s3").hide();
			$("#s4").hide();
			$("#s5").show();
			$("#s6").hide();
			$("#coname1").show();
			$("#coname2").hide();
			$("#address_old").hide();
			$("#editaddress").hide();
			$("#oldsend").hide();
        }else if( src == "6"){
			$("#s1").hide();
            $("#s2").hide();
            $("#s3").hide();
			$("#s4").hide();
			$("#s5").hide();
			$("#s6").show();
			$("#coname1").hide();
			$("#coname2").show();
			$("#address_old").hide();
			$("#editaddress").hide();
			$("#oldsend").hide();
		}else{
            $("#s1").hide();
            $("#s2").hide();
			$("#s3").hide();
			$("#s4").hide();
			$("#s5").hide();
			$("#address_old").hide();
			$("#editaddress").hide();
			$("#oldsend").hide();
        }
    });

	$("#type_send").change(function(){
        var src = $('#type_send option:selected').attr('value');
		document.frm_detail.regis_back.value="";
        if ( src == "N" ){
            $("#regis_back").hide();
        }else if( src == "R" ){
            $("#regis_back").hide();
        }else if( src == "A" ){
			$("#regis_back").show();
        }else if( src == "E" ){			
			$("#regis_back").show();
        }else{
           $("#regis_back").hide();
        }
    });

});
</script>    

<script type="text/javascript">
    var gFiles = 0;
    var summary;
    
    function addFile(){
        var li = document.createElement('div');
        li.setAttribute('id', 'file-' + gFiles);
        li.innerHTML = '<select name="typeletter[]" id="typeletter"><?php
$qry_type=pg_query("select * from letter.type_letter order by type_name asc");
while($res_type=pg_fetch_array($qry_type)){ 
    echo "<option value=\"$res_type[auto_id]\" >$res_type[type_name]</option>";
}?></select>&nbsp;<button onClick="removeFile(\'file-' + gFiles + '\')">ลบ</button>';
        document.getElementById('files-root').appendChild(li);
        gFiles++;
    }
    
    function removeFile(aId) {
        var obj = document.getElementById(aId);
        obj.parentNode.removeChild(obj);
    }
function MM_openbrWindow(theURL,winName,features) { 
		window.open(theURL,winName,features);
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_lt.php'"></div>
<div style="float:left"><input type="button" value="ส่งโดยบันทึกที่อยู่" onclick="window.location='frm_lt_user_detail_new.php'" disabled></div>
<div style="float:left"><input type="button" value="ส่งโดยไม่บันทึก" onclick="window.location='frm_lt_user_detail_dont_save_new.php?id=<?php echo $id;?>&CusID=<?php echo $CusID;?>&CusState=<?php echo $CusState;?>'"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>ทำรายการส่งจดหมาย</B></legend>

<div class="ui-widget" align="left">

<?php
	$qry_name=pg_query("SELECT C.\"CusID\",C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",B.\"N_ContactAdd\",
	D.\"A_NO\",D.\"A_SUBNO\",D.\"A_SOI\",D.\"A_RD\",D.\"A_TUM\",D.\"A_AUM\",D.\"A_PRO\",D.\"A_POST\",A.\"asset_id\",
	D.\"A_ROOM\",D.\"A_FLOOR\",D.\"A_BUILDING\",D.\"A_BAN\"
	FROM  \"Fp\"  A
	LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\"
	LEFT OUTER JOIN \"Fp_Fa1\" D ON A.\"IDNO\"=D.\"IDNO\" AND \"edittime\"='0' and \"CusState\"='$CusState'
	LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=D.\"CusID\"
	WHERE (A.\"IDNO\"='$id' )");
	
	if($res_name=pg_fetch_array($qry_name)){
		$A_CusID=trim($res_name["CusID"]);
		$A_FIRNAME=trim($res_name["A_FIRNAME"]);
		$A_NAME=trim($res_name["A_NAME"]);
		$A_SIRNAME=trim($res_name["A_SIRNAME"]);
		$A_NO=trim($res_name["A_NO"]);
		$A_SUBNO=trim($res_name["A_SUBNO"]);
		$A_SOI=trim($res_name["A_SOI"]);
		$A_RD=trim($res_name["A_RD"]);
		$A_TUM=trim($res_name["A_TUM"]);
		$A_AUM=trim($res_name["A_AUM"]);
		$A_PRO=trim($res_name["A_PRO"]);
		$A_POST=trim($res_name["A_POST"]);
		$N_ContactAdd=$res_name["N_ContactAdd"];
		$asset_id=$res_name["asset_id"];
		
		$A_ROOM=trim($res_name["A_ROOM"]);
		$A_FLOOR=trim($res_name["A_FLOOR"]);
		$A_BUILDING=trim($res_name["A_BUILDING"]);
		$A_BAN=trim($res_name["A_BAN"]);
		
		if($A_ROOM=="" || $A_ROOM=="-" || $A_ROOM=="--"){ //ห้อง
			//ไม่ต้องทำอะไร
		}else{
			$room="ห้อง $A_ROOM";
		}
		
		if($A_FLOOR=="" || $A_FLOOR=="-" || $A_FLOOR=="--"){ //ชั้น
			//ไม่ต้องทำอะไร
		}else{
			$floor="ชั้น $A_FLOOR";
		}
		
		if($A_BAN=="" || $A_BAN=="-" || $A_BAN=="--"){ //หมู่บ้าน
			//ไม่ต้องทำอะไร
		}else{
			$ban="หมู่บ้าน$A_BAN";
		}
		
		$arr_contact = explode("\n",$N_ContactAdd);
		if($A_SUBNO=="" || $A_SUBNO=="-" || $A_SUBNO=="--"){
			//ไม่ต้องทำอะไร
		}else{
			$subno="ม.$A_SUBNO";
		}
		if($A_SOI=="" || $A_SOI=="-" || $A_SOI=="--"){
			//ไม่ต้องทำอะไร
		}else{
			$soi="ซ.$A_SOI";
		}
		if($A_RD=="" || $A_RD=="-" || $A_RD=="--"){
			//ไม่ต้องทำอะไร
		}else{
			$road="ถ.$A_RD";
		}
		if($A_POST=="" || $A_POST=="-" || $A_POST=="--"){
			$A_POST="";
		}

		if($A_PRO=="กรุงเทพมหานคร" || $A_PRO=="กรุงเทพ" || $A_PRO=="กรุงเทพฯ" || $A_PRO=="กทม."){
			$txttum="แขวง".$A_TUM;
			$txtaum="เขต".$A_AUM;
			$txtpro="$A_PRO"; //จังหวัด
		}else{
			$txttum="ต.".$A_TUM;
			$txtaum="อ.".$A_AUM;
			$txtpro="จ.".$A_PRO; //จังหวัด
		}	
	
	   // $address1 = "$A_NO ม.$A_SUBNO ซ.$A_SOI ถ.$A_RD ต.$A_TUM";
		$address1 = "$A_NO $subno $ban $A_BUILDING $room $floor";
		$address2 = "$soi $road $txttum";
		$address3 = "$txtaum $txtpro $A_POST";
		
		//หาข้อมูลลูกค้าโอนสิทธิ์เข้าร่วม
		$qryjoin=pg_query("SELECT \"prefix\", \"f_name\",\"l_name\", \"address\" FROM \"VJoinMain\"  
		where car_license_seq='0' and  carid='$asset_id' and idno='$id' and deleted='0' and cancel='0' order by id DESC limit 1");
		$numjoin=pg_num_rows($qryjoin);
		list($prefix,$f_name,$l_name,$addressco)=pg_fetch_array($qryjoin);
		
		$sql_query5=pg_query("select \"P_ACCLOSE\",\"prefix\", \"f_name\",\"l_name\" from \"VJoin\" v WHERE v.\"asset_id\" = '$asset_id' and \"IDNO\"='$id' order by v.\"P_STDATE\" desc limit 1 ");// ข้อมูลล่าสุด
		list($P_ACCLOSE,$prefix1,$f_name1,$l_name1)=pg_fetch_array($sql_query5);
		
		if($P_ACCLOSE=="f"){
			$prefix=trim($prefix1);
			$f_name=trim($f_name1);
			$l_name=trim($l_name1);
		}else{
			$prefix=trim($prefix);
			$f_name=trim($f_name);
			$l_name=trim($l_name);
		}
		if($prefix=="" || $prefix=="นาง" || $prefix=="นาย" || $prefix=="นางสาว" || $prefix=="น.ส." || $prefix=="นส."){
			$cusnameco="คุณ$f_name  $l_name";
		}else{
			$cusnameco="$prefix$f_name  $l_name";
		}
		
		if($numjoin>0){
			$textco="
			<tr><td></td><td><font color=\"red\" size=\"3\"><b>(สัญญาเข้าร่วม)</b></font></td></tr>
			";
		}
		
	}else{
		exit;
	}


?>

<form name="frm_detail" action="frm_lt_user_detail_new_add.php" method="post" style="margin:0">
<input type="hidden" name="idno" value="<?php echo "$id"; ?>">
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<?php
echo $textco; //แสดงข้อความสัญญาเข้าร่วม กรณีเป็นสัญญาเ้ข้าร่วม
?>
<tr>
    <td width="20%" align="right"><b>ชื่อ/สกุล :</b></td>
    <td width="80%"><input type="hidden" name="CusID" value="<?php echo "$CusID";?>"><?php echo "<span onclick=\"javascript:popU('../nw/manageCustomer/showdetail2.php?CusID=$CusID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ข้อมูลลูกค้า\"><u>$A_FIRNAME $A_NAME $A_SIRNAME</u></span> (<span onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$id&type=outstanding','$id','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ดูตารางการชำระ\"><u>$id</u></span>)"; ?><input type="hidden" name="name" value="<?php echo "$A_FIRNAME $A_NAME $A_SIRNAME"; ?>"></td>
</tr>
<tr>
    <td valign="top" align="right"><b>รายละเอียดสัญญา :</b></td>
	<?php
	$qury_cont = pg_query("select * from \"Fp_Note\" where \"IDNO\" = '$id'");
	$num_cont = pg_num_rows($qury_cont);
	
	if($num_cont == 0){
		$contactnote="-----ยังไม่มีรายละเอียดสัญญา-----";
	}else{
		$result_cont = pg_fetch_array($qury_cont);
		$contactnote = $result_cont["ContactNote"];
		//$contactnote = str_replace("\n", "<br>\n", "$contactnote"); 
	}
	?>
    <td><textarea rows="5" cols="80" readonly><?php echo $contactnote; ?></textarea></td>
</tr>
<tr>
<td align="right"><b>โอนสิทธิ์เข้าร่วมให้กับ :</b></td>
<td>
	<input type="text" name="coname1" id="coname1" readonly="true" size="35">
	<input type="text" name="coname2" id="coname2" value="<?php echo $cusnameco;?>" readonly="true" size="35">
</td>
</tr>
<tr height="50">
	<td align="right"><b>เลือกที่ส่งจดหมาย : </b></td>
	<td>
		<select name="sentto" id="sentto">
			<?php if($CusState == 0){?><option value="1">ที่อยู่ตามสัญญาเช่าซื้อแรกเริ่ม </option><?php }else{?><option value="1">ที่อยู่ตอนทำสัญญา</option><?php }?>
			<?php if($CusState == 0){?><option value="4">ที่อยู่ตามสัญญาเช่าซื้อปัจจุบัน </option><?php }else{?><option value="4">ที่อยู่ที่เปลี่ยนหลังจากทำสัญญา</option><?php }?>			
			<!--<option value="3">ที่อยู่จากฐานข้อมูลเก่า</option>-->
			<option value="2" <?php if($numjoin==0){ ?> selected <?php }?>>ที่อยู่ใช้ส่งจดหมาย</option>
			<?php if($numjoin>0){?>
			<option value="6" <?php if($numjoin>0){ ?> selected <?php }?>>ที่อยู่โอนสิทธิ์เข้าร่วม</option>
			<?php }?>
			<option value="5">อื่นๆ</option>
		</select>
		<input type="button" name="address_old" id="address_old" style="display:none;" onClick="MM_openbrWindow('address_old.php?IDNO=<?php echo $id;?>&stasend=1','','scrollbars=yes,width=1055, left = 0, top = 0')" value="ค้นหา">
		<input type="button" name="editaddress" id="editaddress" style="display:none;" onClick="MM_openbrWindow('frm_lt_edit_detail.php?idno=<?php echo $id;?>&CusID=<?php echo $CusID;?>&CusState=<?php echo $CusState;?>&statusedit=1','','scrollbars=yes,width=1000, left = 0, top = 0')" value="แก้ไขที่อยู่ปัจจุบัน">
		<input type="button" name="oldsend" id="oldsend" style="display:none;" onClick="MM_openbrWindow('frm_lt_report_list_popup.php?IDNO=<?php echo $id;?>&CusID=<?php echo $CusID;?>','','scrollbars=yes,width=900, left = 0, top = 0')" value="ประวัติการส่งจดหมาย">
	</td>
</tr>
<tr valign="top">
    <td align="right"><b>ที่ส่งจดหมาย :</b></td>
    <td id="s1">
		<textarea name="txt_ads1" rows="5" cols="80" readonly="true"><?php echo  $address1."\n".$address2."\n".$address3; ?></textarea>
	</td>
	<td id="s2">
		<?php
		$qry_cus_ads2=pg_query("select \"address\" from letter.cus_address a
		left join letter.\"SendDetail\" b on a.\"address_id\"=b.\"address_id\"
		where \"CusID\" = '$CusID' and \"IDNO\"='$id' and \"Active\" = 'TRUE'");
			$res_ads2=pg_fetch_array($qry_cus_ads2);
			$add_letter=$res_ads2["address"]; 
		?>
		
		<textarea name="txt_ads2" rows="5" cols="80" readonly="true"><?php echo $add_letter;?></textarea>
	</td>
	<td id="s3">
		<textarea name="txt_ads3" id="txt_ads3" rows="5" cols="80" readonly="true"></textarea>
	</td>
	<td id="s4">
		<?php
		//ดึงที่อยู่สัญญาที่ถูกแก้ไขล่าสุด
		$qryadridno=pg_query("select \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_BAN\"
		from \"Fp_Fa1\" where \"IDNO\"='$id' and \"CusState\"='$CusState' order by \"edittime\" DESC limit(1)");
		$resadridno=pg_fetch_array($qryadridno);
			$A_NO1=trim($resadridno["A_NO"]);
			$A_SUBNO1=trim($resadridno["A_SUBNO"]);
			$A_SOI1=trim($resadridno["A_SOI"]);
			$A_RD1=trim($resadridno["A_RD"]);
			$A_TUM1=trim($resadridno["A_TUM"]);
			$A_AUM1=trim($resadridno["A_AUM"]);
			$A_PRO1=trim($resadridno["A_PRO"]);
			$A_POST1=trim($resadridno["A_POST"]);
			$A_ROOM=trim($resadridno["A_ROOM"]);
			$A_FLOOR=trim($resadridno["A_FLOOR"]);
			$A_BUILDING=trim($resadridno["A_BUILDING"]);
			$A_BAN=trim($resadridno["A_BAN"]);
			
			if($A_ROOM=="" || $A_ROOM=="-" || $A_ROOM=="--"){ //ห้อง
				//ไม่ต้องทำอะไร
			}else{
				$room="ห้อง $A_ROOM";
			}
			
			if($A_FLOOR=="" || $A_FLOOR=="-" || $A_FLOOR=="--"){ //ชั้น
				//ไม่ต้องทำอะไร
			}else{
				$floor="ชั้น $A_FLOOR";
			}
			
			if($A_BAN=="" || $A_BAN=="-" || $A_BAN=="--"){ //หมู่บ้าน
				//ไม่ต้องทำอะไร
			}else{
				$ban="หมู่บ้าน$A_BAN";
			}
			
			if($A_SUBNO1=="" || $A_SUBNO1=="-" || $A_SUBNO1=="--"){
				//ไม่ต้องทำอะไร
			}else{
				$subno1="ม.$A_SUBNO1";
			}
			if($A_SOI1=="" || $A_SOI1=="-" || $A_SOI1=="--"){
				//ไม่ต้องทำอะไร
			}else{
				$soi1="ซ.$A_SOI1";
			}
			if($A_RD1=="" || $A_RD1=="-" || $A_RD1=="--"){
				//ไม่ต้องทำอะไร
			}else{
				$road1="ถ.$A_RD1";
			}
			if($A_POST1=="" || $A_POST1=="-" || $A_POST1=="--"){
				$A_POST1="";
			}
			
			if($A_PRO1=="กรุงเทพมหานคร" || $A_PRO1=="กรุงเทพ" || $A_PRO1=="กรุงเทพฯ" || $A_PRO1=="กทม."){
				$txttum1="แขวง".$A_TUM1;
				$txtaum1="เขต".$A_AUM1;
				$txtpro1="$A_PRO1"; //จังหวัด
			}else{
				$txttum1="ต.".$A_TUM1;
				$txtaum1="อ.".$A_AUM1;
				$txtpro1="จ.".$A_PRO1; //จังหวัด
			}			
			$address11 = "$A_NO1 $subno1 $ban $A_BUILDING $room $floor";
			$address22 = "$soi1 $road1 $txttum1 ";
			$address33 = "$txtaum1 $txtpro1 $A_POST1";
		?>
		<textarea name="txt_ads4" id="txt_ads4" rows="5" cols="80" readonly="true"><?php echo  $address11."\n".$address22."\n".$address33; ?></textarea>
	</td>
	<td id="s5"> <!--ที่อยู่อื่นๆ -->
		<?php
		//ดึงที่อยู่อื่นๆที่ถูกแก้ไขล่าสุดมา
		$qryadreach=pg_query("select \"addEach\" from \"Fp_Fa1\" where \"IDNO\"='$id' and \"CusState\"='$CusState' order by \"edittime\" DESC limit(1)");
		list($adr_each)=pg_fetch_array($qryadreach);
		?>
		<textarea name="txt_ads5" rows="5" cols="80" readonly="true"><?php echo $adr_each;?></textarea>
	</td>
	<td id="s6">
		<textarea name="txt_ads6" id="txt_ads6" rows="5" cols="80" readonly="true"><?php echo  $addressco; ?></textarea>
	</td>
</tr>
<tr>
	<td align="right"><b>ประเภทการส่งจดหมาย :</b></td>
	<td>
		<select name="type_send" id="type_send">
			<option value="N">ส่งธรรมดา</option>
			<option value="R">ลงทะเบียน</option>
			<option value="A">ลงทะเบียนตอบรับ</option>
			<option value="E">EMS</option>
		</select>
		<input type="text" name="regis_back" id="regis_back" size="25" style="display:none;" maxlength="13">
	</td>
</tr>
<tr valign="top">
    <td align="right"><b>เลือกรูปแบบจดหมาย :</b></td>
    <td>
<select name="typeletter[]" id="typeletter">
<?php 
$qry_type=pg_query("select \"auto_id\",\"type_name\" from letter.type_letter order by type_name asc");
while($res_type=pg_fetch_array($qry_type)){
    echo "<option value=\"$res_type[auto_id]\" >$res_type[type_name]</option>";
}
?>
</select>
<button type="button" onclick="addFile()">เพิ่มรายการ</button>
<div id="files-root" style="margin:0"></div>
    </td>
</tr>
<tr>
    <td></td>
    <td><input type="submit" value="บันทึก" onclick="return checkdata();"></td>
</tr>
</table>
</form>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>