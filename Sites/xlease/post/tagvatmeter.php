<?php
session_start();
include("../config/config.php");
$id_user = $_SESSION["av_iduser"];
$idno= pg_escape_string($_GET['idno']);

$usersql = pg_query("SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'  ");
$reuser = pg_fetch_array($usersql);
$leveluser = $reuser['emplevel'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <!--<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>-->
	<script type="text/javascript" src="fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	

	<!-- Add jQuery library -->
	
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
<script type="text/javascript">
$(function(){
    $("#box_tab").tabs();
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

$(document).ready(function() {



$("#cusname").autocomplete({
        source: "list_customer.php",
        minLength:1
});



});


num = 0;
function fncCreateElement(){
		num++;
	   var mySpan = document.getElementById('mySpan');
	   var mySpan1 = document.getElementById('mySpan1');
		
		
		var myElement1 = document.createElement('input');
		myElement1.setAttribute('type',"file");
		myElement1.setAttribute('name',"file[]");	
		myElement1.setAttribute('id',"file"+num+"");
		myElement1.setAttribute('size',"35");		
		mySpan.appendChild(myElement1);	
		var myElement2 = document.createElement('input');
		myElement2.setAttribute('type',"button");
		myElement2.setAttribute('name',"del");	
		myElement2.setAttribute('id',"del"+num+"");
		myElement2.setAttribute('value'," ลบ ");
		myElement2.setAttribute('onclick',"fncRemoveElement("+num+")");
		mySpan.appendChild(myElement2);	
		var myElement3 = document.createElement('select');
		myElement3.setAttribute('type',"select");
		myElement3.setAttribute('name',"type[]");	
		myElement3.setAttribute('id',"type"+num+"");
		mySpan.appendChild(myElement3);	
		var Noption = new Option('ประเภทเอกสาร', 'null');
		myElement3.options[myElement3.length] = Noption;		
		var Noption = new Option('ป้ายมิเตอร์', 'taxmeter');
		myElement3.options[myElement3.length] = Noption;
		var Noption = new Option('การ์ด NGV', 'ngvcard');
		myElement3.options[myElement3.length] = Noption;
		var Noption = new Option('เครดิต NGV', 'ngvcredit');
		myElement3.options[myElement3.length] = Noption;
		var Noption = new Option('เล่มทะเบียนรถ', 'bookregis');
		myElement3.options[myElement3.length] = Noption;

		
}

function fncRemoveElement(num){

	   var mySpan = document.getElementById('mySpan'); 
		
			var deleteFile = document.getElementById("file" + num);
			mySpan.removeChild(deleteFile);
			var deleteFile = document.getElementById("type" + num);
			mySpan.removeChild(deleteFile);
			var deleteFile = document.getElementById("del" + num);
			mySpan.removeChild(deleteFile);
			
		
}

function chklist(){
	
	if(document.getElementById("cusname").value== ""){
		
		alert(' กรอกชื่อ ผู้ส่งเอกสารด้วยครับ ');
		return false;
	}else if(document.getElementById("file").value== ""){
	
		alert(' ใส่ไฟล์ที่จะเก็บด้วยครับ ');		
		return false;
	}else if(document.getElementById("type").value== "null"){
	
		alert(' ใส่ประเภทของไฟล์ด้วยครับ ');		
		return false;
	}else{ 
	
		for(i=0;i<=num;i++){

			if(document.getElementById("type" + num).value== "null"){	
				alert(' ใส่ประเภทของไฟล์ด้วยครับ ');		
				return false;
			}else{
				return true;
			}
		}	
	}

}

</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding-bottom: 3px;
    text-align: right;
}

</style>

</head>
<body>
<?php
	$sql1 = "SELECT \"fpicID\", \"IDNO\", picname, cusname, date, id_user, detail FROM \"Fp_document_pic\" where \"IDNO\" = '$idno' and \"status\" = 0";
	$sqlquery1 = pg_query($sql1);
	$rows = pg_num_rows($sqlquery1);
	$no = 0;
?>
<div class="title_top">ประวัติรับเอกสารป้ายภาษีมิเตอร์</div>

<div id="box_tab"> <!-- เริ่ม tabs -->
	<ul>
	<?php
		//สร้าง list
		
			echo "<li><a href=\"#show\">ประวัติเอกสาร</a></li>";
			echo "<li><a href=\"#add\">เพิ่มเอกสาร</a></li>";
		
	?>
	</ul>
	<div id="show" name="show">
	
				<table width="600" border="0"  cellSpacing="1" cellPadding="1"  align="center" bgcolor="#E1E1FF">
	<?php	if($rows == 0){ ?>
	
				
				<tr bgcolor="#CCCCFF" height="25"><td align="center">ไม่มีประวัติการส่งไฟล์เอกสารใดๆ</td></tr>
		
	<?php		}else{ ?>			
					<tr bgcolor="#CCCCFF" height="25">	
						<th align="10%">ลำดับที่</th>
						<th align="35%">ชื่อผู้รับป้าย ( ลูกค้า ) </th>
						<th align="20%">วันที่บันทึกเอกสาร</th>					
						<th align="35%">เอกสาร</th>
				<?php if($leveluser <= 3){ ?>		
						<th align="10%">ลบ</th>
				<?php } ?>		
					</tr>
		<?php		while($re = pg_fetch_array($sqlquery1)){ 
							$no++;   
				
					if($no%2==0){
						$color="#E1E1FF";
					}else{
						$color="#F4F4FF";
					}		?>			
					<tr bgcolor=<?php echo $color?>>
						<td align="center" align="10%"><?php echo $no; ?></td>
						<td align="center" align="35%"><?php echo $re['cusname'];; ?></td>
						<td align="center" align="20%"><?php echo $re['date']; ?></td>					
						<td align="center" align="35%"><a  onclick="javascript:popU('view_doc.php?fpicID=<?php echo $re['fpicID'] ?>&idno=<?php echo $idno ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=600')" style="cursor: pointer;" title="แสดงรายละเอียด"><u> ตรวจสอบเอกสาร </u><br></a></td>
					<?php if($leveluser <= 3){ ?>								
						<td align="center" align="10%"><input type="button" value=" ลบ " onclick="checkconritm(<?php echo $re['fpicID']; ?>)"></td>
					<?php } ?>	
				</tr>
		<?php  } ?>	
	<?php  } ?>			
			</table>
	
	</div>

	<div id="add" name="add">
		<form name="frm" method="POST" action="picture_query.php" enctype="multipart/form-data">
		<input type="hidden" name="idno" id="idno" value="<?php echo $idno ?>">
			<table width="600" frame="border" cellSpacing="1" cellPadding="2"  align="center" bgcolor="#E1E1FF">
				<tr>
					<td colspan="2" align="center"><h3>เพิ่มเอกสาร</h3></td>
				</tr>
					
				<tr bgcolor="#CCCCFF">	
					<td  align="right">ชื่อผู้รับเอกสาร : </td> 
					<td align="left"><input type="text" name="cusname" id="cusname" size="50"></td>
				</tr>
				<tr bgcolor="#CCCCFF">	
					<td  align="right">รายละเอียดเพิ่มเติม : </td> 
					<td align="left"><textarea name="detail" id="detail" cols="47" rows="3"></textarea>
				</tr>
				<tr bgcolor="#CCCCFF">	
					<td  align="right" width="40%">ไฟล์  : </td>
					<td align="left"><input type="file" name="file[]" id="file" size="35"><input type="button" value=" เพิ่ม " onclick="fncCreateElement();" style="width:50px;height:25px;"></td>
		
				</tr>
				<tr bgcolor="#CCCCFF">
					<td  align="right" width="40%">ประเภทของเอกสาร : </td>
					<td align="left">
						<select name="type[]" id="type">
							<option value="null"> ประเภทเอกสาร </option>
							<option value="taxmeter"> ป้ายมิเตอร์ </option>
							<option value="ngvcard"> การ์ด NGV </option>
							<option value="ngvcredit"> เครดิต NGV </option>
							<option value="bookregis"> เล่มทะเบียนรถ </option>
						</select>
					</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td  align="right" width="40%"></td>
					<td>
					<span id="mySpan"><br></span><span id="mySpan1"><br></span>
					</td>
				</tr>		
				<tr bgcolor="#CCCCFF">	
					<td colspan="2" align="center"><input type="submit" value=" บันทึก " style="width:100px; height:50px" onclick="return chklist();"></td>
				</tr>	
			</table>
		</form>	
	</div>
</div>
	
		

<script type="text/javascript">
function checkconritm(id)
{
var idnoo = '<?php echo $oo=$_GET['idno']; ?>';
	if(confirm('คุณต้องการที่จะลบเอกสารชุดนี้ใช่หรือไม่ ?')==true)
	{
		
		window.location = 'del_file.php?fpicID='+id+'&idno='+idnoo;
	}else{ return false;}
}
</script>
</body>
</html>