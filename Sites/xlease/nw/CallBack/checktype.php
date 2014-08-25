<?php
session_start();
include("../../config/config.php");

$method = $_REQUEST['method'];
$calltypeID=$_REQUEST["calltypeID"];
$CallBackID=$_REQUEST["CallBackID"];
if($method == "checkform"){	
	//ตรวจสอบว่าประเภทนี้มีค่าแนะนำหรือไม่
	$qrytype=pg_query("select \"callFrom\",\"callResult\",\"callReject\" from callback_type where \"callTypeID\"='$calltypeID'");
	$res=pg_fetch_array($qrytype);
	list($callFrom,$callResult,$callReject)=$res;	
	
	echo "$callFrom,$callResult,$callReject";
}else if($method=="showfrom"){
	if($CallBackID==""){
		$chk=1;
	}
	
	if($chk!=1){
		//ตรวจสอบว่า typeID ที่เลือกตรงกับข้อมูลเดิมที่มีหรือไม่
		$qrychkid=pg_query("SELECT \"CallBackID\" FROM callback where \"CallBackID\"='$CallBackID' and \"callTypeID\"='$calltypeID'");
		$numchkid=pg_num_rows($qrychkid);
	}else{
		$numchkid=0;
	}
	if($numchkid>0 ){ //แสดงว่าเป็นข้อมูลปัจจุบัน ให้แหล่งข้อมูลขึ้นมาแสดง
		$qrychkfrom=pg_query("SELECT \"callFromID\" FROM callback_details_from where \"CallBackID\"='$CallBackID'");
		while($reschk=pg_fetch_array($qrychkfrom)){		
			list($callFromIDchk)=$reschk;
			
			$qryfrom=pg_query("SELECT \"callFromID\", \"callFromName\" FROM callback_from where \"callFromID\"='$callFromIDchk'");
			echo "<div>";
				$qryfrom=pg_query("SELECT \"callFromID\", \"callFromName\" FROM callback_from where \"callFromID\"='$callFromIDchk'");
				echo "<select name=\"callFromID[]\" id=\"callFromID\">";
				while($resfrom=pg_fetch_array($qryfrom)){
					list($callFromID,$callFromName)=$resfrom;
					?>
					<option value="<?php echo $callFromID;?>" <?php if($callFromID==$callFromIDchk) echo "selected";?>><?php echo $callFromName;?></option>
					<?php
					
				}
				echo "</select>";
			echo "</div>";
		}	
	}else if($numchkid=="0" OR $chk=="1"){	
		//วิธีที่ 1 เรียกโดยไม่ใช้ function
		//$qryfrom=pg_query("SELECT \"callFromID\", \"callFromName\" FROM callback_from where array[\"callTypeID\"] @> array[$calltypeID]");
		
		//วิธีที่ 2 เรียกโดยใช้ function
		$qryfrom=pg_query("SELECT \"callFromID\", \"callFromName\" 
		FROM callback_from 
		where ta_array1d_check(\"callTypeID\"::character varying[], '$calltypeID')=1");
		echo "<select name=\"callFromID[]\" id=\"callFromID\">";
		echo "<option value=\"\">-----เลือก-----</option>";
		while($resfrom=pg_fetch_array($qryfrom)){
			list($callFromID,$callFromName)=$resfrom;
			echo "<option value=$callFromID>$callFromName</option>";
		}
		echo "</select>";
		echo "<button type=\"button\" onclick=\"addFile()\">เพิ่มรายการ</button><font color=red><b>*</b></font>
		(กรุณาสอบถามลูกค้าว่า รู้จักหรือสนใจผลิตภัณฑ์ของบริษัทจากช่องทางใด)";
		echo "<div id=\"files-root\" style=\"margin:0\"></div>";
	}
?>
<script type="text/javascript">
var gFiles = 0;
function addFile(){
	var li = document.createElement('div');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="callFromID[]" id="callFromID"><?php
	//วิธีที่ 1 เรียกโดยไม่ใช้ function
	/*$qry_type=pg_query("SELECT \"callFromID\", \"callFromName\" FROM callback_from
	where array[\"callTypeID\"] @> array[$calltypeID]");*/
	
	//วิธีที่ 2 เรียกโดยใช้ function
	$qry_type=pg_query("SELECT \"callFromID\", \"callFromName\" 
	FROM callback_from 
	where ta_array1d_check(\"callTypeID\"::character varying[], '$calltypeID')=1");
	echo "<option value=\"\">-----เลือก-----</option>";
	while($res_type=pg_fetch_array($qry_type)){ 
		echo "<option value=\"$res_type[callFromID]\" >$res_type[callFromName]</option>";
	}?></select>&nbsp;<button onClick="removeFile(\'file-' + gFiles + '\')">ลบ</button>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;	
}
function removeFile(aId) {
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
	gFiles--;
}
</script>
<?php
}else if($method=="showResult"){
	$callReject=$_GET["callReject"];
?>
<script type="text/javascript">
$("#textresult").hide();
$(document).ready(function(){
	$("#resultno").click(function(){ 
		$("#textresult").show();
	});
		
	$("#resultyes").click(function(){
		$("#textresult").hide();
	});
	
	$("#resultwait").click(function(){
		$("#textresult").hide();
	});
});
</script>
<?php
	echo "<input type=\"radio\" name=\"stsResult\" id=\"resultwait\" value=\"2\" checked>อยู่ระหว่างรอ 
	<input type=\"radio\" name=\"stsResult\" id=\"resultyes\" value=\"1\">ได้ 
	<input type=\"radio\" name=\"stsResult\" id=\"resultno\" value=\"0\">ปฏิเสธ";
	echo "<div id=textresult>";
	echo "<div><u><b>เหตุผลที่ปฏิเสธ</b></u></div>";
	$qryrejec=pg_query("select \"callRejName\",\"callRejName\" from callback_reject where \"callTypeID\"='$calltypeID'");

	while($resrej=pg_fetch_array($qryrejec)){
		list($callRejID,$callRejName)=$resrej;
		echo "<div><input type=\"checkbox\" name=rej[] value=$callRejID>$callRejName</div>";
	}
	echo "</div>";
}

