<?php

  session_start();
  
  include("config/configpost.php");
  include("lib/util.php");
  include("config/config.php");
  
  $nowDate = nowDate(); // วันที่ปัจจุบัน โดยดึงมาจาก DataBase Postgresql
  
  $cmd = pg_escape_string($_REQUEST['cmd']);
  if ($_POST["cmd"] == "check_cus")
  {
    $found = "0";
    
    $vcontact = $outlet->from("vcontact")->where("\"CusID\" = '" . pg_escape_string($_POST["cus"]) . "'")->find();
            
    for ($i = 0 ; $i < count($vcontact) ; $i++)
    {
      $str = $vcontact[$i]->full_name . " , " . $vcontact[$i]->c_regis;
      
      $pos = strrpos($str,$_POST["key"]);
      
      if ($pos !== false)
      {
        $found = "1";
      }
    }
    
    echo $found;
  }
else if($cmd == "load_join1"){
    $id = pg_escape_string($_GET['id']);
    echo '<input type="button" name="txtkr'.$id.'" id="txtkr'.$id.'" size="5" value="คำนวณค่าเข้าร่วม" onclick="windowOpen(\'nw/join_cal/join_cal.php?idno='.pg_escape_string($_GET[idno]).'&inputName='.pg_escape_string($_GET[inputName]).'&change_pay_type='.pg_escape_string($_GET[change_pay_type]).'&page_name='.pg_escape_string($_GET[page_name]).'&pay_date='.pg_escape_string($_GET[pay_date]).'\');" >';
}
else if ($_POST["cmd"] == "join_type")
  {

    $join_type1=pg_query("select join_get_join_type(pg_escape_string($_REQUEST[id]))"); 
    
    echo pg_fetch_result($join_type1,0); 
  }
  else if ($_POST["cmd"] == "check_idno")
  {
    $load_idno = $outlet->from("vcontact")->where("\"IDNO\" = '" . pg_escape_string($_POST["idno"]) . "'")->find();
    
    if (count($load_idno) == 1)
    {
      echo "1";
    }
    else
    {
      echo "ข้อมูลเลขที่สัญญาไม่ถูกต้อง";
    } 
  }
  else if ($_POST["cmd"] == "load_cus")
  {
    $customer = array();
    
    $cus = $outlet->from("fa1")->where("\"CusID\" = '" . pg_escape_string($_POST["cusid"]) . "'")->find();
    
    if (count($cus) == 1)
    {
      $customer["cusid"] = $cus[0]->cusid;
      $customer["cusname"]  = $cus[0]->a_firname . " " . $cus[0]->a_name . " " . $cus[0]->a_sirname;
      
      //load Fp
      $account = $outlet->from("fp")->where("\"CusID\" = '" . pg_escape_string($_POST["cusid"]) . "'")->find();
      
      for ($i = 0 ; $i < count($account) ; $i++)
      {
        $load_remain  = $outlet->from("vcuspayment")->where("\"IDNO\" = '" . $account[$i]->idno . "' and \"R_Date\" is null")->find();
          
        $load_payment = $outlet->from("vcontact")->where("\"IDNO\" = '" . $account[$i]->idno . "'")->findOne();
        $load_payment->period = count($load_remain);

        $customer["asset"][$account[$i]->idno] = $load_payment;
        
                     
        
        /*
        $customer["asset"][$account[$i]->idno]["idno"] = $account[$i]->idno;
        $customer["asset"][$account[$i]->idno]["pmt"] = $account[$i]->p_month;
        $customer["asset"][$account[$i]->idno]["vat"] = $account[$i]->p_vat;
        
        $load_payment  = $outlet->from("vcuspayment")->where("\"IDNO\" = '" . $account[$i]->idno . "' and \"R_Date\" is null")->find();
        $customer["asset"][$account[$i]->idno]["period"] = count($load_payment);
        
        if ($account[$i]->asset_type == 1)       
        {
          $customer["asset"][$account[$i]->idno]["asset_type"] = "รถยนต์";
          
          $asset = $outlet->from("fc")->where("\"CarID\" = '" . $account[$i]->asset_id . "'")->find();
          
          if (count($asset) == 1)
          {
            $customer["asset"][$account[$i]->idno]["asset_name"] = $asset[0]->c_carname . " " . $asset[0]->c_regis;
          }           
        }
        else if ($account[$i]->asset_type == 2)       
        {
          $customer["asset"][$account[$i]->idno]["asset_type"] = "แก๊ส";
          
          $asset = $outlet->from("fgas")->where("\"GasID\" = '" . $account[$i]->asset_id . "'")->find();
          
          if (count($asset) == 1)
          {
            $customer["asset"][$account[$i]->idno]["asset_name"] = $asset[0]->gas_name . " " . $asset[0]->car_regis;
          }            
        }
        else
        {
          $customer["asset"][$account[$i]->idno]["asset_type"] = "error";
          $customer["asset"][$account[$i]->idno]["asset_name"] = "ข้อมูลผิดพลาด";          
        }
        */
      }
      
      echo json_encode($customer);
    } 
  }
  else if ($_POST["cmd"] == "load_asset")
  {
    $asset = $outlet->from("vcontact")->where("\"IDNO\" = '" . pg_escape_string($_POST["idno"]) . "'")->findOne();
   
    $load_remain  = $outlet->from("vcuspayment")->where("\"IDNO\" = '" . pg_escape_string($_POST["idno"]) . "' and \"R_Date\" is null")->find();
    $asset->period = count($load_remain); 
    
    $load_close_discount = $outlet->query("select discount_close('today' , '" . pg_escape_string($_POST["idno"]) . "')");
    $row = $load_close_discount->fetch();  
    $asset->close_discount = $row[0];
    
    echo json_encode($asset); 
  }
  else if ($_POST["cmd"] == "list_typepay")
  {
    $typepay = array();
    
    $load_typepay = $outlet->from("typepay")->orderBy("\"TName\"")->find();
    
    for ($i = 0 ; $i < count($load_typepay) ; $i++)
    {
      $typepay[$load_typepay[$i]->typeid]["id"] = $load_typepay[$i]->typeid;
      $typepay[$load_typepay[$i]->typeid]["name"] = $load_typepay[$i]->tname;
    }
    
    echo json_encode($typepay);
  }
  else if ($_POST["cmd"] == "pay_detail")
  {
    $pay_detail = array();
    
    $load_payment  = $outlet->from("vcuspayment")->where("\"IDNO\" = '" . pg_escape_string($_POST["idno"]) . "' and \"R_Date\" is null")->find();
    $pay_detail["period"] = count($load_payment);
    
    $load_profile = $outlet->from("vcontact")->where("\"IDNO\" = '" . pg_escape_string($_POST["idno"]) . "'")->findOne();
    $pay_detail["amount"] = $load_profile->p_month + $load_profile->p_vat;
    
    echo json_encode($pay_detail);  
  }
  else if ($_POST["cmd"] == "pay_cash1")
  {
    $payment = json_decode(stripcslashes($_POST["payment"]));

    $postid = $outlet->query("select gen_pos_no('$nowDate')");
    $row = $postid->fetch();
    
    $new_postlog = new postlog();
    $new_postlog->postid = $row[0]; 
    $new_postlog->useridpost = $_SESSION["av_iduser"];         
    $new_postlog->postdate = $nowDate;
    $new_postlog->paytype = "CA";
    $new_postlog->acceptpost = 'false';
    $outlet->save($new_postlog);
    
    foreach ($payment as $key => $value)
    {
      $new_fcash = new fcash();
      $new_fcash->postid = $row[0];
      $new_fcash->cusid = pg_escape_string($_POST["cusid"]);
      $new_fcash->idno = $value->idno;
      $new_fcash->typepay = $value->typepay;
      $new_fcash->amtpay = $value->amount;
      $outlet->save($new_fcash);
    }
    
    echo "1";   
  }
  else if ($_POST["cmd"] == "pay_cheque1")
  {
    $cheque = json_decode(stripcslashes($_POST["payment"])); 
    
    $get_postid = $outlet->query("select gen_pos_no('$nowDate')");
    $row = $get_postid->fetch();  
    $postid = $row[0];
    
    $new_postlog = new postlog();
    $new_postlog->postid = $postid; 
    $new_postlog->useridpost = $_SESSION["av_iduser"];
    $new_postlog->postdate = $nowDate;
    $new_postlog->paytype = "CH";
    $new_postlog->acceptpost = 'false';
    $outlet->save($new_postlog);    
    
    for ($i = 0 ; $i < count($cheque) ; $i++)
    {
      //{checkno : cheque.length + 1 , cheque_number : "" , cheque_date : "" , cheque_bank : "" , cheque_bankbranch : "" , cheque_outbkk : "" , cheque_amount : "" , payment : []};                  
      $new_fcheque = new fcheque();
      $new_fcheque->postid = $postid;
      $new_fcheque->cheqeqno = $cheque[$i]->cheque_number;
      $new_fcheque->bankname = $cheque[$i]->cheque_bank;
      $new_fcheque->bankbranch = $cheque[$i]->cheque_bankbranch;
      $new_fcheque->amtoncheque = $cheque[$i]->cheque_amount;
      $new_fcheque->receiptdate = $nowDate; 
      $new_fcheque->dateoncheque = dmy2ymd($cheque[$i]->cheque_date);  
      $new_fcheque->outbangkok = ($cheque[$i]->cheque_outbkk) ? 'true' : 'false';
      $new_fcheque->numofreenter = 0;
      $new_fcheque->ispass = 'false';
      $new_fcheque->accept = 'false';
      $new_fcheque->isreturn = 'false';
      $outlet->save($new_fcheque);    
      
      for ($j = 0 ; $j < count($cheque[$i]->payment) ; $j++)  
      {
        $get_cusid = $outlet->from("vcontact")->where("\"IDNO\" = '" . $cheque[$i]->payment[$j]->idno . "'")->findOne();
                
        $new_detailcheque = new detailcheque();
        $new_detailcheque->postid = $postid;
        $new_detailcheque->chequeno = $cheque[$i]->cheque_number; 
        $new_detailcheque->cusid = $get_cusid->cusid;
        $new_detailcheque->idno = $cheque[$i]->payment[$j]->idno;
        $new_detailcheque->typepay = $cheque[$i]->payment[$j]->typepay;  
        $new_detailcheque->cusamount = $cheque[$i]->payment[$j]->amount; 
        $outlet->save($new_detailcheque); 
      }
    }
    
    echo "1";     
  }
  else if ($_POST["cmd"] == "load_detail")
  {
    $load_detail = $outlet->from("vcuspayment")->where("\"IDNO\" = '" . pg_escape_string($_POST["idno"]) . "'")->orderBy("\"DueNo\"")->find();
    
    echo json_encode($load_detail);    
  }
  else if ($_POST["cmd"] == "list_bank")
  {
    $list_bank = $outlet->from("bankcheque")->find();
    
    echo json_encode($list_bank); 
  }
  else if ($_POST["cmd"] == "depositpay")
  {
    $receipt = array();
	
    $payment = json_decode(stripcslashes($_POST["payment"]));   
    
    foreach ($payment as $p)
    {
      $optionStr = "";

      if ($p->typepay == "133")
      {
        $optionStr = $p->opt;
      }
      
	  $sql = "";	  
	  
	  if ($p->typepay == 1)
	  {
		$sql = "select use_deposit_remain('" . pg_escape_string($_POST['idno']) . "' , " . ($p->amount) . " , " . $p->typepay . " , '" . $optionStr . "' , " . pg_escape_string($_POST['close_discount_amt']) . ")";
	  }
	  else
	  {
		$sql = "select use_deposit_remain('" . pg_escape_string($_POST['idno']) . "' , " . ($p->amount) . " , " . $p->typepay . " , '" . $optionStr . "' , 0)";
	  }
	  
	  $get_receipt = $outlet->query($sql);
      $row = $get_receipt->fetch();  
      $receiptid = $row[0];
      
      array_push($receipt , $receiptid);
    }
	
    echo json_encode($receipt);        
  }

?>
