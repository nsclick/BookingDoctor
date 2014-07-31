<?php
		$session_user = get_session_user();
		//debug_var($session_user);
?> 
<!DOCTYPE html>
<html lang="es">
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

    <script src="<?php echo base_url()?>assets/js/jquery-2.1.0.min.js"></script>
	<script src="<?php echo base_url()?>assets/third_party/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url()?>assets/js/common.js"></script>
	
	<!-- Page added JS -->
	<?php echo $_scripts ?>
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<script type="text/javascript">
	
		var base_url = '<?php echo base_url(); ?>';
		var site_url = '<?php echo site_url(); ?>';
	</script>
	<!-- Davila CSS -->
	<link href="<?=base_url()?>assets/css/davila.css" rel="stylesheet">

	<!-- Google Analytics -->
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	ga('create', '<?php echo $this->config->item('GA-ID') ?>', 'auto');
	ga('send', 'pageview');

	</script>
	<!-- End Google Analytics -->
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
