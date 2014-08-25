<?php

  include("config/config.php");
  include("lib/util.php");

  //session_start(); 
  
  $content = "";
  
  if (isset($_POST["cmd"]))
  {
    if ($_POST["cmd"] == "cash1_1")
    {
      $content = loadHtml("postpayment/html/cash1_1.html"); 
      
      $content = $content . "<script type=\"text/javascript\"> init('" . $_POST["cusid"] . "'); </script>";
    }
  }
  else if (isset($_GET["paytype"]))
  {
    if ($_GET["paytype"] == "ca")
    {
      $content = loadHtml("postpayment/html/cash1.html");      
    }
    else if ($_GET["paytype"] == "ch")
    {
      $content = loadHtml("postpayment/html/cheque1.html");      
    }
  }
  else
  {
    $content = loadHtml("postpayment/html/select.html");
  }
  
  echo $content;
    
?>
