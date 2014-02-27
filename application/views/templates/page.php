<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="shortcut icon" href="../../assets/ico/favicon.ico"> -->

    <title><?php echo $title; ?></title>
	
	<link href="<?=base_url()?>assets/css/normalize.css" rel="stylesheet">
	
    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>assets/third_party/bootstrap/css/bootstrap.css" rel="stylesheet">
	
	<!-- Page added CSS -->
    <?php echo $_styles ?>

    <script src="<?=base_url()?>assets/js/jquery-2.1.0.min.js"></script>
	<script src="<?=base_url()?>assets/third_party/bootstrap/js/bootstrap.min.js"></script>
	
	<!-- Page added JS -->
	<?php echo $_scripts ?>
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

        
  </head>

  <body role="document">
	
	<div class="navbar navbar-default" role="navigation">
	<?php echo $header; ?>
	</div>
	
	<div class="container theme-showcase" role="main">
	<?php echo $content; ?>
	</div>
	
	<?php echo $footer; ?>
    
  </body>
</html>
