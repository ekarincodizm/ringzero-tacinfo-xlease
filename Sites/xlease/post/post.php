<?php

  include("config/config.php");
  include("lib/util.php");

  $content = "";
  
  if (isset($_POST["cmd"]))
  {
    if ($_POST["cmd"] == "cash1_1")
    {
      $content = loadHtml("post/html/cash1_1.html"); 
      
      $content = $content . "<script type=\"text/javascript\"> init('" . $_POST["cusid"] . "'); </script>";
    }
  }
  else if (isset($_GET["paytype"]))
  {
    if ($_GET["paytype"] == "ca")
    {
      $content = loadHtml("post/html/cash1.html");      
    }
    else if ($_GET["paytype"] == "ch")
    {
      
    }
  }
  else
  {
    $content = loadHtml("post/html/select.html");
  }
  
  echo $content;
    
?>
