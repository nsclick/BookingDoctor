<!-- Fixed navbar -->
<?php
		$session_user = get_session_user();
		//debug_var($session_user);
		
		if(isset($session_user['userName'])){
			//Loggued In users menu
			$menu = array(
				'home' => 'Reserva de Horas',
				'consulta' => 'Consulta de horas',
				'registro/modificardatos/' => 'Modificar datos',
			);
		} else {
			//Not Loggued in users Menu
			$menu = array(
				'home' => 'Reserva de Horas',
				'login' => 'Ingreso de usuario',
				'registro/' => 'Registro'
			);
		}
		
		$segment = $this->uri->segment(1);
		$segment = $segment ? $segment : 'home';
		$segment = ($segment == 'buscarmedico') ? 'home' : $segment;
		$segment = ($segment == 'agenda') ? 'home' : $segment;
		
		//debug_var($segment);
?>    
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="navbar-brand"> Men&uacute; Reservas</span>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
			<?php foreach($menu as $controller => $label): ?>
			<?php
				$pos = strpos($controller, $segment);
				$active = '';
				if ($pos !== false) {
					$active = 'class="active"';
				}
			?>
			<li><a <?php echo $active ?> href="<?php echo site_url($controller);?>"><?php echo $label; ?></a></li>
			<?php endforeach; ?>

            <?php if(isset($session_user['userName'])): ?>
				<li class="navbar-text navbar-right sesion">Hola 
	  			<span><?php echo $session_user['userName'] ?></span>
				<a href="<?php echo site_url("login/logout");?>">Cerrar Sesión</a></li>
	  		<?php endif; ?>
			
          </ul>
        </div><!--/.nav-collapse -->
        
      </div>
