<?php
session_start();
header ('Content-type: text/html; charset=utf-8');
include("company.php");

if(isset($_POST['btnrun'])){

    $comp = pg_escape_string($_POST['comp']);
    if(!empty($comp)){
        foreach($company as $v){
            if($v['code'] == $comp){
                $_SESSION["session_company_code"] = $v['code'];
                $_SESSION["session_company_name"] = $v['name'];
                $_SESSION["session_company_thainame"] = $v['thainame'];
                $_SESSION["session_company_server"] = $v['server'];
                $_SESSION["session_company_dbname"] = $v['dbname'];
                $_SESSION["session_company_dbuser"] = $v['dbuser'];
                $_SESSION["session_company_dbpass"] = $v['dbpass'];
                $_SESSION["session_company_asset_car"] = $v['asset_car'];
                $_SESSION["session_company_asset_gas"] = $v['asset_gas'];
                $_SESSION["session_company_nv"]=$v['NV'];
                $_SESSION["session_company_jr"]=$v['JR'];
                $_SESSION["session_company_tv"]=$v['TV'];
				$_SESSION["session_path_save_pdf"]=$v['path_save_pdf'];
                break;
            }
        }
        
        if(empty($_SESSION["session_company_code"]) || empty($_SESSION["session_company_name"]) || empty($_SESSION["session_company_server"]) || empty($_SESSION["session_company_dbname"]) || empty($_SESSION["session_company_dbuser"]) || empty($_SESSION["session_company_dbpass"])){
            echo "connect string empty !";
            exit;
        }else{
            $conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
            $db_connect = pg_connect($conn_string) or die("Can't Connect !");
        }
    }else{
        echo "empty company !";
        exit;
    }
    
    echo '<a href="md5.php">Back</a><br />';
    echo '<table cellpadding="2" cellspacing="1" border="0" width="100%" bgcolor="#C0C0C0" style="font-size:13px">';
    echo '<tr bgcolor="#D0D0D0"><td>STATUS</td><td>ID</td><td>USER</td><td>Old PASS</td><td>Change to MD5</td></tr>';

    $result=pg_query("SELECT \"id_user\",username,password FROM fuser ORDER BY \"id_user\" ");
    while($arr = pg_fetch_array($result)){
        $id_user=$arr["id_user"];
        $username=$arr["username"];
        $password=$arr["password"];
        $md5_password=md5($password);
        
        $update_sql="UPDATE fuser SET \"password\"='$md5_password' WHERE \"id_user\"='$id_user' ";
        if($rs=pg_query($update_sql)){
            echo "<tr bgcolor=\"#FFF\"><td>CHANGE OK</td><td>$id_user</td><td>$username</td><td>$password</td><td>$md5_password</td></tr>";
        }else{
            echo "<tr bgcolor=\"#FFF\"><td>CHANGE ERROR</td><td>$id_user</td><td>$username</td><td>$password</td><td>$md5_password</td></tr>";
        }
    }
    echo '</table>';

}else{
?>
<form name="frm1" id="frm1" method="post">
    <select name="comp" id="comp">
    <?php
    foreach($company as $v){
        echo "<option value=\"$v[code]\">$v[name]</option>\n";
    }
    ?>
    </select>
    <input type="submit" name="btnrun" id="btnrun" value="Run MD5">
</form>
<?php
}
?>