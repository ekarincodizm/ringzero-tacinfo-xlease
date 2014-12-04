<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) แก้ไขรายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function operation_start(){
		var typeid = $("#cbx_pick_type").val();
		
		if (typeid == '13' || typeid == '37' || typeid == '50' || typeid == '51' || typeid == '66' || typeid == '69' 
		|| typeid == '82' || typeid == '84' || typeid == '85' || typeid == '86' || typeid == '89' || typeid == '90'
		|| typeid == '91' || typeid == '93' || typeid == '94' || typeid == '95' || typeid == '96' || typeid == '97')
		{
			typeidText = 'car';
		}
		else
		{
			typeidText = typeid;
		}
		
         
		$.ajax({
			url:"edit_asset_for_sales_"+typeidText+".php",
			type:"HEAD",
			error: function()
			{
				$("#space1").text(" ขออภัย ขณะนี้ยังไม่มีหน้าต่างสำหรับสินค้าประเภทนี้ ");
			},
			success: function()
			{
				$("#space1").load("edit_tb_asset_"+typeidText+".php?astype="+typeid);	
			}
		});
		
}
</script>
</head>
<?php
//ดึงข้อมูลประเภทสินทรัพย์
	$qry_astype = pg_query("SELECT * FROM thcap_asset_biz_astype where \"astypeStatus\" = '1' order by \"astypeName\" ");
?>
<body>
<div align="center">
	<div style="width:900px; display:block;">
        <h2>(THCAP) แก้ไขรายละเอียดสินทรัพย์สำหรับเช่า-ขาย</h2>
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
							
						echo "<option value=\"$astypeid\">$astypeName </option>";
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