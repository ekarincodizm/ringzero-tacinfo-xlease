<?php
  
  include("config/configpost.php");
  include("lib/util.php");
  
  if ($_POST["cmd"] == "check_cus")
  {
    $found = "0";
    
    $vcontact = $outlet->from("vcontact")->where("\"CusID\" = '" . $_POST["cus"] . "'")->find();
            
    for ($i = 0 ; $i < count($vcontact) ; $i++)
    {
      $str = $vcontact[$i]->full_name . " , " . $vcontact[$i]->c_regis;
      
      $pos = strrpos($str, $_POST["key"]);
      
      if ($pos !== false)
      {
        $found = "1";
      }
    }
    
    echo $found;
  }
  else if ($_POST["cmd"] == "load_cus")
  {
    $customer = array();
    
    $cus = $outlet->from("fa1")->where("\"CusID\" = '" . $_POST["cusid"] . "'")->find();
    
    if (count($cus) == 1)
    {
      $customer["name"] = $cus[0]->cusid . " : " . $cus[0]->a_firname . " " . $cus[0]->a_name . " " . $cus[0]->a_sirname;
      
      //load Fp
      $account = $outlet->from("fp")->where("\"CusID\" = '" . $_POST["cusid"] . "'")->find();
      
      for ($i = 0 ; $i < count($account) ; $i++)
      {
        $customer["asset"][$i]["idno"] = $account[$i]->idno;
        
        if ($account[$i]->asset_type == 1)
        {
          $customer["asset"][$i]["asset_type"] = "รถยนต์";
          
          $asset = $outlet->from("fc")->where("\"CarID\" = '" . $account[0]->asset_id . "'")->find();
          
          if (count($asset) == 1)
          {
            $customer["asset"][$i]["asset_name"] = $asset[0]->c_carname . " " . $asset[0]->c_regis;
          }
        }
        else if ($account[$i]->asset_type == 2)
        {
          $customer["asset"][$i]["asset_type"] = "แก๊ส";
          
          $asset = $outlet->from("fgas")->where("\"GasID\" = '" . $cus[0]->asset_id . "'")->find();
          
          if (count($asset) == 1)
          {
            $customer["asset"][$i]["asset_name"] = $asset[0]->gas_name . " " . $asset[0]->car_regis;
          }          
        }
        else
        {
          $customer[$i]["asset_type"] = "error";
        }
      }
      
      echo json_encode($customer);
    } 
  }
  
  
?>
