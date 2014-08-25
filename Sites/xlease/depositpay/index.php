<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="content-language" content="en">
<meta name="author" content="">
<meta http-equiv="Reply-to" content="@.com">
<meta name="generator" content="PhpED 5.2">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="creation-date" content="09/20/2007">
<meta name="revisit-after" content="15 days">

<title>Post placeholder</title>

<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/table.css">
<link rel="stylesheet" type="text/css" href="../js/autocomplete/jquery.autocomplete.css">


<script type="text/javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/json2.js"></script>
<script type="text/javascript" src="../js/autocomplete/jquery.autocomplete.js"></script> 
<script type="text/javascript" src="../js/maskinput/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../js/mousewheel/jquery.mousewheel.js"></script>


<link rel="stylesheet" type="text/css" href="../js/datepick/redmond.datepick.css">
<script type="text/javascript" src="../js/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="../js/datepick/jquery.datepick-th.js"></script>

</head>

<body>

<div style="width:800px; margin-left:auto; margin-right:auto; border:#DDDDDD 1px dashed; padding:5px;">

  <h1 id="caption">ใช้เงินรับฝาก</h1>
  
  <br><br>

  <span class="text_bold">เลือกเลขที่ลูกค้า</span>
  
  <br><br>

  <div id="panelLeft">

    <fieldset>
      <legend>ระบุข้อมูลลูกค้า</legend> 

      <br>
      
      <form id="cash1_1" name="cash1_1" onSubmit="return cash1_1_validate(event);"> 
        <input type="hidden" id="cusid" name="cusid" value="" />
        <label>IDNO/ชื่อ :&nbsp;</label>
        <img src="../postpayment/image/btnFind.jpg" align="top">
        <input id="cash1_idno" name="cash1_idno" type="text" size="25" />
        <input type="image" src="../postpayment/image/btnNext.gif" align="top" />
      </form>
     <div style="text-align:right"><a href=" " onClick="window.close();">ปิดหน้านี้</a></div>             
    </fieldset>
  
  </div> 
  
  <div id="panelRight" >
                    
    <div id="pnIDNO">
      <fieldset>
        <legend>เลือกหมายเลขสัญญา</legend>
        
        <table id="list_idno" class="tb" border="0" cellpadding="0" cellspacing="1" width="100%">
          
          <thead> 
            <tr> 
              <th width="50">IDNO</th> 
              <th>สินทรัพย์</th>
            </tr> 
          </thead> 
          
          <tbody> 
          
          </tbody>
           
        </table>
              
      </fieldset>
    </div>
    
  </div> 

  <div id="footer" style="clear:both;">   

</div>

<script type="text/javascript">
  
  $().ready
  (
    function()
    {
      $("#pnIDNO").hide(""); 
      
      $("#cash1_idno").autocomplete
      (
        "../search_idno.php", 
        {
          width: 260,
          selectFirst: false
        }
      );
      $("#cash1_idno").result
      (
        function(event, data, formatted) 
        {
          if (data)
          {
            $("#cash1_1 > #cusid").val(data[1]);
            
            $("#pnIDNO").show("slow");  
            
            //add idno to table 
            profile = $.ajax
            ({
              type: "POST",
              url: "../api.php",
              data: "cmd=load_cus&cusid=" + data[1] ,
              async: false
            }).responseText;
            
            profile_obj = eval('(' + profile + ')');
            
            for (var v in profile_obj.asset) 
            {
              var row = "<tr><th width='100'><a href='pay.php?idno=" + profile_obj.asset[v].idno + "'>" + profile_obj.asset[v].idno + "</a></th> <th>" + (profile_obj.asset[v].c_carname != null ? profile_obj.asset[v].c_carname + profile_obj.asset[v].c_regis : profile_obj.asset[v].gas_name + profile_obj.asset[v].car_regis ) + "</th></tr>";
              
              $("#list_idno").append(row);
            }
          }
        }
      );
      
    }
  );
  
  function cash1_1_validate(e)
  {
    return false;
  }  
  
</script>
  
</body>

</html>