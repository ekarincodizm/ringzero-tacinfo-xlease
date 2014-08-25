<?php
include("../config/config.php");

$id = pg_escape_string($_GET['id']);
$fieldedit = "";

//ข้อมูลใหม่
$qry = pg_query("SELECT * FROM insure.batch WHERE \"id\"='$id' AND \"type\"='N' AND \"approve_id\" IS NULL ");
if($res = pg_fetch_array($qry)){
    $InsID = $res['InsID'];//
    $Company = $res['Company'];//
    $StartDate = $res['StartDate'];//
    $EndDate = $res['EndDate'];//
    $Code = $res['Code'];//
    $InsMark = $res['InsMark'];//
    $Capacity = $res['Capacity'];//
    
    $Kind = $res['Kind'];//
    $Invest = $res['Invest'];//
    $Premium = $res['Premium'];//
    $Discount = $res['Discount'];//
    $CollectCus = $res['CollectCus'];//
    $InsUser = $res['InsUser'];//
    
    $NetPremium = $res['NetPremium'];
    $TaxStamp = $res['TaxStamp'];
    $Vat = $res['Vat'];
	
	if($Code==""){
		$qry_code=pg_query("select \"Code\" from insure.\"InsureForce\" where \"InsFIDNO\"='$id'");
		$rescode=pg_fetch_array($qry_code);
		$Code2=$rescode["Code"];
	}
}

//ข้อมูลเก่า
$qry_old = pg_query("SELECT * FROM insure.batch WHERE \"id\"='$id' AND \"type\"='O' AND \"approve_id\" IS NULL ");
if($res_old = pg_fetch_array($qry_old)){
    $InsID_old = $res_old['InsID'];//
    $Company_old = $res_old['Company'];//
    $StartDate_old = $res_old['StartDate'];//
    $EndDate_old = $res_old['EndDate'];//
    $Code_old = $res_old['Code'];//
    $InsMark_old = $res_old['InsMark'];//
    $Capacity_old = $res_old['Capacity'];//
    
    $Kind_old = $res_old['Kind'];//
    $Invest_old = $res_old['Invest'];//
    $Premium_old = $res_old['Premium'];//
    $Discount_old = $res_old['Discount'];//
    $CollectCus_old = $res_old['CollectCus'];//
    $InsUser_old = $res_old['InsUser'];//
    
    $NetPremium_old = $res_old['NetPremium'];
    $TaxStamp_old = $res_old['TaxStamp'];
    $Vat_old = $res_old['Vat'];
	
	if($Code_old==""){
		$qry_code_old=pg_query("select \"Code\" from insure.\"InsureForce\" where \"InsFIDNO\"='$id'");
		$rescode_old=pg_fetch_array($qry_code_old);
		$Code2_old=$rescode_old["Code"];
	}
}
?>
<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr>
    <td width="150"><b>กำลังแก้ไข ID</b></td>
    <td><span style="color:red"><?php echo $id; ?></span> </td>
</tr>

<tr>
    <td colspan="2"><br /><u><b>ข้อมูลที่ต้องการแก้ไข</b></u> (ข้อมูลในวงเล็บคือข้อมูลเก่าก่อนทำการแก้ไข)</td>
</tr>

<?php if(!empty($Company)){ $fieldedit .= "Company,"; ?>
<tr>
    <td><b>บริษัทประกัน</b></td>
    <td><?php echo "<b>$Company</b> ($Company_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($InsID) AND substr($id,0,1)=="F"){ $fieldedit .= "InsID,"; ?>
<tr>
    <td><b>เลขกรมธรรม์</b></td>
    <td><?php echo "<b>$InsID</b> ($InsID_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($InsMark)){ $fieldedit .= "InsMark,"; ?>
<tr>
    <td><b>เลขเครื่องหมาย</b></td>
    <td><?php echo "<b>$InsMark</b> ($InsMark_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($Code)){ $fieldedit .= "Code,"; ?>
<tr>
    <td><b><?php if(substr($id,0,1) == "L"){ echo "จำนวนงวด" ;}else{ echo "ประเภท";}?></b></td>
    <td><?php echo "<b>$Code</b> ($Code_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($StartDate)){ $fieldedit .= "StartDate,"; ?>
<tr>
    <td><b>วันที่เริ่ม</b></td>
    <td><?php echo "<b>$StartDate</b> ($StartDate_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($EndDate)){ $fieldedit .= "EndDate,"; ?>
<tr>
    <td><b>วันที่หมดอายุ</b></td>
    <td><?php echo "<b>$EndDate</b> ($EndDate_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($Capacity)){ $fieldedit .= "Capacity,"; ?>
<tr>
    <?php
		if($Code==""){
			$Code=$Code2;
		}
		if($Code=="1.400" || $Code=="1.401" || $Code=="1.402" || $Code=="1.403" || $Code=="1.420" || $Code=="1.421"){
			echo "<td><b>น้ำหนักรวม (กก.)</b></td>";
		}else if($Code=="1.200" || $Code=="1.201" || $Code=="1.202" || $Code=="1.203"){
			echo "<td><b>จำนวนที่นั่ง</b></td>";
		}else{
			echo "<td><b>ขนาดเครื่องยนต์</b></td>";
		}
	?>
    <td><?php echo "<b>$Capacity</b> ($Capacity_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($Discount)){ $fieldedit .= "Discount,"; ?>
<tr>
    <td><b>ส่วนลด</b></td>
    <td><?php echo "<b>".number_format($Discount,2)."</b> (".number_format($Capacity_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($Kind)){ $fieldedit .= "Kind,"; ?>
<tr>
    <td><b>ประเภทประกัน</b></td>
    <td><?php echo "<b>$Kind</b> ($Kind_old)"; ?></td>
</tr>
<?php } 
?>

<?php if($Invest!="" OR !empty($Invest)){ $fieldedit .= "Invest,"; ?>
<tr>
    <td><b>ทุนประกัน</b></td>
    <td><?php echo "<b>".number_format($Invest,2)."</b> (".number_format($Invest_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($Premium)){ $fieldedit .= "Premium,"; ?>
<tr>
    <td><b>ค่าเบี้ยประกัน</b></td>
    <td><?php echo "<b>".number_format($Premium,2)."</b> (".number_format($Premium_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($NetPremium)){ $fieldedit .= "NetPremium,"; ?>
<tr>
    <td><b>NetPremium</b></td>
    <td><?php echo "<b>".number_format($NetPremium,2)."</b> (".number_format($NetPremium_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($TaxStamp)){ $fieldedit .= "TaxStamp,"; ?>
<tr>
    <td><b>TaxStamp</b></td>
    <td><?php echo "<b>".number_format($TaxStamp,2)."</b> (".number_format($TaxStamp_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($Vat)){ $fieldedit .= "Vat,"; ?>
<tr>
    <td><b>Vat</b></td>
    <td><?php echo "<b>".number_format($Vat,2)."</b> (".number_format($Vat_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($CollectCus)){ $fieldedit .= "CollectCus,"; ?>
<tr>
    <td><b>เบี้ยที่เก็บลูกค้า</b></td>
    <td><?php echo "<b>".number_format($CollectCus,2)."</b> (".number_format($CollectCus_old).")"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($InsID) AND (substr($id,0,1)=="U" OR substr($id,0,1)=="L")){ $fieldedit .= "InsID,"; ?>
<tr>
    <td><b>เลขรับแจ้ง</b></td>
    <td><?php echo "<b>".$InsID."</b> ($InsID_old)"; ?></td>
</tr>
<?php } ?>

<?php if(!empty($InsUser)){ $fieldedit .= "InsUser,"; ?>
<tr>
    <td><b>ผู้รับแจ้ง</b></td>
    <td><?php echo "<b>".$InsUser."</b> ($InsUser_old)"; ?></td>
</tr>
<?php } ?>

</table>

<div align="right"><hr /><input type="button" name="btnsave" id="btnsave" value="ยืนยันการแก้ไข"></div>

<script type="text/javascript">
    $('#btnsave').click(function(){
        $.post('approve_edit_save.php',{
            id: '<?php echo $id; ?>',
            fieldedit: '<?php echo substr($fieldedit,0,strlen($fieldedit)-1); ?>'
        },
        function(data){
            if(data.success){
                $('#dialogdetail').remove();
                alert(data.message);
                $("#panel").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
                $("#panel").load("approve_edit_panel.php");
            }else{
                alert(data.message);
            }
        },'json');
    });
</script>