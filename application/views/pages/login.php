<?php
/*
 * Created on 31/01/2014
 *
 * Login form
 */
?>
<div id="wrapper">
	<h1>Inicio de Sesi&oacute;n</h1>
	<form method="POST" action="<?php echo base_url("login/"); ?>" role="form">
		<div class="form-group">
			<label for="rut">R.U.T</label>
			<input type="text" class="form-control" id="rut" name="rut" placeholder="Ingrese su RUT">
		</div>
		<div class="form-group">
			<label for="clave">Clave</label>
			<input type="password" class="form-control" id="clave" name="clave" placeholder="Clave">
		</div>
		<div class="form-group final1">
			
		</div>
		<div class="form-group final2">
			<a href="<?php echo base_url("recuperarclave/"); ?>">Recuperar contrase&ntilde;a</a> |
			<a href="<?php echo base_url("registro/"); ?>">Registrarse</a>
			&nbsp;&nbsp;
			<button type="submit" class="btn btn-default">
				Continuar
			</button>
			<input type="hidden" name="do_login" value="true">
		</div>
		<div class="divclear">&nbsp;</div>
	</form>
</div>