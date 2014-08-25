<?php
include("company.php");
$str_search = pg_escape_string($_GET['key']);

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"3\" border=\"0\">";

foreach($company as $v){

    $conn = "host=". $v["server"] ." port=5432 dbname=". $v["dbname"] ." user=". $v["dbuser"] ." password=". $v["dbpass"] ."";
    $mainconn = pg_connect($conn) or die("Can't Connect !");

    $result=pg_query("SELECT A.*,B.* FROM \"Fa1\" A INNER JOIN \"Fp\" B on A.\"CusID\"=B.\"CusID\" WHERE A.\"A_NAME\" LIKE '%$str_search%' OR A.\"A_SIRNAME\" LIKE '%$str_search%' ORDER BY B.\"IDNO\" ASC");
    while($arr = pg_fetch_array($result)){
        $CusID = $arr["CusID"];
        $IDNO = $arr["IDNO"];
        $A_NAME = $arr["A_NAME"];
        $A_SIRNAME = $arr["A_SIRNAME"];
        
        echo "<tr><td><b>$v[name]</b>, $IDNO | $A_NAME $A_SIRNAME | $CusID</td><td align=\"right\"><input type=\"button\" name=\"sl\" id=\"sl\" value=\"ทำรายการนี้\" onclick=\"alert('$v[name] : $IDNO');\"></td></tr>";
    }
}

echo "</table>";
?>
