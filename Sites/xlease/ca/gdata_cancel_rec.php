<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);
$t = pg_escape_string($_GET["t"]);

if($t == "1"){ // ยกเลิกทั่วไป

$sql_select=pg_query("select \"R_Receipt\",\"R_Money\" from \"Fr\" where \"R_Receipt\" like '%$q%' ORDER BY \"R_Receipt\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $R_Receipt = trim($res_cn["R_Receipt"]);
    $R_Money = number_format($res_cn["R_Money"],2);

        $S_R_Receipt = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $R_Receipt);
        echo "<li onselect=\"this.setText('$R_Receipt : $R_Money').setValue('$R_Receipt'); \"><b>ค่างวด</b> : $S_R_Receipt : $R_Money</li>";
}

$sql_select=pg_query("select \"O_RECEIPT\",\"O_MONEY\" from \"FOtherpay\" where \"O_RECEIPT\" like '%$q%' ORDER BY \"O_RECEIPT\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $O_RECEIPT = trim($res_cn["O_RECEIPT"]);
    $O_MONEY = number_format($res_cn["O_MONEY"],2);

        $S_O_RECEIPT = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $O_RECEIPT);
        echo "<li onselect=\"this.setText('$O_RECEIPT : $O_MONEY').setValue('$O_RECEIPT'); \"><b>ค่าอื่นๆ</b> : $S_O_RECEIPT : $O_MONEY</li>";
}

$sql_select=pg_query("select \"V_Receipt\",\"VatValue\" from \"FVat\" where \"V_Receipt\" like '%$q%' ORDER BY \"V_Receipt\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $V_Receipt = trim($res_cn["V_Receipt"]);
    $VatValue = number_format($res_cn["VatValue"],2);

        $S_V_Receipt = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $V_Receipt);
        echo "<li onselect=\"this.setText('$V_Receipt : $VatValue').setValue('$V_Receipt'); \"><b>ภาษี</b> : $S_V_Receipt : $VatValue</li>";
}

}elseif($t == "2"){ // เงินโอนที่ออกผิดเลขที่สัญญา

$sql_select=pg_query("select \"R_Receipt\",\"R_Money\" from \"Fr\" where \"R_memo\"='TR-ACC' AND \"R_Receipt\" like '%$q%' ORDER BY \"R_Receipt\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $R_Receipt = trim($res_cn["R_Receipt"]);
    $R_Money = number_format($res_cn["R_Money"],2);

        $S_R_Receipt = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $R_Receipt);
        echo "<li onselect=\"this.setText('$R_Receipt : $R_Money').setValue('$R_Receipt'); \"><b>ค่างวด</b> : $S_R_Receipt : $R_Money</li>";
}

$sql_select=pg_query("select \"O_RECEIPT\",\"O_MONEY\" from \"FOtherpay\" where \"O_memo\"='TR-ACC' AND \"O_RECEIPT\" like '%$q%' ORDER BY \"O_RECEIPT\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $O_RECEIPT = trim($res_cn["O_RECEIPT"]);
    $O_MONEY = number_format($res_cn["O_MONEY"],2);

        $S_O_RECEIPT = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $O_RECEIPT);
        echo "<li onselect=\"this.setText('$O_RECEIPT : $O_MONEY').setValue('$O_RECEIPT'); \"><b>ค่าอื่นๆ</b> : $S_O_RECEIPT : $O_MONEY</li>";
}

$sql_select=pg_query("select \"V_Receipt\",\"VatValue\" from \"FVat\" where \"V_memo\"='TR-ACC' AND \"V_Receipt\" like '%$q%' ORDER BY \"V_Receipt\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $V_Receipt = trim($res_cn["V_Receipt"]);
    $VatValue = number_format($res_cn["VatValue"],2);

        $S_V_Receipt = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $V_Receipt);
        echo "<li onselect=\"this.setText('$V_Receipt : $VatValue').setValue('$V_Receipt'); \"><b>ภาษี</b> : $S_V_Receipt : $VatValue</li>";
}
    
}
?>