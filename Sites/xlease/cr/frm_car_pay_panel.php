<?php 
include("../config/config.php"); 
//$mm = pg_escape_string($_GET['mm']);
//$yy = pg_escape_string($_GET['yy']);
$w = pg_escape_string($_GET['w']);
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
//if( !empty($mm) and !empty($yy) ){

        $qry_name=pg_query("SELECT A.*,B.* FROM carregis.\"CarTaxDue\" A
        INNER JOIN \"VContact\" B on A.\"IDNO\"=B.\"IDNO\" 
        WHERE \"ApointmentDate\" is not null AND A.\"BookIn\"='false' AND (B.\"C_REGIS\" like '%$w%' OR B.\"car_regis\" like '%$w%') ORDER BY A.\"IDNO\" ASC ");
    
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $TaxValue = $res_name["TaxValue"];
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
                if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"];
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
			$C_StartDate = $res_name2["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate)); 
			
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        } 
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$C_StartDate"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center"><a href="#" onclick="javascript:popU('frm_car_pay_add.php?cid=<?php echo "$IDCarTax";?>','a<?php echo $IDCarTax; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="add.png" border="0" width="16" height="16" align="absmiddle" alt="เพิ่มการชำระเงิน"></a> 
            <?php if(empty($TaxValue)){ ?>
                
            <?php }//else{ ?>
                <!--<a href="frm_car_pay_edit.php?cid=<?php echo "$IDCarTax";?>"><img src="edit.png" border="0" width="16" height="16" align="absmiddle" alt=""></a>-->
            <?php //} ?>
        </td>
    </tr>
 <?php
        }
//}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="7"><a href="frm_car_pay_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
    </tr>                                                                      
<?php }else{
    echo "<tr><td align=center colspan=10>- ไม่พบข้อมูล -</td></tr>";
} ?>
</table>