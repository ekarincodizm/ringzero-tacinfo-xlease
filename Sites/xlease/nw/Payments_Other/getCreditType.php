<?php
include("../../config/config.php");

$ConID = $_POST["ConID"]; // เลขที่สัญญา

echo pg_creditType($ConID);
?>