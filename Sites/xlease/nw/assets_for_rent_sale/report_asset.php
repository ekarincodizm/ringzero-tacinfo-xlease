<?php
$s_page = pg_escape_string($_GET['s_page']);
$type = pg_escape_string($_GET['type']);
$order = pg_escape_string($_GET['order']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายงานสินทรัพย์สำหรับเช่า-ขาย</title>
<link href="act.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
function show_data(){
	var s_page = $('#s_page').val();
	var display_type = $('input[name="displaytype"]:checked').val();
	var sort_by = $('#displayby').val();
	$.post('gen_asset_report.php',{type:display_type,order:sort_by,s_page:s_page},function(data){
		$('#div_display_data').html(data);
	});
}
function change_default(){
	$('#s_page').val('');
}
function print_pdf(){
	var display_type = $('input[name="displaytype"]:checked').val();
	var sort_by = $('#displayby').val();
	popU('print_report_asset.php?type='+display_type+'&order='+sort_by,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=600');
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	if (!newWindow.opener) newWindow.opener = self;
}
</script>
</head>

<body>
<input type="hidden" name="s_page" id="s_page" value="<?php echo $s_page; ?>" />
<div align="center">
	<div style="width:1224px;">
        <h2>(THCAP) รายงานสินทรัพย์สำหรับเช่า-ขาย</h2>
        <hr />
        <fieldset style="width:1200px; padding:10px;">
            <legend style="font-weight:bold;">เลือกรูปแบบการแสดงข้อมูล</legend>
            <div style="display:inline-block; width:20%;">
                <label>
                    <input type="radio" name="displaytype" id="displaytype1" value="all" onchange="change_default();" <?php if($type==""||$type=="all"){ echo "checked=\"checked\""; } ?> />
                    <span>แสดงสินค้าทั้งหมด</span>
                </label>
            </div>
            <div style="display:inline-block; width:20%;">
                <label>
                    <input type="radio" name="displaytype" id="displaytype2" value="active" onchange="change_default();" <?php if($type=="active"){ echo "checked=\"checked\""; } ?> />
                    <span>แสดงเฉพาะสินค้าที่คงเหลือในบริษัท</span>
                </label>
            </div>
            <div style="display:inline-block; width:20%;">
                <label>
                    <input type="radio" name="displaytype" id="displaytype3" value="unactive" onchange="change_default();" <?php if($type=="unactive"){ echo "checked=\"checked\""; } ?> />
                    <span>แสดงเฉพาะสินค้าที่ให้เช่า-ขายไปแล้ว</span>
                </label>
            </div>
            <div style="display:inline-block; width:20%;">
                <label>
                    <span>เรียงตาม</span>
                    <select name="displayby" id="displayby">
                        <option value="brand" <?php if($order=="brand"){ echo "selected=\"selected\""; } ?>>ชื่อยี่ห้อ</option>
                        <option value="model" <?php if($order=="model"){ echo "selected=\"selected\""; } ?>>ชื่อรุ่น</option>
                        <option value="astypeName" <?php if($order=="astypeName"){ echo "selected=\"selected\""; } ?>>ประเภทสินทรัพย์</option>
                        <option value="productCode" <?php if($order=="productCode"){ echo "selected=\"selected\""; } ?>>รหัสสินค้า</option>
                        <option value="secondaryID" <?php if($order=="secondaryID"){ echo "selected=\"selected\""; } ?>>รหัสสินค้ารอง</option>
                        <option value="pricePerUnit" <?php if($order=="pricePerUnit"){ echo "selected=\"selected\""; } ?>>ต้นทุน/ชิ้น</option>
                        <option value="VAT_value" <?php if($order=="VAT_value"){ echo "selected=\"selected\""; } ?>>ภาษีมูลค่าเพิ่ม</option>
                        <option value="ProductStatusName" <?php if($order=="ProductStatusName"){ echo "selected=\"selected\""; } ?>>สถานะสินค้า</option>
                        <option value="materialisticstatus" <?php if($order=="materialisticstatus"){ echo "selected=\"selected\""; } ?>>การมีอยู่ของสินค้า</option>
                        <option value="explanation" <?php if($order=="explanation"){ echo "selected=\"selected\""; } ?>>คำอธิบาย</option>
                    </select>
                </label>
            </div>
            <div style="display:inline-block; width:17%; text-align:right;">
                <input type="button" name="btn_display" id="btn_display" onclick="show_data();" value="แสดงข้อมูล" style="padding:5px 5px 5px 5px; background-color:#ffffff; cursor:pointer; border:solid 1px #dcdcdc; margin-right:5px; display:inline-block;" />
                <input type="button" name="btn_print" id="btn_print" value="พิมพ์รายงาน" onClick="print_pdf();" style="padding:5px 5px 5px 18px; background-color:#ffffff; background-image:url(images/print.png); background-attachment:scroll; background-position:2px center; background-repeat:no-repeat; cursor:pointer; border:solid 1px #dcdcdc; display:inline-block;" />
            </div>
        </fieldset>
        <div id="div_display_data" style="width:1224px; margin-top:15px;">
            
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	show_data();
});
</script>
</body>
</html>