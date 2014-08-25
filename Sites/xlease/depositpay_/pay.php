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

  <span class="text_bold">ลูกค้า</span>
  <span class="text_blue_bold" id="customer_name"></span>

  <br><br>

  <div id="contract">

  </div>

  <br>
  
<span class="text_bold">รายการ</span>

  <table id="list" class="tb" border="0" cellpadding="0" cellspacing="1" width="780">
    <thead> 
      <tr> 
        <th width="50">รายการ</th> 
        <th width="150">รายการจ่ายเงิน</th> 
        <th width="300">&nbsp;</th> 
        <th width="150">จำนวนเงิน</th>
        <th >&nbsp;</th>  
      </tr> 
    </thead> 
    
    <tbody> 
    
    </tbody>
    
    <tfoot>
      <tr>
        <th width="50"></th> 
        
        <th width="150">
          <select id="cb_typepay" name="cb_typepay" size="1">  
          
          </select>                              
        </th> 
        
        <th width="300"> 
          <div id="pay_detail" style="float:left">
            จำนวนงวด
            <select id="cb_period" name="cb_period" size="1">
            
            </select>
          </div> 
          
          <div id="close_discount" style="float:left">
            ส่วนลด
            <input id="e_close_discount" name="e_close_discount" type="text" size="10" />
          </div>
          
          <div id="other_account" style="float:left"> 
            บัญชีที่โอนไป
            <input id="e_otheraccount" name="e_otheraccount" type="text" />
          </div>
        </th>
        
        <th width="150" align="right">
          <input id="e_amount" name="e_amount" type="text" />
        </th>
        
        <th>
          <button title="เพิ่มรายการ" onclick="addItem();" >เพิ่มรายการ</button>
        </th>       
      </tr>
      
      <tr>
        <th colspan="5"><hr></th>    
      </tr>  
      
      <tr>
        <th colspan="3" align="right"><span id="total_text"></span></th>
        <th align="right"><span id="total_num"></span></th>      
        <th>&nbsp;</th>   
      </tr> 
      
      <tr>
        <th colspan="5"><hr></th>   
      </tr>  
      
      <tr>
        <th colspan="5"><button id="btn_save" title="บันทึกรายการ" onclick="save();">บันทึกรายการ</button></th>   
      </tr>      

    </tfoot> 
  </table>  
  
  <div id="result">
  
  </div> 

</div>

<script type="text/javascript">

  var profile_obj;
  var typepay_obj;
  var payment = [];
  var close_discount_amt = 0;

  function format(data)
  {
    if ( data == null )
    {
      return ""; 
    }
    else
    {
      return data;
    }
  }

  function toggle(item)
  {
    if ( $("#detail" + item).html() == "" ) 
    {
      html = "<br><br><br><center><image src=\"postpayment/image/ajax-loader.gif\" /><br><br><span class=\"text_blue_bold\">กำลังเตรียมข้อมูล</span></center>"; 
      
      $("#detail" + item).html(html);    
    }

    $("#detail" + item).toggle("slow"); 
        
    $.ajax
    ({
      type: "POST",
      url: "../api.php",
      data: "cmd=load_detail&idno=" + item,
      success:  function(msg)
                {
                  detail = eval('(' + msg + ')');
                
                  table = "<table class=\"tb2\">"
                        + " <thead>"
                        + "   <tr>"
                        + "     <th>งวดที่</th>"
                        + "     <th>วันที่นัด</th>"
                        + "     <th>วันที่มาจ่าย</th>"
                        + "     <th>ล่าช้า</th>"
                        + "     <th>ค่าปรับ</th>"
                        + "     <th>เลขที่ใบเสร็จ</th>"
                        + "     <th>เลขที่ VAT</th>"
                        + "     <th>จำนวนเงิน</th>"
                        + "     <th>VAT</th>"
                        + "   </tr>"
                        + " </thead>"
                        + " <tbody>";
                        
                  for (var d in detail)   
                  {
                    table+= "<tr>"
                          + "     <th align=\"right\">" + detail[d].dueno + "</th>"
                          + "     <th>" + format( detail[d].duedate )     + "</th>"
                          + "     <th>" + format( detail[d].r_date )      + "</th>"
                          + "     <th align=\"right\">" + format( detail[d].daydelay )    + "</th>"
                          + "     <th align=\"right\">" + format( detail[d].calamtdelay ) + "</th>"
                          + "     <th>" + format( detail[d].r_receipt )   + "</th>"
                          + "     <th>" + format( detail[d].v_receipt )   + "</th>"
                          + "     <th align=\"right\">" + format( detail[d].r_money )     + "</th>"
                          + "     <th align=\"right\">" + format( detail[d].vatvalue )    + "</th>"
                          + "</tr>";
                  }                  
                        
                  table +=" </tbody>"
                        + "</table>";
                                  
                  $("#detail" + item).html(table);
                }
    }); 
  }
  
  function update_ui()
  {   
    $("#pay_detail").hide();
    $("#close_discount").hide();
    $("#other_account").hide();
    
    if ( $("select#cb_typepay").val() == "1" )
    {
      $("#pay_detail").show();
      $("#e_amount").attr("readonly", "readonly"); 
      
      var pmt = profile_obj.p_month;
      var vat = profile_obj.p_vat;   
        
      $("#e_amount").val( parseFloat( $("select#cb_period").val() ) *  (pmt + vat));
      
      if ( $("select#cb_period").val() == profile_obj.period )
      {
        $("#close_discount").show();
        $("#e_amount").val( parseFloat( $("select#cb_period").val() ) *  (pmt + vat) - (Math.round($("#e_close_discount").val() *100) / 100) );
      }
    }
    else if ( $("select#cb_typepay").val() == "133" )  
    {
      $("#other_account").show(); 
      $("#e_otheraccount").val("");
      $("#e_amount").removeAttr("readonly");  
    }
    else
    {
      $("#pay_detail").hide();
      $("#e_amount").val("");
      $("#e_amount").removeAttr("readonly"); 
    }   
  }  
  
  function addItem()
  {
	//check duplicate item
	for (i = 0 ; i < payment.length ; i++)
    {
      if (payment[i].typepay == $("#cb_typepay").val())
      {       
		alert("มีรายการนี้แล้ว");
	  
        return false;
      }   
      
      i = i + 1;  
    }
  
    if ( isNaN($("#e_amount").val()) | ($("#e_amount").val() == ""))
    {
      alert("ข้อมูลจำนวนเงินไม่ถูกต้อง");
      
      $("#e_amount").focus();
	  
	  return false;
    }
    else
    {
      if ($("#cb_typepay").val() == 133) 
      {
        //validate other account
        exist = $.ajax
        ({
          type: "POST",
          url: "../api.php",
          data: "cmd=check_idno&idno=" + $("#e_otheraccount").val() ,
          async: false
        }).responseText;
        
        if (exist != "1")    
        {
          alert(exist);  
                
          return false;
        }
      }
      
      //validate amount
      var amount = new Number($("#e_amount").val());
    
      if ($("#cb_typepay").val() == 299) 
      {
        //check dp_balance
        var balance = new Number(profile_obj.asset[$("#cb_idno").val()].dp_balance == null ? 0 : profile_obj.asset[$("#cb_idno").val()].dp_balance);
        
        if (amount > balance)
        {
          alert("การลบเงินรับฝากต้องไม่เกินจำนวนเงินรับฝากที่มีอยู่");
          
          return false;
        }
        else
        {
          var amount = (-1 * amount);  
        }
      }
      
      var i = payment.length;
      payment[i] = {idno : $("#cb_idno").val() , typepay: $("#cb_typepay").val() , amount : amount};
      
      if (payment[i].typepay == 1)
      {
        payment[i].opt = $("#cb_period").val();
        payment[i].optText = $("#cb_period").val() + " งวด";
        
        if ($("#cb_period").val() == profile_obj.period)
        {
          close_discount_amt = (Math.round($("#e_close_discount").val() *100) / 100);
          payment[i].optText = payment[i].optText + '  (ส่วนลด ' + (Math.round($("#e_close_discount").val() *100) / 100) + ' บาท)';   
        }
      }
      else if (payment[i].typepay == 133)
      {
        payment[i].opt = $("#e_otheraccount").val();
        payment[i].optText = "บัญชีเลขที่ " + $("#e_otheraccount").val();   
      }
      else 
      {
        payment[i].optText = "";
      }
  
      $("#cb_typepay").val("200");
      update_ui();
      $("#e_amount").val("");
      
      update_table();
    }
  }  
  
  function update_table()
  {
    $("#list > tbody").empty(); 
    
    var index = 1; 
    var total = 0;   
    
    for (i = 0 ; i < payment.length ; i++)
    {
      if (payment[i] != "undefined")
      {       
        var typepayStr = typepay_obj[payment[i]["typepay"]].name;
        var typepayoptText = payment[i]["optText"];
          
        var amt = new Number(payment[i]["amount"]);
        amtStr = amt.toFixed(2);
        
        total = total + amt;

        html = "<tr>"     
             + "  <th width=\"50\">" + index + "</th>" 
             + "  <th width=\"150\">" + typepayStr + "</th>" 
             + "  <th width=\"150\">" + typepayoptText + "</th>"
             + "  <th width=\"150\" align=\"right\">" + amtStr + "</th>"
             + "  <th>&nbsp;</th>"
             + "</tr>";        
             
        $('#list > tbody').append(html); 
      }   
      
      index = index + 1;  
    }
    
    $("#total_text").html("รวม");  
    $("#total_num").html(total.toFixed(2));
  }  
  
  function init(idno)
  {
    $("#e_amount").bind('keypress', 
                        function(e) 
                        { 
                          return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
                        } );
    
    $("#e_close_discount").bind('keypress', 
                            function(e) 
                            { 
                              return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
                            } );
    
    //load customer profile
    profile = $.ajax
              ({
                type: "POST",
                url: "../api.php",
                data: "cmd=load_asset&idno=" + idno ,
                async: false
              }).responseText;
    
    profile_obj = eval('(' + profile + ')');  
    
    $("#customer_name").html(profile_obj.cusid + " : " + profile_obj.full_name);    
    
    html = "<div class='bluebox'>"
         + "  <table>"        
         + "    <tr>"
         + "      <td width='120'>IDNO&nbsp;" + profile_obj.idno + "</td>"
         + "      <td width='250'>" + (profile_obj.c_carname != null ? profile_obj.c_carname + profile_obj.c_regis : profile_obj.gas_name + profile_obj.car_regis ) + "</td>"
         + "      <td width='150'>ค่างวด (รวมภาษี) &nbsp;&nbsp;" + (profile_obj.p_month + profile_obj.p_vat) + "</td>"
         + "      <td width='100'>เหลือ &nbsp;&nbsp;" + profile_obj.period + "&nbsp;งวด</td>"
         + "      <td width='100'>เงินรับฝาก &nbsp;&nbsp;" + (profile_obj.dp_balance == null ? "0" : profile_obj.dp_balance) + "</td>"
         + "      <td width='10'>"
         + "        <div id='tab" + profile_obj.idno + "' class='btn_asc' onclick='toggle(\"" + profile_obj.idno + "\")'>&nbsp;</div>"
         + "      </td>"
         + "    </tr>"
         + "  </table>"
         + "</div>" 
         + "<div id='detail" + profile_obj.idno + "' style='height:200px; overflow:auto;'></div>";

    $("#contract").append(html);
    $("#detail" + profile_obj.idno).hide();   
    
    $("#tab" + profile_obj.idno).toggle
    (
      function () 
      {
        $(this).removeClass("btn_asc");      
        $(this).addClass("btn_desc"); 
      },
      
      function () 
      {
        $(this).removeClass("btn_desc");      
        $(this).addClass("btn_asc"); 
      }
    );
    
    typepay = $.ajax
             ({
                type: "POST",
                url: "../api.php",
                data: "cmd=list_typepay" ,
                async: false
             }).responseText;
      
    typepay_obj = eval('(' + typepay + ')'); 
    
    var period_option = "";
    
    for (i = 1 ; i<= profile_obj.period ; i++)
    {
      period_option  = period_option + '<option value="' + i + '">' + i + '</option>';
    }
    
    $("#e_close_discount").val( Math.round(profile_obj.close_discount *100) / 100 );
    
    $("select#cb_period").html(period_option);    

    var typepay_option = "";
    
    for (var v in typepay_obj)
    {
      if ( (typepay_obj[v].id != 200) && (typepay_obj[v].id != 299) )
      {
        typepay_option  = typepay_option + '<option value="' + typepay_obj[v].id + '">' + typepay_obj[v].name + '</option>';
      }
    } 
    
    $("select#cb_typepay").html(typepay_option);
    
    update_ui();
  
    $("#cb_typepay").change(function() { update_ui(); });
    $("#cb_period").change(function() { update_ui(); });   
    $("#e_close_discount").keydown(function() { update_ui(); }); 
  }
  
  function save()
  {
    if ($("#e_amount").val() != "")
    {
      alert("ยังมีรายการที่ไม่ได้เพิ่ม");
      
      return false;
    }
    
    if ( payment.length == 0 )
    {
      alert("ไม่มีรายการชำระเงินเลย");    
      
      return false;
    }
    
    //total
    var index = 1; 
    var total = 0;   
    
    for (i = 0 ; i < payment.length ; i++)
    {
      if (payment[i] != "undefined")
      {       
        var amt = new Number(payment[i]["amount"]);
        amtStr = amt.toFixed(2);
                
        total = total + amt;
      }   
      
      index = index + 1;  
    } 
   
    /*
    if (total > profile_obj.dp_balance)
    {
      alert("ใช้เงินรับฝากมากกว่าเงินรับฝากที่มีอยู่");    
      
      return false;
    }
    */
  
    $("#btn_save").attr("disabled", true);
  
    $.post
    (
      "../api.php", 
      { 
        cmd : "depositpay" , 
        idno :  profile_obj.idno,
        close_discount_amt : close_discount_amt ,
        payment : JSON.stringify(payment) 
      } ,
      
      function(data)
      {
        $("#result").append("<br><h1>บันทึกข้อมูลแล้ว</h1><br>"); 
        $("#result").append("<span class='text_blue_bold'>รายการใบเสร็จ</span><br>"); 
        
        var receipt = eval('(' + data + ')'); 
        
        for (var v in receipt)
        {
          $("#result").append("<li>ใบเสร็จเลขที่ " + receipt[v] + " <button onclick=\"print_receipt('" + receipt[v] + "')\">พิมพ์</button></li>");  
          
          window.open('print_receipt.php?receipt_no=' + receipt[v] , '_blank');
        }
      }  
    );
  }  
  
  function print_receipt(receipt_no)
  {
    window.open('print_receipt.php?receipt_no=' + receipt_no , '_blank');
  }
  
  init('<?php echo pg_escape_string($_GET['idno']); ?>');
  
</script>
  
</body>

</html>