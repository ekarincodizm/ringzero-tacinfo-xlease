<?php             

  include("config/config.php");
  include("lib/util.php");

  $q = strtolower($_GET["q"]);
  
  if (!$q)
  {
    return; 
  }
  else
  {
    $search_vcontact = $outlet->from("vcontact")->where("\"full_name\" like '%" . $q . "%' or \"IDNO\" like '%" . $q . "%' or \"C_REGIS\" like '%" . $q . "%'")->find();
    
    for ($i = 0 ; $i < count($search_vcontact) ; $i++)
    {
      $str = $search_vcontact[$i]->full_name . " , " . $search_vcontact[$i]->c_regis . "|" . $search_vcontact[$i]->cusid . "\n";
      
      echo $str;        
    }
  }
  
?>
