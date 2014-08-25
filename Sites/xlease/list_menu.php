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
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
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
function calc_max_slide_width(){
	var tab = $('.tab');
	var max_tab = $(tab).length;
	var i = 0;
	var max_slide_width = 0;
	while(i<max_tab)
	{
		max_slide_width = max_slide_width+$(tab[i]).width();
		i++;
	}
	max_slide_width = max_slide_width+50;
	return max_slide_width;
}

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
    $('.list_tab_menu').innerHTML = xmlhttp.responseText;}
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
		var ncountalert=0;
		$.post("nw/warning_zone/check_alert.php",{
			brand : idmenu
		},
		function(data){	
			if(data == "closed"){
				//window.open('nw/warning_zone/index_alert.php?men='+idmenu,k+"_"+code,'toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768');
				window.open('nw/warning_zone/index_alert.php?men='+idmenu,'','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768');
			}else{
				
				$.post("nw/Menu_manual/checkread.php",{
						brand : idmenu
					},
					function(data){
							var strArray = data.split(",");
							var i=0;
							var wopen;
							while(i<strArray.length)
							{  
								if(strArray[i]!="")
								{   
									if(strArray[i]!=0){
											ncountalert++;
											alert('\tเมนูนี้มีการเพิ่มการแนะนำการใช้\n โปรดอ่านคำแนะนำก่อนเริ่มใช้งานเมนู');
											window.open('nw/Menu_manual/frm_pop_alert.php?recid='+strArray[i]+'&path='+path+'&k='+k+'&code='+code+'&data='+data,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1330,height=768');
									}
									else{ ncountalert++;}									
								}
								else{ ncountalert++;}
							  i++;
							}
							if(ncountalert>0){
								$.post("nw/Menu_manual/checkread.php",{
								brand : idmenu
								},
								function(data1){
									var strArray1 = data1.split(",");
									var nzero=0;
									var j=0;
									while(j<strArray1.length)
									{   if(strArray1[j]!="")
										{   
											if(strArray1[j]!=0){nzero=0; break;}
											else{nzero++;}									
										}
										else{nzero++; }
									j++;
									}
									if(nzero>0){
									window.open(path,'','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768');
									}
							
							
					});
							
							}
					});			
			
			}		
		});	
	
}
</script>

<script language=javascript>
	$(document).ready(function(){
		$("#searchText").autocomplete({
			source: "list_menu_search.php?test=สิน",
			minLength:1
		});
	});
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

var searchText = ''; // เมนูที่จะค้นหา
var searchTextSend = ''; // ค่าที่จะส่งไปหาเมนู

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

function searchLoad() // ค้นหาเมนู
{
	searchText = document.getElementById("searchText").value;
	
	searchTextSend = searchText.replace(" ","TspaceT","g");
	
	$('#div_admin_menu').load('list_admin_menu.php?searchText='+searchTextSend);
	$('.list_tab_menu').load('list_tab_menu.php?tabid=0&searchText='+searchTextSend);
	
	if(searchText != '')
	{
		$('#div_user_menu_often').load('empty.php');
		$('#div_user_menu_fav').load('empty.php');
		document.getElementById("searchSpan").innerHTML = 'ค้นหาเมนูด้วยคำว่า "'+searchText+'"';
	}
	else
	{
		$('#div_user_menu_often').load('list_menu_often.php');
		$('#div_user_menu_fav').load('list_menu_favmenu.php?menu=show');
		document.getElementById("searchSpan").innerHTML = '';
	}
}
</script>
<!-- finish date and time show on titlebar-->
<link href="css/list_menu.css" rel="stylesheet" type="text/css" />
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
	
	// หาว่าเป็นพนักงาน TA ด้วยหรือไม่
	$qry_ta = pg_query("select \"isUserTA\" from \"fuser\" where \"id_user\" = '$iduser' ");
	$ta = pg_fetch_result($qry_ta,0);

    echo "<div style=\"float:left\">เข้าสู่ระบบโดย <b>$_SESSION[user_login]</b><br />เข้าสู่ระบบครั้งล่าสุดเมื่อ <b>". date( "d/m/Y H:i:s", strtotime( $_SESSION['lasttime_login']) ) . "</b><br />ไอพีของท่าน  <b>". $_SERVER['REMOTE_ADDR'] . "</b></div>";
	
	if($ta == "1")
	{
		echo "<div style=\"float:right\"><a style=\"cursor:pointer;\" onclick=\"popU('show_tel_email/frm_show_tel_email.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=1250');\"><font color=\"#33CC66\"><b>เบอร์โทรศัพท์และ E-mail พนักงาน</b></font></a> |<a style=\"cursor:pointer;\" onclick=\"popU('https://172.16.2.116:8181/LDAPService/login.html','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=1250');\"><font color=\"#0000FF\"><b>เข้าสูระบบ LDAP</b></font></a> |
		<a href=\"../../taautosales/index.php?passlog=1\"><font color=\"#0000FF\"><b>เข้าสู่ระบบ TA</b></font></a> | <a href=\"change_pass.php\"><font color=\"#ff0000\"><b>เปลี่ยนรหัสผ่าน</b></font></a> | <a HREF=\"logout.php\"><font color=\"#ff0000\"><b>ออกจากระบบ</b></font></a></div>";
	}
	else
	{
		echo "<div style=\"float:right\"><a style=\"cursor:pointer;\" onclick=\"popU('show_tel_email/frm_show_tel_email.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=1250');\"><font color=\"#33CC66\"><b>เบอร์โทรศัพท์และ E-mail พนักงาน</b></font></a> |<a style=\"cursor:pointer;\" onclick=\"popU('https://172.16.2.116:8181/LDAPService/login.html','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=1250');\"><font color=\"#0000FF\"><b>เข้าสูระบบ LDAP</b></font></a> | <a href=\"https://172.16.2.116:8181/LDAPService/login.html\"><font color=\"#0000FF\"><b>เข้าสูระบบ LDAP</b></font></a> |
		<a href=\"change_pass.php\"><font color=\"#ff0000\"><b>เปลี่ยนรหัสผ่าน</b></font></a> | <a HREF=\"logout.php\"><font color=\"#ff0000\"><b>ออกจากระบบ</b></font></a></div>";
	}
    
	echo "<div style=\"clear:both\"></div>";

    $admin_array = GetAdminMenu(); //menu ของ admin

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
?>

<div>
	<br>
	ค้นหาเมนู : 
	<input type="textbox" id="searchText" size="60">
	<input type="button" value="ค้นหาเมนู" onClick="searchLoad();">
	<input type="button" value="แสดงเมนูทั้งหมดของฉัน" onClick="document.getElementById('searchText').value = ''; searchLoad();">
</div>

<div>
	<br><font size="3"><span id="searchSpan"></span></font>
</div>

<?php	
if( count($arr['admin']) > 0 ){
    echo "<div id=\"div_admin_menu\"></div>";
?>
<script type="text/javascript">
$(function(){
    $('#div_admin_menu').load('list_admin_menu.php');
});

var refreshId1 = setInterval(function(){
    //$('#div_admin_menu').fadeOut("fast").load('list_admin_menu.php').fadeIn("fast");
    $('#div_admin_menu').load('list_admin_menu.php?searchText='+searchTextSend);
}, 60000); //Refresh ทุกๆ 60วินาที
</script>
<?php
}

if( count($arr['user']) > 0 ){
	echo "<div id=\"div_user_menu_often\"></div>";
	echo "<div id=\"div_user_menu_fav\"></div>";
	echo "<div id=\"tab_user_menu\"></div>";	
    //echo "<div id=\"div_user_menu\"></div>";
?>
<script type="text/javascript">
$(function(){
    $('#div_user_menu_often').load('list_menu_often.php');
});

var refreshId3 = setInterval(function(){
 
    $('#div_user_menu_often').load('list_menu_often.php');
},3600000); //Refresh ทุกๆ  60 นาที
</script>

<script type="text/javascript">
$(function(){
    $('#div_user_menu_fav').load('list_menu_favmenu.php?menu=show');
});
//comment ไม่ให้ refresh ในส่วนเมนูที่ใช้บ่อย เพื่อให้ระบบไม่ทำงานหนักเกินไป #5695
/*var refreshId4 = setInterval(function(){
 
    $('#div_user_menu_fav').load('list_menu_favmenu.php?menu=show');
}, 420000);*/ //Refresh ทุกๆ 7นาที
</script>

<script type="text/javascript">
$(function(){
	$('#tab_user_menu').load('tab_user_menu.php',function(){
		list_tab_menu('0');
		var nex_interval;
		var prev_interval;
		$('.next').hover(function(){
			var slide_tab = $('.slide_tab');
			var i = $('#cur_margin_left').val();
			var m = parseInt(i);
			
			var max_slide_width = calc_max_slide_width();
			max_slide_width = max_slide_width+m;
			var max_box_width = $('.tab_box').width();
			
			nex_interval = setInterval(function(){
				if(max_slide_width>max_box_width)
				{
					m--;
					$('#cur_margin_left').val(m);
					$(slide_tab).animate({
						marginLeft: m
					},1);
					max_slide_width--;
				}
				else
				{
					clearInterval(nex_interval);
				}
			},10);
		},function(){
			clearInterval(nex_interval);
		});
		$('.prev').hover(function(){
			var slide_tab = $('.slide_tab');
			var i = $('#cur_margin_left').val();
			var m = parseInt(i);
			
			prev_interval = setInterval(function(){
				if(m<0)
				{
					m++;
					$('#cur_margin_left').val(m);
					$(slide_tab).animate({
						marginLeft: m
					},1);
				}
				else
				{
					clearInterval(prev_interval);
				}
			},10);
		},function(){
			clearInterval(prev_interval);
		});
	});
});
//$(function(){
    //$('#div_user_menu').load('list_user_menu.php');
//});

//var refreshId2 = setInterval(function(){
    //$('#div_admin_menu').fadeOut("fast").load('list_admin_menu.php').fadeIn("fast");
    //$('#div_user_menu').load('list_user_menu.php');
//}, 630000); //Refresh ทุกๆ 10นาที 30วินาที

function list_tab_menu(tab_id){
	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');
	$('.list_tab_menu').load('list_tab_menu.php?tabid='+tab_id);
}
var refreshId2 = setInterval(function(){
	var tab_id = $('.active').find('a').attr('id');
	
	if(tab_id==null){
		tab_id=0;
	}
	
    $('.list_tab_menu').load('list_tab_menu.php?tabid='+tab_id+'&searchText='+searchTextSend);
},630000); //Refresh ทุกๆ 10นาที 30วินาที 
</script>
<?php
}
?>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>
</html>