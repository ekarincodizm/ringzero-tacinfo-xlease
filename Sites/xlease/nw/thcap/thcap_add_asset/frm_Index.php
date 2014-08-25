<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) ใส่รายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function operation_start(){
		var typeid = $("#cbx_pick_type").val();
		

		$.ajax({
			url:"add_asset_for_sales_"+typeid+".php",
			type:"HEAD",
			error: function()
			{
				$("#space1").text(" ขออภัย ขณะนี้ยังไม่มีหน้าต่างสำหรับสินค้าประเภทนี้ ");
			},
			success: function()
			{
				$("#space1").load("tb_asset_"+typeid+".php?astype="+typeid);	
			}
		});
		
}
</script>
</head>
<?php
//ดึงข้อมูลประเภทสินทรัพย์
	$qry_astype = pg_query("SELECT * FROM thcap_asset_biz_astype where \"astypeStatus\" = '1'");
?>
<body>
<div align="center">
	<div style="width:900px; display:block;">
        <h2>(THCAP) ใส่รายละเอียดสินทรัพย์สำหรับเช่า-ขาย</h2>
        <hr />
        <fieldset style="margin-bottom:15px;">
            <legend><b>เลือกประเภทสินทรัพย์</b></legend>
            <div style="margin:15px 0px; text-align:center;">
                <span style="display:inline-block">เลือกประเภท : </span>
                <select name="cbx_pick_type" id="cbx_pick_type" style="display:inline-block;" onchange="operation_start();">
						<option value="">--------- เลือกประเภท ---------</option>
					<?php while($re_astype = pg_fetch_array($qry_astype)){ 
							$astypeid = $re_astype["astypeID"]; //รหัสประเภท
							$astypeName = $re_astype["astypeName"]; //ชื่อประเภท
							
							
							//หาจำนวนรายละเอียดที่รออนุมัติของแต่ละประเภทสินทรัพย์
							$qry_fasset = pg_query("SELECT *
													FROM thcap_asset_biz_detail a
													WHERE a.\"astypeID\" = '$astypeid' AND a.\"assetDetailID\" NOT IN (
																		select \"assetDetailID\" from \"thcap_asset_biz_detail_central\" 
																		where \"statusapp\" in('1','0'))");
							$rows_fasset = pg_num_rows($qry_fasset);
							if($rows_fasset > 0){
								$numwait = "( $rows_fasset )";
								$colorbg = "style=\"color:blue\"";
							}else{
								$numwait = "";
								$colorbg = "";
							}
							
						echo "<option value=\"$astypeid\" $colorbg>$astypeName $numwait</option>";
							} 						
							?>	
                </select>

            </div>
        </fieldset>
    </div>
	<div id="space1"></div>
</div>
</body>
</html>