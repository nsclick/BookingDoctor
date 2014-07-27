<div id="wrapper">
<h1>Registro</h1>

<!-- cada tres caracteres es un dia.

000 no hay horas

5A1 - - dia lleno

cualquier otra cosa disponible

fecha de inicio inicio de la agenda desde donde donde inicias 

-->

<p>Para acceder al servicio de <b>Reserva de Horas</b> y otros servicios, debe registrarse completando el siguiente formulario:</p>
 

<div class="registrotabs">
	<h2 class="tab1 taba tabactive">Datos <br /> Personales</h2>
	<h2 class="tab1 tabb">Datos <br /> Contacto</h2>
	<h2 class="tab1 tabc">Datos <br /> Seguridad</h2>
	<div class="divclear">&nbsp;</div>

	<!-- Block 1 -->
	<?php echo form_open('registro1', array('id' => 'registro1', 'class' => 'tabactive')); ?>
	<div class="tabspace space1">
		<div class="form-group">
	    	<label for="rut">RUT*</label>
	    	<input type="text" class="form-control validate[required,rut]" id="Rut_Paciente" name="Rut_Paciente" placeholder="Ej: 12345678-K" value="<?php echo set_value('Rut_Paciente'); ?>"> 
	  	</div> 
	  	<div class="form-group">
	    	<label for="Fechanac_Paciente">Fecha Nacimiento*</label>
			<div class='input-group date'>
	    		<input class="form-control validate[required] text-input datepicker" id="Fechanac_Paciente" name="Fechanac_Paciente" data-format="DD/MM/YYYY" placeholder="DD/MM/YYYY" value="<?php echo set_value('Fechanac_Paciente'); ?>" style="border-right:none"/>
	        	<span class="input-group-addon" style="border-left:none;background:#fff"><span class="glyphicon glyphicon-calendar"></span></span>
	    	</div>    
	  	</div>  
	  	<div class="form-group">
	    	<label for="Nombre_Paciente">Nombres*</label>
	    	<input type="text" class="form-control validate[required]" id="Nombre_Paciente" name="Nombre_Paciente" placeholder="Ingrese su nombre" value="<?php echo set_value('Nombre_Paciente'); ?>">
	  	</div>  
	  	<div class="form-group">
	    	<label for="Apepat_Paciente">Primer Apellido*</label>
	    	<input type="text" class="form-control validate[required]" id="Apepat_Paciente" name="Apepat_Paciente" placeholder="Ingrese su primer apellido" value="<?php echo set_value('Apepat_Paciente'); ?>" >
	  	</div>  
	  	<div class="form-group">
	    	<label for="Apemat_Paciente">Segundo Apellido</label>
	    	<input type="text" class="form-control" id="Apemat_Paciente" name="Apemat_Paciente" placeholder="Ingrese su segundo apellido" value="<?php echo set_value('Apemat_Paciente'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="Direccion_Paciente">Direcci&oacute;n</label>
	    	<input type="text" class="form-control validate[required]" id="Direccion_Paciente" name="Direccion_Paciente" placeholder="Ingrese su dirección" value="<?php echo set_value('Direccion_Paciente'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="Comuna_Paciente">Comuna</label>
	    	<input type="hidden" id="Comuna_Paciente" name="Comuna_Paciente">
	    	<input type="text" class="form-control validate[required]" id="Comuna_Paciente-label" name="Comuna_Paciente-label" placeholder="Ingrese su comuna" value="<?php echo set_value('Direccion_Paciente'); ?>">
	  	</div> 
	  	<div class="form-group">
		    <label for="Ciudad_Paciente">Ciudad</label>
	    	<input type="text" class="form-control validate[required]" id="Ciudad_Paciente" name="Ciudad_Paciente" placeholder="Ingrese la cuidad" value="<?php echo set_value('Ciudad_Pacient'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label>Sexo</label><br>
			<label class="radio-inline">
		  		<input type="radio" class="validate[required]" id="Sexo_Paciente" name="Sexo_Paciente" value="M" <?php echo set_radio('Sexo_Paciente', 'M'); ?>> Masculino
			</label>
			<label class="radio-inline">
		  		<input type="radio" class="validate[required]" id="Sexo_Paciente" name="Sexo_Paciente" value="F" <?php echo set_radio('Sexo_Paciente', 'F'); ?>> Femenino
			</label>
	  	</div> 
	  	<div class="form-group">
	    	<label for="Prevision_Paciente">Previsi&oacute;n</label>
			<select id="Prevision_Paciente" name="Prevision_Paciente" class="form-control validate[required]">
				<option value="" <?php echo set_select('Prevision_Paciente', '', TRUE); ?>></option>
				<?php foreach($medical_services as $id => $name): ?>
				<option value="<?php echo $id ?>" <?php echo set_select('Prevision_Paciente', $id); ?> ><?php echo $name ?></option>
				<?php endforeach; ?>
			</select>
	  	</div>
	  	<div class="divclear">&nbsp;</div>
	  	<div class="btnspace">
	  		<button type="button" class="btn btn-primary sgte" id="sgte1">Siguiente</button>
	  	</div>
	</div>
	</form>
	<!-- End Block 1-->
	
	
	<!-- Block 2 -->
	<?php echo form_open('registro2', array('id' => 'registro2')); ?>
  	<div class="tabspace space2">
	  	<div class="form-group" style="width:23%">
	    	<label for="Fono1_Paciente">Tel&eacute;fono 1</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="prefijo_Fono1_Paciente" name="prefijo_Fono1_Paciente">
						<?php foreach($phone_prefixes as $prefix): ?>
						<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono1_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[required,custom[integer]]" id="Fono1_Paciente" name="Fono1_Paciente" placeholder="Ingrese los Digitos" value="<?php echo set_value('Fono1_Paciente'); ?>">
				</div>
		  	</div> 
	  	</div> 
	  	<div class="form-group" style="width:23%">
	    	<label for="Fono2_Paciente">Tel&eacute;fono 2</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="prefijo_Fono2_Paciente" name="prefijo_Fono2_Paciente">
						<?php foreach($phone_prefixes as $prefix): ?>
						<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono2_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[custom[integer]]" id="Fono2_Paciente" name="Fono2_Paciente" placeholder="Ingrese los Digitos" value="<?php echo set_value('Fono2_Paciente'); ?>">
				</div>
		  	</div> 
	  	</div>
	  	<div class="form-group" style="width:23%">
	    	<label for="FonoMovil1">Celular 1</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="PrefMovil1" name="PrefMovil1">
						<option value="9" selected="selected">9</option>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[required,custom[integer]]" id="FonoMovil1" name="FonoMovil1" placeholder="Ingrese los Digitos" value="<?php echo set_value('FonoMovil1'); ?>">
				</div>
		  	</div> 
	  	</div>
	  	<div class="form-group" style="width:23%">
	    	<label for="FonoMovil2">Celular 2</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="PrefMovil2" name="PrefMovil2">
						<option value="9" selected="selected">9</option>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[custom[integer]]" id="FonoMovil2" name="FonoMovil2" placeholder="Ingrese los Digitos" value="<?php echo set_value('FonoMovil2'); ?>">
				</div>
		  	</div> 
	  	</div>
	  	<div class="form-group">
	    	<label for="Email_Paciente">Email</label>
	    	<input type="text" class="form-control validate[required,custom[email]]" id="Email_Paciente" name="Email_Paciente" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="Email_Paciente-confirma">Confirmar Email</label>
	    	<input type="text" class="form-control validate[required,equals[Email_Paciente]]" id="Email_Paciente-confirma" name="Email_Paciente-confirma" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente-confirma'); ?>">
	  	</div> 
	  	<hr />
	  	<h4>¿Cómo prefieres que te recordemos tu hora médica?</h4>
	  	<div class="form-group">
	    	<label style="font-weight:normal">A través de Mensaje de texto</label>
			<label class="radio-inline">
		  		<input type="radio" name="SMS_notificacion" value="2" <?php echo set_radio('SMS_notificacion', '2', TRUE); ?>> Si
			</label>
			<label class="radio-inline">
		  		<input type="radio" name="SMS_notificacion" value="" <?php echo set_radio('SMS_notificacion', ''); ?>> No
			</label>
	  	</div> 
	  	<div class="form-group">
	    	<label style="font-weight:normal">A través de correo electr&oeacute;nico</label>
			<label class="radio-inline">
		  		<input type="radio" name="EMAIL_notificacion" value="3" <?php echo set_radio('EMAIL_notificacion', '3', TRUE); ?>> Si
			</label>
			<label class="radio-inline">
		  		<input type="radio" name="EMAIL_notificacion" value="" <?php echo set_radio('EMAIL_notificacion', ''); ?>> No
			</label>
	  	</div>
	  	<hr /> 
	  	<h4>Si luego de enviado el mensaje no confirmas la hora, te llamaremos para verificar la cita m&eacute;dica. </h4>
	  	<div class="form-group">
		    <label style="font-weight:normal">¿Quisiera recibir informaci&oacute;n de la cl&ieacute;nica D&aacute;vila?</label>
			<label class="radio-inline">
		  		<input type="radio" class="validate[required]" name="Op_InfoClinica" value="S" <?php echo set_radio('Op_InfoClinica', 'S', TRUE); ?>> Si
			</label>
			<label class="radio-inline">
		  		<input type="radio" class="validate[required]" name="Op_InfoClinica" value="N" <?php echo set_radio('Op_InfoClinica', 'N'); ?>> No
			</label>
	  	</div>
	  	<div class="divclear">&nbsp;</div>
	  	<div class="btnspace">
		  	<button type="button" class="btn btn-primary ante" id="ante2">Anterior</button>
		  	<button type="button" class="btn btn-primary sgte" id="sgte2">Siguiente</button>
		</div>
	</div>
	</form>
	<!-- End Block 2-->
	
	<!-- Block 3-->
	<?php echo form_open('registro3', array('id' => 'registro3')); ?>
  	<div class="tabspace space3">
	  	<p>Para poder reservar horas, consultar o anular horas reservadas, debes establecer una clave personal de m&aacute;ximo 8  d&iacute;gitos </p>
	  	<div class="form-group">
			<label for="Clave_Usuario">Clave</label>
	    	<input type="password" class="form-control validate[required]" id="Clave_Usuario" name="Clave_Usuario" value="<?php echo set_value('Clave_Usuario'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="Clave_Usuario-confirma">Confirmar Clave</label>
	    	<input type="password" class="form-control validate[required,equals[Clave_Usuario]]" id="Clave_Usuario-confirma" name="Clave_Usuario-confirma" value="<?php echo set_value('Clave_Usuario-confirma'); ?>">
	  	</div> 
	  	<p>Escribe una pregunta y una respuesta para que en caso de olvidar tu clave, logres recuperarla.</p>
	  	<div class="form-group">
	    	<label for="Pregunta_Clave">Pregunta</label>
	    	<input type="text" class="form-control validate[required]" id="Pregunta_Clave" name="Pregunta_Clave" placeholder="Ingrese su pregunta clave" value="<?php echo set_value('Pregunta_Clave'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="RespuestaClave">Respuesta</label>
	    	<input type="text" class="form-control validate[required]" id="RespuestaClave" name="RespuestaClave" placeholder="Ingrese su respuesta clave" value="<?php echo set_value('RespuestaClave'); ?>" />
	  	</div>
	  	<div class="divclear">&nbsp;</div>
	  	<div class="btnspace">
	  		<button type="button" class="btn btn-primary ante" id="ante3">Anterior</button>
	  		<button type="button" class="btn btn-primary fin" id="fin3">Finalizar</button>
	  	</div>
  	</div>
  	<div class="enviando">
		<p class="uno">Enviando su registro...</p>
		<p class="dos">Registro exitoso!<br/> <a href="<?php echo site_url("buscarmedico");?>">Ir a Reserva Horas</a> </p>
		<p class="error">Enviado</p>
	</div>
</div>
</form>
</div>
