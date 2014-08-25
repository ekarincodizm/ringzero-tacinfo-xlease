<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')){
    header('Location: mobile_frm_cal_cuspayment.php');
    exit();
}

include("../config/config.php");

// วันที่ปัจจุบัน
$nowDate = nowDate();

$idno=$_SESSION["ses_idno"];

if(empty($idno)){
    header("Location: frm_cuspayment.php");
}

if(empty($_POST["signDate"])){
    $ssdate = nowDate();
}else{
    $ssdate=pg_escape_string($_POST["signDate"]);
}

$qry_VCusPayment=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];

$qry_VCusPayment_last=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$idno') order by \"DueDate\" desc LIMIT(1)");
$res_VCusPayment_last=pg_fetch_array($qry_VCusPayment_last);
$ldate=$res_VCusPayment_last["DueDate"];

$qry_FpFa1=pg_query("select \"P_MONTH\",\"P_VAT\",\"P_MONTH\",\"P_VAT\",\"P_STDATE\",\"P_CLDATE\"
,\"P_StopVatDate\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"P_TOTAL\",
\"P_LAWERFEE\",\"P_ACCLOSE\",\"P_StopVat\",\"repo\",\"ComeFrom\",\"PayType\",\"P_StopVatDate\",
\"asset_id\",A.\"CusID\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");

$res_FpFa1=pg_fetch_array($qry_FpFa1);
    $s_payment_nonvat = $res_FpFa1["P_MONTH"];
    $s_payment_vat = $res_FpFa1["P_VAT"];
    $s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
    $f_date = $res_FpFa1["P_STDATE"];
	$f_cldate = $res_FpFa1["P_CLDATE"];
	$f_stopvatdate = $res_FpFa1["P_StopVatDate"];
    $fullname = trim($res_FpFa1["A_FIRNAME"])." ".trim($res_FpFa1["A_NAME"])." ".trim($res_FpFa1["A_SIRNAME"]);
    $s_fp_ptotal = $res_FpFa1["P_TOTAL"];
    $s_LAWERFEE = $res_FpFa1["P_LAWERFEE"];
    $s_ACCLOSE = $res_FpFa1["P_ACCLOSE"];
    $s_StopVat = $res_FpFa1["P_StopVat"];
	$repo = $res_FpFa1["repo"];
	$ComeFrom=$res_FpFa1["ComeFrom"];
	$chkpaytype=trim($res_FpFa1['PayType']);
	$stopVatDate = $res_FpFa1['P_StopVatDate']; if($stopVatDate=="") $stopVatDate="ไม่ได้ระบุ";
	$assetID = $res_FpFa1["asset_id"];
    $_SESSION["ses_scusid"] = trim($res_FpFa1["CusID"]);

    $qry_thaidate=pg_query("select conversiondatetothaitext('$f_date')");
    $f_dateth=pg_fetch_result($qry_thaidate,0);

$qry_VContact=pg_query("select \"C_TAX_ExpDate\",\"dp_balance\",\"RadioID\",\"asset_id\",\"C_CARNAME\",\"C_YEAR\",\"C_COLOR\"
,\"C_REGIS\",\"car_regis\",\"gas_number\",\"C_REGIS\",\"C_CARNUM\",\"car_regis\" from \"VContact\" WHERE \"IDNO\"='$idno'");

$res_VContact=pg_fetch_array($qry_VContact);
    $s_expdate = $res_VContact["C_TAX_ExpDate"]; 
    $s_dp_balance = $res_VContact["dp_balance"];
    $s_radioid = $res_VContact["RadioID"];
	$asset_idnow=$res_VContact["asset_id"];
	$s_ccarname=$res_VContact["C_CARNAME"];
	$s_year=$res_VContact["C_YEAR"];
	$s_ccolor=$res_VContact["C_COLOR"];
	
	if($res_VContact["C_REGIS"]==""){
        $regis=$res_VContact["car_regis"];
        $r_number="<b>เลขถังแก๊ส</b> ".$res_VContact["gas_number"];
    }else{
        $regis=$res_VContact["C_REGIS"];
        $r_number=$res_VContact["C_CARNUM"];
    }
	$sql_select=pg_query("SELECT m.id,v.\"IDNO\",m.cpro_name,v.\"C_REGIS\",v.\"full_name\",v.\"P_ACCLOSE\",m.cancel,m.car_license,m.idno as idno2 FROM public.\"VJoinMain\" m left join \"VJoin\" v on m.idno=v.\"IDNO\" 
	WHERE v.\"IDNO\"= '$idno'  and m.deleted='0' ");
	
	if($res_cn=pg_fetch_array($sql_select)){
		$id = trim($res_cn["id"]);
	}
	if($id==""){
		$nub_data=0;
	} else {
		$findCarid = pg_query("SELECT carid FROM \"ta_join_main\" WHERE id='$id'");
		$resCarid = pg_fetch_result($findCarid,0); 

		$carid=$resCarid;
	
		// ถ้า carid ไม่มีค่า
		if($carid == "")
		{
			// ให้ไปเอาที่ "Fp"."asset_id" แทน
			$qry_CarID = pg_query("select \"asset_id\" from \"Fp\" where \"IDNO\" = '$idno' ");
			$carid = pg_result($qry_CarID,0);
			
			// หาผู้เข้าร่วมคนปัจจุบัน
			$qry_join = pg_query(" select \"prefix\",\"f_name\",\"l_name\",\"cusid\",\"idno\",b.\"P_STDATE\" from \"ta_join_main\" as a left join \"Fp\" as b on  a.idno = b.\"IDNO\" 
							where b.asset_id = '$carid' and 
							a.\"create_datetime\" = (select max(\"create_datetime\") from \"ta_join_main\" where idno = '$idno')" );
			$nub_data = pg_num_rows($qry_join);
			$res_newjoin = pg_fetch_array($qry_join);
			$NewjoinName = trim($res_newjoin["prefix"])." ".trim($res_newjoin["f_name"])." ".trim($res_newjoin["l_name"]);
			$NewCusID = trim($res_newjoin["cusid"]);
			$NewIDNO = $res_newjoin["idno"];
		}
		else
		{
			// หาผู้เข้าร่วมคนปัจจุบัน
			$qry_join = pg_query(" select \"prefix\",\"f_name\",\"l_name\",\"cusid\",\"idno\",b.\"P_STDATE\" from \"ta_join_main\" as a left join \"Fp\" as b on  a.idno = b.\"IDNO\" 
							where a.carid = '$carid' and 
							a.\"create_datetime\" = (select max(\"create_datetime\") from \"ta_join_main\" where carid = '$carid')" );
			$nub_data = pg_num_rows($qry_join);
			$res_newjoin = pg_fetch_array($qry_join);
			$NewjoinName = trim($res_newjoin["prefix"])." ".trim($res_newjoin["f_name"])." ".trim($res_newjoin["l_name"]);
			$NewCusID = trim($res_newjoin["cusid"]);
			$NewIDNO = $res_newjoin["idno"];
		}
	}
	
   
//หาว่าเลขทะเบียนนี้ปัจจุบันอยู่กับใคร
$qrycarnow=pg_query("select \"C_REGIS\",\"IDNO\",\"C_StartDate\" from \"Fp\" a
left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$asset_idnow' order by \"P_STDATE\" DESC limit 1");
$rescarnow=pg_fetch_array($qrycarnow);
list($C_REGISnew,$idnonow,$C_StartDate)=$rescarnow;



if($idnonow!=$idno and $res_VContact["car_regis"]==""){
	$C_REGISnew="<font color=red> (<span onclick=\"javascript:popU('frm_viewcuspayment.php?idno_names=$idnonow','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>$idnonow</u></span> / $C_REGISnew)</font>";
}else{
	$C_REGISnew="";
}

$_SESSION["ses_h_start"]=$stdate;
$_SESSION["ses_payment_nonvat"]=$s_payment_nonvat;
$_SESSION["ses_payment_vat"]=$s_payment_vat;
$_SESSION["ses_payment_all"]=$s_payment_all;
$_SESSION["ses_start_date"]=$f_date;
$_SESSION["ses_start_dateth"]=$f_dateth;
$_SESSION["ses_last_date"]=$ldate;
$_SESSION["ses_a_fullname"]=$fullname;
$_SESSION["ses_regis"]=$regis;
$_SESSION["ses_r_number"]=$r_number;
$_SESSION["ses_date"]=$ssdate;
$_SESSION["ses_year"]=$s_year;
$_SESSION["ses_expdate"]=$s_expdate;
$_SESSION["ses_ccolor"]=$s_ccolor;
$_SESSION["ses_ccarname"]=$s_ccarname;
$_SESSION["ses_radioid"]=$s_radioid;
$_SESSION["ses_fp_ptotal"]=$s_fp_ptotal;
$_SESSION["ses_LAWERFEE"]=$s_LAWERFEE;
$_SESSION["ses_ACCLOSE"]=$s_ACCLOSE;
$_SESSION["ses_StopVat"]=$s_StopVat;
$_SESSION["ses_dp_balance"]=$s_dp_balance;

//หาวันที่รถหมดอายุการใช้งาน
if($C_StartDate!=null){
	// ของตรวจสอบภาษีรถยนต์
	$qrynub=pg_query("SELECT ta_array1d_count(carregis.\"CreateCheckRound\"('$C_StartDate','1'))");
	list($nubExp)=pg_fetch_array($qrynub);
	$BeforeExp = $nubExp-2;
	$NExp = $nubExp-1;
	
	// ของรอบตรวจมิเตอร์
	$qrynub=pg_query("SELECT ta_array1d_count(carregis.\"CreateCheckRound\"('$C_StartDate','2'))");
	list($nubMeter)=pg_fetch_array($qrynub);
	$BeforeMeter = $nubMeter-2;
	$NMeter = $nubMeter-1;
	
	//หาก่อนรอบสุดท้าย 1 รอบ ของตรวจสอบภาษีรถยนต์
	$qrydata=pg_query("SELECT ta_array1d_get(carregis.\"CreateCheckRound\"( '$C_StartDate','1'),'$BeforeExp')");
	list($beforenddate)=pg_fetch_array($qrydata);
	
	//หารอบสุดท้ายของตรวจสอบภาษีรถยนต์
	$qrydata=pg_query("SELECT ta_array1d_get(carregis.\"CreateCheckRound\"( '$C_StartDate','1'),'$NExp')");
	list($enddate)=pg_fetch_array($qrydata);
	
	//หาก่อนรอบสุดท้าย 1 รอบ ของรอบตรวจมิเตอร์
	$qrydata=pg_query("SELECT ta_array1d_get(carregis.\"CreateCheckRound\"( '$C_StartDate','2'),'$BeforeMeter')");
	list($beforenddate_meter)=pg_fetch_array($qrydata);
	
	//หารอบสุดท้ายของรอบตรวจมิเตอร์
	$qrydata=pg_query("SELECT ta_array1d_get(carregis.\"CreateCheckRound\"( '$C_StartDate','2'),'$NMeter')");
	list($enddate_meter)=pg_fetch_array($qrydata);
	
	//เงือนการแสดงข้อความวันหมดอายุการใช้งานรถ
	if($ssdate>$beforenddate && $ssdate<$enddate){
		$text = "วันที่หมดอายุการใช้งานรถ คือ  $enddate";
	} else if($ssdate==$enddate){
		$text = "หมดอายุการใช้งานรถวันนี้!";
	} else {
		$hidden = "hidden";
	}
}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8">
    <meta http-equiv="Pragma" content="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}
$(function(){
    $(window).bind("beforeunload",function(event){
        var msg="ยืนยันการปิดหน้านี้?";
        $(window).bind("unload",function(event){
            event.stopImmediatePropagation();
            closeAll();
            $.post("logs_any.php","idno=<?php echo $idno; ?>&idmenu=P05");
        });
        return msg;
    });
    
    $("#MenuReturnSearchCusPayment").click(function(){ // กรณีคลิกกลับไปค้นหาใหม่ ไม่ต้องแสดง การแจ้งเตือน
        $(window).unbind("beforeunload");
        closeAll();
        $.post("logs_any.php","idno=<?php echo $idno; ?>&idmenu=P05");
    });
    
    $("#MenuCloseCusPayment").click(function(){ // กรณีคลิกกลับไปค้นหาใหม่ ไม่ต้องแสดง การแจ้งเตือน
        $(window).unbind("beforeunload");
    });
    
    
    $("input").click(function(){ // กรณีคลิกปุ่ม ไม่ต้องแสดง การแจ้งเตือน
        $(window).unbind("beforeunload");
    });
});

$.post("logs_any.php","idno=<?php echo $idno; ?>&idmenu=P05");

/*
$(function(){
    $(window).bind("beforeunload",function(event){
        closeAll();
    });
});
*/
</script> 
<script language=javascript>   
//เพิ่ม function โดยจะทำงาน มื่อ กดปุ่ม ขอยกเลิก
function refreshDeleteData(TName,IdCarTax) 
{
	if(confirm('คุณต้องการ ขอยกเลิก ใช่หรือไม่ ?')==true)
	{
		popU('frm_note_appv.php?typeDep='+TName+'&idcarTax='+IdCarTax+'&permit=no','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600');		
	}
}

function refreshDeleteDataPermit(TName,IdCarTax) 
{ // ยอมให้ลบได้ แม้ ยอดค้างชำระนี้มีการคิดต้นทุนไว้แล้ว ก็ตาม
	popU('popup_del_cal_cuspayment.php?typeDep='+TName+'&idcarTax='+IdCarTax+'','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=200');
}
</script>

<style type="text/css">
<!--
body {font-family:tahoma; color : #333333; font-size:12px;}
H1 {font-family:tahoma; color : #333333; font-size:28px;}
A { font-size:12px; text-decoration:none;}
A:hover { color : #8B8B8B; font-size:12px; text-decoration:none;}
A:visited { color : #333333; font-size:12px; text-decoration:none;} 
input,select{font-family:tahoma; color : #333333; font-size:12px;}
.header{
    text-align:center;       
}
.wrapper{
    width:700; float:center; padding:0px;
}
legend{
    font-family: Tahoma;
    font-size: 14px;    
    color: #0000CC;
}
legend A{ color : #0000CC; font-size: 14px; text-decoration:none;}
legend A:hover{ color : #0000CC; font-size: 14px; text-decoration:none;}
legend A:visited{ color : #0000CC; font-size: 14px; text-decoration:none;}
fieldset{
    padding:3px;
}
.text_gray{
    color:gray;
}
.text_comment{
    color:red;
    font-size: 11px;
}
.odd{
    background-color:#FFFFD7;
    font-size:11px
}
.even{
    background-color:#FFFFCA;
    font-size:11px
}
.result {
    /*font-size: 11px;
    line-height: 20px;*/
    height: 400px;
    width: 100%;
    overflow: auto;
    border: 0px solid #C0C0C0;
    background-color: #FFFFFF;
    padding: 0 0 0 0;
    margin: 0 0 0 0;
}
-->
</style>

<script language="JavaScript">
       var HttPRequest = false;

       function doCallAjax() {
          HttPRequest = false;
          if (window.XMLHttpRequest) { // Mozilla, Safari,...
             HttPRequest = new XMLHttpRequest();
             if (HttPRequest.overrideMimeType) {
                HttPRequest.overrideMimeType('text/html');
             }
          } else if (window.ActiveXObject) { // IE
             try {
                HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
             } catch (e) {
                try {
                   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
             }
          } 
          
          if (!HttPRequest) {
             alert('Cannot create XMLHTTP instance');
             return false;
          }
    
            var url = 'ajax_query.php';
            var pmeters = 'signDate='+document.getElementById("signDate").value;
            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);
            
            HttPRequest.onreadystatechange = function()
            {

                 if(HttPRequest.readyState == 3)  // Loading Request
                  {
                   document.getElementById("mySum").innerHTML  = "Now is Loading...";
                  }

                 if(HttPRequest.readyState == 4) // Return Request
                  {
                   document.getElementById("mySum").innerHTML  = HttPRequest.responseText;
                  }
                
            }

            /*
            HttPRequest.onreadystatechange = call function .... // Call other function
            */

       }
</script>

</head>
<body>

<?php
$menu = $_GET['menu'];

if($menu == "outcus"){
    include "menu_outcus.php";
}else{
    include "menu.php";
}
?>

<?php
//===================== หาการโอนสิทธิ์ =====================//
$search_under_idno = $idno; //ค้นหาว่า โอนต่อให้ IDNO อื่นๆ หรือไม่
do{
    $qry_underlv=pg_query("select \"P_TransferIDNO\",\"asset_id\",\"asset_type\" from \"Fp\" WHERE \"IDNO\"='$search_under_idno'");
    if($res_underlv=pg_fetch_array($qry_underlv)){
        $P_TransferIDNO=$res_underlv["P_TransferIDNO"];
		$asset_id = $res_underlv['asset_id'];
		$asset_type = $res_underlv['asset_type'];
        if(!empty($P_TransferIDNO)){
            $list_idno[]=$P_TransferIDNO;
            $search_under_idno = $P_TransferIDNO;
        }else{
            $search_under_idno = "";
        }
    }
}while(!empty($search_under_idno)); //จบ ค้นหาว่า โอนต่อให้ IDNO อื่นๆ หรือไม่

$list_idno = @array_reverse($list_idno);// สลับค่า array หน้าไปหลัง / หลังไปหน้า

$list_idno[] = $idno;//ใส่ IDNO หลักที่ค้นหาลงไป (IDNO กลาง)

$search_top_idno = $idno; //ค้นหาว่า ได้โอนมาจาก IDNO อื่นๆ หรือไม่
do{
    $qry_toplv=pg_query("select \"IDNO\",\"asset_id\",\"asset_type\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top_idno'");
    if($res_toplv=pg_fetch_array($qry_toplv)){
        $list_idno[]=$res_toplv["IDNO"];
        $search_top_idno=$res_toplv["IDNO"];
		$asset_id = $res_toplv['asset_id'];
		$asset_type = $res_toplv['asset_type'];
    }else{
        $search_top_idno = "";
    }
}while(!empty($search_top_idno)); //จบ ค้นหาว่า ได้โอนมาจาก IDNO อื่นๆ หรือไม่

$list_idno = @array_reverse($list_idno);// สลับค่า array หน้าไปหลัง / หลังไปหน้า
$_SESSION["ses_list_idno"]=$list_idno;
//===================== จบ หาการโอนสิทธิ์ =====================//

//หาเลขเครื่อง

if($asset_type == 1){
    $qry_2=pg_query("select \"C_MARNUM\" from \"Carregis_temp\" WHERE \"IDNO\"='$idno' order by auto_id DESC limit 1");
    if($res_2=pg_fetch_array($qry_2)){    
        $C_MARNUM = $res_2['C_MARNUM'];
    }
	
}else{
    $qry_2=pg_query("select \"marnum\" from \"FGas\" WHERE (\"GasID\"='$asset_id');");
    if($res_2=pg_fetch_array($qry_2)){  
        $C_MARNUM = $res_2['marnum'];
    }
}

$Cusidshow = $_SESSION["ses_scusid"];
?>
 
<div class="wrapper">

<form method="post" action="frm_cal_cuspayment.php?menu=<?php echo $menu; ?>" name="f_list" id="f_list">
<div>
<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td align="left" valign="middle" colspan="3">
            <b>คำนวณยอด ถึงวันที่</b>
            <input type="text" size="12" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo $ssdate; ?>" />
            <input name="button2" type="button" onclick="displayCalendar(document.f_list.signDate,'yyyy-mm-dd',this)" value="ปฏิทิน" /><input name="btnButton" id="btnButton" type="submit" value="คำนวณ" />
        </td align = "right" valign="middle">
		<?php
		//หาจำนวนงวดที่ต้องจ่ายทั้งหมด
			$count_idno = count($list_idno);
			$notpay = 0;
			
				$qry_num = pg_query("select \"IDNO\" from \"VCusPayment\" WHERE  \"IDNO\"='$NewIDNO' AND \"R_Receipt\" is null");
				$nub=pg_num_rows($qry_num);
				$notpay = $nub;
			
		//เหลือ 
		if($notpay>3 || $notpay==0 || empty($notpay)){
			$hidden="hidden";
		} else {
			$hidden="";
		}
		?>
		<td>
			<b <?php echo $hidden ?> ><font size="20" color="#FFBF00">เหลือ <?php echo $notpay;?> งวด </font><b>
		</td>
    </tr>
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td valign="middle" colspan="3">	
			<table>
				<tr>
					<td>
						<b>ชื่อ/สกุล</b> (<font color="#FF1493"><u><a style="cursor:pointer;" onclick="javascipt:popU('../nw/search_cusco/index.php?cusid=<?php echo $Cusidshow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><?php echo $Cusidshow; ?></a></u></font>) <?php echo $fullname. " (".$idno.") "; ?>
						<?php   $qry_data_guan=pg_query("select distinct(a.\"numid\") as numid2 from \"nw_linksecur\" a left join \"nw_linkIDNO\" c on a.\"numid\"=c.\"numid\" where c.\"IDNO\" = '$idno'");
				$rowschk = pg_num_rows($qry_data_guan);

							
				if($rowschk == 0){
					echo "<font color=\"black\"><u>ไม่มีหลักทรัพย์ค้ำประกัน</u></font>";
				}else{	?>
					<span onclick="javasript:popU('frm_guan_estate.php?idno=<?php echo $idno ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=400')" style="cursor:pointer;"><font color="red"><u>ดูข้อมูลการใช้หลักทรัพย์ค้ำประกัน</u></font></span>		
				<?php 
				}

				if($rowschk >0){ //กรณีที่มีหลักทรัพย์ค้ำประกัน
					//ตรวจสอบว่าคืนสินทรัพย์หมดหรือยัง
					$qrychk=pg_query("select a.\"numid\" from \"nw_linksecur\" a 
					left join \"nw_linkIDNO\" b on a.\"numid\"=b.\"numid\" 
					left join \"nw_linknumsecur\" c on a.\"numid\"=c.\"numid\" 
					left join  \"nw_securities\" d on c.\"securID\"=d.\"securID\" 
					where b.\"IDNO\" = '$idno' and d.\"cancel\"='FALSE'");
					$numchk=pg_num_rows($qrychk);
					
					if($numchk==0){ //ถ้าไม่พบสินทรัพย์เลย แสดงว่าคืนให้ลูกค้าหมดแล้ว
						echo "<font color=red>(คืนหลักทรัพย์ค้ำประกันกับลูกค้าหมดแล้ว!)</font>";
					}
				}
				?> 
				<input type="button" name="allCus" value="ลูกค้าเข้าร่วมทั้งหมด" onclick="javasript:popU('frm_cal_all_cuspayment.php?idno=<?php echo $idno; ?>&carid=<?php echo $carid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=680,height=400')" style="cursor:pointer;" <?php if($nub_data==0){echo "hidden";}?> >
					</td>
				</tr>
				<tr>
					<td>
						<b>ผู้เข้าร่วมคนปัจจุบัน: <?php if($nub_data==0) { echo "ไม่พบข้อมูลเข้าร่วม";} else { ?></b>(<font color="#FF1493"><u><a style="cursor:pointer;" onclick="javascipt:popU('../nw/search_cusco/index.php?cusid=<?php echo $NewCusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><?php echo $NewCusID; ?></a></u></font>) <?php echo $NewjoinName;?> 
						<u><font color="#1E90FF"><a style="cursor:pointer;" onclick="javascript:popU('../nw/join_payment/extensions/ta_join_payment/pages/ta_join_payment_view_new.php?idno=<?php echo $NewIDNO; ?>&cusid=<?php echo $NewCusID;?>&pmenu=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=680')">ข้อมูลค่าเข้าร่วม</a><font></u>
						<?php } ?>
					</td>
				</tr>
			</table>
		</td>
        <td align="right" colspan="1">
		<input type="button" value="พิมพ์รายงาน" onclick="window.open('frm_print_cal_cuspayment.php?idno=<?php echo $idno; ?>&date=<?php echo $ssdate; ?>&f_date=<?php echo $f_date; ?>&stdate=<?php echo $stdate; ?>&ldate=<?php echo $ldate; ?>&status=1')">
		<input type="button" value="พิมพ์สำหรับศาล/ลูกค้า" onclick="window.open('frm_print_cal_cuspayment.php?idno=<?php echo $idno; ?>&date=<?php echo $ssdate; ?>&f_date=<?php echo $f_date; ?>&stdate=<?php echo $stdate; ?>&ldate=<?php echo $ldate; ?>&status=2')">
		</td>
    </tr>
	<?php
		//หาค่าภาษี
		$query_tax=pg_query("select b.\"C_TAX_MON\",\"P_BEGIN\" from \"Fp\" a
		left join \"Fc\" b on a.\"asset_id\" = b.\"CarID\"
		where a.\"IDNO\" = '$idno'");
		if($res_tax=pg_fetch_array($query_tax)){
			$C_TAX_MON = $res_tax["C_TAX_MON"];
			$P_BEGIN = $res_tax['P_BEGIN'];
			
		}
		
		//หายอดจัดรวมอุปรกณ์
			//หาว่าเป็นสัญญาแบบ Package หรือไม่
			$qry_ispackage = pg_query("SELECT \"fpackID\" FROM \"Fp_interest\" where \"IDNO\" = '$idno' AND \"fpackID\" IS NOT NULL ");
			$ispack = pg_num_rows($qry_ispackage);
			//หากมีข้อมูลแสดงว่าเป้นแบบ Package
			if($ispack){
				$re_ispack = pg_fetch_array($qry_ispackage);
				$packid = $re_ispack["fpackID"]; //รหัส package
				//หายอดจัด
				$qry_packbegin = pg_query("	SELECT (\"price_accessory\" - \"down_payment\") as \"packbegin\"
											FROM \"Fp_package\" 
											where \"fpackID\" = '$packid'
										  ");
				list($packbegin) = pg_fetch_array($qry_packbegin);
				$txtpackbegin = " (<font color=\"red\"><b>ยอดจัดรวมอุปกรณ์ ".number_format($packbegin,2)."</b></font>) ";
			
			}
		
		// หาวันที่หมดอายุใช้งานกรณีรถแท็กซี่
		if($C_StartDate != "")
		{
			if($C_StartDate <= "2005-12-26")
			{
				$qry_C_CloseDate = pg_query("select ('$C_StartDate'::date + INTERVAL '12 year')::date");
				$C_CloseDate = pg_result($qry_C_CloseDate,0);
			}
			elseif($C_StartDate > "2005-12-26")
			{
				$qry_C_CloseDate = pg_query("select ('$C_StartDate'::date + INTERVAL '9 year')::date");
				$C_CloseDate = pg_result($qry_C_CloseDate,0);
			}
		}
	?>
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td align="left" valign="middle"><b>วันทำสัญญา</b> <?php echo $f_dateth; ?><br><b>ทะเบียน</b> <?php echo "$regis $C_REGISnew"; ?><br><b>เลขตัวถัง</b> <a href="../up/frm_show.php?id=<?php echo $r_number; ?>&type=reg&mode=2" target="_blank"><u><?php echo $r_number; ?></u></a><br><b>เลขเครื่องยนต์</b> <?php echo $C_MARNUM;?></td>
        <td valign="middle"><b>RadioID</b> <?php echo $s_radioid; ?><br><b>ประเภทรถ</b> <?php echo $s_ccarname; ?><br><b>สีรถ</b> <?php echo $s_ccolor; ?><br><b>ค่าภาษี</b> <?php echo number_format($C_TAX_MON,2); ?>  บาท <font color="red">(ข้อมูลยังไม่นิ่ง)</font></td>
        <td align="right" valign="middle"><b>ค่างวดไม่รวม VAT</b> <?php echo number_format($s_payment_nonvat,2); ?><br><b>VAT</b> <?php echo number_format($s_payment_vat,2); ?><br><b>ค่างวดรวม VAT</b> <u><?php echo number_format($s_payment_all,2); ?></u><br><b>ยอดจัด</b> <?php echo number_format($P_BEGIN,2)?><?php echo $txtpackbegin ?></td>
        <td align="right" valign="middle">
        <b>Deposit Balance</b> <span style="color:red; font-weight:bold;"><?php echo number_format($s_dp_balance,2); ?></span><br>
        <b>จำนวนงวดทั้งหมด</b> <?php echo $s_fp_ptotal; ?><br>
        <b>วันที่จดทะเบียน : </b> <?php echo $C_StartDate; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span onclick="javasript:popU('frm_check_round.php?idno=<?php echo $idno ?>&startdate=<?php echo $C_StartDate;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=600')" style="cursor:pointer;" title="แสดงรอบตรวจภาษีรถยนต์/มิเตอร์"><b><u>วันที่หมดอายุภาษี </u>: </b></span><?php echo $s_expdate; ?><br>
        <b>วันที่หมดอายุใช้งานกรณีรถแท็กซี่ (อยู่ระหว่างทดสอบ) : </b> <?php echo $C_CloseDate; ?><br>
		<b>ปีรถ</b> <?php echo $s_year; ?><br>
		<b <?php echo $hidden; ?> ><font size="2" color="#FFOOOO"><?php echo $text; ?></font></b></td>
    </tr>

<?php
$sum_outstanding1 = 0;
$qry_inf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $sum_outstanding1 = $res_inf["sum_outstanding"];
}

$sum_outstanding2 = 0;
$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding2 = $res_inuf["sum_outstanding"];
}

$sum_outstanding3 = 0;
$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsLiveDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding3 = $res_inuf["sum_outstanding"];
}

$qry_amt=pg_query("select \"CusAmt\",\"TypeDep\",\"IDCarTax\" from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDNO\"='$idno' ");
$nub_amt = pg_num_rows($qry_amt);

$user_id = $_SESSION["av_iduser"]; //ดึง รหัส ของผู้ใช้
//ตรวจสอบ สิทการการดำเนินการ ของ ผู้ใช้
$qry_emplevel=pg_query("select \"emplevel\" from \"fuser\" WHERE  \"id_user\"='$user_id'");
$res_emplevel=pg_fetch_array($qry_emplevel);
$emplevel=$res_emplevel["emplevel"];
$Approlevel=false;
if($emplevel<=1){$Approlevel=true;}

if($nub_amt > 0 OR $sum_outstanding1 > 0 OR $sum_outstanding2 > 0 OR $sum_traffic2 > 0){
?>
    <tr bgcolor="#FFC0C0" align="left">
        <td colspan="4"><b>ยอดค้าง</b></td>
    </tr>
<?php
}

 //ยอดค้าง (แถบสีชมพู)
while($res_amt=pg_fetch_array($qry_amt)){
    $CusAmt = $res_amt["CusAmt"];
    $TypeDep = $res_amt["TypeDep"];
	$IdCarTax= $res_amt["IDCarTax"];
    
    $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
    if($res_nn=pg_fetch_array($qry_nn)){
        $TName = $res_nn["TName"];
    }
    //ตรวจสอบว่าอยู่ระหว่างการขออนุมัติหรือไม่
	$qry_wait_app=pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue_reserve\" WHERE \"IDCarTax\" = '$IdCarTax' AND \"Approved\"='9' ");
	$nub_wait_app = pg_num_rows($qry_wait_app);
	
    if($CusAmt != 0){
		//todo : แก้ไขปัญหาชั่วคราวโดยการทำซ่อนการแสดงหนี้ เฉพาะค่าตรวจมิเตอร์ กรณีที่ทะเบียนรถยนต์ NOT LIKE 'ท%' AND NOT LIKE 'ม%' เนื่องจากรถบ้านไม่มีค่านี้
		if(mb_substr($regis,0,1,"utf-8")=="ท" || mb_substr($regis,0,1,"utf-8")=="ม")
		{
?>			<form name="frm" method="post" action="frm_note_app.php">
            <tr bgcolor="#FFC0C0" align="left">
                <td><?php echo $TName; ?></td><td id="cusAmt" name="cusAmt" colspan="2"><?php echo number_format($CusAmt,2); ?></td>
				<!--เพิ่ม ปุ่ม ขอยกเลิก-->	
				<?php
					if($nub_wait_app > 0){ ?>
					<td align="right"><input type="button" value="อยู่ระหว่างรอการอนุมัติยกเลิก" disabled  title="อยู่ระหว่างรอการอนุมัติยกเลิก" ></td>	
					<?php } else {
				?>	
					<td align="right"><input type="button" value="ขอยกเลิก" <?php echo "onclick=\"refreshDeleteData('$TName','$IdCarTax')\"";?>></td>	
				<?php } ?>
            </tr>			
			</form>
<?php
		}
		else
		{
			if($TName!="ตรวจมิเตอร์")
			{
				?>
                <tr bgcolor="#FFC0C0" align="left">
                    <td><?php echo $TName; ?></td><td  id="cusAmt" name="cusAmt" colspan="2"><?php echo number_format($CusAmt,2); ?></td>
					<!--เพิ่ม ปุ่ม ขอยกเลิก-->		
				<?php
					if($nub_wait_app > 0){ ?>	
						<td align="right"><input type="button" value="อยู่ระหว่างรอการอนุมัติยกเลิก" disabled  title="อยู่ระหว่างรอการอนุมัติยกเลิก" ></td>
			<?php } else {	?>	
						<td align="right"><input type="button"  value="ขอยกเลิก" <?php echo "onclick=\"refreshDeleteData('$TName','$IdCarTax')\"";?>></td>	
				<?php } ?>						
				</tr>
                <?php
			}
		}
    }
}

?>    
    
<?php if($sum_outstanding1 != 0){ ?>
    <tr bgcolor="#FFC0C0" align="left">
        <td>ประกันภัยภาคบังคับ (พรบ.)</td><td colspan="2"><?php echo number_format($sum_outstanding1,2); ?></td>
		<td></td>
    </tr>
<?php
    }
    if($sum_outstanding2 != 0){
?>
    <tr bgcolor="#FFC0C0" align="left">
        <td>ประกันภัยภาคสมัครใจ</td><td colspan="2"><?php echo number_format($sum_outstanding2,2); ?></td>		
		<td></td>
    </tr>
<?php
    }
	if($sum_outstanding3 != 0){
?>
    <tr bgcolor="#FFC0C0" align="left">
        <td>ประกันภัยคุ้มครองหนี้</td><td colspan="2"><?php echo number_format($sum_outstanding3,2); ?></td>		
		<td></td>
    </tr>
<?php
    }

$qry_fr=pg_query("select a.\"IDNO\" from \"nw_seize_car\" a 
left join \"NTHead\" b on a.\"IDNO\" = b.\"IDNO\" and a.\"NTID\" = b.\"NTID\"
where b.\"cancel\" = 'FALSE' and b.\"CusState\" = '0' and a.\"status_approve\" = '3' and b.\"IDNO\" ='$idno'"); 
$num_fr=pg_num_rows($qry_fr);

if($s_LAWERFEE == 't' || $s_ACCLOSE == 't' || $s_StopVat == 't' || $repo == 't' || $num_fr > 0){

?>
    <tr bgcolor="#E6FFE6">
        <td align="center" colspan="4">
<?php 
if($s_LAWERFEE == 't'){
	//ตรวจสอบว่ามีการออก NT หรือยังจากตาราง nw_statusNT
	$query_notice=pg_query("select \"statusNT\" from \"nw_statusNT\" a
			left join \"NTHead\" b on a.\"NTID\" = b.\"NTID\" 
			where a.\"IDNO\"='$idno' and b.\"CusState\"='0' and b.cancel='FALSE'");
	$res_notice=pg_fetch_array($query_notice);
	$statusNT=$res_notice["statusNT"];
	if($statusNT == 1 || $statusNT == 3 || $statusNT == 4 || $statusNT == 5 || $statusNT == ""){
		echo '<img src="picflash1.gif" border="0" width="120" height="50">';
	}
}
if($s_ACCLOSE == 't'){
	
    echo '<img src="picflash2.gif" border="0" width="120" height="50">';
}
if($s_StopVat == 't'){
    echo '<img src="picflash3.gif" border="0" width="120" height="50">';
}
if($repo == 't'){
	echo '<img src="picflashnv_01.gif" border="0" width="120" height="50">';
}
if($num_fr > 0){
	echo '<img src="picflashnv_02.gif" border="0" width="120" height="50">';
}

if($chkpaytype=="CC" and $s_ACCLOSE=='t' and $s_StopVat=='t' and $f_date==$f_cldate and $f_date==$f_stopvatdate){
	echo '<img src="picflashnv_03.gif" border="0" width="120" height="50">';
}

if($nowDate >= $enddate_meter){
	echo '<img src="images/car_has_expired.gif" border="0" width="120" height="50">';
}elseif($nowDate >= $beforenddate_meter){
	echo '<img src="images/car_expiring.gif" border="0" width="120" height="50">';
}

// ตรวจสอบว่า ถอดป้ายหรือยัง
$qry_remove_label = pg_query("SELECT m.cancel FROM public.\"VJoinMain\" m left join \"VJoin\" v on m.idno=v.\"IDNO\" WHERE v.\"IDNO\" = '$idno'");
$remove_label = pg_result($qry_remove_label,0);
if($remove_label == 1)
{
	echo '<img src="images/remove_label.gif" border="0" width="120" height="50">';
}
?>
        </td>
    </tr>
<?php
}
?>
</table>


<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0"  align="center">
    <?php
	if($s_StopVat == 't'){
	?>
	<tr><td colspan="13" align="center">
	<?php
		echo "(<b>มีการหยุดส่ง VAT เมื่อวันที่ : </b>$stopVatDate)";
	?>
		</td>
	</tr>
	<?php }?>
	<tr bgcolor="#A8D3FF" style="font-size:11px"  align="center" valign="middle">
        <td width="7%">DueNo.</td>
        <td width="7%">DueDate<br />(วันนัดจ่าย)</td>
        <td width="8%">R_Date<br />(วันทีี่จ่าย)</td>
        <td width="7%">daydelay<br />(วันจ่ายล่าช้า)</td>
        <td width="8%">caldelay<br />(ยอดจ่ายล่าช้า)</td>
        <td width="10%">R_Receipt<br />(เลขที่ใบเสร็จ)</td>
        <td width="7%">PayType</td>
        <td width="7%">V_Receipt<br />(เลขที่ใบvat)</td>
        <td width="7%">V_date<br />(วันที่จ่ายvat)</td>
        <td width="6%">ค่างวดรวม<br>vat</td>
        <td width="6%">ยอดต้อง<br>ชำระ</td>
        <td width="10%">ยอดค้างเช่าซื้อ<br>ทั้งหมด รวม vat</td>
        <td width="10%">ยอดค้างเช่าซื้อ<br>ทั้งหมด ไม่รวม vat</td>
    </tr>
</table>
</div>
<div style="clear:both"></div>
<div class="result"><!-- DIV RESULT -->

<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0"  align="center">

<?php
$count_idno = count($list_idno);
for($b=0; $b<$count_idno; $b++){ // วนลูป IDNO ทั้งหมด
    $b_plus=$b+1;
    $qry_VCusPayment=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
    $res_VCusPayment=pg_fetch_array($qry_VCusPayment);
    $stdate=$res_VCusPayment["DueDate"];

    $qry_VCusPayment_last=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$list_idno[$b]') order by \"DueDate\" desc LIMIT(1)");
    $res_VCusPayment_last=pg_fetch_array($qry_VCusPayment_last);
    $ldate=$res_VCusPayment_last["DueDate"];

    $qry_FpFa1=pg_query("select \"P_MONTH\",\"P_VAT\",\"P_TOTAL\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$list_idno[$b]'");
    $res_FpFa1=pg_fetch_array($qry_FpFa1);
        $s_payment_nonvat = $res_FpFa1["P_MONTH"];
        $s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
        $s_fp_ptotal = $res_FpFa1["P_TOTAL"];

    $money_all_in_vat = $s_payment_all*$s_fp_ptotal;
    $money_all_no_vat = $s_payment_nonvat*$s_fp_ptotal;


    $qry_fullname=pg_query("select \"full_name\" from \"VContact\" WHERE \"IDNO\"='$list_idno[$b]'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $full_name=$res_fullname["full_name"];
    }
    
    if($b==0){
        echo "<tr style=\"font-size:12px; background-color:#F0F0F0; font-weight:bold\">
        <td colspan=11>ลำดับที่ $b_plus : $full_name ($list_idno[$b])</td>
        <td align=right><b>".number_format($money_all_in_vat,2). "</b></td>
        <td align=right><b>".number_format($money_all_no_vat,2). "</b></td>
        </tr>";
    }else{
        echo "<tr style=\"font-size:12px; background-color:#F0F0F0; font-weight:bold\">
        <td colspan=11>ลำดับที่ $b_plus : $full_name ($list_idno[$b])</td>
        <td align=right><b>".number_format($tmp_1,2). "</b></td>
        <td align=right><b>".number_format($tmp_2,2). "</b></td>
        </tr>";
    }

    
    if(($b_plus) != $count_idno){

    $qry_before=pg_query("select \"DueNo\",\"DueDate\",\"R_Date\",\"daydelay\",\"CalAmtDelay\",\"R_Receipt\",
	\"R_Bank\",\"PayType\",\"V_Receipt\",\"V_Date\",\"R_Money\",\"VatValue\"
	from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
    while($resbf=pg_fetch_array($qry_before)){
?>
    <tr style="font-size:11px; background-color:#B3DBAE;" align=center>
        <td width="7%"><?php echo $resbf["DueNo"]; ?></td>
        <td width="7%"><?php echo $resbf["DueDate"]; ?></td>
        <td width="8%"><?php echo $resbf["R_Date"]; ?></td>
        <td width="7%"><?php echo $resbf["daydelay"]; ?></td>
        <td width="8%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%"><?php echo $resbf["R_Receipt"]; ?></td>
        <td width="7%"><?php if(empty($resbf['R_Bank']) && empty($resbf['PayType'])){ }else{ echo "$resbf[R_Bank] / $resbf[PayType]"; } ?></td>
        <td width="7%"><?php echo $resbf["V_Receipt"]; ?></td>
        <td width="7%"><?php echo $resbf["V_Date"]; ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["R_Money"]+$resbf["VatValue"],2); ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($resbf["DueNo"]*$s_payment_all) ,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat),2); ?></td>
    </tr>
<?php
    $tmp_1 = $money_all_in_vat-($resbf["DueNo"]*$s_payment_all);
    $tmp_2 = $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat);
}//จบ หารายการที่ชำระแล้ว

    }else{//else แบ่งรายปัจจุบัน

$qry_before=pg_query("select \"DueNo\",\"DueDate\",\"R_Date\",\"daydelay\",\"CalAmtDelay\",\"R_Receipt\",
	\"R_Bank\",\"PayType\",\"V_Receipt\",\"V_Date\",\"R_Money\",\"VatValue\" from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
while($resbf=pg_fetch_array($qry_before)){
?>
    <tr style="font-size:11px; background-color:#B3DBAE;" align=center>
        <td width="7%"><?php echo $resbf["DueNo"]; ?></td>
        <td width="7%"><?php echo $resbf["DueDate"]; ?></td>
        <td width="8%"><?php echo $resbf["R_Date"]; ?></td>
        <td width="7%"><?php echo $resbf["daydelay"]; ?></td>
        <td width="8%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%"><?php echo $resbf["R_Receipt"]; ?></td>
        <td width="7%"><?php if(empty($resbf['R_Bank']) && empty($resbf['PayType'])){ }else{ echo "$resbf[R_Bank] / $resbf[PayType]"; } ?></td>
        <td width="7%"><?php echo $resbf["V_Receipt"]; ?></td>
        <td width="7%"><?php echo $resbf["V_Date"]; ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["R_Money"]+$resbf["VatValue"],2); ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($resbf["DueNo"]*$s_payment_all) ,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat),2); ?></td>
    </tr>
<?php
    $sumamt+=$resbf["CalAmtDelay"];
    $last_DueDate = $resbf["DueDate"];
    $sumamt2+=$resbf["CalAmtDelay"];
}//จบ หารายการที่ชำระแล้ว
    
$qry_amt=@pg_query("select \"DueNo\",\"DueDate\",\"R_Receipt\",\"R_Bank\",\"PayType\",\"V_Receipt\",\"V_Date\" ,'$ssdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]')  AND (\"DueDate\" BETWEEN '$stdate' AND '$ssdate') "); //รายการที่คำนวณ
while($res_amt=@pg_fetch_array($qry_amt)){
    $s_amt=pg_query("select \"CalAmtDelay\"('$ssdate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);
?>
    <tr style="font-size:11px; background-color:#C6FFC6;" align=center>
        <td width="7%"><?php echo $res_amt["DueNo"]; ?></td>
        <td width="7%"><?php echo $res_amt["DueDate"]; ?></td>
        <td width="8%"><?php echo $ssdate; ?></td>
        <td width="7%"><?php echo $res_amt["dateA"]; ?></td>
        <td width="8%" align="right"><?php echo number_format($res_s,2); ?></td>
        <td width="10%"><?php echo $res_amt["R_Receipt"]; ?></td>
        <td width="7%"><?php if(empty($res_amt['R_Bank']) && empty($res_amt['PayType'])){ }else{ echo "$res_amt[R_Bank] / $res_amt[PayType]"; } ?></td>
        <td width="7%"><?php echo $res_amt["V_Receipt"]; ?></td>
        <td width="7%"><?php echo $res_amt["V_Date"]; ?></td>
        <td width="6%" align="right"><?php echo number_format($s_payment_all,2); ?></td>
        <td width="6%" align="right"><?php echo number_format($s_payment_all+$res_s,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($res_amt["DueNo"]*$s_payment_all) ,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($res_amt["DueNo"]*$s_payment_nonvat),2); ?></td>
    </tr>
<?php
    $sumamt2+=$res_s;
    $sum=$s_payment_all+$res_s;
    $x_sum=$x_sum+$sum;
    $last_DueDate = $res_amt["DueDate"];
} //จบ รายการที่คำนวณ
?>

<?php

//แสดงรายการทั้งหมด ถัดจากวัน DueDate ล่าสุด ที่จ่ายแล้ว หรือ วันถัดจากวัน DueDate ที่คำนวณ
$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($last_DueDate)));// วันถัดจาก Due ล่าสุด

$qry_l=@pg_query("select \"DueNo\",\"DueDate\",\"R_Date\",\"daydelay\",\"CalAmtDelay\",\"R_Receipt\",\"R_Bank\",\"PayType\",
\"V_Receipt\",\"V_Date\"
 from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"DueDate\" BETWEEN '$DateUpdate' AND '$ldate') ");
while($resl=@pg_fetch_array($qry_l)){
    $inum+=1;
    if($inum%2==0){
        echo "<tr class=\"odd\" align=center>";
    }else{
        echo "<tr class=\"even\" align=center>";
    }
?>
    <td width="7%"><?php echo $resl["DueNo"]; ?></td>
    <td width="7%"><?php echo $resl["DueDate"]; ?></td>
    <td width="8%"><?php echo $resl["R_Date"]; ?></td>
    <td width="7%"><?php echo $resl["daydelay"]; ?></td>
    <td width="8%" align="right"><?php echo number_format($resl["CalAmtDelay"],2); ?></td>
    <td width="10%"><?php echo $resl["R_Receipt"]; ?></td>
    <td width="7%"><?php if(empty($resl['R_Bank']) && empty($resl['PayType'])){ }else{ echo "$resl[R_Bank] / $resl[PayType]"; } ?></td>
    <td width="7%"><?php echo $resl["V_Receipt"]; ?></td>
    <td width="7%"><?php echo $resl["V_Date"]; ?></td>
    <td width="6%" align="right"><?php echo number_format($s_payment_all,2); ?></td>
    <td width="6%"></td>
    <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($resl["DueNo"]*$s_payment_all) ,2); ?></td>
    <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($resl["DueNo"]*$s_payment_nonvat),2); ?></td>
</tr>
<?php
}

    }//จบ แบ่งรายปัจจุบัน

}//จบ วนลูป IDNO ทั้งหมด

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}
?>

<tr style="background-color:#EAEAFF;">
    <td bgcolor="#B3DBAE"></td>
    <td align="left" colspan="2" bgcolor="#ffffff" style="font-size:11px;">= ยอดที่คำนวณได้</td>
    <td><div align="right"><b>รวม</b></div></td>
    <td><div align="right"><?php echo number_format($sumamt2,2); ?></div></td>
    <td colspan="5"><div align="right"><b>ยอดค้างทั้งหมด</b></div></td>
    <td align="right"><?php echo number_format($x_sum+$sumamt,2); ?></td>
    <td colspan="2"></td>
</tr>
<tr style="background-color:#EAEAFF;">
    <td bgcolor="#C6FFC6"></td>
    <td align="left" colspan="2" bgcolor="#ffffff" style="font-size:11px;">= ยอดที่ชำระแล้ว</td>
    <td><div align="right"><b>ชำระมา</b></div></td>
    <td><div align="right"><?php echo number_format($otherpay_amt,2); ?></div></td>
    <td colspan="5"><div align="right"><b>ดอกเบิ้ยที่ชำระแล้ว</b></div></td>
    <td align="right"><?php echo number_format($otherpay_amt,2); ?></td>
    <td colspan="2"></td>
</tr>
<tr style="background-color:#EAEAFF;">
    <td bgcolor="#FFFFD7"></td>
    <td align="left" colspan="2" bgcolor="#ffffff" style="font-size:11px;">= ยอดที่ยังไม่ชำระ</td>
    <td><div align="right"><b>คงค้าง</b></div></td>
    <td><div align="right"><?php echo number_format($sumamt2-$otherpay_amt,2); ?></div></td>
    <td colspan="5"><div align="right"><b>ยอดรวมที่ต้องชำระ</b></div></td>
    <td align="right"><?php echo number_format(($x_sum+$sumamt)-$otherpay_amt,2); ?></td>
    <td colspan="2"></td>
</tr>
</table>

</div><!-- END DIV RESULT -->

</form>

</div>

</body>
</html>