<!DOCTYPE html>
<html>
  <head>

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title><?php echo (isset($title) ? $title : "")?></title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo base_url('libs/jquery/jquery-ui.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('libs/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('libs/datepicker/bootstrap-datepicker3.min.css')?>">

    <script src="<?php echo base_url('libs/jquery/jquery-2.1.4.js')?>"></script>
    <script src="<?php echo base_url('libs/jquery/jquery-ui.min.js')?>"></script>
    <script src="<?php echo base_url('libs/bootstrap/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('libs/datepicker/bootstrap-datepicker.min.js')?>"></script>
    <script src="<?php echo base_url('libs/datepicker/bootstrap-datepicker.es.min.js')?>"></script>

    <script>
      var base_url = "<?php echo base_url('index.php')?>";
      var root_dir = "<?php echo base_url()?>"
    </script>

    <style>
      body {
        font-family: 'Roboto', sans-serif;
        font-weight: 300;
        background-color: #f1f1f1; //#f5f8fa
      }

      .cabecera th{
          background-color: #454545;
          color:white;
          font-size: 16px;
          font-weight: 400;
      }
    </style>
  </head>
  <body>
