<?php
include("../config/config.php");
?>
<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#E0E0E0">
<tr bgcolor="#80BFFF" style="font-weight:bold; text-align:center">
    <td>ID</td>
    <td>IDNO</td>
    <td>ชื่อ/สกุล</td>
    <td>ทะเบียน</td>
    <td>วันที่</td>
    <td>ผู้ขออนุมัติ</td>
    <td></td>
</tr>

<?php
$j = 0;
$qry = pg_query("SELECT * FROM insure.batch WHERE \"type\"='N' AND \"approve_id\" Is Null ORDER BY \"id\" ASC ");
while($res = pg_fetch_array($qry)){
    $j++;
    $id = $res['id'];
    $do_date = $res['do_date'];
    $marker_id = $res['marker_id'];
    
    if( substr($id,0,1) == "F" ){
        $qry_idno = pg_query("SELECT \"IDNO\" FROM insure.\"InsureForce\" WHERE \"InsFIDNO\"='$id' ");
        if($res_idno = pg_fetch_array($qry_idno)){
            $IDNO = $res_idno['IDNO'];
        }
    }elseif( substr($id,0,1) == "U" ){
        $qry_idno = pg_query("SELECT \"IDNO\" FROM insure.\"InsureUnforce\" WHERE \"InsUFIDNO\"='$id' ");
        if($res_idno = pg_fetch_array($qry_idno)){
            $IDNO = $res_idno['IDNO'];
        }
    }elseif( substr($id,0,1) == "L" ){
        $qry_idno = pg_query("SELECT \"IDNO\" FROM insure.\"InsureLive\" WHERE \"InsLIDNO\"='$id' ");
        if($res_idno = pg_fetch_array($qry_idno)){
            $IDNO = $res_idno['IDNO'];
        }
    }
    
    $qry_ct = pg_query("SELECT \"full_name\",\"C_REGIS\" FROM \"UNContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_ct = pg_fetch_array($qry_ct)){
        $full_name = $res_ct['full_name'];
        $C_REGIS = $res_ct['C_REGIS'];
    }
    
    
    $qry_name = pg_query("SELECT \"fullname\" FROM \"Vfuser\" WHERE \"id_user\"='$marker_id' ");
    if($res_name = pg_fetch_array($qry_name)){
        $fullname = $res_name['fullname'];
    }
?>
<tr bgcolor="#FFFFFF">
    <td><?php echo $id; ?></td>
    <td><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $C_REGIS; ?></td>
    <td align="center"><?php echo $do_date; ?></td>
    <td><?php echo $fullname; ?></td>
    <td align="center"><a href="javascript:showdetail('<?php echo $id; ?>');" title="แสดงรายการ <?php echo $id; ?>"><u>แสดงรายการนี้</u></a></td>
</tr>
<?php
}

if($j == 0){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>

<script type="text/javascript">
function showdetail(id){
    $('body').append('<div id="dialogdetail"></div>');
    $('#dialogdetail').load('approve_edit_detail.php?id='+id);
    $('#dialogdetail').dialog({
        title: 'ยืนยันการแก้ไข ' +id,
        resizable: false,
        modal: true,  
        width: 700,
        height: 500,
        close: function(ev, ui){
            $('#dialogdetail').remove();
        }
    });
}
</script>