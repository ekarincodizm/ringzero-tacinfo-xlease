<?php
require_once("config/config.php");
require_once("nw/reminder/function_reminder.php");

$iduser = $_SESSION['uid'];
$min_old = $_SESSION['min_old'];

// ถ้าไม่มีค่าการนับ การ refresh ของส่วน alert admin menu แสดงว่า login เข้ามาใช้ครั้งแรก
if($_SESSION['nub_admin_refresh'] == "")
{
	$_SESSION['nub_admin_refresh'] = 1;
}

// เมนูที่จะค้นหา
$searchText = pg_escape_string($_GET["searchText"]);
$searchText = str_replace("TspaceT"," ",$searchText);
if($searchText != ""){$searchText = "and b.\"name_menu\" like '%$searchText%'";}

$nowdate = nowDate();

$query_popup=pg_query("select a.\"annId\" from \"nw_annoucement\" a 
left join \"nw_annouceuser\" b on a.\"annId\"=b.\"annId\"
where \"statusApprove\"='TRUE' and b.\"id_user\"='$iduser' and \"statusAccept\"='1' order by \"approveDate\" limit 1");
$numrows_popup=pg_num_rows($query_popup);
	
if($numrows_popup==0){ //กรณีไม่พบว่ามีประกาศ

$minute=date("i"); //ใช้สำหรับตรวจสอบนาทีการแจ้งเตือนติดต่อลูกค้า
$_SESSION['min_old']=$minute; //เก็บค่าเก่าไว้ตรวจสอบ;

// หากลุ่มของผู้ใช้งานในขณะนั้น
$query_group = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$iduser' ");
while($result_group = pg_fetch_array($query_group))
{
	$user_group = $result_group["user_group"]; // กลุ่มของ user
}
?>
<script language=javascript>
function popupModal(url,width,height){
var myDate=new Date();
var setUniqe=myDate.getTime();

var diaxFeature="dialogWidth:"+width+"px;"
+"dialogHeight:"+height+"px;"
+"center:yes;"
+"edge:raised;" // sunken | raised
+"resizable:no;"
+"maximize:yes;"
+"status:no;"
+"scroll:yes;";
window.showModalDialog(url+"?"+setUniqe,"", diaxFeature);
}

function popupAppv(url,width,height){
var myDate=new Date();
var setUniqe=myDate.getTime();

var diaxFeature="dialogWidth:"+width+"px;"
+"dialogHeight:"+height+"px;"
+"center:yes;"
+"edge:raised;" // sunken | raised
+"resizable:no;"
+"maximize:yes;"
+"status:no;"
+"scroll:yes;";
window.showModalDialog(url,"", diaxFeature);
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function alertCallback()
{
	//window.alert('มีคนรออยู่');
	//top.alert('มีคนรออยู่');
	popU('nw/CallBack/alertCallBack.php?idpopup=<?php echo "$iduser"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=150');
}

</script>

<?php
$qry_popup=pg_query("select distinct(a.\"id_user\") from \"nw_changemenu\" a 
		WHERE a.\"statusApprove\" != '1' and a.\"statusApprove\" <> '0' and \"statusOKapprove\"='FALSE' and a.\"id_user\" = '$iduser'");
		$numrow_popup=pg_num_rows($qry_popup);
	if($numrow_popup > 0){
?>
<center><div onclick="javascript:popU('nw/changemenu/frm_popup.php?idpopup=<?php echo $iduser; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=500')"><BLINK><b><h2>มีรายการเปลี่ยนแปลงสิทธิ์การทำงานของคุณเกิดขึ้น!!</h2></b></BLINK></div></center>
<center>
<input type="button" id="test" value="กรุณาคลิกเพื่อรับทราบรายการ" onclick="javascript:popU('nw/changemenu/frm_popup.php?idpopup=<?php echo $iduser; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=500')">
</center>
<?php
}
?>


<?php
/*require_once("config/config.php");
$iduser = $_SESSION['uid'];*/

$admin_array = GetAdminMenu(); //menu ของ admin

$result=pg_query("SELECT A.*,B.* FROM f_usermenu A 
INNER JOIN f_menu B on A.id_menu=B.id_menu 
WHERE (A.id_user='$iduser') AND (B.status_menu='1') AND (A.status=true) $searchText ORDER BY A.id_menu ASC");
while($arr_menu = pg_fetch_array($result)){
    $menu_id = $arr_menu["id_menu"];                                                                                                      
    $menu_name = $arr_menu["name_menu"];
    $menu_path = $arr_menu["path_menu"];
    
    if(in_array($menu_id,$admin_array)){
        $arr['admin'][$menu_id]['name'] = "$menu_name";
        $arr['admin'][$menu_id]['path'] = "$menu_path";
		$arr['admin'][$menu_id]['idmenu_log'] = "$menu_id";
    }
}

if( count($arr['admin']) > 0 ){
    
$cdate = nowDate();

// update ข้อมูล ปฏิทินงานประจำวัน
$qry_calendar = pg_query("select set_reminder()");

// หาจำนวนรายการที่ต้อง alert ของแต่ละเมนู
$qry_count_menu = pg_query("select * from alert_admin_menu where \"id_user\" = '$iduser' or \"id_user\" = 'ALL'");
while($count_menu = pg_fetch_array($qry_count_menu))
{
	$id_menu = $count_menu["id_menu"];
	$id_user = $count_menu["id_user"];
	
	$count_menu_array["$id_menu"]["$id_user"] = $count_menu["count_list"];
	
	if($count_menu_array["$id_menu"]["$iduser"] != "") // ถ้าเป็นการแจ้งเตือนเฉพาะบุคคล
	{
		$count["$id_menu"] = $count_menu_array["$id_menu"]["$iduser"];
	}
	else // ถ้าเป็นการแจ้งเตือนทุกคนที่เห็นเมนู
	{
		$count["$id_menu"] = $count_menu_array["$id_menu"]["ALL"];
	}
}

//-------------------------------------------------------------------------------------------------------------
// todo AP10 เมนูอนุมัติประกันภัยของ THCAP ยังไม่ได้นำงาน #1855 เข้า ถ้าจะนำงานดังกล่าวเข้า ต้องแก้ไข function count_alert_admin_menu และลบ code ในส่วนนี้ทิ้ง
//-------------------------------------------------------------------------------------------------------------
$qry=pg_query("select 1 as type,auto_id from \"thcap_insure_checkchip\" where \"statusApp\"='2'
union 
select 2 as type,auto_id from \"thcap_insure_temp\" where \"statusApprove\"='2'");
$numrow=pg_num_rows($qry);
$count['AP10'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP15 "(THCAP) ลงค่าธรรมเนียมเงินกู้ MG" ยังไม่ได้นำงาน #2054 เข้า ถ้าจะนำงานดังกล่าวเข้า ต้องแก้ไข function count_alert_admin_menu และลบ code ในส่วนนี้ทิ้ง
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("select * from \"approve_thcap_mg_3dreceipt\"  where \"status\" = 0 order by \"appreceiptID\" DESC");
$numrow = pg_num_rows($qry);
$count['AP15'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP19 ไม่พบงาน และไม่ทราบว่าเป็นเมนูอะไร จึงย้ายไปไว้ใน function ไม่ได้
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("SELECT a.id FROM \"LogsTimeAtt2012\" a left join \"LogsTimeAttApprove\" d on d.id_att=a.id WHERE a.img_id is not null and ( d.approver1_id !='".$_SESSION["av_iduser"]."' or d.approved1 is null ) 
			and d.approved2 is null and d.non_app is null ");
$numrow = pg_num_rows($qry);
$count['AP19'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP35 "(THCAP) อนุมัติเพิ่มวงเงินสัญญา" ยังไม่ได้นำงาน #2727 เข้า ถ้าจะนำงานดังกล่าวเข้า ต้องแก้ไข function count_alert_admin_menu และลบ code ในส่วนนี้ทิ้ง
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("select * from \"thcap_financial_amount_add_temp\" WHERE \"appstatus\" = '0'");
$numrow = pg_num_rows($qry);
$count['AP35'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP49 "(THCAP) รับใบกำกับภาษีซื้อ" ยังไม่ได้นำงาน #3847 เข้า ถ้าจะนำงานดังกล่าวเข้า ต้องแก้ไข function count_alert_admin_menu และลบ code ในส่วนนี้ทิ้ง
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("select * from \"thcap_asset_biz_taxinvoice\"  where \"statusassetID\" = '2' ");
$numrow = pg_num_rows($qry);
$count['AP49'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP52 "จัดการบัญชีธนาคารบริษัท" ยังไม่ได้นำงาน #3919 เข้า ถ้าจะนำงานดังกล่าวเข้า ต้องแก้ไข function count_alert_admin_menu และลบ code ในส่วนนี้ทิ้ง
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("SELECT * from \"BankInt_Waitapp\" WHERE \"statusApp\" = '2'");
$numrow = pg_num_rows($qry);
$count['AP52'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP54 ไม่พบงาน และไม่ทราบว่าเป็นเมนูอะไร จึงย้ายไปไว้ใน function ไม่ได้
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("SELECT * from \"thcap_financial_amount_add_temp\" where \"appstatus\"='0'");
$numrow = pg_num_rows($qry);
$count['AP54'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// AP56 "(THCAP) ตรวจสอบรายการผิดปกติในระบบ"
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("select sum(countrow) from (
											SELECT count(*) as countrow FROM \"thcap_check_correct_channel_otherpay_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_duplicate_use_transfermoney_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_myTransferMoney_with_useTransferMoney_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_interestrate_of_payloan_data\"
											UNION
											SELECT count(*) as countrow FROM (SELECT \"contractID\" FROM \"thcap_check_integrity_tableOfInt_data\" GROUP BY \"contractID\") tba
											UNION
											SELECT count(*) as countrow FROM (SELECT \"contractID\" FROM \"thcap_check_hp_vat_gen_correction_data\" GROUP BY \"contractID\") tbb
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_guaranteed_money_date\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_billpayment_with_transferpayment_date\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_integrity_contractid_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_leasing_gen_effectivetable_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_integrity_ncb_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_statement_bank_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_payterm_correction_data\"
											UNION
											SELECT count(*) as countrow FROM \"thcap_check_acc_debit_credit_amt_data\"
										) a
				");
list($numrow) = pg_fetch_array($qry);
// ต่อ base ที่ตรวจสอบ
$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=postgres user=postgres password=". $_SESSION["session_company_dbpass"] ."";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");
$qry = pg_query("SELECT count(*) as countrow FROM \"check_process_job_data\" ");
list($numrow2) = pg_fetch_array($qry);
// กลับมาต่อ base หลักเหมือนเดิม
$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");
$numrow = $numrow + $numrow2;
$count['AP56'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// todo AP64 "(THCAP) ตรวจรับเงินสดประจำวัน" ยังไม่ได้นำงาน #4678 เข้า ถ้าจะนำงานดังกล่าวเข้า ต้องแก้ไข function count_alert_admin_menu และลบ code ในส่วนนี้ทิ้ง
//-------------------------------------------------------------------------------------------------------------
$qry = pg_query("select * from \"thcap_audit_cashday\" where \"status\" in ('0','2')");
$numrow = pg_num_rows($qry);
$count['AP64'] = $numrow;

//-------------------------------------------------------------------------------------------------------------
// AP79 "ปฏิทินงานประจำวัน"
//-------------------------------------------------------------------------------------------------------------
$qry = getreminderquery($nowdate,$iduser,'2');//fix เป็น  2(tabที่ 2) เพราะ 2 แทน วันที่ปัจจุบัน
$res=pg_num_rows($qry);
$count['AP79'] = $res;
// หารายการทั้งหมดที่ได้ทำแล้วในวันนี้
$qry = pg_query(" 
					SELECT count(*)
					FROM \"reminder_job\" a
					LEFT JOIN \"reminder\" b ON a.reminder_id = b.reminder_id
					WHERE 
						\"reminder_job_status\"='1'::smallint AND
						\"reminder_job_date\"='$nowdate' AND -- เฉพาะ job การติดตามของวันที่ที่สนใจ
						( -- แสดงรายการที่เป็น public ทุกรายการ และแสดงรายการ private เฉพาะที่เป็นของ user นั้นๆ
							b.reminder_isprivate = '0'::smallint OR -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active
							(b.reminder_isprivate = '1'::smallint AND b.reminder_doerid = '$iduser')
						)
");
$res=pg_fetch_array($qry);
// รายการทั้งหมดเท่ากับรายการที่จะต้องทำ หักด้วย รายการที่ทำแล้ว
$count['AP79'] = $count['AP79'] - $res[0];

$nowdatecheck = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$sqlchk = pg_query("SELECT * FROM f_menu_warning where appstatus = '1' AND s_time <= '$nowdatecheck' AND e_time >= '$nowdatecheck'");
$rechk = pg_fetch_array($sqlchk);
$endtimeori = date($rechk['e_time']);						
$deftime1 = (strtotime($endtimeori) - strtotime($nowdatecheck ));					
$min11 = floor(($deftime1 % 3600) / 60);
if($min11 <= 15 and $min11 > 0){
	$numrow1 = pg_num_rows($sqlchk);
}

//------------- ผู้รอการติดต่อกลับ
$nowdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$qryalert=pg_query("select \"callback_Stamp\" from public.\"callback\" where \"CallBackStatus\" IN('1','3') and (\"Want_dep_id\" = '$user_group' or \"Want_id_user\" = '$iduser')");
$p=0;
while($resalert=pg_fetch_array($qryalert)){
	$callback_Stamp=$resalert["callback_Stamp"];
	
	if($callback_Stamp==""){
		$p++;
		break;
	}else if($callback_Stamp < $nowdate){
		$p++;
		break;
	}else{
		$p=0;
	}
}

if($p>0)
{
	/*////// ยกเลิกเงื่อนไขนี้
	กรณีที่ระบบ refresh ทุก 30 วินาที จะพบว่าค่านาทียังคงเป็นค่าเดิม ทำให้ต้องตรวจสอบด้วยว่า นาทีเป็นค่าเดิมหรือไม่
	ถ้าเป็นค่าเดิมไม่ต้องแสดงอีกรอบ แต่ถ้าไม่ใช่ให้นำนาทีมา %3 ถ้าลงตัวค่อย alert
	///////
	if($min_old!=$minute and $minute%3==0){
		echo "<script language=javascript> alertCallback(); </script>";
	}
	//////*/
	
	// แสดงข้อมูลลูกค้าที่รอการติดต่อกลับ ในครั้งแรก และทุกๆ 15 ครั้งที่ refresh
	if($_SESSION['nub_admin_refresh'] == "1") 
	{
		echo "<script language=javascript> alertCallback(); </script>"; 
		$_SESSION['nub_admin_refresh']++;
	}
	elseif($_SESSION['nub_admin_refresh'] == "15") // ถ้า refresh ครบ 15 ครั้ง ให้กลับไปแสดงลูกค้าที่รอการติดต่อกลับตามปกติ
	{
		$_SESSION['nub_admin_refresh'] = 1;
	}
	else
	{
		$_SESSION['nub_admin_refresh']++;
	}
}
//------------- จบเรื่องการติดต่อกลับ

echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="menu"><tr>';
foreach($arr['admin'] as $k => $v){
    $i++;
   
   //ถ้า user เป็น 000 จะไม่สามารถกดเมนูได้
	if($iduser=="000"){
		echo "<td width=\"25%\" align=\"center\" style=\"background-color:#FFFFFF; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px\">
		<div style=\"float:left; width:155px\">
		<IMG SRC=\"images/icon_menu/$k.gif\" WIDTH=\"80\" HEIGHT=\"80\" BORDER=\"0\"><br>$v[name]
		</div>
		<div style=\"clear : both;\"></div>";
	}else{
	   echo "<td width=\"25%\" align=\"center\" style=\"background-color:#FFFFFF; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px\">
		<div style=\"float:left; width:155px\">
		<A HREF=\"javascript:menulog('$v[idmenu_log]');javascript:testalert('$k','$v[path]','$k','$code');\"><IMG SRC=\"images/icon_menu/$k.gif\" WIDTH=\"80\" HEIGHT=\"80\" BORDER=\"0\"><br>$v[name]</A>
		</div>
		<div style=\"clear : both;\"></div>";
	}

	if($count[$k]>0){
		echo "
		<div style=\"margin-top:-95px;width:95px;float:right;\">
		<div style=\"font-size:12px;background-image:url(images/botton.png);width:35px;height:28px;padding-top:8px;\"><span style=\"color:#FFFFFF; font-weight:bold;\">$count[$k]</span></div>
		</div>";
	}
	echo "</td>";
    if($i == 4){
        $i = 0;
        echo '</tr><tr>';
    }
}
echo '</tr></table>';
echo '<div style="clear:both></div>"';

}
}else{ //กรณีพบว่ามีประกาศ
	?>
	<script language="javascript">
		$('body').append('<div id="dialog"></div>');
			$('#dialog').load('nw/annoucement/frm_show2.php');
			$('#dialog').dialog({
			open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
			resizable: false,
			modal: true,  
			width: 1000,
			height: 950,
			closeOnEscape: false, //ไม่สามารถกด esc ในการ ปิดหน้าต่าง
			draggable: false, //กำหนดว่าสามารถ drag ได้หรือไม่
			//position: [top],
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});	
	</script>
	<?php
}
?>