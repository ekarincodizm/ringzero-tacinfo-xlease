<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<input name="show_arti_topic" type="text" id="show_arti_topic" size="50" />
<input name="h_arti_id" type="text" id="h_arti_id" value="" readonly />
</form>
<script type="text/javascript">
function make_autocom(autoObj,showObj){
var mkAutoObj=autoObj;
var mkSerValObj=showObj;
new Autocomplete(mkAutoObj, function() {
this.setValue = function(id) {     
document.getElementById(mkSerValObj).value = id;
}
if ( this.isModified )
this.setValue("");
if ( this.value.length < 1 && this.isNotClick )
return ;   
return "memberSearchAutoComplete.php?q=" +encodeURIComponent(this.value);
});
}  
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("show_arti_topic","h_arti_id");
</script>
</body>
</html>