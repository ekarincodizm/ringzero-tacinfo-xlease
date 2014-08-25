<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>แก้ไขการจัดการประเภทต้นทุนสัญญา</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<?php 
$autoid=pg_escape_string($_GET["autoid"]);
if($autoid!='0'){//0 คือการ บันทึก  ถ้า เป็นเลขอื่น คือ  autoid ที่จะแก้ไข
	/*$sql = pg_query("select \"costtype\"  from \"thcap_cost_type_temp\" where \"autoid\" ='$autoid'");
	$result = pg_fetch_array($sql);
	$costtype=$result["costtype"];*/
	//ดึงข้อมูลจาก ตาราง  thcap_cost_type
	$sql = pg_query("select * from \"thcap_cost_type\" where \"costtype\" ='$autoid'");
	$result = pg_fetch_array($sql);
	$costname_edit=$result["costname"];
	$typeloansuse_edit=$result["typeloansuse"];
	$note_edit=$result["note"];
	$Costtype=$result["status_costtype"];//ประเภทต้นทุน 
}
$rest = substr($typeloansuse_edit,1,strlen($typeloansuse_edit)-2); 
$typeloan = explode(",",$rest );
$i=0;	
?>
<?php if($autoid!='0'){ ?>
		<center><h2>แก้ไขการจัดการประเภทต้นทุนสัญญา</h2></center>
<?php }?>
<table align="center" >
	<tr>
		<td align="right" valign="top" ><b>ชื่อประเภทต้นทุนสัญญา <font color="#FF0000"><b> * </b></font> :</b></td>
		<td><input id="Costname" name="Costname" size="75" value="<?php if($autoid!='0'){ echo $costname_edit;}?>">
		</td>		
	</tr>	
		<tr>
		<td align="right" valign="top" ><b>ประเภทต้นทุน <font color="#FF0000"><b> * </b></font> :</b></td>
		<td>
		<select name="Costtype" id="Costtype"> 	
				<option value="" <?php if($Costtype=="") echo "selected";?>>กรุณาเลือก</option>		
				<option value="0" <?php if($Costtype=="0") echo "selected";?>>ไม่ระบุ</option>
				<option value="1" <?php if($Costtype=="1") echo "selected";?>>ต้นทุนเริ่มแรก</option>
				<option value="2" <?php if($Costtype=="2") echo "selected";?>>ต้นทุนดำเนินการ</option>				
		</select>
		</td>		
	</tr>
	<tr>
	<td align="right" valign="top" ><b>ประเภทสินเชื่อที่ใช้กับประเภทต้นทุนสัญญา<font color="#FF0000"><b> * </b></font> :</b></td>	
	<td>
	<div id="showData">
		<table align="left" width="70%" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
			<tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:13px;" height="20">
				<td width="100"  align="center">ประเภทสินเชื่อ</td>	
				<td width="100"  align="center">เลือก</td>		
			</tr>
				<?php 
				$sql_loantype = pg_query("select distinct \"conType\"  from \"thcap_contract\"");
				
					while($re_loantype  = pg_fetch_array($sql_loantype )){
						$i+=1;
						$loantype=$re_loantype["conType"];
						echo "<tr bgcolor=\"#DBF2FD\">";
						echo "<td align=\"center\">$loantype</td>";?>
						<td  align="center"><input type="checkbox" id="showData" name="showData"  value="<?php echo $loantype; ?>"
						onChange=unselectall(); 
						
						
						<?php if($autoid!='0'){
							for($t=0;$t<sizeof($typeloan);$t++)
							{
								if($typeloan[$t]==$loantype){
								echo " checked=\"checked\" />";
								}
					        }
						}
						echo "</td></tr>" ;					
					}?>				
				
			<tr bgcolor="#DBF2FD">
			<td align="center"> ทุกประเภทสินเชื่อ</td>
			<td align="center"><input type="checkbox" id="all"  name="all" value="all" onChange=SelectAll(<?php echo "'showData'";?>);
			<?php if($autoid!='0'){
					if($rest==""){
					echo " checked=\"checked\" />";
					}
				}?>
			</td>			
			</tr>			
		</table>
		</div>
	</td>
		<tr>		
		<td align="right" valign="top"><b>หมายเหตุ<font color="#FF0000"></font> :</b></td>
		<td><textarea id="note" name="note" cols="60" rows="4" ><?php if($autoid!='0'){echo $note_edit ;}?></textarea>
		</tr>
		
	<tr><td align="center" colspan="2">
	<!--ปุ่ม บันทึก -->
	<?php if($autoid!='0'){ ?>
	<input type="button" value="ยืนยันการแก้ไข" onclick="save();">
	<input type="button" value="ปิด" onclick="window.close();"></td>
	<?php }else{ {?>
	<input type="button" value="บันทึก" onclick="save();">	</td>
	<?php }
	}?>
	</tr>	
</table>
<!--แสดงรายการข้อมูลจัดการประเภทต้นทุนเริ่มแรก ที่ใช้งานอยู่จริง(ล่าสุด)-->
<?php 
if($autoid=='0'){ //กรณีที่กดมาแก้ไข
	include('frm_appvtotal.php');
	$autoid='0';
}?>
<script type="text/javascript">
function save(){
	//ตรวจสอบว่าป้อนข้อมูลครบหรือไม่
	var ele=$('input[name=showData]'); 	
	var ele1=$('input[name=all]'); 
	if(document.getElementById('Costname').value == ""){
			alert("กรุณาระบุชื่อประเภทต้นทุนสัญญา");
			document.getElementById('Costname').focus();
			return false;
	}
	else if(document.getElementById('Costtype').value == ""){
			alert("กรุณาเลือกประเภทต้นทุน");
			document.getElementById('Costname').focus();
			return false;
	}
	else{
		var type = [];
		var num=0;
		var c=0;
		if($(ele1[0]).is(':checked')){
			c++;	
			type[num]={loantype:$(ele1[0]).val()};
		}
		else{
			for (i=0; i< ele.length; i++)
			{	
				if($(ele[i]).is(':checked')){	
				c++;
				type[num]={loantype:$(ele[i]).val()};
				num+=1;
				}
			}
		}
		if(c==0){
			alert("กรุณาเลือกประเภทสินเชื่อ");
			return false;
		}
		else{
			$.post("process_addtypeloan.php",{
			type : JSON.stringify(type) ,
			name: $('#Costname').val(),
			note: $('#note').val(),
			status:$('#Costtype').val(),
			autoid:<?php echo $autoid;?>
			},			
			function(data){

			 if(data==1){
					alert("บันทึกรายการเรียบร้อย");					
				}
			else if(data==3){
				alert("ไม่สามารถบันทึกได้  เนื่องจากข้อมูลไม่มีการเปลี่ยนแปลง");
			}
			else if(data==4){
				alert("ไม่สามารถบันทึกได้  เนื่องจากมีรายการการที่รอการอนุมัติอยู่");
			}
			else {	
					alert("ไม่สามารถบันทึกได้  กรุณาดำเนินการอีกครั้งในภายหลัง");						
			}
				
				if(<?php echo $autoid;?>!=0){
					window.opener.location.reload();
					window.close();}
					else{
				window.location.reload();
				}
			});	
		}	
	}	
}	
function unselectall(){	
	document.getElementById("all").checked =false;
}
function SelectAll(no){	
	
	var ele=$('input[name='+no+']'); 	
    if(document.getElementById("all").checked == true){
		var i=1;
		for (i=0; i< ele.length; i++)
		{
			$(ele[i]).removeAttr('checked');
			//$(ele[i]).attr ( "checked" ,"checked" );
			//alert($(ele[i]).val());
			document.getElementById("all").checked =true;
		}		   
	}
	
		var no=0;
		var ele1=$('input[name=showData]'); 
		for (i=0; i< ele1.length; i++)
		{
			if($(ele1[i]).is(':checked')){			
			no+=1;
			}
		}
}
</script>