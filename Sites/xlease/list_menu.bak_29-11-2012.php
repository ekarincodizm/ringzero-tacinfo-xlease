<?php
require_once("config/config.php"); 
$iduser = $_SESSION['uid'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title><?php echo $_SESSION["session_company_name"]; ?></title>

<script type="text/javascript">
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
</script>

<style type="text/css">
body {
    font-family: tahoma;
    font-size: 11px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 5px;
    padding-bottom: 5px;
}
H1{
    font-size: 16px;
    color: #585858;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
H2{
    font-size: 22px;
    color: #888800;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}

.wrapper{
	width:700; border: solid 0px;
}

.menu{
	margin:3px; text-align:center;
}

a:link, a:visited, a:hover {
    color: #585858;
    text-decoration: none;
}
a:hover {
    color: #ACACAC;
    text-decoration: none;
}

/* ====================== */
.roundedcornr_box {
   background: #ffffff;
   width: 700px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(img/roundedcornr_br.png) no-repeat bottom right;
}

.roundedcornr_top div, .roundedcornr_top, 
.roundedcornr_bottom div, .roundedcornr_bottom {
   width: 100%;
   height: 15px;
   font-size: 1px;
}
.roundedcornr_content {
    margin: 0 15px;
}
</style>

<link type="text/css" href="jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}

function loadurl(dest) {
try {
    xmlhttp = window.XMLHttpRequest?new XMLHttpRequest():
    new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e) { /* do nothing */ }

xmlhttp.onreadystatechange = triggered;
xmlhttp.open("GET", dest);
xmlhttp.send(null);

}
function triggered() {
    if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)) {
    document.getElementById("div_user_menu").innerHTML = xmlhttp.responseText;}
}

function menulog(name){
	$.post("menu_log.php",{
			id : name			
		}
	)
}

function menulog_key_Shortcuts(name){ // เก็บประวัติการเข้าใช้เมนูจากการเข้าใช้คีย์ลัด (F1,F2)
	$.post("menu_log_from_key.php",{
			id : name			
		}
	)
}


$(function(){
    $(window).bind("beforeunload",function(event){
        var msg="คุณกำลังปิดหน้าต่า่งหลัก หน้าต่างโปรแกรมที่เกี่ยวข้องจะปิดตัวทั้งหมด ?";
        $(window).bind("unload",function(event){
            event.stopImmediatePropagation();
            // แทรก ajax code ลบ session หรืออื่น ๆ
            closeAll();
        });
        return msg;
    });
});

function testalert(idmenu,path,k,code){
		$.post("nw/warning_zone/check_alert.php",{
			brand : idmenu
		},
		function(data){	
			if(data == "closed"){
				window.open('nw/warning_zone/index_alert.php?men='+idmenu,k+"_"+code,'toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768');
			}else{
				$.post("nw/Menu_manual/checkread.php",{
						brand : idmenu
					},
					function(data){				
							if(data != 0){
								alert('\tเมนูนี้มีการเพิ่มการแนะนำการใช้\n โปรดอ่านคำแนะนำก่อนเริ่มใช้งานเมนู');
								window.open('nw/Menu_manual/frm_pop_alert.php?recid='+data+'&path='+path+'&k='+k+'&code'+code,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1330,height=768');
							}else{
								window.open(path,k+"_"+code,'toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768');
							}
					});
			}		
		});	
	
}
</script>
<!--Date and time show on titlebar-->
<?php 
$thai_w=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
$thai_n=array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
$w=$thai_w[date("w")];
$d=date("d");
$n=$thai_n[date("n") -1];
$y=date("Y") +543;
$timenow = date('H:i:s'); 
list($hr,$min,$sec) = explode(":",$timenow);
$timecal = $hr.":".$min.":".$sec;
?>

<script language="">
var limit="<?php echo $timecal ?>"
var daythai = "<?php echo $w ?>"
var daynum = "<?php echo $d ?>"
var month = "<?php echo $n ?>"
var year = "<?php echo $y ?>"
if (document.images){
var parselimit=limit.split(":")
parselimit=parselimit[0]*60*60+parselimit[1]*60+parselimit[2]*1
}
function begintimer(){

parselimit+=1
curhour=Math.floor(parselimit/3600)%24
curmin=Math.floor(parselimit/60)%60
cursec=parselimit%60

curtime="<center>วัน<font color=red> "+daythai+" </font>ที่<font color=red> "+daynum+" </font><font color=red> "+month+" </font> <font color=red> "+year+" </font> เวลา : <font color=red> "+curhour+" </font>นาฬิกา <font color=red> "+curmin+" </font>นาที <font color=red>"+cursec+" </font>วินาที </center>"
document.getElementById('dplay').innerHTML = curtime;
setTimeout("begintimer()",1000)
}
</script>
<!-- finish date and time show on titlebar-->
</head>

<!-- ถ้ากดปุ่ม F1 หรือ F2 จะเป็นคีย์ลัดไปยังหน้าเมนูที่ระบุไว้  และจะเก็บประวัติการเข้าใช้เมนูด้วย-->
<body
	onkeydown="
		if(event.keyCode==112)
		{
			popU('post/frm_cuspayment.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1335,height=770');
			menulog_key_Shortcuts('post/frm_cuspayment.php');
		}
		if(event.keyCode==113)
		{
			popU('nw/thcap_installments/frm_Index.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1335,height=770');
			menulog_key_Shortcuts('nw/thcap_installments/frm_Index.php');
		}
	"
	onLoad="begintimer()"
>

<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
   
      <div class="roundedcornr_content">

<div style="float:left;"><h2><?php echo $_SESSION["session_company_name"]; ?></h2></div><div style="float:right;padding-top:14px;" id=dplay></div>
<div style="clear:both;"></div>
<hr/>

<?php
	$user_login = $_SESSION[user_login];
	$login_date_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
	$ip_login = $_SERVER['REMOTE_ADDR'];
	
	pg_query("BEGIN WORK");
	$status = 0;
	
	$qry_ins="insert into public.\"nw_log_access\"(\"username\",\"login_datetime\",\"IP_Address\") values ('$user_login','$login_date_time','$ip_login')";
	if($resultS=pg_query($qry_ins)){
	}else{
		$status++;
	}
	
	if($status == 0)
	{
		pg_query("COMMIT");
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<div style=\"float:left\">ไม่สามารถบันทึกข้อมูลการเข้าใช้งานได้!!</div>";
	}

    echo "<div style=\"float:left\">เข้าสู่ระบบโดย <b>$_SESSION[user_login]</b><br />เข้าสู่ระบบครั้งล่าสุดเมื่อ <b>". date( "d/m/Y H:i:s", strtotime( $_SESSION['lasttime_login']) ) . "</b><br />ไอพีของท่าน  <b>". $_SERVER['REMOTE_ADDR'] . "</b></div>";
    echo "<div style=\"float:right\"><a href=\"change_pass.php\"><font color=\"#ff0000\"><b>เปลี่ยนรหัสผ่าน</b></font></A> | <A HREF=\"logout.php\"><font color=\"#ff0000\"><b>ออกจากระบบ</b></font></a></div>";
    echo "<div style=\"clear:both\"></div>";

    $admin_array = $_session['menu_admin']; //menu ของ admin

    $result=pg_query("SELECT A.*,B.* FROM f_usermenu A 
    INNER JOIN f_menu B on A.id_menu=B.id_menu 
    WHERE (A.id_user='$iduser') AND (B.status_menu='1') AND (A.status=true) ORDER BY A.id_menu ASC");
    while($arr_menu = pg_fetch_array($result)){
        $menu_id = $arr_menu["id_menu"];                                                                                                      
        $menu_name = $arr_menu["name_menu"];
        $menu_path = $arr_menu["path_menu"];
        
        if(in_array($menu_id,$admin_array)){
            $arr['admin'][$menu_id]['name'] = "$menu_name";
            $arr['admin'][$menu_id]['path'] = "$menu_path";
        }else{
            $arr['user'][$menu_id]['name'] = "$menu_name";
            $arr['user'][$menu_id]['path'] = "$menu_path";
        }
    }

if( count($arr['admin']) > 0 ){
    echo "<div id=\"div_admin_menu\"></div>";
?>
<script type="text/javascript">
$(function(){
    $('#div_admin_menu').load('list_admin_menu.php');
});

var refreshId1 = setInterval(function(){
    //$('#div_admin_menu').fadeOut("fast").load('list_admin_menu.php').fadeIn("fast");
    $('#div_admin_menu').load('list_admin_menu.php');
}, 30000); //Refresh ทุกๆ 30วินาที
</script>
<?php
}

if( count($arr['user']) > 0 ){
	echo "<div id=\"div_user_menu_often\"></div>";
	echo "<div id=\"div_user_menu_fav\"></div>";	
    echo "<div id=\"div_user_menu\"></div>";
?>
<script type="text/javascript">
$(function(){
    $('#div_user_menu_often').load('list_menu_often.php');
});

var refreshId3 = setInterval(function(){
 
    $('#div_user_menu_often').load('list_menu_often.php');
}, 630000); 
</script>

<script type="text/javascript">
$(function(){
    $('#div_user_menu_fav').load('list_menu_favmenu.php?menu=show');
});

var refreshId4 = setInterval(function(){
 
    $('#div_user_menu_fav').load('list_menu_favmenu.php?menu=show');
}, 300000); 
</script>

<script type="text/javascript">
$(function(){
    $('#div_user_menu').load('list_user_menu.php');
});

var refreshId2 = setInterval(function(){
    //$('#div_admin_menu').fadeOut("fast").load('list_admin_menu.php').fadeIn("fast");
    $('#div_user_menu').load('list_user_menu.php');
}, 630000); //Refresh ทุกๆ 10นาที 30วินาที
</script>
<?php
}
?>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>
</html>