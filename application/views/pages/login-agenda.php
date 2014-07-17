<?php
/*
 * Created on 31/01/2014
 *
 * Login form
 */
?>
<div id="wrapper">
	<h1>Primero debe iniciar sesi&oacute;n</h1>
	<?php echo form_open('login/do_login', array('id' => 'login-form')); ?>
		<input type="hidden" name="redirec" value="agenda/confirmacion" />
		<div class="form-group">
			<label for="rut">R.U.T</label>
			<input type="text" class="form-control validate[required,rut]" id="Rut_PacienteTitular" name="Rut_PacienteTitular" placeholder="Ej: 12345678-K">
		</div>
		<div class="form-group">
			<label for="clave">Clave</label>
			<input type="password" class="form-control validate[required]" id="Clave_Paciente" name="Clave_Paciente" placeholder="Clave">
		</div>
		<div class="form-group final1">
			
		</div>
		<div class="form-group final2">
			<a href="<?php echo site_url("recuperarclave"); ?>">Recuperar contrase&ntilde;a</a> |
			<a href="<?php echo site_url("registro"); ?>">Registrarse</a>
			&nbsp;&nbsp;
			<button type="button" class="btn btn-default" id="session-start">
				Continuar
			</button>
			<input type="hidden" name="do_login" value="true">
		</div>
		<div class="divclear">&nbsp;</div>			
	</form>

	<div class="enviando">
		<p class="uno">Iniciando sesión...</p>
		<p class="dos">Inicio de sesión exitoso! </p>
		<p class="error">Fallo el inicio de sesión, Intentelo mas tarde.</p>
	</div>
	
</div>
