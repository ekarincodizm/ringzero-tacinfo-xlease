<?php 
include("../config/config.php");
?>
<html>
<head>
<title> working with jscript</title>
<script type="text/javascript">
function hide()
{ 

 if(document.getElementById('typepayment').value==1)
 {
   document.getElementById('ccount').style.visibility = 'visible'; 
   //alert("type=1");
 }
 else
 {
  document.getElementById('ccount').style.visibility = 'hidden'; 
  //alert("type other");
 }
 
} 
</script> 
</head>
<body>
<script type="text/javascript">

<!--
var gFiles = 0;
function addFile() {

var li = document.createElement('li');
li.setAttribute('id', 'file-' + gFiles);
li.innerHTML = '<select name="typepayment[]" id="typepayment"><?php 
$qry_type=pg_query("select * from \"TypePay\" ");
while($res_type=pg_fetch_array($qry_type))
{ 
echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
}
?></select>&nbsp;<input type="text" name="ccount[]" id="ccount" style="visibility:visible;">&nbsp;<input type="text" name="amt[]" id="amt[]"><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
document.getElementById('files-root').appendChild(li);
gFiles++;
}
function removeFile(aId) {
var obj = document.getElementById(aId);
obj.parentNode.removeChild(obj);
}
-->
</script>


<button onClick="addFile()">ADD</button>
<form method="post" action="process_transfer.php">
<ol id="files-root">
<li>
<select name="typepayment[]" id="typepayment">
<?php 
$qry_type=pg_query("select * from \"TypePay\" ");
while($res_type=pg_fetch_array($qry_type))
{
$opt_type="<option value=\"$res_type[TypeID]\">$res_type[TName]</option>";

echo $opt_type;

}
?>
</select>
<input type="text" name="ccount[]" id="ccount" style="visibility:visible;">&nbsp;<input type="text" name="amt[]" id="amt[]">
</ol>
<input type="submit" value="NEXT" >
</form>

</body>
</html>
