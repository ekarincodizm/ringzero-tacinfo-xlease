<?php 
include("../config/config.php"); 
$select_com = pg_escape_string($_GET['com']);
$type = pg_escape_string($_GET['type']);

if($type == "getdate"){
?>
<b>เลือกวันที่</b> 
<SELECT NAME="date" id="date">
    <option value="">เลือก</option>
<?php
$qry=pg_query("SELECT \"CQDate\" FROM insure.\"VListUnForcePayBy\" WHERE \"Company\"='$select_com' GROUP BY \"CQDate\" ORDER BY \"CQDate\" ASC ");
while($res=pg_fetch_array($qry)){
    $CQDate = $res["CQDate"];
?>
    <option value="<?php echo "$CQDate"; ?>"><?php echo "$CQDate"; ?></option>        
<?php        
}
?>
</SELECT>

<?php
}
?>