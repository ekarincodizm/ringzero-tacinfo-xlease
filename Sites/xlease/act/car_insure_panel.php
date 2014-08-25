<?php
include("../config/config.php");
?>

<script type="text/javascript">
function page(id,s){
    $("#panel").fadeOut(300);
    $("#panel").load("car_insure_panel.php?id="+ id +"&s="+ s);
    $("#panel").fadeIn(300);
}
</script>

<style type="text/css">
.page{
    padding: 2px;
}

.page a, .page a:visited{
    color : #8B8B8B; font-size:16px; text-decoration:none
}
.page a:hover{
    color : #000000; font-size:16px; text-decoration:none
}
.odd{
    background-color:#EDF8FE;
    font-size:13px
}
.even{
    background-color:#D5EFFD;
    font-size:13px
}
</style>


<div style="float:left">
<?php
$id = pg_escape_string($_GET['id']);
$s = pg_escape_string($_GET['s']);
$qry=pg_query("select * from insure.\"InsureForce\" WHERE \"CarID\" = '$id' ORDER BY \"InsFIDNO\" ASC");
$numrows = pg_num_rows($qry);
if($numrows == 0){ echo "ไม่พบประวัติการซื้อประกัน !"; exit; }
while($res=pg_fetch_array($qry)){
    $InsFIDNO[] = $res["InsFIDNO"];
}

$count_InsFIDNO = count($InsFIDNO);
echo "ทั้งหมด $count_InsFIDNO รายการ";
?>
</div>
<div style="float:right" class="page">
<?php
if(empty($s)){
    $search_array = 0;
}else{
    //$search_array = array_search($s,$InsFIDNO);
    $search_array = $s;
}

if($count_InsFIDNO > 1 AND $search_array != 0){
    $lt = $search_array-1;
    echo "<a href=\"#\" onclick=\"javascript:page('$id',0)\">&lt;&lt; </a>";
    echo "<a href=\"#\" onclick=\"javascript:page('$id',$lt)\">&lt; </a>";
}else{
    //ไม่ต้องแสดงเครื่องหมาย << <
}

echo "$InsFIDNO[$search_array]";

if($count_InsFIDNO > 1 AND $search_array != ($count_InsFIDNO-1)){
    $gt = $search_array+1;
    $gt_last = $count_InsFIDNO-1;
    echo "<a href=\"#\" onclick=\"javascript:page('$id',$gt)\"> &gt;</a>";
    echo "<a href=\"#\" onclick=\"javascript:page('$id',$gt_last)\"> &gt;&gt; </a>";
}else{
    //ไม่ต้องแสดงเครื่องหมาย > >>
}
?>
</div>
<div style="clear:both"></div>

<?php
    $qry_dc=pg_query("select A.*,B.* from \"insure\".\"InsureForce\" A 
    LEFT OUTER JOIN \"UNContact\" B ON A.\"IDNO\"=B.\"IDNO\" 
    WHERE A.\"InsFIDNO\"='$InsFIDNO[$search_array]'");
    if($res_if=pg_fetch_array($qry_dc)){
        $IDNO = $res_if["IDNO"];
        $full_name = $res_if["full_name"];
        $regis = $res_if["C_REGIS"];
        //$car_regis = $res_if["car_regis"];
        //$asset_type = $res_if["asset_type"]; if($asset_type == 1){ $regis = $c_regis; }else{ $regis = $car_regis; }
        $car_num = $res_if["C_CARNUM"];
        //$InsFIDNO = $res_if["InsFIDNO"];
        $InsID = $res_if["InsID"];
        $InsMark = $res_if["InsMark"];
        $Company = $res_if["Company"];
        $StartDate = $res_if["StartDate"];
        $EndDate = $res_if["EndDate"];
        $NetPremium = $res_if["NetPremium"];
        $Premium = $res_if["Premium"];
        $Discount = $res_if["Discount"];
        $CoPayInsReady = $res_if["CoPayInsReady"]; if($CoPayInsReady == 't'){ $copay = "ชำระแล้ว"; }else{ $copay = "ยังไม่ชำระ"; }
        $CollectCus = $res_if["CollectCus"];
        $Cancel = $res_if["Cancel"];
    }
    
    $outins=pg_query("select \"insure\".outstanding_insforce('$InsFIDNO[$search_array]')");
    $out_ins=pg_fetch_result($outins,0);

    $qry_bname=pg_query("select \"InsFullName\" from \"insure\".\"InsureInfo\" WHERE \"InsCompany\"='$Company' ");
    if($res_bname=pg_fetch_array($qry_bname)){
        $InsFullName = $res_bname["InsFullName"]; 
    }
?>

<div style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; background-color:#F0F0F0">
<table width="100%" border="0" cellSpacing="0" cellPadding="3">
    <tr align="left">
        <td width="15%"><b>ชื่อผู้เช่า</b></td>
        <td width="35%"><b></b> <?php echo "$full_name ($IDNO)"; ?></td>
        <td width="30%"><b>ทะเบียนรถ</b></td>
        <td width="20%"><?php echo $regis; ?></td>
    </tr>
    <tr align="left">
        <td><b>เลขถัง</b></td>
        <td><b></b> <a href="../up/frm_show.php?id=<?php echo $car_num; ?>&type=reg&mode=2" target="_blank"><u><?php echo $car_num; ?></u></a></td>
        <td><b>รหัสกรมธรรม์</b></td>
        <td><a href="../up/frm_show.php?id=<?php echo $InsFIDNO[$search_array]; ?>&type=insfo&mode=1" target="_blank"><u><?php echo $InsFIDNO[$search_array]; ?></u></a></td>
    </tr>
    <tr align="left">
        <td><b>เลขกรมธรรม์</b></td>
        <td><?php echo $InsID; ?></td>
        <td><b>เลขเครื่องหมาย</b></td>
        <td><?php echo $InsMark; ?></td> 
    </tr>
    <tr align="left">
        <td><b>บริษัทประกัน</b></td>
        <td><?php echo $InsFullName; ?></td>
        <td><b>วันที่เริ่มคุ้มครอง</b></td>
        <td><?php echo $StartDate; ?></td>
    </tr>
    <tr align="left">
        <td></td>
        <td></td>
        <td><b>วันที่หมดอายุ</b></td>
        <td><?php echo $EndDate; ?></td>
    </tr>
    <tr align="left">
        <td><b>ค่าเบิ้ยสุทธิ</b></td>
        <td><?php echo number_format($NetPremium,2); ?> <span class="text_gray">บาท.</span></td>
        <td><b>ยอดค้างชำระ</b></td>
        <td><?php echo number_format($out_ins,2); ?> <span class="text_gray">บาท.</span></td>
    </tr>
    <tr align="left">
        <td><b>ค่าเบี้ยประกัน</b></td>
        <td><?php echo number_format($Premium,2); ?> <span class="text_gray">บาท.</span></td>
    </tr>
    <tr align="left">
        <td><b>ส่วนลด</b></td>
        <td><?php echo number_format( $Discount,2); ?> <span class="text_gray">บาท.</span></td>
        <td><b>สถานะการชำระให้บริษัทประกัน</b></td>
        <td><?php echo $copay; ?></td>
    </tr>
    <tr align="left">
        <td><b>เบิ้ยที่ต้องชำระ</b></td>
        <td><?php echo number_format($CollectCus,2); ?> <span class="text_gray">บาท.</span></td>
        <?php if($Cancel != 'f'){ ?>              
        <td colspan=2 bgcolor="#FFFFCC" align="center"><FONT COLOR="#ff0000"><b>&gt;&gt;&gt; รายการนี้ถูกยกเลิก &lt;&lt;&lt;</b></FONT></td>
        <?php } ?>
    </tr>
</table>
</div>

<div style="clear:both; margin-top:10px"></div>

<div style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; background-color:#F0F0F0">
<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>วันที่ชำระ</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>จำนวนเงิน</td>
        <td>สถานะการชำระ</td>
    </tr>
<?php
$qry_vcus=pg_query("select * from \"FOtherpay\" WHERE  \"RefAnyID\"='$InsFIDNO[$search_array]' AND \"O_Type\"='103' AND \"Cancel\"='false' order by \"O_DATE\" ");
while($resvc=pg_fetch_array($qry_vcus)){
    $vcus_nub+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>     
    <td align="center"><?php echo $resvc["O_DATE"]; ?></td>
    <td align="center"><?php echo $resvc["O_RECEIPT"]; ?></td>
    <td align="right"><?php echo number_format($resvc["O_MONEY"],2); ?></td>
    <td align="center"><?php echo $resvc["PayType"]; ?></td>
</tr>
<?php
}
if($vcus_nub==0){
?>
<tr align="center" valign="middle">
    <td colspan="4">- ไม่พบข้อมูล -</td>
</tr>   
<?php
}
?>
</table>
</div>