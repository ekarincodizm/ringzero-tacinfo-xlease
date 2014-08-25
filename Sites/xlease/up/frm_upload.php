<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
$condition = pg_escape_string($_POST["condition"]);
if($condition == ""){
	$condition=1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>

<script language="JavaScript">
var HttPRequest = false;

function doCallAjax() {
    HttPRequest = false;
    if (window.XMLHttpRequest) { // Mozilla, Safari,...
        HttPRequest = new XMLHttpRequest();
        if (HttPRequest.overrideMimeType) {
			HttPRequest.overrideMimeType('text/html');
        }
    }else if (window.ActiveXObject) { // IE
		try{
			HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try {
				HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){}
		}
    } 
          
    if (!HttPRequest) {
        alert('Cannot create XMLHTTP instance');
        return false;
    }
    
    var url = 'ajax_query.php';
    //var pmeters = 'code='+document.getElementById("code").value;
    var pmeters = 'getid='+document.getElementById("searchid").value+'&type='+document.getElementById("type").value; // 2 Parameters
    //var pmeters = 'getid='+document.getElementById("searchid").value;
    HttPRequest.open('POST',url,true);

    HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    HttPRequest.setRequestHeader("Content-length", pmeters.length);
    HttPRequest.setRequestHeader("Connection", "close");
    HttPRequest.send(pmeters);
                      
    HttPRequest.onreadystatechange = function()
	{
        if(HttPRequest.readyState == 3){  // Loading Request{
            document.getElementById("myShow").innerHTML = "Now is Loading...";
        }

        if(HttPRequest.readyState == 4){ // Return Request{
            document.getElementById("myShow").innerHTML = HttPRequest.responseText;
        }           
    }

    /*
    HttPRequest.onreadystatechange = call function .... // Call other function
    */
}

</script>
    
    </head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="header"><h1>AV.LEASING</h1></div>
<div class="wrapper">

<Script language="JavaScript">
<!-- Begin
function check1(){
var temp;
if(document.cf.type.value == ""){ alert("กรุณา เลือกประเภท"); return false; }
if(document.cf.getid.value == ""){ alert("กรุณา เลือกรหัส หรือ ระบุชื่อไฟล์"); return false; }
if(document.cf.file.value == ""){ alert("กรุณา เลือกไฟล์เอกสาร"); return false; }
obt('cf');
}
// End -->
</Script>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<form name="frm_fuc1" id="frm_fuc1" method="post" action="">
    <tr bgcolor="#FFFFFF">
        <td><b>เลือกประเภท</b></td>
        <td>
<select name="type" style="width:150px;" onchange="document.frm_fuc1.submit()";>
<option value="">----- เลือก -----</option>

<?php 
$qry_inf=pg_query("select \"id\", \"name\" from \"DocumentType\" WHERE sub is null OR sub = '' ORDER BY \"id\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $id = $res_inf["id"];
    $name = $res_inf["name"];
    if(pg_escape_string($_POST['type']) == $id){
        echo "<option value=\"$id\" selected>$name</option>"; $show_name = "$name";
    }else{
        echo "<option value=\"$id\">$name</option>";
    }


    $qry_inf2=pg_query("select \"id\", \"name\" from \"DocumentType\" WHERE sub='$id' ORDER BY \"id\" ASC");
    while($res_inf2=pg_fetch_array($qry_inf2)){
        $subid = $res_inf2["id"];
        $subname = $res_inf2["name"];
        if(pg_escape_string($_POST['type']) == $subid){
            echo "<option value=\"$subid\" selected>- $subname</option>"; $show_name = "$subname";
        }else{
            echo "<option value=\"$subid\">- $subname</option>";
        }
    }

} 
?>

</select>
        </td>
    </tr>
</form>
<?php
$type = pg_escape_string($_POST['type']);
$qry_dt=pg_query("select \"table\" from \"DocumentType\" WHERE id='$type' ");
if($res_dt=pg_fetch_array($qry_dt)){
    $table = $res_dt["table"];
}

//เงื่อนไขในการเลือกค้นหา
if(!empty($table)){
?>
<FORM name="chksearch"  id="chksearch" method="post" action="frm_upload.php"  enctype="multipart/form-data">
    <tr bgcolor="#FFFFFF">
        <td><b>เงื่อนไขการเลือกรหัส</b></td>
        <td>
		<input type="hidden" name="type" value="<?php echo $type;?>">
		<input type="radio" name="condition" value="1" onclick="document.chksearch.submit();" <?php if($condition==1 || $condition==""){?> checked <?php }?>>ค้นหาเฉพาะที่ยังไม่มีไฟล์แนบเท่านั้น 
		<input type="radio" name="condition" value="2" onclick="document.chksearch.submit();" <?php if($condition==2){?> checked <?php }?>>ค้นหาจากทั้งหมด
        </td>
    </tr>
</form>
<?php
} //end เงื่อนไขในการเลือกค้นหา
?>
<FORM name="cf"  id="cf" method="post" action="frm_upload_ok.php"  enctype="multipart/form-data" onSubmit="return check1()">
<?php
if(!empty($table)){
?>

    <tr bgcolor="#FFFFFF">
        <td><b><u>เลือกรหัส</u> <?php echo $show_name; ?></b></td>
        <td>
<input name="getid" type="hidden" id="getid" value=""/>
<input type="hidden" name="condition" value="<?php echo $condition;?>">
<input type="text" id="searchid" name="searchid" size="80" onchange="JavaScript:doCallAjax();">
        </td>
    </tr>
<?php
}elseif(!empty($type)){
?> 
    <tr bgcolor="#FFFFFF">
        <td><b><u>ระบุชื่อไฟล์</u> <?php echo $show_name; ?></b></td>
        <td>
<input name="getid" type="text" id="getid" size="80" />
        </td>
    </tr>
<?php } ?>
    <tr bgcolor="#FFFFFF">
        <td><b>เลือกไฟล์เอกสาร</b></td>
        <td><input type="file" name="file" size="80"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td valign=top><b>ผลการค้นหาไฟล์</b><br><font size="1">- <u>ไม่เลือก</u> ระบบจะสร้างไฟล์ใหม่<br>- <u>เลือก</u> ระบบจะเซฟทับไฟล์เดิม<br>- เลือก ได้เพียง 1 ไฟล์</font></td>
        <td>
<span id="myShow">ไม่พบไฟล์</span>
        </td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center"><input name="type" type="hidden" id="type" value="<?php echo $type; ?>" /><input type="submit" name="ok" value="  บันทึก  "></td>
    </tr>
</table>

</FORM>

</div>

<div align="center">
<input type="button" value="  Close  " onclick="javascript:window.close();">
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
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata.php?type=<?php echo pg_escape_string($_POST['type']); ?>&condition=<?php echo $condition;?>&q=" + this.value;
    });    
}    

make_autocom("searchid","getid");
</script>

</body>
</html>