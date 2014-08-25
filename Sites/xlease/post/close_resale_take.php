<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>
    
</head>
<body id="mm">

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ปิดสัญญารถยึด/ขายคืน</B></legend>


<div class="ui-widget" align="center">

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>ทะเบียน</td>
    <td>IDNO</td>
    <td>ชื่อ</td>
    <td>วันทำสัญญา</td>
    <td>&nbsp;</td>
</tr>

<?php
$query_fp=pg_query("SELECT * FROM \"Fp\" WHERE \"P_CLDATE\" is null AND \"P_ACCLOSE\"='FALSE' ORDER BY \"asset_id\" ASC");
while($res_fp=pg_fetch_array($query_fp)){
    $n++;
    $IDNO = $res_fp['IDNO'];
    $P_STDATE = $res_fp['P_STDATE'];
    $asset_id = $res_fp['asset_id'];
    
    if($n == 1){
        $query_vcon=pg_query("SELECT * FROM \"VContact\" WHERE \"IDNO\"='$IDNO'");
        if($res_vcon=pg_fetch_array($query_vcon)){
            $full_name = $res_vcon['full_name'];
            $asset_type = $res_vcon['asset_type'];
            $C_REGIS = $res_vcon['C_REGIS'];
            $car_regis = $res_vcon['car_regis'];
            if($asset_type == 1){
                $regis = $C_REGIS;
            }else{
                $regis = $car_regis;
            }
        }
    }
    
    if($old_asset_id == $asset_id){
    
    $query_vcon=pg_query("SELECT * FROM \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_vcon=pg_fetch_array($query_vcon)){
        $full_name = $res_vcon['full_name'];
        $asset_type = $res_vcon['asset_type'];
        $C_REGIS = $res_vcon['C_REGIS'];
        $car_regis = $res_vcon['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }

    $i+=1;
    if($i%2==0){
        $color = "even";
    }else{
        $color = "odd";
    }
?>
<tr class="<?php echo $color; ?>" align="left">
    <td><?php echo "$regis"; ?></td>
    <td align="center"><?php echo "$old_IDNO"; ?></td>
    <td><?php echo "$old_full_name"; ?></td>
    <td align="center"><?php echo "$old_P_STDATE"; ?></td>
    <td align="center"><input type="button" name="btn1" id="btn1" value="ทำรายการนี้" onclick="javascript:popU('close_resale_take_detail.php?oid=<?php echo $old_IDNO; ?>&nid=<?php echo $IDNO; ?>&stdate=<?php echo $P_STDATE; ?>','<?php echo "$old_IDNO-$IDNO-$P_STDATE"; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=500,height=300');"></td>
</tr>
<tr class="<?php echo $color; ?>" align="left">
    <td><?php echo "$regis"; ?></td>
    <td align="center"><?php echo "$IDNO"; ?></td>
    <td><?php echo "$full_name"; ?></td>
    <td align="center"><?php echo "$P_STDATE"; ?></td>
    <td></td>
</tr>
<?php
    }

    $old_regis = $regis;
    $old_IDNO = $IDNO;
    $old_full_name = $full_name;
    $old_P_STDATE = $P_STDATE;
    $old_asset_id = $asset_id;

}
?>

</table>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>