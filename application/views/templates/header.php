<!-- Fixed navbar -->
<?php
		$session_user = get_session_user();
		//debug_var($session_user);
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
          	<li><a href="<?php echo site_url("home");?>">Reserva de Horas</a></li>
            <li><a href="<?php echo site_url("consulta/");?>">Consulta de horas</a></li>
            <?php if(is_array(isset($session_user['userName']))): ?>
            <li><a href="<?php echo site_url("adicionafamilia/");?>">Adicionar familiar</a></li>
            <?php endif; ?>
            <li><a href="<?php echo site_url("modificardatos/");?>">Modificar datos</a></li>
            <li><a href="<?php echo site_url("registro/");?>">Registro</a></li>
            
          </ul>
        </div><!--/.nav-collapse -->
        
      </div>
     
