<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="content-language" content="en">
<meta name="author" content="">
<meta http-equiv="Reply-to" content="@.com">
<meta name="generator" content="PhpED 5.2">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="creation-date" content="09/20/2007">
<meta name="revisit-after" content="15 days">

<title>Print receipt</title>

<script type="text/javascript" src="../js/jquery.js"></script> 

</head>

<body>

  <form id="print_receipt" method="post" action="link_recprint.php">
    <input type="hidden" name="idno_names" value="<?php echo pg_escape_string($_GET['receipt_no']); ?>" />
	
  </form>

  <script>
    $(document).ready(function () 
    {
      $("form:first").submit();
    });
  </script>
</body>

</html>
