<?php 
include("../config/config.php"); 

if(!empty($_GET['mm'])) { $mm = pg_escape_string($_GET['mm']);}
if(!empty($_GET['yy'])) { $yy = pg_escape_string($_GET['yy']);}
if(!empty($_GET['w'])) { $w = pg_escape_string($_GET['w']);}

?>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td> 
        <td></td>
    </tr>
   
<?php
if( !empty($mm) and !empty($yy) ){
    
$qry_name=pg_query("SELECT A.\"IDCarTax\",A.\"IDNO\",A.\"ApointmentDate\",A.\"TaxDueDate\",A.\"TypeDep\"
,B.\"full_name\",B.\"asset_type\",B.\"C_REGIS\",D.\"car_regis\",B.\"C_StartDate\"   FROM carregis.\"CarTaxDue\" A
INNER JOIN \"VCarregistemp\" B on A.\"IDNO\"=B.\"IDNO\" 
LEFT JOIN \"Fp\" C ON A.\"IDNO\"=C.\"IDNO\"
LEFT JOIN \"FGas\" D ON C.asset_id=D.\"GasID\"
WHERE EXTRACT(MONTH FROM A.\"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM A.\"TaxDueDate\")='$yy' 
AND (A.\"TypeDep\"='101' OR A.\"TypeDep\"='105' ) AND A.\"BookIn\"='false' AND (B.\"C_REGIS\" like '%$w%' OR D.\"car_regis\" like '%$w%') ORDER BY A.\"TaxDueDate\" ASC ");
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
                if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            
           // $asset_id = $res_name["asset_id"];
            $full_name = $res_name["full_name"];
            $asset_type = $res_name["asset_type"];
            $C_REGIS = $res_name["C_REGIS"];
            $car_regis = $res_name["car_regis"];
			$C_StartDate = $res_name["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate));
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"> <a href="#" onclick="javascript:popU('frm_car_show_detail.php?id=<?php echo "$IDNO";?>','a0','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><u><?php echo "$IDNO"; ?></u></a></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$C_StartDate"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center">
            <?php if($ApointmentDate == "-"){ ?>
                <a href="#" onclick="javascript:popU('frm_car_add.php?cid=<?php echo "$IDCarTax";?>','a1','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="add.png" border="0" width="16" height="16" align="absmiddle" alt="เพิ่มวันนัด"></a>
            <?php }else{ ?>
                <a href="#" onclick="javascript:popU('frm_car_edit.php?cid=<?php echo "$IDCarTax";?>','a2','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="edit.png" border="0" width="16" height="16" align="absmiddle" alt="แก้ไขวันนัด"></a>
            <?php } ?>
        </td>
    </tr>
 <?php
        }
}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="7"><a href="frm_car_show_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
    </tr>                                                                      
<?php
}else{
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";   
}
 ?>
</table>