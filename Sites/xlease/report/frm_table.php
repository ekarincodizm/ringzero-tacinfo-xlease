<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(function(){
    $("#data1").dblclick(function(){
        var numOp=$("#data1 option:selected").length;
        if(numOp>0){
                for(i=0;i<numOp;i++){
                    $("#data2").prepend($("#data1 option:selected").eq(i));
        // เมื่อมีการย้ายตำแหน่งแล้วต้องการเอา selected ออก  ให้เอา comment 
        // บรรทัดล่างต่อจากนี้ออก        
        //            $("#data2 option:selected").attr("selected",""); 
                }
        }
    });
    
    $("#data2").dblclick(function(){
        var numOp=$("#data2 option:selected").length;
        if(numOp>0){
                for(i=0;i<numOp;i++){
                    $("#data1").prepend($("#data2 option:selected").eq(i));
        // เมื่อมีการย้ายตำแหน่งแล้วต้องการเอา selected ออก  ให้เอา comment 
        // บรรทัดล่างต่อจากนี้ออก                            
        //            $("#data1 option:selected").attr("selected","");
                }
        }
    });
    
    $("#btn1").click(function(){
        //alert( $(".data2").val() );
    });
    
});
</script>

<style type="text/css">
.myList{
    width:200px;
    height:120px;
}
</style>


</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>

<div style="float:left"></div>
<div style="float:right"><!--<input type="button" value="  Close  " onclick="javascript:window.close();">--></div>
<div style="clear:both"></div>
        
<fieldset><legend><B>สมุดบัญชีเงินสดรับจ่าย</B></legend>

<div style="margin:10px">

<form id="form1" name="form1" method="post" action="frm_table_show.php" target="_blank">

<div style="margin:5px">
เดือน
<select name="mm" id="mm">
<?php
$nowmonth = date("m");
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
    
}
?>    
</select>
ปี 
<select name="yy" id="yy">
<?php
$nowyear = date("Y");
$year_a = $nowyear + 10; 
$year_b =  $nowyear - 10;

$s_b = $year_b+543;

while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
} 
?>
</select>
</div>

<div style="width:500px; margin:5px">
<div style="width:203px; float:left; font-size:12px; color:#008080; font-weight:bold">ประเภท AC TABLE</div>
<div style="width:200px; float:left; font-size:12px; color:#008080; font-weight:bold">ประเภทที่ต้องการให้แสดง</div>
<div style="clear:both"></div>
</div>

<div style="margin:5px">
<select name="data1[]" size="5" multiple="MULTIPLE" class="myList" id="data1">
<?php
$sql = pg_query("SELECT * FROM account.\"AcTable\" WHERE \"AcType\" LIKE 'CUR%' OR \"AcType\" LIKE 'SAV%' ORDER BY \"AcID\"");
while($result = pg_fetch_array($sql)){
    $AcID = $result['AcID'];
    $AcName = $result['AcName'];
    $data_cursav[] = $AcID;
    echo "<option value=\"$AcID\">$AcName</option>";
}
?>
</select>
<select name="data2[]" size="5" multiple="MULTIPLE" class="myList" id="data2">
</select>
<br />
<?php
$sql = pg_query("SELECT * FROM account.\"AcTable\" WHERE \"AcType\" LIKE 'CUR%' OR \"AcType\" LIKE 'SAV%' ORDER BY \"AcID\"");
while($result = pg_fetch_array($sql)){
    $AcID2 = $result['AcID'];
?>
<input type="hidden" name="data_cursav[]" id="data_cursav" value="<?php echo "$AcID2"; ?>">
<?php
}
?>
<input type="submit" name="btn1" id="btn1" value="พิมพ์รายงาน">
</div>

</form>

</div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>