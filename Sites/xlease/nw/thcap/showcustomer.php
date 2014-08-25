<?php
session_start();
include("../../config/config.php");

$contractID = $_REQUEST['contractID'];

echo "<table width=\"100%\" border=\"0\"  align=\"left\">";
$qryspecial2=pg_query("SELECT thcap_fullname, \"relation\" FROM \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' order by \"CusState\"");
$numspec=pg_num_rows($qryspecial2);

$i=0;
while($resspec2=pg_fetch_array($qryspecial2)){
list($name,$relation)=$resspec2;		
	?>
 <tr>
	<td>
	<input type="radio" name="name" id="cusname<?php echo $i?>" value="<?php echo $name;?>" <?php if($i==0){ echo "checked";}?>> <?php echo "$name ($relation)"; ?>
	</td>
	</tr>
<?php	
$i++;
}
echo "<tr><td><input type=\"hidden\" name=\"numcontract\" id=\"numcontract\" value=\"$numspec\"><input type=\"radio\" name=\"name\" value=\"999\" id=\"each\">อื่นๆ <input type=\"text\" name=\"cuseach\" id=\"cuseach\" size=\"50\" disabled=\"true\"></td></tr>";
echo "</table>";
?>
<script>
$(document).ready(function(){	
	$("#each").click(function(){ 
		if($('#each') .attr( 'checked')==true){
			document.getElementById("cuseach").disabled=false;
			document.getElementById("cuseach").focus();
		}else{
			document.getElementById("cuseach").disabled=true;
		}
	});
});
</script>