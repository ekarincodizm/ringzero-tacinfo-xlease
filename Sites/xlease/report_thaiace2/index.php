<?php
include('include/config.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $project['name']; ?></title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    BODY{
        font-size: 13px;
    }
    </style>
  </head>
  <body>

    <div class="container">
        <div class="page-header">
            <h2><?php echo $project['name']; ?></h2>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm" id="btn_show">แสดงข้อมูล</button>
            <a href="report_excel.php"  target="_blank" class="btn btn-primary btn-sm" role="button">Export Excel</a>
        </div>
        <div id="div_show" style="margin-top:15px; margin-bottom:15px"></div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="bootstrap/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script>
    $('#btn_show').click(function(){
        $('#btn_show').attr('disabled',true);
        $('#div_show').empty();
        $('#div_show').html('Loading...');
        $('#div_show').load('api.php?cmd=div_show',function(){
            $('#btn_show').attr('disabled',false);
            //console.log('Load Success.');
        });
    });
    </script>

  </body>
</html>