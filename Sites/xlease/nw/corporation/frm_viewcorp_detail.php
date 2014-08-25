<?php
include("../../config/config.php");

	$user_id = $_SESSION["av_iduser"];
	
	$corp_regis=pg_escape_string($_GET['corp_regis']);
	$view=pg_escape_string($_GET['view']);
	$editable = pg_escape_string($_GET['editable']);
	$v_corpedit = pg_escape_string($_GET["corpedit"]);
	
	$lastest_edit = "";
	//----- ข้อมูลนิติบุคคล
	if($view == 2) // ถ้าดูข้อมูลที่ไม่อนุมัติ
	{
		// หาจำนวนที่แก้ไขครั้งล่าสุด
		$query_maxedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ");
		while($res_maxedit = pg_fetch_array($query_maxedit))
		{
			$maxedit = $res_maxedit["maxedit"];
		}
	
		$query_corp = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" is null ");
	}
	elseif($view == 3) // ถ้าดูข้อมูลที่รออนุมัติ
	{
		// หาจำนวนที่แก้ไขครั้งล่าสุด
		if($v_corpedit=="")
		{
			$query_maxedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" = 'false' and \"hidden\" = 'false' ");
			while($res_maxedit = pg_fetch_array($query_maxedit))
			{
				$maxedit = $res_maxedit["maxedit"];
			}
		}
		else
		{
			$maxedit = $v_corpedit;
		}
		
		$query_corp = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'false' ");
	}
	else // ถ้าเป็นการหาจากข้่อมูลปกติ (ลูกค้านิติบุคคลที่อนุมัติแล้วจากตาราง th_corp)
	{
		$query_corp = pg_query("select * from public.\"th_corp\" where \"corp_regis\" = '$corp_regis' ");
	}
	
	$row1=pg_num_rows($query_corp);
	
	while($result_corp = pg_fetch_array($query_corp))
	{
		$corpID = $result_corp["corpID"];
		$corpType = $result_corp["corpType"]; // ประเภทนิติบุคคล
		$corpName_THA = $result_corp["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
		$corpName_ENG = $result_corp["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
		$trade_name = $result_corp["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
		$TaxNumber = $result_corp["TaxNumber"]; // เลขที่ประจำตัวผู้เสียภาษี
		$phone = $result_corp["phone"]; // โทรศัพท์
		$Fax = $result_corp["Fax"]; // Fax
		$mail = $result_corp["mail"];
		$website = $result_corp["website"];
		$date_of_corp = $result_corp["date_of_corp"]; // วันที่จดทะเบียนบริษัท
		$initial_capital = $result_corp["initial_capital"]; // ทุนจดทะเบียนเริ่มแรก
		$authority = $result_corp["authority"]; // ผู้มีอำนาจการทำรายการของบริษัท
		$current_capital = $result_corp["current_capital"]; // ทุนจดทะเบียนปัจจุบัน
		$asset_avg = $result_corp["asset_avg"]; // สินทรัพย์เฉลี่ย
		$revenue_avg = $result_corp["revenue_avg"]; // รายได้เฉลี่ย
		$debt_avg = $result_corp["debt_avg"]; // หนี้สินเฉลี่ย
		$net_profit = $result_corp["net_profit"]; // กำไรสุทธิ
		$date_of_last_data = $result_corp["date_of_last_data"]; // วันที่ของข้อมูลล่าสุด
		$trends_profit = $result_corp["trends_profit"]; // แนวโน้มกำไร
		$BusinessType = $result_corp["BusinessType"]; // ประเภทธุรกิจ
		$IndustypeID = $result_corp["IndustypeID"]; // รหัสประเภทอุตสาหกรรม
		$explanation = $result_corp["explanation"]; // คำอธิบายกิจการ
		$CountryCode = $result_corp["CountryCode"]; // รหัสสัญชาติ หรือ รหัสประเทศ
		
		$phone = str_replace("#"," ต่อ ",$phone);
		
		if($IndustypeID == 0)
		{
			$IndustypeName = "ไม่ระบุ";
		}
		else
		{
			$query_Industype = pg_query("select \"IndustypeName\" from public.\"th_corp_industype\" where \"IndustypeID\" = '$IndustypeID' ");
			while($result_Industype = pg_fetch_array($query_Industype))
			{
				$IndustypeName = $result_Industype["IndustypeName"];
			}
		}
		
		if($CountryCode != "")
		{
			$qry_country = pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$CountryCode' ");
			$CountryName_THAI = pg_fetch_result($qry_country,0);
		}
	}
	
	//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ตามหนังสือรับรอง
	if($view == 2)
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '1' and \"addsEdit\" = '$maxedit' and \"Approved\" is null ");
	}
	elseif($view == 3)
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '1' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'false' ");
	}
	else
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '1' ");
	}
	
	$row2=pg_num_rows($query_adds);
	
	while($result_corp = pg_fetch_array($query_adds))
	{
		$C_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
		$C_floor = $result_corp["floor"]; // จำนวนชั้น
		$C_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
		$C_room = $result_corp["room"]; // หมายเลขห้อง
		$C_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
		$C_Moo = $result_corp["Moo"]; // หมู่ที่
		$C_Building = $result_corp["Building"]; // อาคาร/สถานที่
		$C_Village = $result_corp["Village"]; // หมู่บ้าน
		$C_Lane = $result_corp["Lane"]; // ซอย
		$C_Road = $result_corp["Road"]; // ถนน
		$C_District = $result_corp["District"]; // แขวง/ตำบล
		$C_State = $result_corp["State"]; // เขต/อำเภอ
		$C_Province = $result_corp["ProvinceID"]; // จังหวัด
		$C_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
		$C_Country = $result_corp["Country"]; // ประเทศ
		$C_phone = $result_corp["phone"]; // โทรศัพท์
		$C_Fax = $result_corp["Fax"]; // โทรสาร
		$C_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
		$C_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
		$C_Acquired = $result_corp["Acquired"]; // ได้มาโดย
		$C_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
		
		$C_phone = str_replace("#"," ต่อ ",$C_phone);
		
		if($C_floor != "")
		{
			$C_addsStyle = "$C_addsStyle $C_floor ชั้น";
		}
		
		if($C_Province != "")
		{
			$query_C_Province_name = pg_query("select \"proName\" from public.\"nw_province\" where \"proID\" = '$C_Province'");
			while($res_C_Province_name = pg_fetch_array($query_C_Province_name))
			{
				$C_Province = $res_C_Province_name["proName"];
			}
		}
	}
	
	// หาสิทธิการใช้งานเมนู "แก้ไขข้อมูลลูกค้า"
	$qry_claim = pg_query("select * from \"f_usermenu\" where \"id_user\" = '$user_id' and \"id_menu\" = 'P40' and \"status\" = true ");
	$row_claim = pg_num_rows($qry_claim);
	
	//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่สำนักงานใหญ่
	if($view == 2)
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '2' and \"addsEdit\" = '$maxedit' and \"Approved\" is null ");
	}
	elseif($view == 3)
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '2' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'false' ");
	}
	else // ถ้าเป็นการดูแบบลูกค้าที่อนุมัติแล้ว
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '2' ");
	}
	
	$row3=pg_num_rows($query_adds);
	
	while($result_corp = pg_fetch_array($query_adds))
	{
		$H_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
		$H_floor = $result_corp["floor"]; // จำนวนชั้น
		$H_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
		$H_room = $result_corp["room"]; // หมายเลขห้อง
		$H_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
		$H_Moo = $result_corp["Moo"]; // หมู่ที่
		$H_Building = $result_corp["Building"]; // อาคาร/สถานที่
		$H_Village = $result_corp["Village"]; // หมู่บ้าน
		$H_Lane = $result_corp["Lane"]; // ซอย
		$H_Road = $result_corp["Road"]; // ถนน
		$H_District = $result_corp["District"]; // แขวง/ตำบล
		$H_State = $result_corp["State"]; // เขต/อำเภอ
		$H_Province = $result_corp["ProvinceID"]; // จังหวัด
		$H_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
		$H_Country = $result_corp["Country"]; // ประเทศ
		$H_phone = $result_corp["phone"]; // โทรศัพท์
		$H_Fax = $result_corp["Fax"]; // โทรสาร
		$H_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
		$H_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
		$H_Acquired = $result_corp["Acquired"]; // ได้มาโดย
		$H_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
		
		$H_phone = str_replace("#"," ต่อ ",$H_phone);
		
		if($H_floor != "")
		{
			$H_addsStyle = "$H_addsStyle $H_floor ชั้น";
		}
		
		if($H_Province != "")
		{
			$query_H_Province_name = pg_query("select \"proName\" from public.\"nw_province\" where \"proID\" = '$H_Province'");
			while($res_H_Province_name = pg_fetch_array($query_H_Province_name))
			{
				$H_Province = $res_H_Province_name["proName"];
			}
		}
	}
	
	//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
	if($view == 2)
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '3' and \"addsEdit\" = '$maxedit' and \"Approved\" is null ");
	}
	elseif($view == 3)
	{
		$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '3' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'false' ");
	}
	else // ถ้าเป็นการดูแบบลูกค้าที่อนุมัคิแล้ว
	{
		$qr_last_edit = pg_query("select max(\"doerStamp\") as last_edit from \"th_corp_temp\" where \"Approved\"='true' and \"corp_regis\"='$corp_regis'");
		$rs_last_edit = pg_fetch_array($qr_last_edit);
		$lastest_edit = "วันที่แก้ไขข้อมูลครั้งล่าสุด : ".$rs_last_edit["last_edit"];
		$div_show_last_edit = "
			<div align=\"center\">
				<div style=\"padding:0.2em; text-align:right; font-family:tahoma; color : #ff0000; font-size:14px; font-weight: bold;\">$lastest_edit</div>
			</div>
		";
		$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '3' ");
	}
	
	$row4=pg_num_rows($query_adds);
	
	while($result_corp = pg_fetch_array($query_adds))
	{
		$M_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
		$M_floor = $result_corp["floor"]; // จำนวนชั้น
		$M_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
		$M_room = $result_corp["room"]; // หมายเลขห้อง
		$M_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
		$M_Moo = $result_corp["Moo"]; // หมู่ที่
		$M_Building = $result_corp["Building"]; // อาคาร/สถานที่
		$M_Village = $result_corp["Village"]; // หมู่บ้าน
		$M_Lane = $result_corp["Lane"]; // ซอย
		$M_Road = $result_corp["Road"]; // ถนน
		$M_District = $result_corp["District"]; // แขวง/ตำบล
		$M_State = $result_corp["State"]; // เขต/อำเภอ
		$M_Province = $result_corp["ProvinceID"]; // จังหวัด
		$M_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
		$M_Country = $result_corp["Country"]; // ประเทศ
		$M_phone = $result_corp["phone"]; // โทรศัพท์
		$M_Fax = $result_corp["Fax"]; // โทรสาร
		$M_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
		$M_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
		$M_Acquired = $result_corp["Acquired"]; // ได้มาโดย
		$M_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
		
		$M_phone = str_replace("#"," ต่อ ",$M_phone);
		
		if($M_floor != "")
		{
			$M_addsStyle = "$M_addsStyle $M_floor ชั้น";
		}
		
		if($M_Province != "")
		{
			$query_M_Province_name = pg_query("select \"proName\" from public.\"nw_province\" where \"proID\" = '$M_Province'");
			while($res_M_Province_name = pg_fetch_array($query_M_Province_name))
			{
				$M_Province = $res_M_Province_name["proName"];
			}
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายละเอียดลูกค้านิติบุคคล</title>

<script language="javascript" type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" /> 
<script language="javascript" type="text/javascript" src="js/jquery.coolfieldset.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.coolfieldset.css" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<script>	
	$(function() {
		$( "#tabs" ).tabs();
	});
	$(function() {
		$( ".viewsign button:first" ).button({
            icons: {
                primary: "ui-icon-zoomin"
            },
            text: false
		});
	});

</script>
<style type="text/css">
.viewCustomerInfo #tabs .ui-tabs-nav.ui-helper-reset.ui-helper-clearfix.ui-widget-header.ui-corner-all .ui-state-default.ui-corner-top.ui-tabs-selected.ui-state-active {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #eb8f00;
	text-decoration: none;
}
.viewCustomerInfo #tabs .ui-tabs-nav.ui-helper-reset.ui-helper-clearfix.ui-widget-header.ui-corner-all .ui-state-default.ui-corner-top {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #1c94c4;
	text-decoration: none;
}
#tabs
{
	background-color:#fff;
}
.viewCustomerInfo #tabs #tabs-1 {
	background-color: #fff;
}
.ui-widget-content {
	background-color: #fff;
}
.viewCustomerInfo #tabs {
	background-color: #fff;
}
.ui-tabs-panel.ui-widget-content.ui-corner-bottom
{
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #555;
	text-decoration: none;	
}

.viewCustomerInfo {
	width: 950px;
}
body {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #555;
	text-decoration: none;
}
</style>
<!---- หน้าต่าง Popup รูปภาพ ---->

<!-- Add jQuery library -->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		
			$('.fancyboxa').fancybox({
				minWidth: 450,
				maxWidth: 450
						
			});
			$('.fancyboxb').fancybox({	
				minWidth: 450,
				maxWidth: 450
			  });
			
			$(".pdforpic").fancybox({
			   minWidth: 500,
			   maxWidth: 800,
			   'height' : '600',
			   'autoScale' : true,
			   'transitionIn' : 'none',
			   'transitionOut' : 'none',
			   'type' : 'iframe'
			});
			
			$('#tabs ul li a').click(function(){
				change_title();
			});
			
			change_title();

		});
		
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

function change_title(){
	var tab_value = $('.ui-tabs-selected').find('a').html();
	var cor_name = $('input[name="corp_regis"]').val();
	if(tab_value!='บันทึกการติดตาม')
	{
		document.title = 'รายละเอียดลูกค้านิติบุคคล_'+cor_name+'_'+tab_value;
	}
}

function printpage(){
	var tab_value = $('.ui-tabs-selected').find('a').html();
	var cor_name = $('input[name="corpName_THA"]').val();
	if(tab_value!='บันทึกการติดตาม')
	{
		document.title = cor_name+'_'+tab_value+'.pdf';
	}
	
	window.print();

}



	</script>
</head>

<body>
<center>
<div class="viewCustomerInfo">
	<?php
		echo $div_show_last_edit;
	?>
    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">ข้อมูลบริษัท</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#tabs-2">กรรมการและผู้รับมอบอำนาจ</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#tabs-3">ผู้ถือหุ้น</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#tabs-4">ผู้ติดต่อ</a></li>
			<li class="ui-state-default ui-corner-top" bgcolor=""><a href="#1" style="cursor:pointer;" onClick="javascipt:popU('follow_cus.php?corpID=<?php echo $corpID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">บันทึกการติดตาม</a></li>			
		</ul>

        <div class="ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">
        	<div style="display:block; width:100%; text-align:right;">
					<?php
		// ถ้ามีสิทธิแก้ไขข้อมูลลูกค้า
		if($row_claim > 0)
		{
		?>
			<input type="button" name="popUp" value="ขอแก้ไขข้อมูลลูกค้า" onclick="javascript:popU('frm_EditCorpAll.php?corpID=<?php echo $corpID; ?>&editcorp=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');" style="cursor:pointer;"></input>
		<?php
		}
		?>
            	<input type="button" name="btn_print_p" id="btn_print_p" value="พิมพ์หน้านี้" onClick="printpage();" style="margin-right:20px;" />
            </div>
            <center>
            <?php
			echo $v_corpedit;
			if($view == 3&&$editable!="f")
			{
			?>
            <!--<form name="frm1" method="post" action="frm_EditCorpAll.php?editcorp=2&corpID=<?php //echo $corpID; ?>">-->
			<form name="frm1" method="post" action="frm_EditCorpAll.php?corp_regis=<?php echo $corp_regis; ?>">
            <?php
			}
			else
			{
			?>
            <!--<form name="frm1" method="post" action="frm_EditCorpAll.php?corp_regis=<?php //echo $corp_regis; ?>">-->
			<form name="frm1" method="post" action="frm_EditCorpAll.php?editcorp=2&corpID=<?php echo $corpID; ?>">
            <?php
			}
			?>
            <table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td align="center">
                        <fieldset class="coolfieldset" id="fs1"><legend><B>ลูกค้านิติบุคคล</B></legend>
                        <div>
                        <center>
                            <table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF" class="customerInfo">
                                <tr>
                                    <td align="right">ชื่อนิติบุคคลภาษาไทย :</td><td><input type="text" name="corpName_THA" size="25" value="<?php echo $corpName_THA; ?>" disabled></td>
                                    <td align="right">ชื่อนิติบุคคลภาษาอังกฤษ :</td><td><input type="text" name="corpName_ENG" size="25" value="<?php echo $corpName_ENG; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">ชื่อย่อ/เครื่องหมายทางการค้า :</td><td><input type="text" name="trade_name" size="25" value="<?php echo $trade_name; ?>" disabled></td>
                                    <td align="right">ประเภทนิติบุคคล :</td><td><input type="text" size="25" value="<?php echo $corpType; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">เลขทะเบียนนิติบุคคล(13 หลัก) :</td><td><input type="text" name="corp_regis" size="25" value="<?php echo $corp_regis; ?>" disabled></td>
                                    <td align="right">เลขที่ประจำตัวผู้เสียภาษี(10 หลัก) :</td><td><input type="text" name="TaxNumber" size="25" value="<?php echo $TaxNumber; ?>" disabled></td>
                                </tr>
								<tr>
                                    <td align="right">สัญชาตินิติบุคคล :</td><td><input type="text" name="corpNationality" size="25" value="<?php echo $CountryName_THAI; ?>" disabled></td>
                                    <td></td><td></td>
                                </tr>
                                <tr>
                                    <td align="right">โทรศัพท์ :</td>
                                    <td>
                                        <input type="text" name="phone" size="25" value="<?php echo $phone; ?>" disabled>
                                    </td>
                                    <td align="right">โทรสาร :</td><td><input type="text" name="Fax" size="25" value="<?php echo $Fax; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">E-mail :</td><td><input type="text" name="mail" size="25" value="<?php echo $mail; ?>" disabled></td>
                                    <td align="right">Website :</td><td><input type="text" name="website" size="25" value="<?php echo $website; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">วันที่จดทะเบียนบริษัท :</td><td><input type="text" name="datepicker_regis" id="datepicker_regis" value="<?php echo $date_of_corp; ?>" disabled style="text-align:center" size="15"></td>
                                    <td align="right">ทุนจดทะเบียนเริ่มแรก :</td><td><input type="text" name="initial_capital" size="25" value="<?php if($initial_capital != ""){echo number_format($initial_capital,2);} ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td valign="top" align="right">ผู้มีอำนาจการทำรายการของบริษัท :</td><td colspan="3"><textarea name="authority" cols="70" rows="2" readonly><?php echo $authority; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td align="right">วันที่ของข้อมูลล่าสุด :</td><td><input type="text" name="datepicker_last" id="datepicker_last" value="<?php echo $date_of_last_data; ?>" disabled style="text-align:center" size="15"></td>
                                    <td align="right">ทุนจดทะเบียนปัจจุบัน :</td><td><input type="text" name="current_capital" size="25" value="<?php if($current_capital != ""){echo number_format($current_capital,2);} ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">สินทรัพย์เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="asset_avg" size="25" value="<?php if($asset_avg != ""){echo number_format($asset_avg,2);} ?>" disabled></td>
                                    <td align="right">รายได้เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="revenue_avg" size="25" value="<?php if($revenue_avg != ""){echo number_format($revenue_avg,2);} ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">หนี้สินเฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="debt_avg" size="25" value="<?php if($debt_avg != ""){echo number_format($debt_avg,2);} ?>" disabled></td>
                                    <td align="right">กำไรสุทธิ(3 ปีล่าสุด) :</td><td><input type="text" name="net_profit" size="25" value="<?php if($net_profit != ""){echo number_format($net_profit,2);} ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">แนวโน้มกำไร :</td><td><input type="text" name="trends_profit" size="25" value="<?php echo $trends_profit; ?>" disabled></td>
                                    <td align="right">ประเภทธุรกิจ :</td><td><input type="text" name="BusinessType" size="25" value="<?php echo $BusinessType; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td align="right">ประเภทอุตสาหกรรม :</td><td><input type="text" name="IndustypeID" size="25" value="<?php echo $IndustypeName; ?>" disabled></td>
                                    <td></td><td></td>
                                </tr>
                                <tr>
                                    <td valign="top" align="right">คำอธิบายกิจการ :</td><td colspan="3"><textarea name="explanation" cols="70" rows="2" readonly><?php echo $explanation; ?></textarea></td>
                                </tr>
                            </table>
                        </center>
                        </div>
                        </fieldset>
                        
                      <br>
                        
                        <fieldset class="coolfieldset" id="fs2"><legend><B>ที่อยู่ตามหนังสือรับรอง</B></legend>
                        <div>
                        <center>
                                <table class="customerInfo">
                                    <tr>
                                        <td align="right" width="100">บ้านเลขที่ :</td><td><input type="text" name="C_HomeNumber" size="25" value="<?php echo $C_HomeNumber; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right" width="100">ห้อง :</td><td><input type="text" name="C_room" size="25" value="<?php echo $C_room; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right" width="100">ชั้น :</td><td><input type="text" name="C_LiveFloor" size="25" value="<?php echo $C_LiveFloor; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">หมู่ที่ :</td><td><input type="text" name="C_Moo" size="25" value="<?php echo $C_Moo; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="C_Building" size="25" value="<?php echo $C_Building; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">หมู่บ้าน :</td><td><input type="text" name="C_Village" size="25" value="<?php echo $C_Village; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ซอย :</td><td><input type="text" name="C_Lane" size="25" value="<?php echo $C_Lane; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">ถนน :</td><td><input type="text" name="C_Road" size="25" value="<?php echo $C_Road; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">แขวง/ตำบล :</td><td><input type="text" name="C_District" size="25" value="<?php echo $C_District; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">เขต/อำเภอ :</td><td><input type="text" name="C_State" size="25" value="<?php echo $C_State; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">จังหวัด :</td><td><input type="text" name="C_Province" size="25" value="<?php echo $C_Province; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="C_Postal_code" size="25" value="<?php echo $C_Postal_code; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ประเทศ :</td><td><input type="text" name="C_Country" size="25" value="<?php echo $C_Country; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">โทรศัพท์ :</td><td><input type="text" name="C_phone" size="25" value="<?php echo $C_phone; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">เบอร์ FAX :</td><td><input type="text" name="C_Fax" size="25" value="<?php echo $C_Fax; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="C_Live_it" size="23" value="<?php echo $C_Live_it; ?>" disabled> ปี</td>
                                        <td width="30"></td>
                                        <td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="C_Completion" size="25" value="<?php echo $C_Completion; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">ได้มาโดย :</td><td><input type="text" name="C_Acquired" size="25" value="<?php echo $C_Acquired; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="C_purchase_price" size="20" value="<?php if($C_purchase_price != ""){echo number_format($C_purchase_price,2);} ?>" disabled> บาท</td>
                                        <td width="30"></td>
                                        <td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $C_addsStyle; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td></td>
                                    </tr>
                                </table>
                        </center>
                        </div>
                        </fieldset>
                        
                        <br>
                        
                        <fieldset class="coolfieldset" id="fs3"><legend><B>ที่อยู่สำนักงานใหญ่</B></legend>
                        <div>
                        <center>
                                <table class="customerInfo">
                                    <tr>
                                        <td align="right" width="100">บ้านเลขที่ :</td><td><input type="text" name="H_HomeNumber" size="25" value="<?php echo $H_HomeNumber; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right" width="100">ห้อง :</td><td><input type="text" name="H_room" size="25" value="<?php echo $H_room; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right" width="100">ชั้น :</td><td><input type="text" name="H_LiveFloor" size="25" value="<?php echo $H_LiveFloor; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">หมู่ที่ :</td><td><input type="text" name="H_Moo" size="25" value="<?php echo $H_Moo; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="H_Building" size="25" value="<?php echo $H_Building; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">หมู่บ้าน :</td><td><input type="text" name="H_Village" size="25" value="<?php echo $H_Village; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ซอย :</td><td><input type="text" name="H_Lane" size="25" value="<?php echo $H_Lane; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">ถนน :</td><td><input type="text" name="H_Road" size="25" value="<?php echo $H_Road; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">แขวง/ตำบล :</td><td><input type="text" name="H_District" size="25" value="<?php echo $H_District; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">เขต/อำเภอ :</td><td><input type="text" name="H_State" size="25" value="<?php echo $H_State; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">จังหวัด :</td><td><input type="text" name="H_Province" size="25" value="<?php echo $H_Province; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="H_Postal_code" size="25" value="<?php echo $H_Postal_code; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ประเทศ:</td><td><input type="text" name="H_Country" size="25" value="<?php echo $H_Country; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">โทรศัพท์ :</td><td><input type="text" name="H_phone" size="25" value="<?php echo $H_phone; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">เบอร์ FAX :</td><td><input type="text" name="H_Fax" size="25" value="<?php echo $H_Fax; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="H_Live_it" size="23" value="<?php echo $H_Live_it; ?>" disabled> ปี</td>
                                        <td width="30"></td>
                                        <td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="H_Completion" size="25" value="<?php echo $H_Completion; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">ได้มาโดย :</td><td><input type="text" name="H_Acquired" size="25" value="<?php echo $H_Acquired; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="H_purchase_price" size="20" value="<?php if($H_purchase_price != ""){echo number_format($H_purchase_price,2);} ?>" disabled> บาท</td>
                                        <td width="30"></td>
                                        <td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $H_addsStyle; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td></td>
                                    </tr>
                                </table>
                        </center>
                        </div>
                        </fieldset>
                        
                        <br>
                        
                        <fieldset class="coolfieldset" id="fs4"><legend><B>ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)</B></legend>
                        <div>
                        <center>
                                <table class="customerInfo">
                                    <tr>
                                        <td align="right" width="100">บ้านเลขที่ :</td><td><input type="text" name="M_HomeNumber" size="25" value="<?php echo $M_HomeNumber; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right" width="100">ห้อง :</td><td><input type="text" name="M_room" size="25" value="<?php echo $M_room; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right" width="100">ชั้น :</td><td><input type="text" name="M_LiveFloor" size="25" value="<?php echo $M_LiveFloor; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">หมู่ที่ :</td><td><input type="text" name="M_Moo" size="25" value="<?php echo $M_Moo; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="M_Building" size="25" value="<?php echo $M_Building; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">หมู่บ้าน :</td><td><input type="text" name="M_Village" size="25" value="<?php echo $M_Village; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ซอย :</td><td><input type="text" name="M_Lane" size="25" value="<?php echo $M_Lane; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">ถนน :</td><td><input type="text" name="M_Road" size="25" value="<?php echo $M_Road; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">แขวง/ตำบล :</td><td><input type="text" name="M_District" size="25" value="<?php echo $M_District; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">เขต/อำเภอ :</td><td><input type="text" name="M_State" size="25" value="<?php echo $M_State; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">จังหวัด :</td><td><input type="text" name="M_Province" size="25" value="<?php echo $M_Province; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="M_Postal_code" size="25" value="<?php echo $M_Postal_code; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ประเทศ:</td><td><input type="text" name="M_Country" size="25" value="<?php echo $M_Country; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">โทรศัพท์ :</td><td><input type="text" name="M_phone" size="25" value="<?php echo $M_phone; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">เบอร์ FAX :</td><td><input type="text" name="M_Fax" size="25" value="<?php echo $M_Fax; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="M_Live_it" size="23" value="<?php echo $M_Live_it; ?>" disabled> ปี</td>
                                        <td width="30"></td>
                                        <td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="M_Completion" size="25" value="<?php echo $M_Completion; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td align="right">ได้มาโดย :</td><td><input type="text" name="M_Acquired" size="25" value="<?php echo $M_Acquired; ?>" disabled></td>
                                    </tr>
                                    <tr>
                                        <td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="M_purchase_price" size="20" value="<?php if($M_purchase_price != ""){echo number_format($M_purchase_price,2);} ?>" disabled> บาท</td>
                                        <td width="30"></td>
                                        <td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $M_addsStyle; ?>" disabled></td>
                                        <td width="30"></td>
                                        <td></td>
                                    </tr>
                                </table>
                        </center>
                        </div>
                        </fieldset>
                        
                        <br>
                        
                        <fieldset class="coolfieldset" id="fs5"><legend><B>บัญชีธนาคารของลูกค้านิติบุคคล</B></legend>
                        <div>
                        <center>
                                <table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
                                    <tr align="center" bgcolor="#79BCFF">
                                        <th width="30"></th>
                                        <th>เลขที่บัญชี</th>
                                        <th>ชื่อบัญชี</th>
                                        <th>ธนาคาร</th>
                                        <th>สาขา</th>
                                        <th>ประเภทบัญชี</th>
                                    </tr>
                                    <?php
                                    if($view == 2)
                                    {
                                        $query = pg_query("select \"acc_Number\",\"bankID\",\"acc_Name\",\"branch\",\"acc_type\" from public.\"th_corp_acc_temp\" where \"corp_regis\" = '$corp_regis' and \"accEdit\" = '$maxedit' and \"Approved\" is null ");
                                    }
                                    elseif($view == 3)
                                    { // กรณีที่ไม่อนุมัติ
                                        $query = pg_query("select \"acc_Number\",\"bankID\",\"acc_Name\",\"branch\",\"acc_type\" from public.\"th_corp_acc_temp\" where \"corp_regis\" = '$corp_regis' and \"accEdit\" = '$maxedit' and \"Approved\" = 'false' ");
                                    }
                                    else
                                    {
                                        $query = pg_query("select \"acc_Number\",\"bankID\",\"acc_Name\",\"branch\",\"acc_type\" from public.\"th_corp_acc\" where \"corp_regis\" = '$corp_regis' ");
                                    }
                                    $numrows = pg_num_rows($query);
                                    $i=0;
                                    while($result = pg_fetch_array($query))
                                    {
                                        $i++;
                                        $acc_Number = $result["acc_Number"]; // เลขที่บัญชี
                                        $bankID = $result["bankID"]; // รหัสธนาคาร
                                        $acc_Name = $result["acc_Name"]; // ชื่อบัญชี
                                        $branch = $result["branch"]; // สาขา
                                        $acc_type = $result["acc_type"]; // ประเภทบัญชี
                                        
                                        $query_bank = pg_query("select \"bankName\" from public.\"BankProfile\" where \"bankID\" = '$bankID' ");
                                        while($resultBank = pg_fetch_array($query_bank))
                                        {
                                            $bankName = $resultBank["bankName"]; // ชื่อธนาคาร
                                        }
                                        
                                        if($i%2==0){
                                            echo "<tr class=\"odd\">";
                                        }else{
                                            echo "<tr class=\"even\">";
                                        }
                                        
                                        echo "<td align=\"center\">$i</td>";
                                        echo "<td align=\"center\">$acc_Number</td>";
                                        echo "<td>$acc_Name</td>";
                                        echo "<td>$bankName</td>";
                                        echo "<td>$branch</td>";
                                        echo "<td align=\"center\">$acc_type</td>";
                                        echo "</tr>";
                                    }
                                    
                                    if($numrows==0){
                                        echo "<tr bgcolor=#FFFFFF><td colspan=6 align=center><b>ไม่พบบัญชีธนาคาร</b></td><tr>";
                                    }
                                    ?>
                                </table>
                        </center>
                        </div>
                        </fieldset>
                        <?php
						//ข้อมูลอื่น ๆ
							if($view == 2)
							{
								$query = pg_query("select \"Proportion_in_country\",\"Proportion_out_country\",\"Proportion_Cash\",\"Proportion_Credit\",\"Amount_Employee\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" is null ");
							}
							elseif($view == 3)
							{ // กรณีที่ไม่อนุมัติ
								$query = pg_query("select \"Proportion_in_country\",\"Proportion_out_country\",\"Proportion_Cash\",\"Proportion_Credit\",\"Amount_Employee\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'false' ");
							}
							else
							{
								$query = pg_query("select \"Proportion_in_country\",\"Proportion_out_country\",\"Proportion_Cash\",\"Proportion_Credit\",\"Amount_Employee\" from public.\"th_corp\" where \"corp_regis\" = '$corp_regis' ");
							}
							$rows = pg_num_rows($query);
							while($rs=pg_fetch_assoc($query))
							{
								$Proportion_in_country=$rs['Proportion_in_country'];
								$Proportion_out_country=$rs['Proportion_out_country'];
								$Proportion_Cash=$rs['Proportion_Cash'];
								$Proportion_Credit=$rs['Proportion_Credit'];
								$Amount_Employee=$rs['Amount_Employee'];
								
							}
						?>
                        <br>
                  <fieldset class="coolfieldset" id="fs6">
                        	<legend>ข้อมูลอื่น ๆ</legend>
                            <div class="customerInfo">
                            <?php
								include("corp_other.php");
							?>
                            </div>
                        </fieldset>
                    <script type="text/javascript">
							var other1='<?php echo "$Proportion_in_country" ?>';
							var other2='<?php echo "$Proportion_out_country" ?>';
							var other3='<?php echo "$Proportion_Cash" ?>';
							var other4='<?php echo "$Proportion_Credit" ?>';
							var other5='<?php echo "$Amount_Employee" ?>';
							document.frm1.Proportion_in_country.value=other1;
							document.frm1.Proportion_out_country.value=other2;
							document.frm1.Proportion_Cash.value=other3;
							document.frm1.Proportion_Credit.value=other4;
							document.frm1.Amount_Employee.value=other5;
						</script>
                        
                        <?php
                        if($view == 3)
                        {
                            $query_noAppv = pg_query("select \"RemarkAll\",\"appvUser\",\"appvStamp\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'false' ");
                            while($res_noAppv = pg_fetch_array($query_noAppv))
                            {
                                $RemarkAll = stripslashes($res_noAppv["RemarkAll"]);
								$appvUser = $res_noAppv["appvUser"];
								$appvStamp = $res_noAppv["appvStamp"];
								
								$qry_appv_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"username\" = '$appvUser' ");
								while($result_appv_name = pg_fetch_array($qry_appv_name))
								{
									$appv_fullname = $result_appv_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
								}
                            }
                        ?>
                            <br />
                            <table>
                            	<tr>
                                    <td align="right"></td><td>
                                    	<div style="font-size:14px; font-weight:bold; color:#ff0000; text-align:left;">ผู้ทำรายการไม่อนุมัติ : <?php echo $appv_fullname; ?> วันเวลาที่ทำรายการไม่อนุมัติ : <?php echo $appvStamp; ?></div>
                                    <td>
                                </tr>
                                <tr>
                                    <td align="right"></td><td><td>
                                </tr>
                                <tr>
                                    <td align="right">สาเหตุที่ไม่อนุมัติ :</td><td><textarea name="explanation" cols="70" rows="3" readonly><?php echo $RemarkAll; ?></textarea><td>
                                </tr>
                            </table>
                        <?php
                        }
                        ?>
                        
                        <br><br>
                        <?php
                        if($view == 3&&$editable!="f")
                        {
                        ?>
                            <input type="submit" value="แก้ไข"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php
                        }
						?>
                        <br><br>
                    </td>
                </tr>
            </table>
            </form>
            </center>
  		</div>
        <div class="ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-2">
        	<div style="display:block; width:100%; text-align:right;">
            	<input type="button" name="btn_print_p" id="btn_print_p" value="พิมพ์หน้านี้" onClick="printpage();" style="margin-right:20px;" />
            </div>
        	<fieldset class="coolfieldset" id="fs7"><legend><B>กรรมการ</B></legend>
            <div>
			<center>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
						
						
				<?php 
				
				if($view == 2)
				{
					$sql1 = pg_query("select \"CusID\",path_signature from public.\"th_corp_board_temp\" where \"corp_regis\" = '$corp_regis' and \"boardEdit\" = '$maxedit' and \"Approved\" is null ");
				}
				elseif($view == 3)
				{ // กรณีที่ไม่อนุมัติ
					$sql1 = pg_query("select \"CusID\",path_signature from public.\"th_corp_board_temp\" where \"corp_regis\" = '$corp_regis' and \"boardEdit\" = '$maxedit' and \"Approved\" = 'false' ");
				}
				else
				{
					$sql1 = pg_query("select \"CusID\",path_signature from public.\"th_corp_board\" where \"corp_regis\" = '$corp_regis' ");
				}
				$row7 = pg_num_rows($sql1);		
				if($row7 == 0){ ?>
					<center> ไม่มีรายชื่อกรรมการ </center>
				<?php	}else{
						$num = 1;
						
						while($re1 = pg_fetch_array($sql1)){
							$CusID = $re1['CusID'];
						?>
						<tr align="right" width="25%">
							<td style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#555;">ชื่อกรรมการคนที่ <?php echo $num; ?> : </td>
							
						<?php $sql2 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re2 = pg_fetch_array($sql2);
							   $row2_2 = pg_num_rows($sql2);
							  
									if($row2_2==0){
									$fullname =  $CusID;
									}else{
										$fullname = $re2['full_name'];
									}
							  
				?>
	
							<td align="left" width="30%" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#555;"><?php echo $fullname;?></td>
							<td align="right" width="15%" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#555;">ตัวอย่างลายเซ็นต์ :</td>
							<td align="left" width="30%">
				<?php if($re1['path_signature'] == ""){
							
				
						}else{	?>			
							<a class="fancyboxa" href="upload/<?php echo $re1['path_signature']; ?>" data-fancybox-group="gallery" title="<?php echo $fullname;?>"><u> แสดงลายเซ็นต์ </u></a></td>
				<?php } ?>		
						</tr>
						
					<?php 
						$num++;
						}
					}
					?>	
				</table>
			</center>
            </div>
			</fieldset>
            <br>
            <fieldset class="coolfieldset" id="fs11">
            <legend><B>ผู้รับมอบอำนาจ</B></legend>
            <div>
			<center>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">		
			<?php
            if($view == 2)
				{
					$sql11 = pg_query("select \"CusID\",path_receipt_authority from public.\"th_corp_attorney_temp\" where \"corp_regis\" = '$corp_regis' and \"attorneyEdit\" = '$maxedit' and \"Approved\" is null ");
				}
				elseif($view == 3)
				{ // กรณีที่ไม่อนุมัติ
					$sql11 = pg_query("select \"CusID\",path_receipt_authority from public.\"th_corp_attorney_temp\" where \"corp_regis\" = '$corp_regis' and \"attorneyEdit\" = '$maxedit' and \"Approved\" = 'false' ");
				}
				else
				{
					$sql11 = pg_query("select \"CusID\",path_receipt_authority from public.\"th_corp_attorney\" where \"corp_regis\" = '$corp_regis' ");
				}
				$row11 = pg_num_rows($sql11);		
					if($row11 == 0){ ?>
						<center> ไม่มีรายชื่อผู้รับมอบอำนาจ </center>
			<?php	}else{  
						$num = 1;
						
						while($re11 = pg_fetch_array($sql11)){
							$CusID = $re11['CusID'];
						?>
						<tr align="right" width="25%">
							<td style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#555;">ผู้รับมอบอำนาจคนที่ <?php echo $num; ?> : </td>
							
						<?php $sql12 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re12 = pg_fetch_array($sql12);
							  $row12 = pg_num_rows($sql12);
							  
									if($row12==0){
									$fullname =  $CusID;
									}else{
										$fullname = $re12['full_name'];
									}
	  
							  
						?>
	
							<td align="left" width="30%" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#555;"><?php echo $fullname;?></td>
							<td align="right" width="15%" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#555;">ใบรับมอบอำนาจ : </td>
							<td align="left" width="30%">
						<?php if($re11['path_receipt_authority'] == ""){
							
				
						}else{	?>	
							
							<a class="pdforpic" href="upload/<?php echo $re11['path_receipt_authority']; ?>" data-fancybox-group="gallery" title="<?php echo $fullname;?>"><u> แสดงใบรับมอบอำนาจ </u></a></td>
							
						<?php } ?>	
						</tr>
						
					<?php 
						$num++;
						}
					}	
					?>	
				</table>
			</center>
            </div>
			</fieldset>
        </div>
        <div class="ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-3">
        	<div style="display:block; width:100%; text-align:right;">
            	<input type="button" name="btn_print_p" id="btn_print_p" value="พิมพ์หน้านี้" onClick="printpage();" style="margin-right:20px;" />
            </div>
        	<fieldset class="coolfieldset" id="fs9">
            <legend><B>ผู้ถือหุ้น</B></legend>
            <div>
			<center>
			
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" >
						<tr align="right" bgcolor="#79BCFF">
							<th align="center" height="25">ลำดับที่</th>
							<th align="center">ชื่อผู้ถือหุ้น</th>
							<th align="center">จำนวนหุ้น</th>
							<th align="center">มูลค่าหุ้น</th>
							<th align="center">มุลค่าหุ้นที่ถือ</th>
							<th align="center">เปอร์เซ็นต์หุ้น</th>
							<th align="center">ตัอวย่างลายเซ็นต์</th>
							
						</tr>
						
			<?php 
			
			if($view == 2)
				{
					$sql9 = pg_query("select \"CusID\",share_amount,share_value,path_signature from public.\"th_corp_share_temp\" where \"corp_regis\" = '$corp_regis' and \"shareEdit\" = '$maxedit' and \"Approved\" is null ");
				}
				elseif($view == 3)
				{ // กรณีที่ไม่อนุมัติ
					$sql9 = pg_query("select \"CusID\",share_amount,share_value,path_signature from public.\"th_corp_share_temp\" where \"corp_regis\" = '$corp_regis' and \"shareEdit\" = '$maxedit' and \"Approved\" = 'false' ");
				}
				else
				{
					$sql9 = pg_query("select \"CusID\",share_amount,share_value,path_signature from public.\"th_corp_share\" where \"corp_regis\" = '$corp_regis' ");
				}
				  $row9 = pg_num_rows($sql9);		
				if($row9 == 0){ ?>
						<tr><td align="center" colspan="7">ไม่มีรายชื่อผู้ถือหุ้น</td></tr>
			<?php	}else{  
						$num = 1;
						
						while($re9 = pg_fetch_array($sql9)){
							$CusID = $re9['CusID'];
							$sql7_7 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re7_7 = pg_fetch_array($sql7_7);
							  $row7_7 = pg_num_rows($sql7_7);
						
						if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}

						if($row7_7==0){
							$fullname =  $CusID;
						}else{
							$fullname = $re7_7['full_name'];
						}
							
							
						?>						
							<td align="center" width="5%"><?php echo $num;?></td>
							<td align="left" width="20%"><?php echo $fullname;?></td>
							<td align="right" width="10%"><?php echo $re9['share_amount'];?></td>
							<td align="right" width="20%"><?php echo $re9['share_value'];?></td>
						<?php	
						
						if($re9['share_amount']=="" || $re9['share_value']=="")
						{
							$sumshare = "";
						}
						else
						{
							$sumshare = $re9['share_amount'] * $re9['share_value'];
						}
						
						?>
							
							<td align="right" width="10%"><?php echo number_format($sumshare,2); ?></td>
							
							<?php if($current_capital == "" || $sumshare == "")
								{
									$percent = "";
								}
								else
								{
									$percent = (($sumshare / $current_capital) * 100);
									$percent = $percent."%";
								}
							?>		
							<td align="right" width="10%"><?php echo $percent;?></td>
							<td align="center" width="15%">
						<?php if($re9['path_signature'] == ""){
							
				
						}else{	?>	
							
							<a class="fancyboxb" href="upload/<?php echo $re9['path_signature']; ?>" data-fancybox-group="gallery" title="<?php echo $fullname;?>"><u>ตัวอย่างลายเซ็นต์</u></a></td>
						<?php } ?>	
						</tr>
						
					<?php 
						$num++;
						}
					}	
					?>	
				</table>
			
			</center>
            </div>
			</fieldset>
        </div>
        <div id="tabs-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
        	<div style="display:block; width:100%; text-align:right;">
            	<input type="button" name="btn_print_p" id="btn_print_p" value="พิมพ์หน้านี้" onClick="printpage();" style="margin-right:20px;" />
            </div>
        	<fieldset class="coolfieldset" id="fs8"><legend><B>ผู้ติดต่อ</B></legend>
            <div>
			<center>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" >
						<tr align="right" bgcolor="#79BCFF">
							<th align="center" height="25">ลำดับที่</th>
							<th align="center">ชื่อผู้ติดต่อ</th>
							<th align="center">ตำแหน่ง</th>
							<th align="center">ประสานงานเรื่อง</th>
							<th align="center">เบอร์โทรศัพท์</th>
							<th align="center">เบอร์มือถือ</th>
							<th align="center">email</th>
							
						</tr>
						
			<?php 
			
			if($view == 2)
				{
					$sql3 = pg_query("select \"CommunicantName\",\"position\",\"subject\",\"phone\",\"mobile\",\"email\" from public.\"th_corp_communicant_temp\" where \"corp_regis\" = '$corp_regis' and \"communicantEdit\" = '$maxedit' and \"Approved\" is null ");
				}
				elseif($view == 3)
				{ // กรณีที่ไม่อนุมัติ
					$sql3 = pg_query("select \"CommunicantName\",\"position\",\"subject\",\"phone\",\"mobile\",\"email\" from public.\"th_corp_communicant_temp\" where \"corp_regis\" = '$corp_regis' and \"communicantEdit\" = '$maxedit' and \"Approved\" = 'false' ");
				}
				else
				{
					$sql3 = pg_query("select \"CommunicantName\",\"position\",\"subject\",\"phone\",\"mobile\",\"email\" from public.\"th_corp_communicant\" where \"corp_regis\" = '$corp_regis' ");
				}
				$row8 = pg_num_rows($sql3);		
				if($row8 == 0){ ?>
						<tr ><td align="center" colspan="7">ไม่มีรายชื่อผู้ติดต่อ</td></tr>
			<?php	}else{	  
						$num = 1;
						
						while($re3 = pg_fetch_array($sql3)){
							
						if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}	
							
						?>						
							<td align="center" width="5%"><?php echo $num;?></td>
							<td align="center" width="20%"><?php echo $re3['CommunicantName'];?></td>
							<td align="center" width="10%" ><?php echo $re3['position'];?></td>
							<td align="center" width="20%"><?php echo $re3['subject'];?></td>
							<td align="center" width="10%"><?php echo $re3['phone'];?></td>
							<td align="center" width="10%"><?php echo $re3['mobile'];?></td>
							<td align="center" width="15%"><?php echo $re3['email'];?></td>
						</tr>
						
					<?php 
						$num++;
						}
					}
					?>	
				</table>
				
			
			</center>
            </div>
			</fieldset>
        </div>
	</div>	
</div>
</center>
</body>
</html>
<?php
echo "<script type=\"text/javascript\">";
if($row1==0)
{
	echo "$('#fs1').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs1').coolfieldset({speed:\"fast\"});";
}
if($row2==0)
{
	echo "$('#fs2').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs2').coolfieldset({speed:\"fast\"});";
}
if($row3==0)
{
	echo "$('#fs3').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs3').coolfieldset({speed:\"fast\"});";
}
if($row4==0)
{
	echo "$('#fs4').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs4').coolfieldset({speed:\"fast\"});";
}
if($numrows==0)
{
	echo "$('#fs5').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs5').coolfieldset({speed:\"fast\"});";
}
if($Proportion_in_country==""&&$Proportion_out_country==""&&$Proportion_Cash==""&&$Proportion_Credit==""&&$Amount_Employee=="")
{
	echo "$('#fs6').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs6').coolfieldset({speed:\"fast\"});";
}
if($row7==0)
{
	echo "$('#fs7').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs7').coolfieldset({speed:\"fast\"});";
}
if($row8==0)
{
	echo "$('#fs8').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs8').coolfieldset({speed:\"fast\"});";
}
if($row9==0)
{
	echo "$('#fs9').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs9').coolfieldset({speed:\"fast\"});";
}
if($row11==0)
{
	echo "$('#fs11').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs11').coolfieldset({speed:\"fast\"});";
}
echo "</script>";
?>