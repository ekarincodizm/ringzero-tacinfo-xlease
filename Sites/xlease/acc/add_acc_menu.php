<?php
session_start();
?>
<html>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<style type="text/css">
<!--
#Content {
    overflow: hidden;
    width: 800px;
    margin-right: auto;
    margin-left: auto;
    margin-top: 0px;
    color: #000000;
    font-family: sans-serif;
    font-size: 13px;
    font-weight: bold;
}
#Content .LeftC {
    float: left;
    margin-right: 25px;
}
A:link {
    font-size: 13px;
    color: #000000;
    font-weight:normal;
}
A:visited {
    font-size: 13px;
    color: #000000;
    font-weight:normal;
}
A:active {
    font-size: 13px;
    color: #000000;
    font-weight:normal;
}
A:hover {
    font-size: 13px;
    color: #969696;
    font-weight:normal;
}
-->
</style>

</head>
<body>

<div id="Content">
    <div class="LeftC">

<b>บันทึกบัญชี</b> 
<a href="add_acc_manual.php" target=frm_r>บันทึกเอง</a>
<a href="add_acc_formula.php" target=frm_r>ใช้สูตรทางบัญชี</a>

    </div>
</div>