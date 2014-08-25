<?php

include("../../config/config.php");


$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติการลงเวลาแบบพิเศษ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}


	function show_p(id){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('popup_app_dtl.php?id='+id);
    $('#dialog').dialog({
        title: 'รายละเอียด '+id,
        resizable: false,
        modal: true,  
        width: 650,
        height: 260,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
</script>

</head>
<body>
    <style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>อนุมัติการลงเวลาแบบพิเศษ</h1></div>
		<div class="wrapper">
			<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="8" align="left" style="font-weight:bold;">
                	<?php
			$qry_fr=pg_query("SELECT a.id,a.datetime,a.memo,b.type_name,c.ac,d.approved1,d.approved2 FROM \"LogsTimeAtt2012\" a left join \"LogsTimeAttType\" b on a.type_id=b.type_id left 
			join \"Vfuser\" c on a.user_id=c.id_user left join \"LogsTimeAttApprove\" d on d.id_att=a.id WHERE a.img_id is not null and ( d.approver1_id !='".$_SESSION["av_iduser"]."' or d.approved1 is null ) 
			and d.approved2 is null and d.non_app is null order by a.datetime ");
			/*echo "SELECT a.id,a.datetime,a.memo,b.type_name,c.ac,d.approved1,d.approved2 FROM \"LogsTimeAtt2012\" a left join \"LogsTimeAttType\" b on a.type_id=b.type_id left 
			join \"Vfuser\" c on a.user_id=c.id_user left join \"LogsTimeAttApprove\" d on d.id_att=a.id WHERE a.img_id is not null and  d.approver1_id !='".$_SESSION["av_iduser"]."' ";*/
			$nub=pg_num_rows($qry_fr); 
			echo "จำนวนทั้งหมด $nub รายการ"; ?>
                
                </td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
			
                <td align="center">ชื่อ-นามสกุลพนักงาน</td>
                <td align="center">วันเวลา</td>
				<td align="center">ประเภท</td>
				<td align="center">หมายเหตุ</td>
                <td align="center">สถานะ</td>
               
				<td>รายละเอียด</td>
			</tr>
			<?php
			
			while($sql_row4=pg_fetch_array($qry_fr)){
				//$cpro_name = $sql_row4['cpro_name'];
					$id = $sql_row4['id'];
					//$user_id = $sql_row4['user_id'];
					$datetime= $sql_row4['datetime'];
					$type_name =$sql_row4['type_name']; 
					$memo =$sql_row4['memo']; 
					$ac = $sql_row4['ac'];
					$approved1=  $sql_row4["approved1"];
   					$approved2=  $sql_row4["approved2"];
		
				/*	$res_profile=pg_query("select approved1,approver1_id,approved2,approver2_id from \"LogsTimeAttApprove\" where id_att='$id'");
   $res_userprofile=pg_fetch_array($res_profile);
   $approved1=  $res_userprofile["approved1"];
   $approved2=  $res_userprofile["approved2"];*/
   
   
    if($approved1=='f' || $approved1=='')$app_text="รอการอนุมัติครั้งที่ 1";
	//else if($approved1=='t' && $approved2=='f' )$app_text="อนุมัติครั้งที่ 1 แล้ว";
	else if($approved1=='t' && $approved2=='')$app_text="รอการอนุมัติครั้งที่ 2";
	//else if($approved1=='t' && $approved2=='t')$app_text="อนุมัติครั้งที่ 2 แล้ว";
	
	
	
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
            <td align="center"><?php echo $i; ?></td>
				
				<td align="left"><?php echo $ac; ?></td>
                <td align="left"><?php echo substr($datetime,0,19); ?></td>
                <td align="left"><?php echo $type_name; ?></td>
                <td align="center"><?php echo $memo; ?></td>
      
                <td align="center"><?php echo $app_text; ?></td>
				<td align="center">
					<span onclick="javascript:show_p('<?php echo $id; ?>')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
				</td>
				
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>