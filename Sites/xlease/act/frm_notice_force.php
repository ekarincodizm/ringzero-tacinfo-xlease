<?php 
include("../config/config.php"); 
$gdate = pg_escape_string($_POST['gdate']);

if(empty($gdate)) $gdate = date("Y-m-d");

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>     
</head>
<body>

<div class="header"><h1>รายงานการแจ้งประกันภัย</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>ประกันภัยภาคบังคับ (พรบ.)</b></legend>

<div style="float:left; padding: 5px 0px 5px 0px">
<form method="post" action="" name="f_list" id="f_list">
<b>วันที่</b> <input type="text" size="12" readonly="true" style="text-align:center;" id="gdate" name="gdate" value="<?php echo $gdate; ?>" /><input name="button2" type="button" onclick="displayCalendar(document.f_list.gdate,'yyyy-mm-dd',this)" value="ปฏิทิน" /><input name="btnButton" id="btnButton" type="submit" value="เลือก" />
</form>
</div>

<div style="clear:both;"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">บริษัทประกัน</td>
        <td align="center">เลขกรมธรรม์</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่เริ่มคุ้มครอง</td>
        <td align="center">วันสิ้นสุด</td>
        <td align="center">ค่าเบี้ยประกัน</td>
    </tr>
   
<?php
if( isset($gdate) ){
    
$qry_inf=pg_query("select * from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $company = $res_inf["InsCompany"];    
  
    $summary = 0;
    $qry_if=pg_query("select * from \"insure\".\"InsureForce\" WHERE \"Company\" = '$company' AND \"DoDate\" = '$gdate' AND \"Cancel\"='FALSE' ORDER BY \"InsID\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $nub++;
        $InsFIDNO = $res_if["InsFIDNO"];
        $Company = $res_if["Company"];
        $InsID = $res_if["InsID"];
        $IDNO = $res_if["IDNO"];
        $StartDate = $res_if["StartDate"];
        $EndDate = $res_if["EndDate"];
        $Premium = $res_if["Premium"];
            $summary+=$Premium;
        
        $qry_name=pg_query("select * from insure.\"VInsForceDetail\" WHERE \"InsFIDNO\"='$InsFIDNO'");
        if($res_name=pg_fetch_array($qry_name)){
            $full_name = $res_name["full_name"]; 
            $C_REGIS = $res_name["C_REGIS"];
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$Company"; ?></td>
        <td align="left"><?php echo "$InsID"; ?></td>
        <td align="left"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$C_REGIS"; ?></td>
        <td align="center"><?php echo "$StartDate"; ?></td>
        <td align="center"><?php echo "$EndDate"; ?></td>
        <td align="right"><?php echo number_format($Premium,2); ?></td>
    </tr>
<?php        
    }
    if($rows > 0){
 ?>

    <tr style="background-color:#ffffff; font-size:12px;">
        <td align="left"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="7"><b>รวมเงิน <?php echo number_format($summary,2); ?></b></td>
    </tr>                                                                      

<?php    
    }
}

if($nub == 0){
?>
    <tr>
        <td colspan="10" align="center">- ไม่พบข้อมูล -</td>
    </tr>
<?php
}

}
?>
</table>

<?php
    if($nub > 0){
        echo "<div align=\"right\"><a href=\"frm_notice_force_print.php?gdate=$gdate\" target=\"_blank\"><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"\"> <b>สั่งพิมพ์</b></a></div>";
    }
?>

</div>

</body>
</html>