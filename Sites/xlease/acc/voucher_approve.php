<?php
include("../config/config.php");

$now_date = nowDate();//ดึง วันที่จาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
<!--

function selectAll(select)
{
    with (document.frm1)
    {
        var checkval = false;
        var i=0;
        var nub = 0;

        for (i=0; i< elements.length; i++){
            if (elements[i].type == 'radio' && !elements[i].disabled){
                if (elements[i].name.substring(0, select.length) == select){
                    if (elements[i].id == 'L'){
                        nub+=1;
                        elements[i].checked = true;
                    }
                }
            }
        }
        
        if(nub > 0){
            document.frm1.button2.disabled = false;
            document.frm1.button3.disabled = true;
        }else{
            document.frm1.button2.disabled = true;
            document.frm1.button3.disabled = false;
        }
        
    }
}

function selectAll2(select)
{
    with (document.frm1)
    {
        var checkval = false;
        var i=0;
        var aaaaa;
        var nub = 0;

        for (i=0; i< elements.length; i++){
            if (elements[i].type == 'radio' && !elements[i].disabled){
                if (elements[i].name.substring(0, select.length) == select){
                    if (elements[i].id == 'R'){
                        nub+=1;
                        elements[i].checked = true;
                    }
                }
            }
        }
        
        if(nub > 0){
            document.frm1.button2.disabled = true;
            document.frm1.button3.disabled = false;
        }else{
            document.frm1.button2.disabled = false;
            document.frm1.button3.disabled = true;
        }
        
    }
}

function selectDisable1(select){
    document.frm1.button2.disabled = false;
}
function selectDisable2(select){
    document.frm1.button3.disabled = false;
}
/*
function selectDisable(field){
    var temp=0;
    for (i = 0; i < field.length; i++){
        if( field[i].checked == true ){
            if( document.frm1.cid2[i].checked == true ){
                if( document.frm1.cid2[i].value == field[i].value ){
                    field[i].checked = false;
                }else{
                    temp = temp+1;
                }
            }else{
                temp = temp+1;
            }
        }
    }
    if(temp > 0){
        document.frm1.button2.disabled = false;
    }else{
        document.frm1.button2.disabled = true;
    }
}

function selectDisable2(field){
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ){
            if( document.frm1.cid[i].checked == true ){
                if( document.frm1.cid[i].value == field[i].value ){
                    field[i].checked = false;
                }else{
                    temp = temp+1;
                }
            }else{
                temp = temp+1;
            }
        }
    
    if(temp > 0){
        document.frm1.button3.disabled = false;
    }else{
        document.frm1.button3.disabled = true;
    }
}
*/
// -->
</script>

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:12px;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
</style>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" value="รออนุมัติ" class="ui-button" disabled /><input name="button" type="button" class="ui-button" onclick="window.location='voucher_approve_list.php'" value="อนุมัติแล้ว" /></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Approve Voucher</B></legend>

<div style="margin:10px;">

<div class="ui-widget">

<form name="frm1" id="frm1" action="voucher_approve_update.php" method="post">

<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr align="center" bgcolor="#42A0FF" style="font-weight:bold; line-height:15px">
    <td width="100">Voucher ID</td>
    <td>วันที่</td>
    <td>รายละเอียด</td>
    <td>เงินสด</td>
    <td>เลขที่เช็ค</td>
    <td>ยอดเงินในเช็ค</td>
    <td>รวม</td>
    <td>ผู้เบิก</td>
    <td width="80">อนุมัติ<br /><input type="radio" name="radiochk" onclick="javascript:selectAll('select');"></td>
    <td width="80">ยกเลิก<br /><input type="radio" name="radiochk" onclick="javascript:selectAll2('select');"></td>
</tr>
<?php
$nub = 0;
$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"qpprove_id\" is null AND cancel ='false' ORDER BY \"vc_id\" ASC ");
$num = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $sum = 0;
    $vc_id = $res_name["vc_id"];
    $print_date = $res_name["print_date"];
    $vc_detail = $res_name["vc_detail"]; $arr_detail = explode("\n",$vc_detail); $vc_detail = $arr_detail[0];
    $cash_amt = $res_name["cash_amt"];
    $cq_id = $res_name["cq_id"];
    $cq_amt = $res_name["cq_amt"];
    $maker_id = $res_name["maker_id"];
    $acb_id = $res_name["acb_id"];
    $sum = $cash_amt+$cq_amt;

    if(substr($acb_id,0,1) != "G"){
        continue;
    }
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\" style=\"line-height:15px\">";
    }else{
        echo "<tr class=\"even\" style=\"line-height:15px\">";
    }
?>
    <td align="center"><?php echo "$vc_id"; ?></td>
    <td align="center"><?php echo "$print_date"; ?></td>
    <td><?php echo "$vc_detail"; ?></td>
    <td align="right"><?php echo number_format($cash_amt,2); ?></td>
    <td align="center"><?php echo "$cq_id"; ?></td>
    <td align="right"><?php echo number_format($cq_amt,2); ?></td>
    <td align="right" style="font-weight:bold; color:red"><?php echo number_format($sum,2); ?></td>
    <td align="center"><?php echo "$maker_id"; ?></td>
    <!--
    <td align="center"><input type="checkbox" id="<?php echo "selectone$nub"; ?>" name="<?php echo "selectone$nub"; ?>" value="<?php echo "$vc_id"; ?>" onclick="selectDisable(document.frm1.cid);"></td>
    <td align="center"><input type="checkbox" id="<?php echo "selecttwo$nub"; ?>" name="<?php echo "selecttwo$nub"; ?>" onclick="selectDisable2(document.frm1.cid2);"></td>-->
    
<td align="center"><input type="radio" id="L" name="<?php echo "select[$nub]"; ?>" value="<?php echo "L#$vc_id"; ?>" onclick="selectDisable1('select');"></td>
<td align="center"><input type="radio" id="R" name="<?php echo "select[$nub]"; ?>" value="<?php echo "R#$vc_id"; ?>" onclick="selectDisable2('select');"></td>
</tr>

<?php
    $nub += 1;
}

if($num>0){
?>
<tr>
    <td colspan="8"></td>
    <td align="center"><input name="button2" id="button2" type="submit" class="ui-button" value="อนุมัติ" disabled></td>
    <td align="center"><input name="button3" id="button3" type="submit" class="ui-button" value="ยกเลิก" disabled></td>
</tr>
<?php
}else{
?>
<tr>
    <td colspan="10" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>
</table>

</form>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>