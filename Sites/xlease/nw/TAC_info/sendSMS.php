<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	if(!isset($_SESSION['username']))
	{
		header("Location:index.php");
	}
	else if($_SESSION['userType']!=admin)
	{
		header("Location:index.php");
	}
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='1'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle1=$result['NewsTypeName'];
	$newsValue1=$result['NewsTypeID'];
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='2'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle2=$result['NewsTypeName'];
	$newsValue2=$result['NewsTypeID'];
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='3'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle3=$result['NewsTypeName'];
	$newsValue3=$result['NewsTypeID'];
	
	pg_query("BEGIN");
	$status=0;
	
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
	if($params=="")
	{
		$currentUrl = $protocol . '://' . $host . $script;
	}
	else
	{
    	$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	}
	
	$server=$_SERVER["REMOTE_ADDR"];
	$visitDate=date("Y-m-d H:i:s");
	$visitor="";
	
	if(isset($_SESSION['username']))
	{
		$sql="select * from \"TrMember\" where \"Username\"='".$_SESSION['username']."'";
		$dbquery=pg_query($sql);
		$result=pg_fetch_assoc($dbquery);
		if($result['isAdmin']==0)
		{
			$visitor="user";
		}
		else
		{
			$visitor="admin";
		}
	}
	else
	{
		$visitor="general";
	}
	
	$sql="insert into \"TrStatistic\"(\"Remote_IP\", \"Remote_Time\", \"Visit_Path\", \"visitor_type\") values('$server','$visitDate','$currentUrl','$visitor')";
	if($result=pg_query($sql))
	{}
	else
	{
		$status++;
	}
	if($status==0)
	{
		pg_query("COMMIT");
	}
	else
	{
		pg_query("ROLLBACK");
		echo "บันทึกข้อมูลล้มเหลว";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
<link href="style.css" rel="stylesheet" type="text/css">
<script src="jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
 
$(document).ready(function(){
 
    var counter = 1;
	
	$("#addButton").hide();
	
	$("#rbnSendManual").click(function () {
		$("#addButton").show();
		$("#tbxTele1").attr("disabled","");
	});
 
    $("#addButton").click(function () {  
	
	counter++; 
 
	var newTextBoxDiv = $(document.createElement('div'))
	     .attr("id", 'tele' + counter);
 
	newTextBoxDiv.after().html('<label>เบอร์โทรศัพท์ : </label>' +
	      '<input type="text" name="tbxTele' + counter + 
	      '" id="tbxTele' + counter + '" class="width" >'+'<input type="button" name="btnDeleteRow' + counter + '" id="btnDeleteRow' + counter + '" value="' + counter + '" class="btnDeleteRow" onclick="fncRemoveElement(value)" >');
		  
 
	newTextBoxDiv.appendTo("#div_telephoneNumber");
	document.getElementById('rows').value = counter;

     });
	 
	 //var curRow=$("#btnDeleteRow").val();
 
     //$("#btnDeleteRow1"+curRow).click(function () {  
 
	//counter--;
 
        //$("#tele" + curRow).remove();
		//alert(counter);
 
     //});
	 
  });
</script>
<script language=javascript>
function fncRemoveElement(number){ 
                
	var counter;
	counter=document.getElementById('rows').value;
	counter--;
	var mySpan = document.getElementById('div_telephoneNumber'); 
	var deleteDiv = document.getElementById("tele" + number);
	mySpan.removeChild(deleteDiv);
	document.getElementById('rows').value = counter;
	};
</script>
</head>
<body>
	<div align="center">
    	<div id="main">
        	<table width="800" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td height="190">
                    <iframe width="800" height="220" src="header.php" frameborder="0" name="iframe_header"></iframe>
                    </td>
           	  	</tr>
                <tr>
                	<td align="center" valign="top" id="content">
               	  		<table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="400" align="center" valign="top" id="content">
                                    <div id="content_box">
                                        <table width="780" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="middle" id="td_top_menu">Create SMS Script :: สร้างสคริปต์สำหรับส่ง SMS</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                               		<form name="addNews" method="post" action="createScript_Process.php">
                                               		  <div id="divCreateScriptFrm">
                                                      <table border="0" cellpadding="0" cellspacing="5" width="500" id="tb_frmCreateScript">
                                                      <tr>
                                                      <td align="right" width="100" class="sendSMSLabel">ชื่อผู้ส่ง : </td>
                                                      <td align="left"><input type="text" name="tbxSender" id="tbxSender" class="width"></td>
                                                      </tr>
                                                      <tr>
                                                      <td align="right" valign="top" class="sendSMSLabel">ข้อความ : </td>
                                                      <td><textarea name="tarMessage" id="tarMessage" cols="45" rows="5" class="width"></textarea></td>
                                                      </tr>
                                                      <tr>
                                                      <td align="right" valign="middle" class="sendSMSLabel">วันที่ส่ง : </td>
                                                      <td><input type="text" name="tbxSendTime" id="tbxSendTime" class="width"></td>
                                                      </tr>
                                                      <tr>
                                                      <td align="right" valign="middle" class="sendSMSLabel"></td>
                                                      <td id="tdtextFormat">* รูปแบบ yyy-mm-dd hh:mm</td>
                                                      </tr>
                                                      <tr>
                                                      <td align="right" class="sendSMSLabel">รูปแบบการส่ง : </td>
                                                      <td class="sendSMSLabel"><input type="radio" name="radio" id="rbnNotSend" value="1"> ยังไม่เคยส่ง <input type="radio" name="radio" id="rbnSended" value="2"> เคยส่งแล้ว <input type="radio" name="radio" id="rbnSendAll" value="3"> ส่งทั้งหมด <input type="radio" name="radio" id="rbnSendManual" value="4"> กำหนดเอง</td>
                                                      </tr>
                                                      <tr>
                                                      <td colspan="2">
                                                      <div id="div_telephoneNumber">
                                                      	<div id="tele1">
                                                        	<label>เบอร์โทรศัพท์ : </label>
                                                            <input type="text" name="tbxTele1" id="tbxTele1" value="" class="width" disabled><input type="button" name="btnDeleteRow1" id="btnDeleteRow1" value="1" class="btnDeleteRow">
                                                        </div>
                                                      </div>
                                                      </td>
                                                      </tr>
                                                      <tr>
                                                      <td></td>
                                                      <td valign="middle"><img src="images/add_button.png" id="addButton"> <input type="image" src="images/save_button.png" /><input type="hidden" name="rows" id="rows" value="1"></td>
                                                      </tr>
                                                      </table>
                                               		  </div>
                                               		</form>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td></td>
                </tr>
                <tr>
                	<td height="30"><iframe width="800" height="30" frameborder="0" src="footer.php" scrolling="no"></iframe>
                    </td>
                </tr>
            </table>
        </div>
	</div>
    <ul id="navigation">
        <li class="home"><a href="index.php" title="หน้าหลัก"></a></li>
        <li class="admin"><a href="adminMenu.php" title="เมนูผู้ดูแลระบบ"></a></li>
        <li class="back"><a href="javascript:history.back(-1);" title="กลับก่อนหน้า"></a></li>
    </ul>
    <script type="text/javascript">
        $(function() {
            $('#navigation a').stop().animate({'marginLeft':'-95px'},1000);

            $('#navigation > li').hover(
                function () {
                    $('a',$(this)).stop().animate({'marginLeft':'-2px'},200);
                },
                function () {
                    $('a',$(this)).stop().animate({'marginLeft':'-95px'},200);
                }
            );
        });
    </script>
</body>
</html>