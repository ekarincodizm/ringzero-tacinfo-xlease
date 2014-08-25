<?php
require_once("config/config.php");
$iduser = $_SESSION['uid'];

if(empty($iduser)){
    echo "<div style=\"padding: 30px; color:#ff0000; font-size:13px; text-align: center;\">เนื่องจากท่านไม่มีการทำงานต่อเนื่องในระยะเวลาที่กำหนด ระบบจึงปิดตัวเองเพื่อความปลอดภัย<br>หากท่านต้องการทำงาน กรุณา LOGIN เข้าระบบใหม่</div>";
}else{
    $admin_array = GetAdminMenu(); //menu ของ admin
    $code = md5(uniqid(rand().time(), true));
	
	$tab_id = pg_escape_string($_GET['tabid']);
	
	// เมนูที่จะค้นหา
	$searchText = pg_escape_string($_GET["searchText"]);
	$searchText = str_replace("TspaceT"," ",$searchText);
	if($searchText != ""){$searchText = "and b.\"name_menu\" like '%$searchText%'";}
    
	if($tab_id=='0')
	{
    	$qr=pg_query("SELECT A.*,B.* FROM f_usermenu A 
    INNER JOIN f_menu B on A.id_menu=B.id_menu 
    WHERE (A.id_user='$iduser') AND (B.status_menu='1') AND (A.status=true) $searchText ORDER BY A.id_menu ASC");
	}
	else
	{
		$qr=pg_query("select * from \"Vmenu_tab\" where \"id_user\"='$iduser' and \"tabID\"='$tab_id'");
	}
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		while($res = pg_fetch_array($qr))
		{
			if($tab_id=='0')
			{
				$menu_id = $res["id_menu"];                                                                                                      
				$menu_name = $res["name_menu"];
				$menu_path = $res["path_menu"];
			}
			else
			{
				$menu_id = $res['f_menuID'];
				$menu_name = $res['name_menu'];
				$menu_path = $res['path_menu'];
			}
			
			if(!in_array($menu_id,$admin_array)){
				$arr['user'][$menu_id]['name'] = "$menu_name";
				$arr['user'][$menu_id]['path'] = "$menu_path";
				$arr['user'][$menu_id]['idmenu_log'] = "$menu_id";
			}
		}
	
		if( count($arr['user']) > 0 ){
			echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="menu"><tr>';
			foreach($arr['user'] as $k => $v){
				$j++;
				
				//ถ้า user เป็น 000 จะไม่สามารถกดเมนูได้
				if($iduser=="000"){
					echo "<td width=\"25%\">
					<IMG SRC=\"images/icon_menu/$k.gif\" WIDTH=\"80\" HEIGHT=\"80\" BORDER=\"0\" ><br>$v[name]
					</td>";
				}else{
				
					echo "<td width=\"25%\">
					<A HREF=\"javascript:loadurl('list_user_menu.php'),menulog('$v[idmenu_log]'); javascript:testalert('$k','$v[path]','$k','$code');\"><IMG SRC=\"images/icon_menu/$k.gif\" WIDTH=\"80\" HEIGHT=\"80\" BORDER=\"0\" ><br>$v[name]</A>
					</td>";
				}
				if($j == 4){
					$j = 0;
					echo '</tr><tr>';
				}
			}
			echo '</tr></table>';
		}
		else
		{
			echo "<div style=\"padding: 30px; color:#ff0000; font-size:13px; text-align: center;\">ท่านยังไม่ได้รับสิทธิ์ในการใช้งานเมนูในกลุ่มนี้ครับ</div>";
		}
    }
}
?>