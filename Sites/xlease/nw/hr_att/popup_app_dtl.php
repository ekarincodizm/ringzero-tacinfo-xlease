<?php
include("../../config/config.php");

$id = $_GET['id'];

//include("image.php");
$id_user = $_SESSION["av_iduser"]; // id ของ user ที่กำลังใช้งานอยู่ในขณะนั้น
// หาสิทธิ์ของ user
$searchLevel = pg_query("select * from \"Vfuser\" where \"id_user\" = '$id_user' ");
while($leveluser = pg_fetch_array($searchLevel))
{
	$level_user = $leveluser["emplevel"];
}//$level_user =2;
$qrytype=pg_query("SELECT a.datetime,a.memo,b.type_name,c.ac,a.img_id,e.approved1,e.approved2 FROM \"LogsTimeAtt2012\" a left join \"LogsTimeAttType\" b on a.type_id=b.type_id left 
			join \"Vfuser\" c on a.user_id=c.id_user left join \"LogsTimeAttApprove\" e on e.id_att=a.id WHERE a.id ='$id' ");


while($sql_row4=pg_fetch_array($qrytype)){
	//$id = $sql_row4['id'];
					//$user_id = $sql_row4['user_id'];
					$datetime= $sql_row4['datetime'];
					$type_name =$sql_row4['type_name']; 
					$memo =$sql_row4['memo']; 
					$ac = $sql_row4['ac'];
					$img_id = $sql_row4['img_id'];
					$approved1=  $sql_row4["approved1"];
   					$approved2=  $sql_row4["approved2"];
}
$st_non_app = 6;

	    if($approved1=='f' || $approved1=='' ){$app_text="รอการอนุมัติครั้งที่ 1";if($level_user==1)$st=4;else $st=0;$st_non_app=6;}

	else if($approved1=='t' && ($approved2=='' || $approved2=='f')){$app_text="รอการอนุมัติครั้งที่ 2";if($level_user==1)$st=5;else $st=1;$st_non_app=2;}
	//else if($approved1=='t' && $approved2=='t'){$app_text="อนุมัติครั้งที่ 2 แล้ว";}
	/*
function data_uri($file, $mime) 
{  
  $contents = file_get_contents($file);
  $base64   = base64_encode($contents); 
  return ('data:' . $mime . ';base64,' . $base64);
}*/
?>
    
<fieldset>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.button {
    border: 1px solid #006;
    background: #ccf;
}
.button:hover {
    border: 1px solid #f00;
    background: #eef;
}
</style>

<form name="frm1" id="frm1" action="" method="post">

<table width="100%" cellpadding="0" cellspacing="1" border="0">
   <!--<tr>

    <td align="right"><b>รูปภาพ : </b></td><td><?php 
/*	$local_file = 'Image/img.bmp';
	//echo $local_file;
	$server_file = 'FaceScan/'.$img_id.'.bmp';

// set up basic connection
$conn_id = ftp_connect($_SESSION["session_ftp_server"]) or die("Couldn't connect to $ftp_server"); 


// try to login
if (@ftp_login($conn_id, $_SESSION["session_ftp_user_name"], $_SESSION["session_ftp_user_pass"])) {
   // echo "Connected \n";
} else {
    echo "Couldn't connect \n";
}
// login with username and password
//$login_result = ftp_login($conn_id, $_SESSION["session_ftp_user_name"], $_SESSION["session_ftp_user_pass"]);

// try to download $server_file and save to $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) { ?> <img src="Image/img.bmp" border="1" /></td><?php
   // echo "Successfully written to $local_file\n";
} else {
    echo "There was a problem\n";
}

// close the connection
ftp_close($conn_id);
*/
?>
     
    
   
</tr>-->
<tr>
    <td width="50%" align="right" ><b>ชื่อ-นามสกุลพนักงาน : </b></td><td width="50%">&nbsp; <?php echo $ac ?> </td>
   
   
</tr>
<tr>
   
    <td align="right"><b>วันเวลา : </b></td><td>&nbsp;  <?php echo substr($datetime,0,19) ?></td>
    
   
</tr>
<tr>
   
    <td align="right"><b>ประเภท : </b></td><td>&nbsp;  <?php echo $type_name ?></td>
    
   
</tr>
<tr>
   
    <td align="right"><b>หมายเหตุ : </b></td><td>&nbsp; <font color=blue><?php echo $memo ?></font></td>
    
   
</tr>
<tr>
   
    <td align="right"><b>สถานะ : </b></td><td>&nbsp; <font color=red><?php echo $app_text ?></font></td>
    
   
</tr>
<!--
<tr>
   
    <td align="right"><b>หมายเหตุในการขอลงเวลาแบบพิเศษ : </b></td><td>&nbsp;  <?php echo $memo ?></td>
    
   
</tr> -->
<tr>
   
    <td align="right"><b>หมายเหตุในการอนุมัติ/ไม่อนุมัติ : </b></td><td>&nbsp;  <b><font color=red><textarea name="memo" id="memo" cols="40" rows="2"></textarea></font></b></td>
    
   
</tr>
</table>

<div style="text-align:right; margin-top:10px">
  
  <input class="button ui-button" type="button"  id="cancelvalue" value="ไม่อนุมัติ">
<input type="button" class="ui-button " name="btn_save" id="btn_save" value="อนุมัติ"  />
</div>
</form>
</fieldset>
<script type="text/javascript">

$('#btn_save').click(function(){
  
$("#btn_save").attr('disabled', true);
	
	
		$.post("app_api.php",{
			
			id :'<?php echo $id;?>',
			memo :$("#memo").val(),
			st :<?php echo $st;?>,
			
		},
		function(data){
			//alert(data);
				alert("บันทึกรายการเรียบร้อย");
			window.location.reload() ;
		});
	});
	
	$('#cancelvalue').click(function(){
  
$("#cancelvalue").attr('disabled', true);
	
	
		$.post("app_api.php",{
			
			id :'<?php echo $id;?>',
			memo :$("#memo").val(),
			st :<?php echo $st_non_app;?>,
			
		},
		function(data){
			
				alert("บันทึกรายการเรียบร้อย");
			window.location.reload() ;
		});
	});
	

  
</script>
