<?php

session_start();

$cmort_credit_new = $_POST["cmort_credit_new"];
$cmort_credit_old = $_POST["cmort_credit_old"];
$cmort_credit_new = str_replace(',','',$cmort_credit_new);
$cmort_credit_old = str_replace(',','',$cmort_credit_old);
$result = $cmort_credit_new+$cmort_credit_old;

echo number_format($result);



?>