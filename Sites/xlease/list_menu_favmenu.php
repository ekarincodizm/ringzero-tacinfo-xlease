<?php 
session_start();
$user_id = $_SESSION["av_iduser"];
include('config/config.php'); 

$code = md5(uniqid(rand().time(), true));
$detailmaenu = pg_escape_string($_GET['menu']);
if($detailmaenu=='show'){
$imagelo = "images/icon_menu/";
$linklo = "";
$uplo = "nw/drag_drop/";
}else if($detailmaenu=='edit'){
$imagelo = "../../images/icon_menu/";
$linklo = "../../";
$uplo = "";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<link type="text/css" href="<?php echo $linklo ?>jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="<?php echo $linklo ?>jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $linklo ?>jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<style>
ul{
	width: 650px;
	padding:0px;
	margin: 0px;
	
}
#response {
	padding:10px;
	background-color:#9F9;
	border:2px solid #396;
	margin-bottom:20px;
}
#list li {
	float:left;
	list-style: none;
	width:25%;
	height: 100px;	
}
</style>
<script type="text/javascript">
$(document).ready(function(){ 	
	  function slideout(){
  setTimeout(function(){
  $("#response").slideUp("slow", function () {
      });
    
}, 2000);}
	
    $("#response").hide();
	$(function() {
	$("#list ul").sortable({ opacity: 0.4, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize") + '&update=update'; 
			$.post("<?php echo $uplo ?>updateList.php", order, function(theResponse){
				// $("#response").html(theResponse);
				// $("#response").slideDown('slow');
				slideout();
			}); 															 
		}								  
		});
	});

});	

function popU(U,N,T){
newWindow = window.open(U,N,T); 
	
}

function go(e,idname) 
{
     var rightclick; 
     if (e.which) {rightclick = (e.which == 3);}
     else if(e.button) {rightclick = (e.button == 2);}
	 
		if(rightclick){
		document.oncontextmenu=new Function("return false");	
		del(idname);
		
		}

} 

function del(id){
	$.post("<?php echo $uplo ?>insert_fav_menu.php",{
				idmenu : id,
				check : 'del'
			 },
			function(data){	
				document.location.reload(true);
			});		
}  
</script>
</head>
<body>
<fieldset style="width:650px;">
<legend>
<?php if($detailmaenu=='show'){ ?>
<A HREF="javascript:popU('nw/drag_drop/index.php','<?php echo $code; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768')">
<?php  } ?>
<u>เมนูโปรด</u></A></legend>
<div id="container">
  <div id="list">
    

    <ul>
      <?php
				
				$query  = "SELECT * FROM f_menu a inner join f_favorite_menu b on a.id_menu = b.id_menu where b.\"id_user\" = '$user_id' order by id_menunumber ";
				$result = pg_query($query);
				$rr = pg_num_rows($result);
				if($rr == 0){ echo "<b><h3>ยังไม่มีเมนูชื่นชอบ</h3></b>"; }else{
				while($row = pg_fetch_array($result))
				{			
				$id = stripslashes($row['id_menu']);
				$text = stripslashes(trim($row['name_menu']));
				$path_menu = stripslashes(trim($row['path_menu']));				
				?>
	  <li style="text-align:center;" id="arrayorder_<?php echo $id ?>" onmouseup="go(event,'<?php echo $id ?>')">
	 <?php if($user_id != "000"){ ?>
	 
						
		<?php echo "<A HREF=\"javascript:testalert('$id','$path_menu','$id','$code'),loadurl('list_user_menu.php'),menulog('$id')\"> "; ?>
	 
	 
		<!--<A HREF="javascript:popU('<?php //echo $linklo.$path_menu ?>','<?php //echo $id."_".$code; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768')">-->
	  <?php } ?>
  <IMG SRC="<?php echo $imagelo.$id ?>.gif" WIDTH="80" HEIGHT="80" BORDER="0"><br><font size="1.5px"><?php echo $text ?></font></A>
	  </li>
	 <?php }
}	 ?>
    </ul>
  </div>
</div>
</fieldset>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7025232-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
