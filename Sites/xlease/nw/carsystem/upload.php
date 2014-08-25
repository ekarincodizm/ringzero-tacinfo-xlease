<?php
	session_start();
	include("../../config/config.php");
	if(!isset($_SESSION['username']))
	{
		echo "<script type=\"text/javascript\">";
		echo "alert('หน้านี้สำหรับสมาชิกเท่านั้น  กรุณาเข้าสู่ระบบก่อนครับ');";
		echo "window.location.href=\"index.php\";";
		echo "</script>";
	}
	else if(!isset($_SESSION['fullpath']))
	{
		echo "<script type=\"text/javascript\">";
		echo "alert('คุณยังไม่ได้โพสต์ประกาศครับ');";
		echo "window.location.href=\"index.php\";";
		echo "</script>";
	}
	$id = $_GET['id'];
?>
<html>
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>เลือกไฟล์ภาพ : ระบบประกาศ</title>

<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="scripts/fileuploader/fileuploader.css" rel="stylesheet" type="text/css" />
<link href="scripts/Jcrop/jquery.Jcrop.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="scripts/jquery-impromptu.js"></script>
<script type="text/javascript" src="scripts/fileuploader/fileuploader.js"></script>
<script type="text/javascript" src="scripts/Jcrop/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="scripts/jquery-uberuploadcropper.js"></script>

<script type="text/javascript">
$(function() {
	
	$('#UploadImages').uberuploadcropper({
		//---------------------------------------------------
		// uploadify options..
		//---------------------------------------------------
		'fileObjName' : '1',
		'debug'		: true,
		'action'	: 'upload1.php',
		'params'	: {},
		'allowedExtensions': ['jpg','jpeg','png','gif'],
		'sizeLimit'	: 5242880,
		//'multiple'	: true,
		//---------------------------------------------------
		//now the cropper options..
		//---------------------------------------------------
		'aspectRatio': 4/3, 
		'allowSelect': false,			//can reselect
		'allowResize' : true,			//can resize selection
		'setSelect': [ 0, 0, 160, 120 ],	//these are the dimensions of the crop box x1,y1,x2,y2
		//'minSize': [ 800, 600 ],		//if you want to be able to resize, use these
		'maxSize': [ 800, 600 ],
		//---------------------------------------------------
		//now the uber options..
		//---------------------------------------------------
		'folder': '<?php echo $_SESSION['croppath']; ?>',			// only used in uber, not passed to server
		'cropAction': 'crop.php',		// server side request to crop image
		'onComplete': function(imgs,data){
		
			window.location.reload();
			//var $PhotoPrevs = $('#PhotoPrevs');
//
//						for(var i=0,l=imgs.length; i<l; i++){
//							$PhotoPrevs.append('<img src="uploads/'+ imgs[i].filename +'?d='+ (new Date()).getTime() +'" width="80" />');
//						}
		}
	});
	
}); 
function deletefile(path){
	window.location.href='deleteImage.php?path='+path;
}
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#divheader1').load('header.php');
	$('#divfooter1').load('footer.php');
});
function finish(){
	alert("บันทึกข้อมูลเรียบร้อยแล้วครับ");
	window.location.href='index.php';
};
function rollback(){
	window.location.href='rollbackpost.php?id=<?php echo $id ?>';
}
function addcomment(btnid,id,number,imgid){
	$('#'+id).html('<input type="text" maxlength="50" id="tbxImageComment'+number+'" name="tbxImageComment'+number+'" class="tbxImageComment" onfocus="togglesyle(id);" onBlur="togglesyleback(id);" /><span class="spanok" onclick="savecomment(\''+number+'\',\''+imgid+'\',\'tbxImageComment'+number+'\',\''+id+'\');">เสร็จ</span>');
	$('#'+btnid).hide();
}
function togglesyle(id){
	$('#'+id).css({
		'border':'solid 1px #0f9fe2',
		'color':'#333'
	});
}
function togglesyleback(id){
	$('#'+id).css({
		'border':'solid 1px #cacaca',
		'color':'#666'
	});
}
function savecomment(number,imgid,tbxid,spanid){
	var value=document.getElementById(tbxid).value;
	$.ajax({
		type:'POST',
		url:'update_comment.php',
		data:'imgid='+imgid+'&value='+value,
	  
			success:function(data){
				if(data==1){
					$("#"+spanid).html(value+"<span class=\"addcomment\" id=\"btnedit\""+number+" onclick=\"editcomment('"+spanid+"',id,'"+number+"','"+imgid+"','"+value+"');\">แก้ไข</span>");
				}
				else if(data==0){
					$("#"+spanid).html("ไม่สามารถบันทึกคำอธิบายภาพได้ กรุณาติดต่อผู้ดูแลระบบครับ");
				}
			}
		});
}
function editcomment(spanid,btnid,number,imgid,value)
{
	$('#'+spanid).html("<input type=\"text\" maxlength=\"50\" id=\"tbxImageComment"+number+"\" name=\"tbxImageComment"+number+"\" class=\"tbxImageComment\" value=\""+value+"\" onfocus=\"togglesyle(id);\" onBlur=\"togglesyleback(id);\" /><span class=\"spanok\" onclick=\"savecomment('"+number+"','"+imgid+"','tbxImageComment"+number+"','"+spanid+"');\">เสร็จ</span>");
	$('#'+btnid).hide();
}
</script>
</head>

<body>
<div id="divheader1"></div>
<div align="center">
    <div id="wrapper">
    <div id="divoperation"><div onClick="rollback();">ยกเลิกประกาศ</div><div onClick="finish();">เสร็จสมบูรณ์</div></div>
        <div id="UploadImages">
        </div>
        <div id="PhotoPrevs">
            <?php
                $sql="select * from carsystem.\"productImage\" where \"postID\"='$id'";
				$dbquery=pg_query($sql);
				$i=1;
                while($rs=pg_fetch_assoc($dbquery)){
					$imgID=$rs['imageID'];
					$comment=$rs['imageComment'];
                    $path=$_SESSION['thumnailspath'].$rs['imageName'];
                    $openimg = fopen($path,"r");
                    $size = filesize($path);
                    if($size<1000)
                    {
                        $size = $size." Byte";
                    }
                    else if($size>999)
                    {
                        $size = ($size/1000)." KB";
                    }
                    else if($size>999999)
                    {
                        $size = ($size/1000000)." MB";
                    }
                    ?>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family:tahoma; font-size:13px; font-weight:bold; color:#444; overflow:auto; border-bottom:solid 1px #efefef;">
                            <tr style="padding:5px 0px 5px 0px;">
                                <td width="12%" align="left" rowspan="2">
                                <img src="<?php echo $path; ?>" width="80" />
                                </td>
                                <td width="43%" align="left">
                                ชื่อภาพ : <?php if(mb_strlen($rs['imageName'])>25){echo mb_substr($rs['imageName'],0,25)."...";}else{echo $rs['imageName'];} ?>
                                </td>
                                <td width="30%" align="left">
                                ขนาด : <?php echo $size; ?>
                                </td>
                                <td width="15%" align="right" rowspan="2">
                                	<span class="spanDelete" onClick="deletefile('<?php echo $rs['imageName'] ?>');">ลบ</span>
                                </td>
                            </tr>
                            <tr style="padding:5px 0px 5px 0px;">
                                <td align="left" colspan="2">
                                คำอธิบายภาพ : 
                                <div id="divcomment">
									<?php
                                    	if($comment=="")
										{
											echo "<span class=\"nocomment\" id=\"spanpic".$i."\">ยังไม่มีคำบรรยายภาพ</span><span id=\"btnadd".$i."\" class=\"addcomment\" onclick=\"addcomment(id,'spanpic".$i."','".$i."','".$imgID."');\">เพิ่ม</span>";
										}
										else
										{
											echo "<span class=\"nocomment\" id=\"spanpic".$i."\">$comment</span><span class=\"addcomment\" id=\"btnedit".$i."\" onclick=\"editcomment('spanpic".$i."',id,'".$i."','".$imgID."','".$comment."');\">แก้ไข</span>";
										}
									?><!--<input type="text" maxlength="50" id="tbxImageComment" name="tbxImageComment" />-->
                                </div>
                                </td>
                            </tr>
                        </table>
                    <?php
					$i++;
                }
            ?>
        </div>
    </div>
</div>
<div id="divfooter1"></div>
</body>
</html>
