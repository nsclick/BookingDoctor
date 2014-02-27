<?php
/*
 * Created on 31/01/2014
 *
 * Login form
 */
?>
	
	<h1>Autenticación de usuario</h1>

	<form method="POST" action="<?php echo base_url("login/");?>" role="form">
	  <div class="form-group">
	    <label for="rut">R.U.T</label>
	    <input type="text" class="form-control" id="rut" name="rut" placeholder="Ingrese su RUT">
	  </div>
	  <div class="form-group">
	    <label for="clave">Clave</label>
	    <input type="password" class="form-control" id="clave" name="clave" placeholder="Clave">
	  </div>
	  <button type="submit" class="btn btn-default">Continuar</button> <a href="<?php echo base_url("recuperarclave/");?>">¿Olvidaste tu clave?</a>
	  <input type="hidden" name="do_login" value="true">
	</form>

	
	<br>
	<div class="container-fluid">		  
		  <div class="row">
		  	<div class="col-md-12">Si no estás registrado o aún no has establecido tu clave, ingresa aquí: <a href="<?php echo base_url("registro/");?>">Registro</a></div>
		  </div>	
		  
	</div>
      
      
      