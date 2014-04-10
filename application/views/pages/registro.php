<h1>Registro</h1>

<!-- cada tres caracteres es un dia.

000 no hay horas

5A1 - - dia lleno

cualquier otra cosa disponible

fecha de inicio inicio de la agenda desde donde donde inicias 

-->


<p>Para registrarte ingresa los siguientes datos:</p>
<p>Al registrarte podrás acceder al servicio de <b>Reserva de Horas.</b></p>

<?php 
	$attributes = array(
	'id' => 'registro', 
	//'class' => 'form-inline'
); ?>


<?php echo form_open('registro/guardar', $attributes); ?>

<div class="form-group">
    <label for="rut">RUT*</label>
    <input type="text" class="form-control validate[required,rut]" id="Rut_Paciente" name="Rut_Paciente" placeholder="Ej: 12345678K" value="<?php echo set_value('Rut_Paciente'); ?>"> 
  </div>  
  <div class="form-group">
    <label for="Fechanac_Paciente">Fecha Nacimiento*</label>
  
	<div class='input-group date' id='datetimepicker1'>
    	<input type='date' class="form-control validate[required] text-input datepicker" id="Fechanac_Paciente" name="Fechanac_Paciente" data-format="DD/MM/YYYY" placeholder="DD/MM/YYYY" value="<?php echo set_value('Fechanac_Paciente'); ?>">
        <span class="input-group-addon" >
        	<span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>    
  
  </div>  
  <div class="form-group">
    <label for="Nombre_Paciente">Nombres*</label>
    <input type="text" class="form-control validate[required]" id="Nombre_Paciente" name="Nombre_Paciente" placeholder="Ingrese su nombre" value="<?php echo set_value('Nombre_Paciente');?>">
  </div>  
  <div class="form-group">
    <label for="Apepat_Paciente">Primer Apellido*</label>
    <input type="text" class="form-control validate[required]" id="Apepat_Paciente" name="Apepat_Paciente" placeholder="Ingrese su primer apellido" value="<?php echo set_value('Apepat_Paciente');?>" >
  </div>  
  <div class="form-group">
    <label for="Apemat_Paciente">Segundo Apellido</label>
    <input type="text" class="form-control" id="Apemat_Paciente" name="Apemat_Paciente" placeholder="Ingrese su segundo apellido" value="<?php echo set_value('Apemat_Paciente');?>">
  </div> 

  <div class="form-group">
    <label for="Direccion_Paciente">Dirección</label>
    <input type="text" class="form-control validate[required]" id="Direccion_Paciente" name="Direccion_Paciente" placeholder="Ingrese su dirección" value="<?php echo set_value('Direccion_Paciente');?>">
  </div> 
  
  <div class="form-group">
    <label for="Comuna_Paciente">Comuna</label>
    <input type="hidden" id="Comuna_Paciente" name="Comuna_Paciente">
    <input type="text" class="form-control validate[required]" id="Comuna_Paciente-label" name="Comuna_Paciente-label" placeholder="Ingrese su comuna" value="<?php echo set_value('Direccion_Paciente');?>">
  </div> 

  <div class="form-group">
    <label for="Ciudad_Paciente">Ciudad</label>
    <input type="text" class="form-control validate[required]" id="Ciudad_Paciente" name="Ciudad_Paciente" placeholder="Ingrese la cuidad" value="<?php echo set_value('Ciudad_Pacient');?>">
  </div> 

  <div class="form-group">
    <label for="Fono1_Paciente">Teléfono 1</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="prefijo_Fono1_Paciente" name="prefijo_Fono1_Paciente">
				<?php foreach($phone_prefixes as $prefix): ?>
					<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono1_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[required,custom[integer],minSize[7],maxSize[7]]" id="Fono1_Paciente" name="Fono1_Paciente" placeholder="Ingrese los 7 Digitos" value="<?php echo set_value('Fono1_Paciente');?>">
		</div>
	  </div> 
  </div> 

  <div class="form-group">
    <label for="Fono2_Paciente">Teléfono 2</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="prefijo_Fono2_Paciente" name="prefijo_Fono2_Paciente">
				<?php foreach($phone_prefixes as $prefix): ?>
					<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono2_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[custom[integer],minSize[7],maxSize[7]]" id="Fono2_Paciente" name="Fono2_Paciente" placeholder="Ingrese los 7 Digitos" value="<?php echo set_value('Fono2_Paciente');?>">
		</div>
	  </div> 
  </div>

  <div class="form-group">
    <label for="FonoMovil1">Celular 1</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="PrefMovil1" name="PrefMovil1">
				<option value="2" selected="selected">9</option>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[required,custom[integer],minSize[8],maxSize[8]]" id="FonoMovil1" name="FonoMovil1" placeholder="Ingrese los 8 Digitos" value="<?php echo set_value('FonoMovil1');?>">
		</div>
	  </div> 
  </div>

  <div class="form-group">
    <label for="FonoMovil2">Celular 2</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="PrefMovil2" name="PrefMovil2">
				<option value="2" selected="selected">9</option>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[custom[integer],minSize[8],maxSize[8]]" id="FonoMovil2" name="FonoMovil2" placeholder="Ingrese los 8 Digitos" value="<?php echo set_value('FonoMovil2');?>">
		</div>
	  </div> 
  </div>

  <div class="form-group">
    <label for="Email_Paciente">Email</label>
    <input type="text" class="form-control validate[required,custom[email]]" id="Email_Paciente" name="Email_Paciente" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente');?>">
  </div> 

  <div class="form-group">
    <label for="Email_Paciente-confirma">Confirmar Email</label>
    <input type="text" class="form-control validate[required,equals[Email_Paciente]]" id="Email_Paciente-confirma" name="Email_Paciente-confirma" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente-confirma');?>">
  </div> 

  <div class="form-group">
    <label>Sexo</label><br>
	<label class="radio-inline">
	  <input type="radio" id="Sexo_Paciente" name="Sexo_Paciente" value="M" <?php echo set_radio('Sexo_Paciente', 'M'); ?>> Masculuno
	</label>
	<label class="radio-inline">
	  <input type="radio" id="Sexo_Paciente" name="Sexo_Paciente" value="F" <?php echo set_radio('Sexo_Paciente', 'F'); ?>> Femenino
	</label>
  </div> 

  <div class="form-group">
    <label for="Prevision_Paciente">Previsión</label>
	<select id="Prevision_Paciente" name="Prevision_Paciente" class="form-control validate[required]">
			<option value="" <?php echo set_select('Prevision_Paciente', '', TRUE); ?>>-- SELECCIONE --</option>
			<?php foreach($medical_services as $id => $name): ?>
				<option value="<?php echo $id ?>" <?php echo set_select('Prevision_Paciente', $id); ?> ><?php echo $name ?></option>
			<?php endforeach; ?>
	</select>
  </div> 
  
  <br>
  <p>¿Cómo prefieres que te recordemos tu hora médica?</p>

  <div class="form-group">
    <label>A través de Mensaje de texto</label><br>
	<label class="radio-inline">
	  <input type="radio" name="SMS_notificacion" value="2" <?php echo set_radio('SMS_notificacion', '2', TRUE); ?>> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="SMS_notificacion" value="" <?php echo set_radio('SMS_notificacion', ''); ?>> No
	</label>
  </div> 

  <div class="form-group">
    <label>A través de correo electrónico</label><br>
	<label class="radio-inline">
	  <input type="radio" name="EMAIL_notificacion" value="3" <?php echo set_radio('EMAIL_notificacion', '3', TRUE); ?>> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="EMAIL_notificacion" value="" <?php echo set_radio('EMAIL_notificacion', ''); ?>> No
	</label>
  </div> 
  
  <p>Si luego de enviado el mensaje no confirmas la hora, te llamaremos para verificar la cita médica. </p><br/>

  <div class="form-group">
    <label>¿Quisiera recibir infocrmación de la clínica Dávila?</label><br>
	<label class="radio-inline">
	  <input type="radio" name="Op_InfoClinica" value="S" <?php echo set_radio('Op_InfoClinica', 'S', TRUE); ?>> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="Op_InfoClinica" value="N" <?php echo set_radio('Op_InfoClinica', 'N'); ?>> No
	</label>
  </div> 
  
  <br/>
  <p>Para poder reservar horas, consultar o anular horas reservadas, debes establecer una clave personal de máximo 8  digitos </p>
  <br/>

  <div class="form-group">
    <label for="Clave_Usuario">Clave</label>
    <input type="password" class="form-control validate[required]" id="Clave_Usuario" name="Clave_Usuario" value="<?php echo set_value('Clave_Usuario');?>">
  </div> 
  <div class="form-group">
    <label for="Clave_Usuario-confirma">Confirmar Clave</label>
    <input type="password" class="form-control validate[required,equals[Clave_Usuario]]" id="Clave_Usuario-confirma" name="Clave_Usuario-confirma" value="<?php echo set_value('Clave_Usuario-confirma');?>">
  </div> 
  
  <br/>
  <p>Escribe una pregunta y una respuesta para que en caso de olvidar tu clave, logres recuperarla.</p>
  
  <div class="form-group">
    <label for="Pregunta_Clave">Pregunta</label>
    <input type="text" class="form-control validate[required]" id="Pregunta_Clave" name="Pregunta_Clave" placeholder="Ingrese su pregunta clave" value="<?php echo set_value('Pregunta_Clave');?>">
  </div> 
  <div class="form-group">
    <label for="RespuestaClave">Respuesta</label>
    <input type="text" class="form-control validate[required]" id="RespuestaClave" name="RespuestaClave"  placeholder="RespuestaClave" value="<?php echo set_value('RespuestaClave');?>">
  </div> 
  
  <br/><br/>
  <button type="submit" class="btn btn-primary">Registrar</button>
  <br/><br/>
  
</form>
