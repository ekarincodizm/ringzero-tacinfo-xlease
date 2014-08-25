<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title>Search</title>

<style type="text/css">
body {
    font-family: tahoma;
    font-size: 11px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 5px;
    padding-bottom: 5px;
}
H1{
    font-size: 16px;
    color: #585858;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
H2{
    font-size: 22px;
    color: #888800;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}

.wrapper{
	width:700; border: solid 0px;
}

.menu{
	margin:3px; text-align:center;
}

a:link, a:visited, a:hover {
    color: #585858;
    text-decoration: none;
}
a:hover {
    color: #ACACAC;
    text-decoration: none;
}

/* ====================== */
.roundedcornr_box {
   background: #ffffff;
   width: 700px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(img/roundedcornr_br.png) no-repeat bottom right;
}

.roundedcornr_top div, .roundedcornr_top, 
.roundedcornr_bottom div, .roundedcornr_bottom {
   width: 100%;
   height: 15px;
   font-size: 1px;
}
.roundedcornr_content {
    margin: 0 15px;
}
</style>

<link type="text/css" href="jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>


<script type="text/javascript">
$(document).ready(function(){
    $('#search').keyup(function(){
        $("#panel").empty();
        if($('#search').val() != ""){
            $('#panel').text('กำลังค้นหา กรุณารอสักครู่...');
            $("#panel").load("main_search.php?key="+ $("#search").val());
        }
    });
});
</script>

</head>

<body>

<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<h2>Search</h2>
<hr/>
<div class="wrapper">

<div class="ui-widget">
    <p><label for="birds"><b>ค้นหา ชื่อ,สกุล</b></label>
    <input id="search" name="search" size="80" /></p>
    <div id="panel"></div>
</div>

</div>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>
</html>