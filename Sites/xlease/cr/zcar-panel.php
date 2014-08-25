<?php
include("../config/config.php");
$regis = pg_escape_string($_GET['regis']);
if(empty($regis)){
    echo "ไม่พบเลขทะเบียน!";
    exit;
}

$resid=$_SESSION["av_iduser"];
$qry_ad=pg_query("select \"id_user\" from \"fuser\" WHERE \"id_user\"='$resid' AND \"emplevel\"<='10' ");
$numrow_ad = pg_num_rows($qry_ad);

$tc =1 ;//$tc =1 ใช้แบบ ใหม่ของนวมินทร์   , $tc =0 ใช้ระบบ Approve เก่าของจรัญ
?>

<script type="text/javascript">

function showadd(a){
    $('body').append('<div id="dialog"></div>');
    $('#dialog').load('zcar-popup-add.php?idaddcar='+a);
    $('#dialog').dialog({
        title: 'เพิ่มข้อมูล '+a,
        resizable: false,
        modal: true,  
        width: 800,
        height: 350,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });
}

function err_rights(){
alert("ท่านไม่มีสิทธิ์ทำรายการ !!");
}
function msg_success(){
alert("แก้ไขข้อมูลเรียบร้อยแล้ว!!");
}
function msg_del_success(){
alert("ลบข้อมูลเรียบร้อยแล้ว!!");
}
function swaptype(a,b){
    $('body').append('<div id="dialog"></div>');
    $('#dialog').load('zcar-admin-confirm.php?idaddcar='+a+'&type='+b);
    $('#dialog').dialog({
        title: 'Admin ยืนยันการเปลี่ยนประเภทของรายการ '+a,
        resizable: false,
        modal: true,  
        width: 400,
        height: 150,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });
}

function editbill(a){
    $("#bill"+a).load("zcar-textbox.php?module=showbill&id="+a,function (data, textStatus, XMLHttpRequest){
        if (textStatus == "success"){
            $("#chbill"+a).focus();
            $("#chbill"+a).select();
        }
    });
}

function savebill(a,b){
    var w=$("#chbill"+a).val();
    if(b != w){
	<?php if($tc==1 && $numrow_ad==0){?>
	err_rights();
	$("#bill"+a).load("zcar-textbox.php?module=defaultbill&id="+a);
	<?php }else if($tc==1 && $numrow_ad==1){?>
	      $.post('zcar-change-confirm-send-nv.php',{
           cmd: 'bill',
            id: a,
            w: w
        },
        function(data){
		//alert(data.message);
		//document.getElementById('show_err').innerHTML=data.message;
            if(data.success){
               // $('#dialogsave').dialog( "close" );
                mystring = $('#tbsearch').val();
                myarray = mystring.split("|");
                var cregis = encodeURIComponent ( myarray[0] );
			   msg_success();
                $("#panel").load("zcar-panel.php?regis="+ cregis);
            }else{
                alert(data.message);
            }
        },'json');
	<?php }else {?>
        $('body').append('<div id="dialogsave"></div>');
        $('#dialogsave').load('zcar-change-confirm.php?cmd=bill&id='+a+'&w='+w);
        $('#dialogsave').dialog({
            title: 'Admin ยืนยัน',
            resizable: false,
            modal: true,  
            width: 400,
            height: 150,
            close: function(ev, ui){
                $('#dialogsave').remove();
            }
        });
		<?php } ?>
    }else{
        $("#bill"+a).load("zcar-textbox.php?module=defaultbill&id="+a);
    }
}

function editnobill(a){
<?php if($tc==1 && $numrow_ad==0){?>
    $("#nobill"+a).load("zcar-textbox.php?module=shownobill&id="+a,function (data, textStatus, XMLHttpRequest){
        if (textStatus == "success"){
            $("#chnobill"+a).focus();
            $("#chnobill"+a).select();
        }
    });
	<?php } ?>
}

function savenobill(a){
    var w=$("#chnobill"+a).val();
    if(w != ""){
	<?php if($tc==1 && $numrow_ad==0){?>
	err_rights();
	$("#nobill"+a).load("zcar-textbox.php?module=defaultnobill&id="+a);
	<?php }else if($tc==1 && $numrow_ad==1){?>
	      $.post('zcar-change-confirm-send-nv.php',{
           cmd: 'nobill',
            id: a,
            w: w
        },
        function(data){
            if(data.success){
               // $('#dialogsave').dialog( "close" );
                mystring = $('#tbsearch').val();
                myarray = mystring.split("|");
                var cregis = encodeURIComponent ( myarray[0] );
			   msg_success();
                $("#panel").load("zcar-panel.php?regis="+ cregis);
            }else{
                alert(data.message);
            }
        },'json');
	<?php }else {?>
        $('body').append('<div id="dialogsave"></div>');
        $('#dialogsave').load('zcar-change-confirm.php?cmd=nobill&id='+a+'&w='+w);
        $('#dialogsave').dialog({
            title: 'Admin ยืนยัน',
            resizable: false,
            modal: true,  
            width: 400,
            height: 150,
            close: function(ev, ui){
                $('#dialogsave').remove();
            }
        });
		<?php } ?>
    }else{
        $("#nobill"+a).load("zcar-textbox.php?module=defaultnobill&id="+a);
    }
}

function editnotnobill(a){

    $("#notnobill"+a).load("zcar-textbox.php?module=shownotnobill&id="+a,function (data, textStatus, XMLHttpRequest){
        if (textStatus == "success"){
            $("#chnotnobill"+a).focus();
            $("#chnotnobill"+a).select();
        }
    });
	
}

function savenotnobill(a,b){
    var w=$("#chnotnobill"+a).val();
    if(b != w){
	<?php if($tc==1 && $numrow_ad==0){?>
	err_rights();
	$("#notnobill"+a).load("zcar-textbox.php?module=defaultnotnobill&id="+a);
	<?php }else if($tc==1 && $numrow_ad==1){?>
	      $.post('zcar-change-confirm-send-nv.php',{
           cmd: 'notnobill',
            id: a,
            w: w
        },
        function(data){
            if(data.success){
               // $('#dialogsave').dialog( "close" );
                mystring = $('#tbsearch').val();
                myarray = mystring.split("|");
                var cregis = encodeURIComponent ( myarray[0] );
			   msg_success();
                $("#panel").load("zcar-panel.php?regis="+ cregis);
            }else{
                alert(data.message);
            }
        },'json');
	<?php }else {?>
        $('body').append('<div id="dialogsave"></div>');
        $('#dialogsave').load('zcar-change-confirm.php?cmd=notnobill&id='+a+'&w='+w);
        $('#dialogsave').dialog({
            title: 'Admin ยืนยัน',
            resizable: false,
            modal: true,  
            width: 400,
            height: 150,
            close: function(ev, ui){
                $('#dialogsave').remove();
            }
        });
		<?php } ?>
    }else{
        $("#notnobill"+a).load("zcar-textbox.php?module=defaultnotnobill&id="+a);
    }
}
function delBill(a){
   

	<?php if($tc==1 && $numrow_ad==0){?>
	err_rights();
	
	<?php }else if($tc==1 && $numrow_ad==1){?>
	
	      $.post('zcar-change-confirm-send-nv.php',{
           cmd: 'del',
            id: a,
            w: a
        },
        function(data){
		//alert(data.message);
		//document.getElementById('show_err').innerHTML=data.message;
            if(data.success){
			
               // $('#dialogsave').dialog( "close" );
               mystring = $('#tbsearch').val();
                myarray = mystring.split("|");
                var cregis = encodeURIComponent ( myarray[0] );
			   msg_del_success();
                $("#panel").load("zcar-panel.php?regis="+ cregis);
            }else{
                alert(data.message);
            }
        },'json');
	
		<?php } ?>
    
}
</script>

<table width="100%" cellpadding="3" cellspacing="1" border="0" bgcolor="#FFFFFF">
<tr style="background:#ACACAC; font-weight:bold; text-align:center">
    <td colspan="5"></td>
    <td colspan="1">มีใบเสร็จ</td>
    <td colspan="1">ไม่มีใบเสร็จ</td>
    <td></td>
</tr>
<tr style="background:#ACACAC; font-weight:bold; text-align:center">
    <td>เลขที่สัญญา</td>
    <td>Note</td>
    <td>Date</td>
    <td>ประเภทค่าใช้จ่าย</td>
    <td>เลขที่ใบเสร็จ</td>
    <td>จำนวนเงิน</td>
    <td>จำนวนเงิน</td>
    <td>รวม</td>
</tr>
<?php
$qry=pg_query("select \"IDNO\",\"full_name\" from \"UNContact\" WHERE \"C_REGIS\"='$regis'");
while($res=pg_fetch_array($qry)){
    $IDNO=trim($res["IDNO"]);
    $full_name=trim($res["full_name"]);
    
    $qry_cartax=pg_query("select * from carregis.\"CarTaxDue\" where \"IDNO\"='$IDNO' ORDER BY \"TaxDueDate\" ASC");
    while($res_cartax=pg_fetch_array($qry_cartax)){
        $IDCarTax = $res_cartax["IDCarTax"];
        $TaxDueDate = $res_cartax["TaxDueDate"];
        $remark = substr($res_cartax["remark"],0,50);
        $CusAmt = $res_cartax["CusAmt"];
        $TypeDep = $res_cartax["TypeDep"];
        
        $main_TDName = "";
        $qry_typepay1=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
        if($res_typepay1=pg_fetch_array($qry_typepay1)){
            $main_TDName = $res_typepay1["TName"];
        }
        
        $qry_detail=pg_query("select * from carregis.\"DetailCarTax\" where \"IDCarTax\"='$IDCarTax' ORDER BY \"IDDetail\" ASC");
        $numrow_detail = pg_num_rows($qry_detail);
              
		echo "<tr><td colspan=\"10\" bgcolor=\"#CAE4FF\"><a href=\"#\"><img src=\"z-add.png\" border=\"0\" width=\"16\" height=\"16\" title=\"Add\" onclick=\"showadd('$IDCarTax');\"></a>
		| $IDCarTax | $full_name | $main_TDName</td></tr>";

		$sumbill=0;
		$sumnobill=0;
		$sumallbill=0;
        while($res_detail=pg_fetch_array($qry_detail)){
            $IDDetail = $res_detail["IDDetail"];
            $CoPayDate = $res_detail["CoPayDate"];
            $TaxValue = $res_detail["TaxValue"];
            $TypePay = $res_detail["TypePay"];
            $BillNumber = $res_detail["BillNumber"];
			$sumallbill=$sumallbill+$TaxValue;
            
            $TDName = "";
            $qry_typepay=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
            if($res_typepay=pg_fetch_array($qry_typepay)){
                $TDName = $res_typepay["TName"];
            }
			?>
			<tr style="background:#F0F0F0; vertical-align: top;">
			<?php
			if($IDCarTax != $old_IDCarTax){
				$old_IDCarTax = $IDCarTax;
			?>
				<td><?php echo "$IDNO"; ?></td>
				<td><?php echo "$remark"; ?></td>
				<?php
				}else{
					$old_IDCarTax = $IDCarTax;
				?>
					<td></td>
					<td></td>
				<?php
				}
				?>
				<td><?php echo "$CoPayDate"; ?></td>
				<td><?php if($tc==1 && $numrow_ad==1){?> <IMG SRC="../images/icon_menu/P22.gif" style="cursor:pointer" WIDTH="15" title="ลบข้อมูล" HEIGHT="15" BORDER="0" onclick="javascript:if(confirm('ยืนยันการลบข้อมูล?')){delBill('<?php echo $IDDetail; ?>');}"><?php } echo "$TDName"; ?></td>
				<td>
					<div align="center" id="bill<?php echo $IDDetail; ?>" name="bill<?php echo $IDDetail; ?>">
					<?php
					if(empty($BillNumber)){
						echo "<a  onclick=\"editbill('$IDDetail');\" title=\"แก้ไขได้เมื่อระดับ <= 10 โดยการ Click\"><b>...</b></a>";
					}else{
						echo "<a  onclick=\"editbill('$IDDetail');\" title=\"แก้ไขได้เมื่อระดับ <= 10 โดยการ Click\"><b>$BillNumber</b></a>";
					}
					?>
					</div>
				</td>

				<?php
				if(!empty($BillNumber)){
					$sumbill=$sumbill+$TaxValue;
					?>
					<td align="right"><div id="notnobill<?php echo $IDDetail; ?>" name="notnobill<?php echo $IDDetail; ?>"><a onclick="editnotnobill('<?php echo $IDDetail; ?>');" title="แก้ไขได้เมื่อระดับ <= 10 โดยการ Click"><b><?php echo number_format($TaxValue,2); ?></b></a></div></td>
					<td align="center"><div id="nobill<?php echo $IDDetail; ?>" name="nobill<?php echo $IDDetail; ?>"><a onclick="editnobill('<?php echo $IDDetail; ?>');" title="แก้ไขได้เมื่อระดับ <= 10 โดยการ Click"><b>...</b></a></div></td>
					<?php
				}else{
					$sumnobill=$sumnobill+$TaxValue;
					?>
					<td align="center"><div id="nobill<?php echo $IDDetail; ?>" name="nobill<?php echo $IDDetail; ?>"><a onclick="editnobill('<?php echo $IDDetail; ?>');" title="แก้ไขได้เมื่อระดับ <= 10 โดยการ Click"><b>...</b></a></div></td>
					<td align="right"><div id="notnobill<?php echo $IDDetail; ?>" name="notnobill<?php echo $IDDetail; ?>"><a onclick="editnotnobill('<?php echo $IDDetail; ?>');" title="แก้ไขได้เมื่อระดับ <= 10 โดยการ Click"><b><?php echo number_format($TaxValue,2); ?></b></a></div></td>
					<?php
				}
				?>
				<td align="right"><b><?php echo number_format($TaxValue,2); ?></b></td>
			</tr>
		<?php
        }
		echo "<tr align=right style=\"font-weight:bold\" bgcolor=\"#FFCCCC\"><td colspan=5>รวม</td><td>".number_format($sumbill,2)."</td><td>".number_format($sumnobill,2)."</td><td>".number_format($sumallbill,2)."</td></tr>";
    }
}
?>
</table><!--<div id ="show_err"></div>-->