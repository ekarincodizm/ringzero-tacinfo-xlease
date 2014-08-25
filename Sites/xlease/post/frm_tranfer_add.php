<?php
session_start();
include("../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script> 
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script type="text/javascript">
$(document).ready(function(){
    $('#btn1').click(function(){
        if( $('#fa_cusid').val() == "" ){
            $.post('frm_tranfer_check.php',{
                addname: $('#add_name').val(),
                addsurname: $('#add_surname').val()
            },
            function(data){
                if(data.success){
                    $('#frm_edit').submit();
                }else{
                    alert(data.message);
                }
            },'json');
        }else{
            $('#frm_edit').submit();
        }
    });
});
</script>

<script type="text/javascript">
function clear_cus(){
    document.frm_edit.name.value='';
    document.frm_edit.name.focus();
    document.frm_edit.add_firstname.disabled = false; document.frm_edit.add_firstname.value = '';
    document.frm_edit.add_name.disabled = false; document.frm_edit.add_name.value = '';
    document.frm_edit.add_surname.disabled = false; document.frm_edit.add_surname.value = '';
    document.frm_edit.add_reg.disabled = false; document.frm_edit.add_reg.value = 'ไทย';
    document.frm_edit.add_birthdate.disabled = false; document.frm_edit.add_birthdate.value = '';
    document.frm_edit.add_pair.disabled = false; document.frm_edit.add_pair.value = '';
    document.frm_edit.add_card.disabled = false; document.frm_edit.add_card.value = '';
    document.frm_edit.add_address.disabled = false; document.frm_edit.add_address.value = '';
    document.frm_edit.add_idcard.disabled = false; document.frm_edit.add_idcard.value = '';
    document.frm_edit.add_moo.disabled = false; document.frm_edit.add_moo.value = '';
    document.frm_edit.add_dateidcard.disabled = false; document.frm_edit.add_dateidcard.value = '';
        document.frm_edit.buttonadd_dateidcard.disabled = false; document.frm_edit.add_dateidcard.value = '<?php echo nowDate(); ?>';
    document.frm_edit.add_soi.disabled = false; document.frm_edit.add_soi.value = '';
    document.frm_edit.add_bycard.disabled = false; document.frm_edit.add_bycard.value = '';
    document.frm_edit.add_road.disabled = false; document.frm_edit.add_road.value = '';
    document.frm_edit.add_contactadd.disabled = false; document.frm_edit.add_contactadd.value = '';
    document.frm_edit.add_tambon.disabled = false; document.frm_edit.add_tambon.value = '';
    document.frm_edit.add_ampur.disabled = false; document.frm_edit.add_ampur.value = '';
    document.frm_edit.add_province.disabled = false;
    document.frm_edit.fa_cusid.value = '';
}

function disable_cus(){
    document.frm_edit.name.value='';
    document.frm_edit.name.focus();
    document.frm_edit.add_firstname.disabled = true; document.frm_edit.add_firstname.value = '';
    document.frm_edit.add_name.disabled = true; document.frm_edit.add_name.value = '';
    document.frm_edit.add_surname.disabled = true; document.frm_edit.add_surname.value = '';
    document.frm_edit.add_reg.disabled = true; document.frm_edit.add_reg.value = 'ไทย';
    document.frm_edit.add_birthdate.disabled = true; document.frm_edit.add_birthdate.value = '';
    document.frm_edit.add_pair.disabled = true; document.frm_edit.add_pair.value = '';
    document.frm_edit.add_card.disabled = true; document.frm_edit.add_card.value = '';
    document.frm_edit.add_address.disabled = true; document.frm_edit.add_address.value = '';
    document.frm_edit.add_idcard.disabled = true; document.frm_edit.add_idcard.value = '';
    document.frm_edit.add_moo.disabled = true; document.frm_edit.add_moo.value = '';
    document.frm_edit.add_dateidcard.disabled = true; document.frm_edit.add_dateidcard.value = '';
        document.frm_edit.buttonadd_dateidcard.disabled = true; document.frm_edit.add_dateidcard.value = '<?php echo nowDate(); ?>';
    document.frm_edit.add_soi.disabled = true; document.frm_edit.add_soi.value = '';
    document.frm_edit.add_bycard.disabled = true; document.frm_edit.add_bycard.value = '';
    document.frm_edit.add_road.disabled = true; document.frm_edit.add_road.value = '';
    document.frm_edit.add_contactadd.disabled = true; document.frm_edit.add_contactadd.value = '';
    document.frm_edit.add_tambon.disabled = true; document.frm_edit.add_tambon.value = '';
    document.frm_edit.add_ampur.disabled = true; document.frm_edit.add_ampur.value = '';
    document.frm_edit.add_province.disabled = true;
}

function SetDate(){
    
    var HiddenDate = $('#hid_startDate').val();
    var HiddenDateArr  =  HiddenDate.split("-");
    
    var FDate = $('#f_startDate').val();
    var FDateArr = FDate.split("-");
    
    var hd = FDateArr[0]+"-"+FDateArr[1]+"-"+HiddenDateArr[2];
    
    if(HiddenDate > hd){
        $('#f_startDate').val(HiddenDate);
    }else{
        $('#f_startDate').val(hd);
    }
}

function ChkDate(){
    if( $("#f_pstdate").val() < '<?php echo $_POST["tranfer_rdate"]; ?>' ){
        alert("วันที่ไม่ถูกต้อง");
        $("#f_pstdate").val( '<?php echo nowDate(); ?>' );
    }
}
</script>

</head>
<body onload="disable_cus()">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>
        
<div class="header"><h1>โอนสิทธิ์ เช่าซื้อ</h1></div>
<div class="wrapper">
<?php
$nowdate = nowDate();
$edt_cusbyyear = $_POST["tranfer_cusbyyear"];
$edt_idno = $_POST["tranfer_idno"];
$edt_duenum = $_POST["tranfer_duenum"];
$edt_rdate = $_POST["tranfer_rdate"];
$edt_start_duedate = $_POST["tranfer_start_duedate"];
$edt_cus_compri = $_POST["tranfer_cus_compri"];
$edt_acc_compri = $_POST["tranfer_acc_compri"];
$edt_acc_commis = $_POST["tranfer_acc_commis"];
$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($edt_rdate))); 

list($n_year,$n_month,$n_day) = split('-',$nowdate);
list($a_year,$a_month,$a_day) = split('-',$edt_start_duedate);

//หาจำนวนวัน หรือวันที่สิ้นเดือนของเดือนและปีปัจจุบันที่เริ่มสัญญาใหม่
$qryday=pg_query("SELECT \"gen_numDaysInMonth\"('$n_month','$n_year')");
list($numday)=pg_fetch_array($qryday);

//กรณีวันที่ปัจจุบันมากกว่าจำนวนวันสิ้นเดือน ให้กำหนดใหม่เป็นวันที่สิ้นเดือนปัจจุบัน
// เช่น งวดเก่าต้องเริ่มวันที่ 31 แต่เดือนปัจจุบันเป็นเดือนมิถุนายน ดังนั้นวันที่ที่กำหนดใหม่คือวันที่ 30
if($a_day>$numday){ 
	$a_day=$numday;
}

$date_fix = $n_year."-".$n_month."-".$a_day;

$qry_fp=pg_query("select * from \"Fp\" where (\"IDNO\" ='$edt_idno')");
$res_fp=pg_fetch_array($qry_fp);
  
$fp_cusid=trim($res_fp["CusID"]);
$fp_carid=trim($res_fp["asset_id"]);
$fp_stdate=$res_fp["P_STDATE"];
$fp_pmonth=$res_fp["P_MONTH"];
$fp_pvat=$res_fp["P_VAT"];
$fp_ptotal=$res_fp["P_TOTAL"];
$fp_pdown=$res_fp["P_DOWN"];
$fp_pvatofdown=$res_fp["P_VatOfDown"];
$fp_begin=$res_fp["P_BEGIN"];
$fp_beginx=$res_fp["P_BEGINX"];
$fp_fdate=$res_fp["P_FDATE"];
$fp_cusby_year=$res_fp["P_CustByYear"];
$fp_Comm=$res_fp["Comm"];

if(empty($edt_cus_compri)){
	$edt_cus_compri=$fp_begin;
}

if(empty($edt_acc_compri)){
	$edt_acc_compri=$fp_beginx;
}

if(empty($tranfer_acc_commis)){
	$edt_acc_commis=$fp_Comm;
}

   
?>

<form id="frm_edit" name="frm_edit" method="post" action="frm_tranfer_add_ok.php">
<input type="hidden" name="tranfer_cusbyyear" value="<?php echo $edt_cusbyyear;?>">
<input type="hidden" name="tranfer_idno" value="<?php echo $edt_idno;?>">
<input type="hidden" name="tranfer_duenum" value="<?php echo $edt_duenum;?>">
<input type="hidden" name="tranfer_rdate" value="<?php echo $edt_rdate;?>">
<input type="hidden" name="tranfer_start_duedate" value="<?php echo $edt_start_duedate;?>">
<input type="hidden" name="tranfer_cus_compri" value="<?php echo $edt_cus_compri;?>">
<input type="hidden" name="tranfer_acc_compri" value="<?php echo $edt_acc_compri;?>">
<input type="hidden" name="tranfer_acc_commis" value="<?php echo $edt_acc_commis;?>">
<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
        <td colspan="6" style="background-color:#84C1FF;"><b>ข้อมูลผู้ทำสัญญา</b></td>
    </tr>
<?php
$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$fp_cusid' ");
$res_fa1=pg_fetch_array($qry_fa1);
$fa1_cusid=trim($res_fa1["CusID"]);
$fa1_firname=trim($res_fa1["A_FIRNAME"]);
$fa1_name=trim($res_fa1["A_NAME"]);
$fa1_surname=trim($res_fa1["A_SIRNAME"]);
$fa1_pair=trim($res_fa1["A_PAIR"]);
$fa1_no=trim($res_fa1["A_NO"]);
$fa1_subno=trim($res_fa1["A_SUBNO"]);
$fa1_soi=trim($res_fa1["A_SOI"]);
$fa1_rd=trim($res_fa1["A_RD"]);	
$fa1_tum=trim($res_fa1["A_TUM"]);	
$fa1_aum=trim($res_fa1["A_AUM"]);
$fa1_pro=trim($res_fa1["A_PRO"]);	
$fa1_post=trim($res_fa1["A_POST"]);
	  
$qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$fp_cusid' ");
$res_fn1=pg_fetch_array($qry_Fn);
?>
    <tr>
        <td width="13%"><b>ชื่อ-สกุล</b></td>
        <td width="37%"><?php echo $fa1_name."  ".$fa1_surname." (".$edt_idno.")"; ?></td>
        <td width="10%"><b>สัญชาติ</b></td>
        <td width="40%"><?php echo $res_fn1["N_SAN"]; ?></td>
    </tr>
    <tr>
        <td><b>อายุ</b></td>
        <td colspan="3"><?php echo $res_fn1["N_AGE"]; ?></td>
    </tr>
    <tr>
        <td><b>ชื่อ คู่สมรส</b></td>
        <td><?php echo $fa1_pair; ?></td>
        <td><b>บัตรแสดงตัว</b></td>
        <td><?php echo $res_fn1["N_CARD"]; ?></td>
    </tr>
    <tr>
        <td><b>เลขที่</b></td>
        <td><?php echo $fa1_no; ?></td>
        <td><b>เลขที่บัตร</b></td>
        <td><?php echo $res_fn1["N_IDCARD"]; ?></td>
    </tr>
    <tr>
        <td><b>หมู่ที่</b></td>
        <td><?php echo $fa1_subno; ?></td>
        <td><b>วันที่ออกบัตร</b></td>
        <td><?php echo $res_fn1["N_OT_DATE"]; ?></td>
    </tr>
    <tr>
        <td><b>ซอย</b></td>
        <td><?php echo $fa1_soi; ?></td>
        <td><b>ออกให้โดย</b></td>
        <td><?php echo $res_fn1["N_BY"]; ?></td>
    </tr>
    <tr>
        <td><b>ถนน</b></td>
        <td colspan="3"><?php echo $fa1_rd; ?></td>
    </tr>
    <tr>
        <td><b>แขวง/ตำบล</b></td>
        <td colspan="3"><?php echo $fa1_tum; ?></td>
    </tr>
    <tr>
        <td><b>เขต/อำเภอ</b></td>
        <td colspan="3"><?php echo $fa1_aum; ?></td>
    </tr>
    <tr>
        <td><b>จังหวัด</b></td>
        <td colspan="3"><?php echo $fa1_pro; ?></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    
    <tr>
        <td colspan="6" style="background-color:#84C1FF;"><b>ข้อมูลผู้รับโอน</b></td>
    </tr>
    <tr>
        <td colspan="4" bgcolor="#FFFFD9">
            <b>ตรวจสอบชื่อ : </b>ชื่อ-สกุล 
            <input name="fa_cusid" type="hidden" id="fa_cusid" value="">
            <input type="text" id="name" name="name" size="60" value="">
            <input type="button" name="buttonclear" onclick="clear_cus();" value="เพิ่มข้อมูลใหม่">
        </td>
    </tr>
    </table>
    
    <table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
        <td valign="top">

<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
        <td><b>คำนำหน้าชื่อ</b></td>
        <td><input type="text" id="add_firstname" name="add_firstname" size="10"></td>
    </tr>
    <tr>
        <td><b>ชื่อ</b></td>
        <td><input type="text" id="add_name" name="add_name" size="13"> <b>สกุล</b> <input type="text" id="add_surname" name="add_surname" size="13"></td>
    </tr>
    <tr>
        <td><b>ชื่อ คู่สมรส</b></td>
        <td><input type="text" id="add_pair" name="add_pair" value=""></td>
    </tr>
    <tr>
        <td><b>เลขที่</b></td>
        <td><input type="text" id="add_address" name="add_address" value=""></td>
    </tr>
    <tr>
        <td><b>หมู่ที่</b></td>
        <td><input type="text" id="add_moo" name="add_moo" value=""></td>
    </tr>
    <tr>
        <td><b>ซอย</b></td>
        <td><input type="text" id="add_soi" name="add_soi" value=""></td>
    </tr>
    <tr>
        <td><b>ถนน</b></td>
        <td><input type="text" id="add_road" name="add_road" value=""></td>
    </tr>
    
    <tr>
        <td><b>แขวง/ตำบล</b></td>
        <td><input type="text" id="add_tambon" name="add_tambon" value=""></td>
    </tr>
    <tr>
        <td><b>เขต/อำเภอ</b></td>
        <td><input type="text" id="add_ampur" name="add_ampur" value=""></td>
    </tr>
    <tr>
        <td><b>จังหวัด</b></td>
        <td>
        <select id="add_province" name="add_province" size="1">
        <option value="">เลือก</option>
        <?php
		$query_province=pg_query("select * from \"nw_province\" order by \"proID\"");
		while($res_pro = pg_fetch_array($query_province)){
		?>
		<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$reg_value){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
		<?php
		}
		?>
        </select>        
        </td>
    </tr>
</table>
        
        </td>
        <td valign="top">
        
<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
        <td><b>สัญชาติ</b></td>
        <td><input type="text" id="add_reg" name="add_reg" value="ไทย"></td>
    </tr>
    <tr>
        <td><b>อายุ</b></td>
        <td><input type="text" id="add_birthdate" name="add_birthdate" value=""></td>
    </tr>
    <tr>
        <td><b>บัตรแสดงตัว</b></td>
        <td><input type="text" id="add_card" name="add_card" value=""></td>
    </tr>
    <tr>
        <td><b>เลขที่บัตร</b></td>
        <td><input type="text" id="add_idcard" name="add_idcard" value=""></td>
    </tr>
    <tr>
        <td><b>วันที่ออกบัตร</b></td>
        <td><input id="add_dateidcard" name="add_dateidcard" type="text" readonly="true" value="<?php echo nowDate(); ?>"/>
                <input name="buttonadd_dateidcard" type="button" onclick="displayCalendar(document.frm_edit.add_dateidcard,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr>
        <td><b>ออกให้โดย</b></td>
        <td><input type="text" id="add_bycard" name="add_bycard" value=""></td>
    </tr>
    <tr>
        <td><b>ที่ติดต่อ</b></td>
        <td rowspan="3"><textarea id="add_contactadd" name="add_contactadd" rows="4" cols="40"></textarea></td>
    </tr>
</table>

        </td>
    </tr>
</table>

<br />    
<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
        <td colspan="4"  style="background-color:#84C1FF;"><b>ขัอมูลผู้ค้ำประกัน</b></td>
    </tr>

<?php	
$qry_cc=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$edt_idno' ");
$res_cc=pg_fetch_array($qry_cc);
$residno_cc=$res_cc["IDNO"];
if(empty($residno_cc)){
?>
	    
     <tr>
        <td colspan="4"><div align="left">** ไม่มีข้อมูลผู้ค้ำประกัน</div></td>
     </tr>
<?php
}else{

    $qry_fn=pg_query("select distinct A.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",C.\"CusID\" from \"ContactCus\" A
                       LEFT OUTER JOIN \"Fa1\" C on C.\"CusID\" = A.\"CusID\" 
					   where A.\"IDNO\"='$edt_idno' AND \"CusState\"!=0 order by \"CusState\" ");
    while($res_fn=pg_fetch_assoc($qry_fn)){
	    $fullname=trim($res_fn["A_FIRNAME"])." ".trim($res_fn["A_NAME"])." ".trim($res_fn["A_SIRNAME"]);
	    $a++;                 
?>	
    <tr>
	    <td></td>
		<td colspan="3"><?php echo $a.". ".$fullname; ?></td>
	</tr>
<?php
    }
?>
<?php
}
?>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4" style="background-color:#84C1FF;"><b>ข้อมูลรถแท็กซี่</b></td>
<?php
$qry_car=pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$edt_idno' ");
$res_fc=pg_fetch_array($qry_car);
$fc_carid=trim($res_fc["CarID"]);
$fc_name=trim($res_fc["C_CARNAME"]);
$fc_year=trim($res_fc["C_YEAR"]);
$fc_regis=trim($res_fc["C_REGIS"]);

$fcs_regis_by=trim($res_fc["C_REGIS_BY"]);
if(empty($fcs_regis_by)){
    $fc_regis_by="เลือก";
	$reg_value=" ";
}else{
    $fc_regis_by=$fcs_regis_by;
	$reg_value=$fcs_regis_by;
}
 
$fc_color=trim($res_fc["C_COLOR"]);
$fc_num=trim($res_fc["C_CARNUM"]);
$fc_mar=trim($res_fc["C_MARNUM"]);
$fc_mi=trim($res_fc["C_Milage"]);
$fc_expert=trim($res_fc["exp_date"]);
$fc_mon=trim($res_fc["C_TAX_MON"]);
?>

    </tr>
    <tr>
        <td><b>ยี่ห้อรถ</b></td>
        <td colspan="3"><?php echo $fc_name; ?></td>
    </tr>
    <tr>
        <td><b>รุ่นปี</b></td>
        <td colspan="3"><?php echo $fc_year; ?></td>
    </tr>
    <tr>
        <td><b>เลขตัวถัง</b></td>
        <td colspan="3"><?php echo $fc_num; ?></td>
    </tr>
    <tr>
        <td><b>เลขเครื่องยนต์</b></td>
        <td colspan="3"><?php echo $fc_mar; ?></td>
    </tr>
    <tr>
        <td><b>ทะเบียน</b></td>
        <td colspan="3"><?php echo $fc_regis; ?></td>
    </tr>
    <tr>
        <td><b>จังหวัดที่จดทะเบียน</b></td>
        <td colspan="3"><?php echo $reg_value; ?></td>
    </tr>
    <tr>
        <td><b>สี</b></td>
        <td colspan="3"><?php echo $fc_color; ?></td>
    </tr>
    <tr>
        <td><b>เลขไมล์</b></td>
        <td colspan="3"><?php echo $fc_mi;?></td>
    </tr>
    <tr>
        <td><b>วันที่ต่ออายุภาษี</b></td>
        <td colspan="3"><?php echo $fc_expert; ?></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="6" style="background-color:#84C1FF;"><b>ข้อมูลสัญญา</b></td>
    </tr>
    <tr>
        <td><b>วันที่ทำสัญญา</b></td>
        <td colspan="3"><input type="text" id="f_pstdate" name="f_pstdate" value="<?php echo $nowdate; ?>" onchange="ChkDate();" />
                <input name="button2" type="button" onclick="displayCalendar(document.frm_edit.f_pstdate,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr>
        <td><b>วันที่งวดแรก</b></td>
        <td colspan="3">
        <input name="hid_startDate" id="hid_startDate" type="hidden" value="<?php echo $date_fix; ?>"/>
        <input name="f_startDate" id="f_startDate" type="text" readonly="true" value="<?php echo $date_fix; ?>" onchange="SetDate();" />
        <input name="button" type="button" onclick="displayCalendar(document.frm_edit.f_startDate,'yyyy-mm-dd',this)" value="ปฏิทิน" /> <span style="color:#808080">(เลขวันที่ จะไม่เปลี่ยนแปลง)</span></td>
    </tr>
    <tr>
        <td><b>ค่างวดรวม vat</b></td>
        <td><?php echo number_format($fp_pmonth+$fp_pvat,2); ?></td>
        <td colspan="2"><b>ค่างวดไม่รวม vat =</b> <?php echo number_format($fp_pmonth,2); ?> | <b>vat ค่างวด =</b> <?php echo number_format($fp_pvat,2); ?></td>
    </tr>
    <tr>
        <td><b>จำนวนงวด</b></td>
        <td colspan="3"><?php echo $edt_duenum; ?></td>
    </tr>
    <tr>
        <td><b>ดาวน์รวม vat</b></td>
        <td><?php echo number_format($fp_pdown+$fp_pvatofdown,2); ?></td>
        <td colspan="2"><b>ดาวน์ไม่รวม vat =</b> <?php echo number_format($fp_pdown,2); ?> | <b>vat ดาวน์ =</b> <?php echo number_format($fp_pvatofdown,2); ?></td>
    </tr>
    <tr>
        <td><b>เงินต้นลูกค้า</b></td>
        <td colspan="3"><?php echo number_format($edt_cus_compri,2); ?></td>
    </tr>
    <tr>
        <td><b>เงินต้นทางบัญชี</b></td>
        <td colspan="3"><?php echo number_format($edt_acc_compri,2); ?></td>
    </tr>
    <tr>
        <td><b>ปีทางบัญชีที่ทำสัญญา</b></td>
        <td colspan="3"><?php echo $edt_cusbyyear; ?></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4" align="center"><input name="btn1" id="btn1" type="button" value="บันทึก" /> <input type="button" value=" กลับ " onclick="window.location='frm_tranfer.php'" /></td>
    </tr>
</table> 
</form>

</div>
        </td>
    </tr>
</table>

<script type="text/javascript">
function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
            document.frm_edit.add_firstname.disabled = true; document.frm_edit.add_firstname.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_name.disabled = true; document.frm_edit.add_name.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_surname.disabled = true; document.frm_edit.add_surname.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_reg.disabled = true; document.frm_edit.add_reg.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_birthdate.disabled = true; document.frm_edit.add_birthdate.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_pair.disabled = true; document.frm_edit.add_pair.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_card.disabled = true; document.frm_edit.add_card.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_address.disabled = true; document.frm_edit.add_address.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_idcard.disabled = true; document.frm_edit.add_idcard.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_moo.disabled = true; document.frm_edit.add_moo.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_dateidcard.disabled = true; document.frm_edit.add_dateidcard.value = 'ใช้ข้อมูลที่เลือก';
                document.frm_edit.buttonadd_dateidcard.disabled = true;
            document.frm_edit.add_soi.disabled = true; document.frm_edit.add_soi.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_bycard.disabled = true; document.frm_edit.add_bycard.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_road.disabled = true; document.frm_edit.add_road.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_contactadd.disabled = true; document.frm_edit.add_contactadd.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_tambon.disabled = true; document.frm_edit.add_tambon.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_ampur.disabled = true; document.frm_edit.add_ampur.value = 'ใช้ข้อมูลที่เลือก';
            document.frm_edit.add_province.disabled = true;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata_fa.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("name","fa_cusid");
</script>

</body>
</html>