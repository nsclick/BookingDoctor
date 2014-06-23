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
				'login/logout' => 'Cerrar Sesión'
			);
		} else {
			//Not Loggued in users Menu
			$menu = array(
				'home' => 'Reserva de Horas',
				'login' => 'Ingreso de usuario',
				'registro/' => 'Registro'
			);
		}
		
		
		
?>    
	  <?php if(isset($session_user['userName'])): ?>
	  <div><?php echo $session_user['userName'] ?></div>
	  <?php endif; ?>
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
			<li class="activ"><a href="<?php echo site_url($controller);?>"><?php echo $label; ?></a></li>
			<?php endforeach; ?>
			
            <!-- <li><a href="<?php echo site_url("consulta/");?>">Consulta de horas</a></li>
            <li><a href="<?php echo site_url("registro/adicionafamilia/");?>">Adicionar familiar</a></li>
            <li><a href="<?php echo site_url("registro/modificardatos/");?>">Modificar datos</a></li>
            <li><a href="<?php echo site_url("registro/");?>">Registro</a></li>
            <li><a href="<?php echo site_url("login/logout");?>">Cerrar Sesión</a></li>
			-->
          </ul>
        </div><!--/.nav-collapse -->
        
      </div>
     
