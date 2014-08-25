<?php
require_once("config/config.php");
$iduser = $_SESSION['uid'];

if(empty($iduser)){
    echo "<div style=\"padding: 30px; color:#ff0000; font-size:13px; text-align: center;\">เนื่องจากท่านไม่มีการทำงานต่อเนื่องในระยะเวลาที่กำหนด ระบบจึงปิดตัวเองเพื่อความปลอดภัย<br>หากท่านต้องการทำงาน กรุณา LOGIN เข้าระบบใหม่</div>";
}else{
    $admin_array = $_session['menu_admin']; //menu ของ admin
    $code = md5(uniqid(rand().time(), true));
    
    $result=pg_query("SELECT A.*,B.* FROM f_usermenu A 
    INNER JOIN f_menu B on A.id_menu=B.id_menu 
    WHERE (A.id_user='$iduser') AND (B.status_menu='1') AND (A.status=true) and (A.\"id_menu\" not in (select distinct \"f_menuID\" from \"Vmenu_tab\")) ORDER BY A.id_menu ASC");
    while($arr_menu = pg_fetch_array($result)){
        $menu_id = $arr_menu["id_menu"];                                                                                                      
        $menu_name = $arr_menu["name_menu"];
        $menu_path = $arr_menu["path_menu"];
        
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
			
			
				/*echo "<td width=\"25%\">
				<A HREF=\"javascript:popU('$v[path]','$k"."_"."$code','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1330,height=768'); javascript:loadurl('list_user_menu.php'),menulog('$v[idmenu_log]');\"><IMG SRC=\"images/icon_menu/$k.gif\" WIDTH=\"80\" HEIGHT=\"80\" BORDER=\"0\" ><br>$v[name]</A>
				</td>";		*/	
			}
			if($j == 4){
                $j = 0;
                echo '</tr><tr>';
            }
        }
        echo '</tr></table>';
    }
}
?>