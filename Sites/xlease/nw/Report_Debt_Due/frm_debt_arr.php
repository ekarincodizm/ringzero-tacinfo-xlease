<?php
include("../../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานยอดหนี้อื่นๆค้างชำระ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">

	
	function show_p(id,dtl){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('popup_arr_dtl.php?id='+id);
    $('#dialog').dialog({
        title: 'แสดงหนี้ค้างชำระของ '+id+'-'+dtl,
        resizable: false,
        modal: true,  
        width: 1024,
        height: 600,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
	$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.ab tr:hover td {
	background-color:#FF9;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<form method="post" name="form1" action="frm_Index.php">
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			     
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>(THCAP) รายงานยอดหนี้อื่นๆค้างชำระ</B></legend>
				<div align="center">
					<div class="ui-widget">
			
<?php
	
							$qry_debt_due=pg_query("select c.\"tpID\", sum(b.\"typePayLeft\") as total, c.\"tpDesc\" from thcap_temp_otherpay_debt b ,account.\"thcap_typePay\" c
where b.\"typePayID\" = c.\"tpID\" and b.\"debtStatus\"='1' group by c.\"tpID\", c.\"tpDesc\" order by c.\"tpID\", c.\"tpDesc\" "); 
							$row_debt_due = pg_num_rows($qry_debt_due);
?>
							<div>
							<div align="right"><a href="debt_arr_pdf.php" target="_blank"><span style="font-size:15px; color:#0000FF;">พิมพ์รายงาน <img src="images/icoPrint.png" alt="" width="17" height="14" /></span></a></div>
								<table class="ab" width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#999999">
									<thead>
									<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
                                    <th>ลำดับ</th>
										<th>ประเภทรับชำระ</th>
										<th>คำอธิบายประเภทรับชำระ</th>
										<th>ยอดรวมค้างชำระ</th>
										
									</tr>
									</thead>
<?php
							
									if($row_debt_due == 0)
									{
										echo "<tr><td colspan=4 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบข้อมูล-</b></td></tr>";
									}
									else
									{	
										$i = 0;
										while($res_fc = pg_fetch_array($qry_debt_due))
										{
											$i++;
											$typePayID =trim($res_fc["tpID"]);
											$total =trim($res_fc["total"]);
											$tpDesc =trim($res_fc["tpDesc"]);
											//$tpFullDesc =trim($res_fc["tpFullDesc"]);
											
											if($i%2==0){
												echo "<tr class=\"odd2\">";
											}else{
												echo "<tr class=\"even2\">";
											}
							
											echo "<td align=\"center\">$i</th>";
											echo "<td align=\"left\">$typePayID <u><a title=\"$typePayID - $tpDesc\" href=\"javascript:show_p('$typePayID','$tpDesc');\"  ><img src=\"images/full_page.png\" width=16 height=16 /></a></u></th>";
											echo "<td>$tpDesc</th>";
											echo "<td align=\"right\">".number_format($total,2)."</th>";
											echo "</tr>";
											
											$sum_total += $total; 
										}
										
										echo "<tr bgcolor=\"#ffb0e3\">";
										echo "<td align=\"right\" COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>รวมทั้งสิ้น</b></th>";
										echo "<td align=\"right\"> <b>".number_format($sum_total,2)." </b></th>";
										echo "</tr>";
									}
?>
								</table>

							</div>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>
</body>
</html>