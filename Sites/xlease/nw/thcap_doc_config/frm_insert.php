<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$nowDate = nowDateTime();
$conTypeName = $_GET['conType'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตั้งค่าเอกสารสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function checktype(){
	
	$.post("checktype.php",{
			type : document.getElementById("newDoc").value,
			contype : document.getElementById("conType").value
		},
		function(data){		
			
				if(data=='NO'){
						//alert(' ประเภทเอกสารสัญญาซ้ำ ');
						document.getElementById("newDoc").style.backgroundColor ="#FF0000";
						var textalert = 'เอกสารสัญญานี้มีอยู่แล้ว ';
						$("#checktype").css('color','#ff0000');
						$("#checktype").html(textalert);
						document.getElementById("valuechk").value='1';
				}else if(data == 'YES'){
						document.getElementById("newDoc").style.backgroundColor = "#33FF33";
						$("#checktype").html("");
						document.getElementById("valuechk").value='0';
				}
				else if(data=='Dup')
				{
					document.getElementById("newDoc").style.backgroundColor ="#FF0000";
					var textalert = 'เอกสารสัญญานี้กำลังรออนุมัติ ';
					$("#checktype").css('color','#ff0000');
					$("#checktype").html(textalert);
					document.getElementById("valuechk").value='2';
				}
		});
};
function Check(){
	var Message = "check";
	var Noerror = Message;
	
	if(document.getElementById("newDoc").value==""){
		Message = "กรุณาระบุชื่อเอกสารสัญญา";
	} else if(document.getElementById("reason").value==""){
		Message = "กรุณาระบุเหตุผลที่เพิ่มเอกสาร";
	} else if(document.getElementById("valuechk").value=="1"){
		Message = "เอกสารนี้มีอยู่แล้ว กรุณาเปลี่ยนด้วย";
	} else if(document.getElementById("doc_Ranking").value==""){
		Message = "กรุณาระบุกอันดับของเอกสารด้วย";
	} else if(document.getElementById("valuechk").value=="2"){
		Message = "เอกสารนี้รออนุมัติอยู่ กรุณาเปลี่ยนด้วย";
	} 
	
	if(Message==Noerror){
		return true;
		} else {
			alert(Message);
			return false;
			}
}

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
};
</script>
</head>
<body>
	<div>
		<h2>เพิ่มเอกสารของสัญญาประเภท: <?php echo $conTypeName; ?> </h2>
	</div>
	<div>
		<form name="frm"action="process_insert.php" method="post" onsubmit="return Check();">
			<div>
				<table>
					<tr>
						<td>
							<label><b>ชื่อเอกสาร: </b></label> <input type="text" name="newDoc" id="newDoc" onkeyup="checktype();" onblur="checktype();" onchange="checktype();" autocomplete="off"> <span id="checktype" name="checktype"></span></td>
							<input type="hidden" name="conType" id="conType" value="<?php echo $conTypeName ?>">
							<input type="hidden" name="valuechk" id="valuechk" >
						</td>
					</tr>
					<tr>
						<td>
							<label><b>สถานะการใช้งาน: </b></label><select name="useable">
																	<option value="1">ใช้งาน-ใช้งานเสมอ</option>
																	<option value="0">ไม่ใช้งาน</option>
																	<option value="2">ใช้งาน-ไม่จำเป็นต้องใช้เสมอ</option>
																</select> 
						</td>
					</tr>
					<tr>
						<td>
							<label><b>จัดอันดับเอกสาร: </b></label><input type="text" id="doc_Ranking" name="doc_Ranking" onkeypress="check_num(event);" size="3" />
						</td>
					</tr>
					<tr>
						<td>
							<label><b>เหตุผล: </b></label><br>
							<textarea name="reason" id="reason" cols="40" rows="10" ></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="submit" id="submit" value="บันทึก">
							<input type="button" name="cancle" id="cancle" value="ยกเลิก" onclick="window.close();">
						</td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</body>
</html>