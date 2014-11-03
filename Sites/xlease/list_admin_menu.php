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
    
$cdate=date("Y-m-d");

// update ข้อมูล ปฏิทินงานประจำวัน
$qry_calendar = pg_query("select set_reminder()");

// หาจำนวนรายการที่ต้อง alert ของแต่ละเมนู
$qry_count_menu = pg_query("select * from alert_admin_menu where \"id_user\" = '$iduser' or \"id_user\" = 'ALL'");
while($count_menu = pg_fetch_array($qry_count_menu))
{
	$id_menu = $count_menu["id_menu"];
	$id_user = $count_menu["id_user"];
	
	$count_menu_array["$id_menu"]["$id_user"] = $count_menu["count_list"];
}

if($count_menu_array["C06"]["$iduser"] != ""){$count['C06'] = $count_menu_array["C06"]["$iduser"];}
else{$count['C06'] = $count_menu_array["C06"]["ALL"];}

if($count_menu_array["G09"]["$iduser"] != ""){$count['G09'] = $count_menu_array["G09"]["$iduser"];}
else{$count['G09'] = $count_menu_array["G09"]["ALL"];}

if($count_menu_array["N05"]["$iduser"] != ""){$count['N05'] = $count_menu_array["N05"]["$iduser"];}
else{$count['N05'] = $count_menu_array["N05"]["ALL"];}

if($count_menu_array["N16"]["$iduser"] != ""){$count['N16'] = $count_menu_array["N16"]["$iduser"];}
else{$count['N16'] = $count_menu_array["N16"]["ALL"];}

if($count_menu_array["P15"]["$iduser"] != ""){$count['P15'] = $count_menu_array["P15"]["$iduser"];}
else{$count['P15'] = $count_menu_array["P15"]["ALL"];}

if($count_menu_array["VC03"]["$iduser"] != ""){$count['VC03'] = $count_menu_array["VC03"]["$iduser"];}
else{$count['VC03'] = $count_menu_array["VC03"]["ALL"];}

if($count_menu_array["P27"]["$iduser"] != ""){$count['P27'] = $count_menu_array["P27"]["$iduser"];}
else{$count['P27'] = $count_menu_array["P27"]["ALL"];}

if($count_menu_array["P29"]["$iduser"] != ""){$count['P29'] = $count_menu_array["P29"]["$iduser"];}
else{$count['P29'] = $count_menu_array["P29"]["ALL"];}

if($count_menu_array["A91"]["$iduser"] != ""){$count['A91'] = $count_menu_array["A91"]["$iduser"];}
else{$count['A91'] = $count_menu_array["A91"]["ALL"];}

if($count_menu_array["A92"]["$iduser"] != ""){$count['A92'] = $count_menu_array["A92"]["$iduser"];}
else{$count['A92'] = $count_menu_array["A92"]["ALL"];}

if($count_menu_array["TMA01"]["$iduser"] != ""){$count['TMA01'] = $count_menu_array["TMA01"]["$iduser"];}
else{$count['TMA01'] = $count_menu_array["TMA01"]["ALL"];}

if($count_menu_array["P80"]["$iduser"] != ""){$count['P80'] = $count_menu_array["P80"]["$iduser"];}
else{$count['P80'] = $count_menu_array["P80"]["ALL"];}

if($count_menu_array["A99"]["$iduser"] != ""){$count['A99'] = $count_menu_array["A99"]["$iduser"];}
else{$count['A99'] = $count_menu_array["A99"]["ALL"];}

if($count_menu_array["A15"]["$iduser"] != ""){$count['A15'] = $count_menu_array["A15"]["$iduser"];}
else{$count['A15'] = $count_menu_array["A15"]["ALL"];}

if($count_menu_array["AD99"]["$iduser"] != ""){$count['AD99'] = $count_menu_array["AD99"]["$iduser"];}
else{$count['AD99'] = $count_menu_array["AD99"]["ALL"];}

if($count_menu_array["AU99"]["$iduser"] != ""){$count['AU99'] = $count_menu_array["AU99"]["$iduser"];}
else{$count['AU99'] = $count_menu_array["AU99"]["ALL"];}

if($count_menu_array["A98"]["$iduser"] != ""){$count['A98'] = $count_menu_array["A98"]["$iduser"];}
else{$count['A98'] = $count_menu_array["A98"]["ALL"];}

if($count_menu_array["AP01"]["$iduser"] != ""){$count['AP01'] = $count_menu_array["AP01"]["$iduser"];}
else{$count['AP01'] = $count_menu_array["AP01"]["ALL"];}

if($count_menu_array["AP02"]["$iduser"] != ""){$count['AP02'] = $count_menu_array["AP02"]["$iduser"];}
else{$count['AP02'] = $count_menu_array["AP02"]["ALL"];}

if($count_menu_array["AP03"]["$iduser"] != ""){$count['AP03'] = $count_menu_array["AP03"]["$iduser"];}
else{$count['AP03'] = $count_menu_array["AP03"]["ALL"];}

if($count_menu_array["AP04"]["$iduser"] != ""){$count['AP04'] = $count_menu_array["AP04"]["$iduser"];}
else{$count['AP04'] = $count_menu_array["AP04"]["ALL"];}

if($count_menu_array["AP05"]["$iduser"] != ""){$count['AP05'] = $count_menu_array["AP05"]["$iduser"];}
else{$count['AP05'] = $count_menu_array["AP05"]["ALL"];}

if($count_menu_array["AP06"]["$iduser"] != ""){$count['AP06'] = $count_menu_array["AP06"]["$iduser"];}
else{$count['AP06'] = $count_menu_array["AP06"]["ALL"];}

if($count_menu_array["AP07"]["$iduser"] != ""){$count['AP07'] = $count_menu_array["AP07"]["$iduser"];}
else{$count['AP07'] = $count_menu_array["AP07"]["ALL"];}

if($count_menu_array["AP08"]["$iduser"] != ""){$count['AP08'] = $count_menu_array["AP08"]["$iduser"];}
else{$count['AP08'] = $count_menu_array["AP08"]["ALL"];}

if($count_menu_array["AP09"]["$iduser"] != ""){$count['AP09'] = $count_menu_array["AP09"]["$iduser"];}
else{$count['AP09'] = $count_menu_array["AP09"]["ALL"];}

$qry=pg_query("select 1 as type,auto_id from \"thcap_insure_checkchip\" where \"statusApp\"='2'
union 
select 2 as type,auto_id from \"thcap_insure_temp\" where \"statusApprove\"='2'");
$numrow=pg_num_rows($qry);
$count['AP10'] = $numrow;

if($count_menu_array["AP11"]["$iduser"] != ""){$count['AP11'] = $count_menu_array["AP11"]["$iduser"];}
else{$count['AP11'] = $count_menu_array["AP11"]["ALL"];}

if($count_menu_array["AP12"]["$iduser"] != ""){$count['AP12'] = $count_menu_array["AP12"]["$iduser"];}
else{$count['AP12'] = $count_menu_array["AP12"]["ALL"];}

if($count_menu_array["AP13"]["$iduser"] != ""){$count['AP13'] = $count_menu_array["AP13"]["$iduser"];}
else{$count['AP13'] = $count_menu_array["AP13"]["ALL"];}

if($count_menu_array["AP14"]["$iduser"] != ""){$count['AP14'] = $count_menu_array["AP14"]["$iduser"];}
else{$count['AP14'] = $count_menu_array["AP14"]["ALL"];}

$qry = pg_query("select * from \"approve_thcap_mg_3dreceipt\"  where \"status\" = 0 order by \"appreceiptID\" DESC");
$numrow = pg_num_rows($qry);
$count['AP15'] = $numrow;

if($count_menu_array["QA01"]["$iduser"] != ""){$count['QA01'] = $count_menu_array["QA01"]["$iduser"];}
else{$count['QA01'] = $count_menu_array["QA01"]["ALL"];}

if($count_menu_array["AP16"]["$iduser"] != ""){$count['AP16'] = $count_menu_array["AP16"]["$iduser"];}
else{$count['AP16'] = $count_menu_array["AP16"]["ALL"];}

if($count_menu_array["AP17"]["$iduser"] != ""){$count['AP17'] = $count_menu_array["AP17"]["$iduser"];}
else{$count['AP17'] = $count_menu_array["AP17"]["ALL"];}

if($count_menu_array["AP18"]["$iduser"] != ""){$count['AP18'] = $count_menu_array["AP18"]["$iduser"];}
else{$count['AP18'] = $count_menu_array["AP18"]["ALL"];}

$qry = pg_query("SELECT a.id FROM \"LogsTimeAtt2012\" a left join \"LogsTimeAttApprove\" d on d.id_att=a.id WHERE a.img_id is not null and ( d.approver1_id !='".$_SESSION["av_iduser"]."' or d.approved1 is null ) 
			and d.approved2 is null and d.non_app is null ");
$numrow = pg_num_rows($qry);
$count['AP19'] = $numrow;

if($count_menu_array["AP20"]["$iduser"] != ""){$count['AP20'] = $count_menu_array["AP20"]["$iduser"];}
else{$count['AP20'] = $count_menu_array["AP20"]["ALL"];}

if($count_menu_array["AP21"]["$iduser"] != ""){$count['AP21'] = $count_menu_array["AP21"]["$iduser"];}
else{$count['AP21'] = $count_menu_array["AP21"]["ALL"];}

if($count_menu_array["AP22"]["$iduser"] != ""){$count['AP22'] = $count_menu_array["AP22"]["$iduser"];}
else{$count['AP22'] = $count_menu_array["AP22"]["ALL"];}

if($count_menu_array["AP23"]["$iduser"] != ""){$count['AP23'] = $count_menu_array["AP23"]["$iduser"];}
else{$count['AP23'] = $count_menu_array["AP23"]["ALL"];}

if($count_menu_array["AP24"]["$iduser"] != ""){$count['AP24'] = $count_menu_array["AP24"]["$iduser"];}
else{$count['AP24'] = $count_menu_array["AP24"]["ALL"];}

if($count_menu_array["AP25"]["$iduser"] != ""){$count['AP25'] = $count_menu_array["AP25"]["$iduser"];}
else{$count['AP25'] = $count_menu_array["AP25"]["ALL"];}

if($count_menu_array["AP26"]["$iduser"] != ""){$count['AP26'] = $count_menu_array["AP26"]["$iduser"];}
else{$count['AP26'] = $count_menu_array["AP26"]["ALL"];}

if($count_menu_array["AP27"]["$iduser"] != ""){$count['AP27'] = $count_menu_array["AP27"]["$iduser"];}
else{$count['AP27'] = $count_menu_array["AP27"]["ALL"];}

if($count_menu_array["AP28"]["$iduser"] != ""){$count['AP28'] = $count_menu_array["AP28"]["$iduser"];}
else{$count['AP28'] = $count_menu_array["AP28"]["ALL"];}

if($count_menu_array["AP29"]["$iduser"] != ""){$count['AP29'] = $count_menu_array["AP29"]["$iduser"];}
else{$count['AP29'] = $count_menu_array["AP29"]["ALL"];}

if($count_menu_array["AP30"]["$iduser"] != ""){$count['AP30'] = $count_menu_array["AP30"]["$iduser"];}
else{$count['AP30'] = $count_menu_array["AP30"]["ALL"];}

if($count_menu_array["AP31"]["$iduser"] != ""){$count['AP31'] = $count_menu_array["AP31"]["$iduser"];}
else{$count['AP31'] = $count_menu_array["AP31"]["ALL"];}

if($count_menu_array["AP32"]["$iduser"] != ""){$count['AP32'] = $count_menu_array["AP32"]["$iduser"];}
else{$count['AP32'] = $count_menu_array["AP32"]["ALL"];}

if($count_menu_array["AP33"]["$iduser"] != ""){$count['AP33'] = $count_menu_array["AP33"]["$iduser"];}
else{$count['AP33'] = $count_menu_array["AP33"]["ALL"];}

$qry = pg_query("select * from \"thcap_financial_amount_add_temp\" WHERE \"appstatus\" = '0'");
$numrow = pg_num_rows($qry);
$count['AP35'] = $numrow;

if($count_menu_array["TM04"]["$iduser"] != ""){$count['TM04'] = $count_menu_array["TM04"]["$iduser"];}
else{$count['TM04'] = $count_menu_array["TM04"]["ALL"];}

if($count_menu_array["TMA03"]["$iduser"] != ""){$count['TMA03'] = $count_menu_array["TMA03"]["$iduser"];}
else{$count['TMA03'] = $count_menu_array["TMA03"]["ALL"];}

if($count_menu_array["TMA04"]["$iduser"] != ""){$count['TMA04'] = $count_menu_array["TMA04"]["$iduser"];}
else{$count['TMA04'] = $count_menu_array["TMA04"]["ALL"];}

if($count_menu_array["AP36"]["$iduser"] != ""){$count['AP36'] = $count_menu_array["AP36"]["$iduser"];}
else{$count['AP36'] = $count_menu_array["AP36"]["ALL"];}

if($count_menu_array["AP37"]["$iduser"] != ""){$count['AP37'] = $count_menu_array["AP37"]["$iduser"];}
else{$count['AP37'] = $count_menu_array["AP37"]["ALL"];}

if($count_menu_array["AP38"]["$iduser"] != ""){$count['AP38'] = $count_menu_array["AP38"]["$iduser"];}
else{$count['AP38'] = $count_menu_array["AP38"]["ALL"];}

if($count_menu_array["AP39"]["$iduser"] != ""){$count['AP39'] = $count_menu_array["AP39"]["$iduser"];}
else{$count['AP39'] = $count_menu_array["AP39"]["ALL"];}

if($count_menu_array["AP44"]["$iduser"] != ""){$count['AP44'] = $count_menu_array["AP44"]["$iduser"];}
else{$count['AP44'] = $count_menu_array["AP44"]["ALL"];}

if($count_menu_array["AP45"]["$iduser"] != ""){$count['AP45'] = $count_menu_array["AP45"]["$iduser"];}
else{$count['AP45'] = $count_menu_array["AP45"]["ALL"];}

if($count_menu_array["AP46"]["$iduser"] != ""){$count['AP46'] = $count_menu_array["AP46"]["$iduser"];}
else{$count['AP46'] = $count_menu_array["AP46"]["ALL"];}

if($count_menu_array["AP47"]["$iduser"] != ""){$count['AP47'] = $count_menu_array["AP47"]["$iduser"];}
else{$count['AP47'] = $count_menu_array["AP47"]["ALL"];}

if($count_menu_array["AP48"]["$iduser"] != ""){$count['AP48'] = $count_menu_array["AP48"]["$iduser"];}
else{$count['AP48'] = $count_menu_array["AP48"]["ALL"];}

$qry = pg_query("select * from \"thcap_asset_biz_taxinvoice\"  where \"statusassetID\" = '2' ");
$numrow = pg_num_rows($qry);
$count['AP49'] = $numrow;

if($count_menu_array["AP50"]["$iduser"] != ""){$count['AP50'] = $count_menu_array["AP50"]["$iduser"];}
else{$count['AP50'] = $count_menu_array["AP50"]["ALL"];}

if($count_menu_array["TMZ02"]["$iduser"] != ""){$count['TMZ02'] = $count_menu_array["TMZ02"]["$iduser"];}
else{$count['TMZ02'] = $count_menu_array["TMZ02"]["ALL"];}

if($count_menu_array["AP51"]["$iduser"] != ""){$count['AP51'] = $count_menu_array["AP51"]["$iduser"];}
else{$count['AP51'] = $count_menu_array["AP51"]["ALL"];}

$qry = pg_query("SELECT * from \"BankInt_Waitapp\" WHERE \"statusApp\" = '2'");
$numrow = pg_num_rows($qry);
$count['AP52'] = $numrow;

if($count_menu_array["AP53"]["$iduser"] != ""){$count['AP53'] = $count_menu_array["AP53"]["$iduser"];}
else{$count['AP53'] = $count_menu_array["AP53"]["ALL"];}

$qry = pg_query("SELECT * from \"thcap_financial_amount_add_temp\" where \"appstatus\"='0'");
$numrow = pg_num_rows($qry);
$count['AP54'] = $numrow;

if($count_menu_array["AP55"]["$iduser"] != ""){$count['AP55'] = $count_menu_array["AP55"]["$iduser"];}
else{$count['AP55'] = $count_menu_array["AP55"]["ALL"];}

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

if($count_menu_array["AP57"]["$iduser"] != ""){$count['AP57'] = $count_menu_array["AP57"]["$iduser"];}
else{$count['AP57'] = $count_menu_array["AP57"]["ALL"];}

if($count_menu_array["AP58"]["$iduser"] != ""){$count['AP58'] = $count_menu_array["AP58"]["$iduser"];}
else{$count['AP58'] = $count_menu_array["AP58"]["ALL"];}

if($count_menu_array["AP59"]["$iduser"] != ""){$count['AP59'] = $count_menu_array["AP59"]["$iduser"];}
else{$count['AP59'] = $count_menu_array["AP59"]["ALL"];}

if($count_menu_array["AP60"]["$iduser"] != ""){$count['AP60'] = $count_menu_array["AP60"]["$iduser"];}
else{$count['AP60'] = $count_menu_array["AP60"]["ALL"];}

if($count_menu_array["AP61"]["$iduser"] != ""){$count['AP61'] = $count_menu_array["AP61"]["$iduser"];}
else{$count['AP61'] = $count_menu_array["AP61"]["ALL"];}

if($count_menu_array["AP62"]["$iduser"] != ""){$count['AP62'] = $count_menu_array["AP62"]["$iduser"];}
else{$count['AP62'] = $count_menu_array["AP62"]["ALL"];}

if($count_menu_array["AP63"]["$iduser"] != ""){$count['AP63'] = $count_menu_array["AP63"]["$iduser"];}
else{$count['AP63'] = $count_menu_array["AP63"]["ALL"];}

$qry = pg_query("select * from \"thcap_audit_cashday\" where \"status\" in ('0','2')");
$numrow = pg_num_rows($qry);
$count['AP64'] = $numrow;

if($count_menu_array["AP65"]["$iduser"] != ""){$count['AP65'] = $count_menu_array["AP65"]["$iduser"];}
else{$count['AP65'] = $count_menu_array["AP65"]["ALL"];}

//$qry = pg_query("select * from  \"thcap_checkReceiptID\" where  \"checkstatus\" = '0'");
if($count_menu_array["AP66"]["$iduser"] != ""){$count['AP66'] = $count_menu_array["AP66"]["$iduser"];}
else{$count['AP66'] = $count_menu_array["AP66"]["ALL"];}

if($count_menu_array["AP67"]["$iduser"] != ""){$count['AP67'] = $count_menu_array["AP67"]["$iduser"];}
else{$count['AP67'] = $count_menu_array["AP67"]["ALL"];}

if($count_menu_array["AP68"]["$iduser"] != ""){$count['AP68'] = $count_menu_array["AP68"]["$iduser"];}
else{$count['AP68'] = $count_menu_array["AP68"]["ALL"];}

$no=0;

if($count_menu_array["AP69"]["$iduser"] != ""){$count['AP69'] = $count_menu_array["AP69"]["$iduser"];}
else{$count['AP69'] = $count_menu_array["AP69"]["ALL"];}

if($count_menu_array["AP70"]["$iduser"] != ""){$count['AP70'] = $count_menu_array["AP70"]["$iduser"];}
else{$count['AP70'] = $count_menu_array["AP70"]["ALL"];}

if($count_menu_array["AP71"]["$iduser"] != ""){$count['AP71'] = $count_menu_array["AP71"]["$iduser"];}
else{$count['AP71'] = $count_menu_array["AP71"]["ALL"];}

if($count_menu_array["AP72"]["$iduser"] != ""){$count['AP72'] = $count_menu_array["AP72"]["$iduser"];}
else{$count['AP72'] = $count_menu_array["AP72"]["ALL"];}

if($count_menu_array["AP73"]["$iduser"] != ""){$count['AP73'] = $count_menu_array["AP73"]["$iduser"];}
else{$count['AP73'] = $count_menu_array["AP73"]["ALL"];}

if($count_menu_array["AP74"]["$iduser"] != ""){$count['AP74'] = $count_menu_array["AP74"]["$iduser"];}
else{$count['AP74'] = $count_menu_array["AP74"]["ALL"];}

if($count_menu_array["AP75"]["$iduser"] != ""){$count['AP75'] = $count_menu_array["AP75"]["$iduser"];}
else{$count['AP75'] = $count_menu_array["AP75"]["ALL"];}

if($count_menu_array["AP76"]["$iduser"] != ""){$count['AP76'] = $count_menu_array["AP76"]["$iduser"];}
else{$count['AP76'] = $count_menu_array["AP76"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP77
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP77"]["$iduser"] != ""){$count['AP77'] = $count_menu_array["AP77"]["$iduser"];}
else{$count['AP77'] = $count_menu_array["AP77"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP78
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP78"]["$iduser"] != ""){$count['AP78'] = $count_menu_array["AP78"]["$iduser"];}
else{$count['AP78'] = $count_menu_array["AP78"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP79
//------------------------------------------------------------------------------------------------------------------------------------
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

//------------------------------------------------------------------------------------------------------------------------------------
// AP80
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP80"]["$iduser"] != ""){$count['AP80'] = $count_menu_array["AP80"]["$iduser"];}
else{$count['AP80'] = $count_menu_array["AP80"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP81
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP81"]["$iduser"] != ""){$count['AP81'] = $count_menu_array["AP81"]["$iduser"];}
else{$count['AP81'] = $count_menu_array["AP81"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP82
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP82"]["$iduser"] != ""){$count['AP82'] = $count_menu_array["AP82"]["$iduser"];}
else{$count['AP82'] = $count_menu_array["AP82"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP83
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP83"]["$iduser"] != ""){$count['AP83'] = $count_menu_array["AP83"]["$iduser"];}
else{$count['AP83'] = $count_menu_array["AP83"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP84
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP84"]["$iduser"] != ""){$count['AP84'] = $count_menu_array["AP84"]["$iduser"];}
else{$count['AP84'] = $count_menu_array["AP84"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP85
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP85"]["$iduser"] != ""){$count['AP85'] = $count_menu_array["AP85"]["$iduser"];}
else{$count['AP85'] = $count_menu_array["AP85"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP86
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP86"]["$iduser"] != ""){$count['AP86'] = $count_menu_array["AP86"]["$iduser"];}
else{$count['AP86'] = $count_menu_array["AP86"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP87
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP87"]["$iduser"] != ""){$count['AP87'] = $count_menu_array["AP87"]["$iduser"];}
else{$count['AP87'] = $count_menu_array["AP87"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP88
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP88"]["$iduser"] != ""){$count['AP88'] = $count_menu_array["AP88"]["$iduser"];}
else{$count['AP88'] = $count_menu_array["AP88"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP89
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP89"]["$iduser"] != ""){$count['AP89'] = $count_menu_array["AP89"]["$iduser"];}
else{$count['AP89'] = $count_menu_array["AP89"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP90
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP90"]["$iduser"] != ""){$count['AP90'] = $count_menu_array["AP90"]["$iduser"];}
else{$count['AP90'] = $count_menu_array["AP90"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP91
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP91"]["$iduser"] != ""){$count['AP91'] = $count_menu_array["AP91"]["$iduser"];}
else{$count['AP91'] = $count_menu_array["AP91"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP92
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP92"]["$iduser"] != ""){$count['AP92'] = $count_menu_array["AP92"]["$iduser"];}
else{$count['AP92'] = $count_menu_array["AP92"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP93
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP93"]["$iduser"] != ""){$count['AP93'] = $count_menu_array["AP93"]["$iduser"];}
else{$count['AP93'] = $count_menu_array["AP93"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP94
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP94"]["$iduser"] != ""){$count['AP94'] = $count_menu_array["AP94"]["$iduser"];}
else{$count['AP94'] = $count_menu_array["AP94"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP95
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP95"]["$iduser"] != ""){$count['AP95'] = $count_menu_array["AP95"]["$iduser"];}
else{$count['AP95'] = $count_menu_array["AP95"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP96 // (THCAP) Approve Create งานยึด
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP96"]["$iduser"] != ""){$count['AP96'] = $count_menu_array["AP96"]["$iduser"];}
else{$count['AP96'] = $count_menu_array["AP96"]["ALL"];}


//------------------------------------------------------------------------------------------------------------------------------------
// AP97 // (THCAP) ตรวจสอบรับทรัพย์สินรับคืน-ยึดคืน
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP97"]["$iduser"] != ""){$count['AP97'] = $count_menu_array["AP97"]["$iduser"];}
else{$count['AP97'] = $count_menu_array["AP97"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP98 // (THCAP) Approved Cancel NT
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP98"]["$iduser"] != ""){$count['AP98'] = $count_menu_array["AP98"]["$iduser"];}
else{$count['AP98'] = $count_menu_array["AP98"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// AP99 // (THCAP) อนุมัติปิดสัญญา
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["AP99"]["$iduser"] != ""){$count['AP99'] = $count_menu_array["AP99"]["$iduser"];}
else{$count['AP99'] = $count_menu_array["AP99"]["ALL"];}

//------------------------------------------------------------------------------------------------------------------------------------
// TMW09
//------------------------------------------------------------------------------------------------------------------------------------
if($count_menu_array["TMW09"]["$iduser"] != ""){$count['TMW09'] = $count_menu_array["TMW09"]["$iduser"];}
else{$count['TMW09'] = $count_menu_array["TMW09"]["ALL"];}

if($count_menu_array["TMZ01"]["$iduser"] != ""){$count['TMZ01'] = $count_menu_array["TMZ01"]["$iduser"];}
else{$count['TMZ01'] = $count_menu_array["TMZ01"]["ALL"];}

if($count_menu_array["TUP01"]["$iduser"] != ""){$count['TUP01'] = $count_menu_array["TUP01"]["$iduser"];}
else{$count['TUP01'] = $count_menu_array["TUP01"]["ALL"];}

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