<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
$cdate=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 

<fieldset><legend><B>ยกเลิกใบเสร็จ</B></legend>


<form name="search" method="post" action="cancel_recid.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="0" align="center">
    <tr align="center">
      <td><b>เลขที่ใบเสร็จ</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>" />
        <input type="text" id="idno" name="idno" size="60" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
        <input type="submit" name="submit" value="   ค้นหา   ">
      </td>
   </tr>
</table>
</form>

<div style="font-weight:bold;">รายการขอยกเลิกใบเสร็จ วันที่ <?php echo $cdate; ?></div>
<table width="700" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">no.</td>
      <td align="center">รหัสยกเลิกใบเสร็จ</td>
      <td align="center">จำนวนเงิน</td>
      <td align="center">เหตุผล</td>
      <td align="center">สถานะ</td>
   </tr>

<?php
$n = 0;
$qry_cc=pg_query("select * from \"CancelReceipt\" WHERE c_date='$cdate' ORDER BY c_receipt ASC ");
$numrow_cc=pg_num_rows($qry_cc);
while($res_cc=pg_fetch_array($qry_cc)){
    
    $SIDNO = $res_cc['IDNO'];
    /*
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$SIDNO' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        $vat = $res_cc1['VatValue'];
    }
    */
    $cs_memo= str_replace("\n", "<br>\n", "$res_cc[c_memo]"); 
    
    $n++;
    if($res_cc["admin_approve"]=='t'){
        $sta="อนุมัติยกเลิกใบเสร็จแล้ว";
    }else{
        $sta="รอการอนุมัติ";
    }
    
    if($res_cc["admin_approve"]=='t'){
        echo "<tr bgcolor=\"#FFC0C0\">";
    }else{
    
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\" valign=top>";
        }else{
            echo "<tr class=\"even\" valign=top>";
        }
    
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_cc["c_receipt"]; ?></td>
        <td align="right"><?php echo number_format($res_cc["c_money"],2); ?></td>
        <td align="left"><?php echo $cs_memo; ?></td>
        <td align="left"><?php echo $sta; ?></td>
    </tr>
<?php  
}
?>
</table>
  
  </fieldset> 

</div>
        </td>
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
        return "gdata_cancel_rec.php?q=" + this.value;
    });
}
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("idno","idno_names");
</script>

</body>
</html>
