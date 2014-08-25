<?php include("../config/config.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>

</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
	</tr>
	<tr>
		<td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>

<div class="wrapper">

<?php
if(isset($_REQUEST['h_arti_id'])){

    $qry_ct=pg_query("select \"full_name\",\"C_CARNUM\",\"C_REGIS\",\"CusID\",\"asset_id\",\"C_COLOR\",\"C_CARNAME\"  from \"VContact\" WHERE (\"IDNO\"='".pg_escape_string($_REQUEST[h_arti_id])."')");
    if($res_ct=pg_fetch_array($qry_ct)){
        $full_name = $res_ct["full_name"];      if($full_name == ""){ $full_name = "-"; }
        $car_num = $res_ct["C_CARNUM"];         if($car_num == ""){ $car_num = "-"; }
        $c_regis = $res_ct["C_REGIS"];          if($c_regis == ""){ $c_regis = "-"; }
        $cus_id = $res_ct["CusID"];
        $asset_id = $res_ct["asset_id"];
        $C_COLOR = $res_ct["C_COLOR"];
        $C_CARNAME = $res_ct["C_CARNAME"];
    }

}
?>

<script>
function validate(){
    var theMessage = "";
    var noErrors = theMessage;

    if (document.insureforce.insid.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเลขกรมธรรม์";
    }
    if (document.insureforce.insmark.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเลขเครื่องหมาย";
    }

    if(theMessage == noErrors){
        return true;
    }else{
        alert(theMessage);
        return false;
    }
}
</script>

<script>
function validate2(){
    var theMessage = "";
    var noErrors = theMessage;

    if (document.search.car_id.value == "") {
        theMessage = "กรุณาใส่คำที่ต้องการค้นหา";
    }

    if(theMessage == noErrors){
        return true;
    }else{
        alert(theMessage);
        return false;
    }
}
</script>

<fieldset><legend><B>เพิ่มข้ิอมูลประกันภัยภาคบังคับ (พรบ.) - พิมพ์กรรมธรรม์</B></legend>

<form name="search" method="post" action="" onsubmit="return validate2(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>IDNO, เลขตัวถัง, ชื่อผู้เช่า, ทะเบียน</b>
        <input name="h_arti_id" type="hidden" id="h_arti_id" value="<?php echo pg_escape_string($_REQUEST['h_arti_id']); ?>" />
        <input type="text" id="car_id" name="car_id" size="50" value="<?php echo pg_escape_string($_REQUEST['h_arti_id']); ?>">
        <input type="submit" name="submit_search" value="   ค้นหา   ">
      </td>
   </tr>
</table>
</form>
<?php
if(isset($_REQUEST['h_arti_id']) ){
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">Insure ID</td>
        <td align="center">เลขกรมธรรม์</td>
        <td align="center">เลขเครื่องหมาย</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันที่หมดอายุ</td>
    </tr>

<?php
    $qry_if2=pg_query("select \"InsFIDNO\",\"InsID\",\"InsMark\",\"StartDate\",\"EndDate\" from insure.\"InsureForce\" WHERE \"IDNO\"='".pg_escape_string($_REQUEST[h_arti_id])."' ORDER BY \"InsFIDNO\" ASC");
    while($res_if2=pg_fetch_array($qry_if2)){
        $s_InsFIDNO = $res_if2["InsFIDNO"];
        $s_InsID = $res_if2["InsID"];
        $s_InsMark = $res_if2["InsMark"];
        $s_StartDate = $res_if2["StartDate"];
        $s_EndDate = $res_if2["EndDate"];

        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php 
        if(empty($s_InsID) AND empty($s_InsMark)){
            $nubb += 1;
            echo "<a href=\"?h_arti_id=".pg_escape_string($_REQUEST[h_arti_id])."&InsFIDNO=$s_InsFIDNO\" title=\"พิมพ์กรรมธรรม์ $s_InsFIDNO\"><div style=\"font-weight:bold; color:red;\"><u>$s_InsFIDNO</u></div></a>";
        }else{
            echo "$s_InsFIDNO";
        }
         ?></td>
        <td align="left"><?php echo "$s_InsID"; ?></td>
        <td align="left"><?php echo "$s_InsMark"; ?></td>
        <td align="center"><?php echo "$s_StartDate"; ?></td>
        <td align="center"><?php echo "$s_EndDate"; ?></td>
    </tr>

<?php
    }
?>
</table>
<?php

if(isset($_REQUEST['InsFIDNO']) ){

    $qry_if=pg_query("select \"InsFIDNO\",\"Company\",\"Code\",\"StartDate\",\"EndDate\",\"Capacity\",\"Discount\",\"Premium\",\"CollectCus\"
	from insure.\"InsureForce\" WHERE \"IDNO\"='".pg_escape_string($_REQUEST[h_arti_id])."' AND \"InsID\" is null AND \"InsMark\" is null AND \"Cancel\"='false' ");
    if($res_if=pg_fetch_array($qry_if)){
        $InsFIDNO = $res_if["InsFIDNO"];
        $Company = $res_if["Company"];
        $Code = $res_if["Code"];
        $StartDate = $res_if["StartDate"];
        $EndDate = $res_if["EndDate"];
        $Capacity = $res_if["Capacity"];
        $Discount = $res_if["Discount"];
        $Premium = $res_if["Premium"];
        $CollectCus = $res_if["CollectCus"];

    
?>
<form id="insureforce" name="insureforce" method="post" action="frm_insure_force_actual_add.php" onsubmit="return validate(this)">
<input type="hidden" name="infidno" value="<?php echo $InsFIDNO; ?>">
<input type="hidden" name="company" value="<?php echo $Company; ?>">

<input type="hidden" name="date_start" value="<?php echo $StartDate; ?>">
<input type="hidden" name="date_end" value="<?php echo $EndDate; ?>">

<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td width="18%"><b>ชื่อ</b></td>
      <td width="35%" colspan="1" class="text_gray"><?php echo $full_name." (".pg_escape_string($_REQUEST['h_arti_id']).")" ?></td>
      <td><b>Insure ID</b></td>
      <td class="text_gray"><?php echo $InsFIDNO; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขถัง</b></td>
      <td class="text_gray"><a href="../up/frm_show.php?id=<?php echo $car_num; ?>&type=reg&mode=2" target="_blank"><u><?php echo $car_num; ?></u></a></td>
      <td><b>ประเภทรถ</b></td>
      <td class="text_gray"><?php echo $C_CARNAME; ?></td>
   </tr>
   <tr align="left">
      <td><b>ทะเบียนรถ</b></td>
      <td class="text_gray"><?php echo $c_regis; ?></td>
      <td><b>สีรถ</b></td>
      <td class="text_gray"><?php echo $C_COLOR; ?></td>
   </tr>
   <tr align="left">
      <td><b>บริษัทประกัน</b></td>
      <td colspan="3" class="text_gray"><?php echo $Company; ?></td>
   </tr>
   <tr align="left">
      <td><b>ประเภท</b></td>
      <td colspan="1" class="text_gray"><?php echo $Code; ?></td>
	  <?php
		if($Code=="1.400" || $Code=="1.401" || $Code=="1.402" || $Code=="1.403" || $Code=="1.420" || $Code=="1.421"){
			echo "<td><b>น้ำหนักรวม (กก.)</b></td>";
		}else if($Code=="1.200" || $Code=="1.201" || $Code=="1.202" || $Code=="1.203"){
			echo "<td><b>จำนวนที่นั่ง</b></td>";
		}else{
			echo "<td><b>ขนาดเครื่องยนต์</b></td>";
		}
	  ?>
      <td colspan="1" class="text_gray"><?php echo number_format($Capacity,0); ?></td>
   </tr>
   <tr align="left">
      <td><b>วันที่เริ่ม</b></td>
      <td colspan="1" class="text_gray"><?php echo $StartDate; ?></td>
      <td><b>วันที่หมดอายุ</b></td>
      <td colspan="1" class="text_gray"><?php echo $EndDate; ?></td>
   </tr>
   <tr align="left">
      <td><b>ส่วนลด</b></td>
      <td colspan="3" class="text_gray"><?php echo number_format($Discount,2); ?> บาท.</td>
   </tr>
   <tr align="left">
      <td><b>ค่าเบิ้ยประกัน</b></td>
      <td colspan="3" class="text_gray"><?php echo number_format($Premium,2); ?> บาท.</td>
   </tr>
    <tr align="left">
      <td><b>เบี้ยที่เก็บกับลูกค้า</b></td>
      <td colspan="3" class="text_gray"><?php echo number_format($CollectCus,2); ?> บาท.</td>
   </tr>
      <tr align="left">
      <td><b>เลขกรมธรรม์</b></td>
      <td colspan="3"><input type="text" name="insid" size="30"></td>
   </tr>
   <tr align="left">
      <td><b>เลขเครื่องหมาย</b></td>
      <td colspan="3"><input type="text" name="insmark" size="30"></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
</form>
 <?php 
}

}

if($nubb == 0){
    echo "<div align=center style=\"color:red; font-weight:bold;\"><br /><br />ยังไม่มีการบันทึก&nbsp;เริ่มต้น<br>หรือ ได้เคยมีัการพิมพ์ พรบ. ไปแล้ว<br /><br /></div>";
}

}
?>
</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>

<script type="text/javascript">
function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("car_id","h_arti_id");
</script>

</body>
</html>