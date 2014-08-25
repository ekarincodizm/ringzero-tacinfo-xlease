<?php
  
  function loadHtml($filename)
  {
    $f = fopen($filename , 'r');
    $data = fread($f , filesize($filename));
    fclose($f);
    
    return $data;
  }
  
  function dmy2mdy($dmy)
  {
    $parts = split("/" , $dmy);
    
    return $parts[1] . "/" . $parts[0] . "/" . $parts[2];
  }
  
  function dmy2ymd($dmy)
  {
    $parts = split("/" , $dmy);
    
    return $parts[2] . "/" . $parts[1] . "/" . $parts[0];        
  }
  
?>
