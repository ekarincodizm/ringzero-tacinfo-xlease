<?php
include("../../config/config.php");
$realpath = redirect($_SERVER['PHP_SELF'],'nw/thcap/show_remark.php'); 

$contractID=$_GET["contractID"];
?>
<link href="list_tab.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('#list_tab_menu').load('list_tab_debtshow.php?tab_id=1&contractID='+'<?php echo $contractID?>');
	
	$('#showdebt').load('tab_debt.php',function(){
		list_tab_menu(1);
	});

});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');
	$('.list_tab_menu').load('list_tab_debtshow.php?tabid='+tab_id+'&contractID='+'<?php echo $contractID?>');
	
}
function selectAll(select){
    with (document.frm)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
</script>
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
<div style="width:950px;margin:0px auto;">  
	<div style="padding-top:10px;"><?php include('../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
	<div style="padding-top:10px;"><?php include('../thcap/frm_debtwaitApp.php'); //รายการขอตั้งหนี้ที่รออนุมัติ ?></div>
	<div style="padding:10px;">
		<!--หนี้ทั้งหมดที่มี-->
		<form method="post" name="frm" action="frm_reportdebt_adddate.php">
		<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
		<tr>
			<td>
				<div id="showdebt"><div>
			</td>
		</tr>
		</table>
		<div style="padding:10px;text-align:center;"><input type="hidden" name="contractID"value="<?php echo $contractID;?>"><input type="submit" value="บันทึกข้อมูล" onclick="return confirm('ยืนยันข้อมูลอีกครั้ง')"><input type="reset" value="ยกเลิกรายการ"></div>
		</form>
	</div>
</div>

