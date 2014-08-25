<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="jquery-ui/js/jquery-1.10.2.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script> 
<link rel="stylesheet" type="text/css" href="jquery-ui/css/flick/jquery-ui-1.10.4.custom.css" /> 
 <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<title>Export excel data</title>
<style>
 body{
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
.ui-autocomplete {
		max-height: 400px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
	}
	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 400px;
	}



tr.border_bottom td {
  border-bottom:1pt solid #999;
}


.table-disable-hover.table tbody tr:hover td,
.table-disable-hover.table tbody tr:hover th {
    background-color: inherit;
}

.working{background:url('images/gif-loading.gif') no-repeat right center; background-size:16px 16px;}
	  
</style>

</head>
<body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
       <div class="container">
          <h2>Export excel data</h2>
       </div>
    </div>
  </div>
  
		<div class="container">
    	<div class="row">
        	<div class="span12"> 
			
           <!-- <button class="btn" onclick="window.location.href='select_csv.php?conn=av'">Av leasing</button> -->
           <button class="btn" onclick="window.location.href='select_csv.php?conn=tha'">Download CSV</button>
                        
     </div>
    	 </div>
		</div>
  
 </body>
</html>
