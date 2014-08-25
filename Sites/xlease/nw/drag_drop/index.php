<?php 
session_start();
$user_id = $_SESSION["av_iduser"];
include('../../config/config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<style>
ul{
	width: 650px;
	padding:0px;
	margin: 0px;
	
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
	$("#panel").load("../../list_menu_favmenu.php?menu=edit");
});	

function popU(U,N,T){
newWindow = window.open(U,N,T); 	
}

function loadpage(){
	$("#panel").load("../../list_menu_favmenu.php?menu=edit");
}

function update(id){
	$.post("insert_fav_menu.php",{
		 idmenu : id,
		 check : 'add'
	 },
	function(data){
		if(data == 'false') alert("\t\tทุกเมนูเป็นเมนูโปรดครบทุกเมนูแล้ว \t\n\n*คุณสามารถลบเมนูออกจากกล่องโดยการคลิกขวาที่เมนู");
		else if(data == 'doubly') alert(" เมนูนี้ถูกเพิ่มแล้วครับ ");
		$("#panel").load("../../list_menu_favmenu.php?menu=edit");
	});	
}


</script>
</head>

<body>
<div align="center" >
<!--เมนูชื่นชอบ-->
<div align="center"><h2>จัดการเมนูโปรด</h2></div>
<div  align="center" id="panel_explanation">
<table>
<tr><td><font color="red" size="2" align="center"><u><b>คำอธิบาย<b></u></font></td></tr>
<tr><td><font color="red" size="2"><b>วิธีการ เพิ่มเมนูโปรด</b></font></td></tr>
<tr><td><font color="red" size="2">- วิธืที่1  คลิกที่เมนูที่ต้องการเพิ่ม</font></td><tr>
<tr><td><font color="red" size="2">- วิธืที่2  คลิกลากเมนูที่ต้องการเพิ่ม ไปในกรอบเมนูโปรด  หรือ คลิกลากสลับตำแหน่งระหว่างเมนู (เมนูที่อยู่นอกกรอบ เมนูโปรด)</font></td><tr>
<tr><td><font color="red" size="2"><b>วิธีการ การนำเมนูโปรดออก</b></font></td><tr>
<tr><td><font color="red" size="2">- คลิกขวาที่เมนูที่ต้องการนำออก (เมนูที่อยู่นอกกรอบ เมนูโปรด)</font></td><tr>
</table></div>
<div id="panel"  align="center"></div>
<div style="margin-top:15px;"></div>
 <div id="list">  
  <ul style="width:1100px;" >
      <?php
				$admin_array = GetAdminMenu(); //menu ของ admin
				$o = 1;
				for($p=0;$p<sizeof($admin_array);$p++){
					
					if($o == sizeof($admin_array)){		
						$admenu = $admenu."'".$admin_array[$p]."'";
					}else{			
						$admenu = $admenu."'".$admin_array[$p]."'".",";		
					}$o++;
				}
				 $query  = "SELECT B.id_menu as idmenu,B.name_menu,B.path_menu FROM f_usermenu A 
    INNER JOIN f_menu B on A.id_menu=B.id_menu 
    WHERE (A.id_user='$user_id') AND (B.status_menu='1') AND (A.status=true) AND (B.id_menu NOT IN ($admenu)) ORDER BY A.id_menu ASC";
				$result = pg_query($query);

				while($row = pg_fetch_array($result))
				{			
				$id = stripslashes($row['idmenu']);
				$text = stripslashes(trim($row['name_menu']));
				$path_menu = stripslashes(trim($row['path_menu']));	
				?>
	  <li style="text-align:center;cursor:pointer;width:10%;height:110px;" id="<?php echo $id ?>" onmouseup="update(id)" >
	  <IMG SRC="../../images/icon_menu/<?php echo $id ?>.gif" WIDTH="80" HEIGHT="80" BORDER="0"><br><font size="1.5px"><?php echo $text ?></font></A>
	  </li>
	 <?php } ?>
    </ul>
 </div>
</div>


</body>
</html>
