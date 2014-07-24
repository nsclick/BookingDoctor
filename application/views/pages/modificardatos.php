<?php
	$secret_question 	= $user_access && isset ($user_access['pregunta_clave']) ? $user_access['pregunta_clave'] : '';
	$secret_answer 		= $user_access && isset ($user_access['respuesta_clave']) ? $user_access['respuesta_clave'] : '';
	$user_rut			= $user_data['rut_paciente'] . '-' . $user_data['dv_paciente'];

	$cargas = array();
	if (is_array($family_data)) {
		foreach ($family_data as $carga) {
			$rut_carga = $carga['rut_paciente'] . '-' . $carga['dv_paciente'];
			if ($rut_carga != $user_rut) {
				$cargas[] = $carga;
			}
		}
	}

	$active_tab = !isset ( $active_tab ) ? 'personal_info' : $active_tab;

	$receive_information	= $user_data['recibe_informacion'] == 'S' ? true : false;
	$receive_via_sms 		= '';
	$receive_via_mail 		= '';
	switch ($user_data['via_confirmacion']) {
		case '2^3':
			$receive_via_sms 	= true;
			$receive_via_mail 	= true;
			break;
		case '2^':
		case '2^ ':
			$receive_via_sms	= true;
			$receive_via_mail	= false;
			break;
		case '^3':
		case ' ^3':
			$receive_via_sms	= false;
			$receive_via_mail	= true;
		default:
			$receive_via_mail 	= false;
			$receive_via_sms	= false;
	}
?>

<?php if ( isset ( $message ) && !empty ( $message ) ): ?>
<!-- Message Box -->	
	<div class="alert alert-<?php echo $message['class']; ?> alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	  <span><?php echo $message['text']; ?></span>
	</div>
<!--/ Message Box -->	
<?php endif; ?>

<!-- Wrapper -->
<div id="wrapper" class="modificar">
	<h1>Modificar Datos</h1>

	<!-- Tab Links -->
	<div class="btab">
		<ul>
			<li class="<?php echo ($active_tab == 'personal_info') ? 'active' : ''; ?>"><a href="#personal_info" role="tab" data-toggle="tab">Informaci&oacute;n personal</a></li>
			<li class="<?php echo ($active_tab == 'contact_info') ? 'active' : ''; ?>"><a href="#contact_info" role="tab" data-toggle="tab">Informaci&oacute;n de contacto</a></li>
			<li class="<?php echo ($active_tab == 'security_info') ? 'active' : ''; ?>"><a href="#security_info" role="tab" data-toggle="tab">Informaci&oacute;n de seguridad</a></li>
			<li class="<?php echo ($active_tab == 'family_info') ? 'active' : ''; ?>"><a href="#family_info" role="tab" data-toggle="tab">Cargas</a></li>
		</ul>
		<div class="divclear">&nbsp;</div>
	</div>
	<!--/ Tab Links -->

	<!-- Tab Content -->
	<div class="tab-content">

		<!-- Información Personal -->
		<?php
			$personal_tab_class = $active_tab == 'personal_info' ? 'tab-pane active' : 'tab-pane';
			echo form_open('', array(
				'id'	=> 'personal_info',
				'class'	=> $personal_tab_class
			));
		?>
			<h2>Informaci&oacute;n Personal</h2>
			<div class="form-group">
		    	<label for="rut">RUT*</label>
		    	<input type="text" class="form-control validate[required,rut]" id="Rut_Paciente" name="Rut_Paciente" placeholder="Ej: 12345678-K" value="<?php echo set_value('Rut_Paciente', $user_rut); ?>"> 
		  	</div> 
		  	<div class="form-group">
		    	<label for="Fechanac_Paciente">Fecha Nacimiento*</label>
				<div class='input-group date'>
		    		<input type="text" class="form-control validate[required] text-input datepicker" id="Fechanac_Paciente" name="Fechanac_Paciente" data-format="DD/MM/YYYY" placeholder="DD/MM/YYYY" value="<?php echo set_value('Fechanac_Paciente', $user_data['fecha_nac_paciente']); ?>" style="border-right:none"/>
		        	<span class="input-group-addon" style="border-left:none;background:#fff"><span class="glyphicon glyphicon-calendar"></span></span>
		    	</div>    
		  	</div>  
		  	<div class="form-group">
		    	<label for="Nombre_Paciente">Nombres*</label>
		    	<input type="text" class="form-control validate[required]" id="Nombre_Paciente" name="Nombre_Paciente" placeholder="Ingrese su nombre" value="<?php echo set_value('Nombre_Paciente', $user_data['nombre_paciente']); ?>">
		  	</div>  
		  	<div class="form-group">
		    	<label for="Apepat_Paciente">Primer Apellido*</label>
		    	<input type="text" class="form-control validate[required]" id="Apepat_Paciente" name="Apepat_Paciente" placeholder="Ingrese su primer apellido" value="<?php echo set_value('Apepat_Paciente', $user_data['apepat_paciente']); ?>" >
		  	</div>  
		  	<div class="form-group">
		    	<label for="Apemat_Paciente">Segundo Apellido</label>
		    	<input type="text" class="form-control" id="Apemat_Paciente" name="Apemat_Paciente" placeholder="Ingrese su segundo apellido" value="<?php echo set_value('Apemat_Paciente', $user_data['apemat_paciente']); ?>">
		  	</div> 
		  	<div class="form-group">
		    	<label for="Direccion_Paciente">Dirección</label>
		    	<input type="text" class="form-control validate[required]" id="Direccion_Paciente" name="Direccion_Paciente" placeholder="Ingrese su dirección" value="<?php echo set_value('Direccion_Paciente', $user_data['direccion_paciente']); ?>">
		  	</div> 
		  	<div class="form-group">
		    	<label for="Comuna_Paciente">Comuna</label>
		    	<input type="hidden" id="Comuna_Paciente" name="Comuna_Paciente">
		    	<input type="text" class="form-control validate[required]" id="Comuna_Paciente-label" name="Comuna_Paciente" placeholder="Ingrese su comuna" value="<?php echo set_value('Comuna_Paciente', $user_data['comuna_paciente']); ?>">
		  	</div> 
		  	<div class="form-group">
			    <label for="Ciudad_Paciente">Ciudad</label>
		    	<input type="text" class="form-control validate[required]" id="Ciudad_Paciente" name="Ciudad_Paciente" placeholder="Ingrese la cuidad" value="<?php echo set_value('Ciudad_Pacient', $user_data['ciudad_paciente']); ?>">
		  	</div> 
		  	<div class="form-group">
		    	<label>Sexo</label><br>
				<label class="radio-inline">
			  		<input type="radio" class="validate[required]" id="Sexo_Paciente" name="Sexo_Paciente" value="M" <?php echo set_radio('Sexo_Paciente', 'M', $user_data['sexo_paciente'] == 'M' ); ?>> Masculuno
				</label>
				<label class="radio-inline">
			  		<input type="radio" class="validate[required]" id="Sexo_Paciente" name="Sexo_Paciente" value="F" <?php echo set_radio('Sexo_Paciente', 'F', $user_data['sexo_paciente'] == 'F'); ?>> Femenino
				</label>
		  	</div> 
		  	<div class="form-group">
		    	<label for="Prevision_Paciente">Previsi&oacute;n</label>
				<select id="Prevision_Paciente" name="Prevision_Paciente" class="form-control validate[required]">
					<option value="">Selecciona tu previsi&oacute;n</option>
					<?php foreach($medical_services as $id => $name): ?>
					<option value="<?php echo $id ?>" <?php echo set_select('Prevision_Paciente', $id, $user_data['prevision_paciente'] == $id ); ?> ><?php echo $name ?></option>
					<?php endforeach; ?>
				</select>
		  	</div>
		  	<div class="divclear">&nbsp;</div>

		  	<div style="text-align:center"><button type="submit" class="btn btn-primary">Guardar Cambios</button></div>
			
		  	<?php foreach ($user_data as $key => $value): ?>
		  		<input type="hidden" name="user[<?php echo $key; ?>]" value="<?php echo $value;?>" />
		  	<?php endforeach; ?>
		  	<input type="hidden" name="action" value="save_personal_info" />
		</form>
		<!-- Información Personal -->

		<!-- Información de Contacto -->
		<?php
			$contact_tab_class = $active_tab == 'contact_info' ? 'tab-pane active' : 'tab-pane';
			echo form_open('', array(
				'id'	=> 'contact_info',
				'class'	=> $contact_tab_class
			));
		?>
		  	<h2>Informaci&oacute;n de Contacto</h2>
		  	<div class="form-group" style="width:23%">
		    	<label for="Fono1_Paciente">Teléfono 1</label>
		      	<div class="row">
					<!--
					<div class="col-lg-4">
						<select class="form-control" id="prefijo_Fono1_Paciente" name="prefijo_Fono1_Paciente">
							<?php foreach($phone_prefixes as $prefix): ?>
							<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono1_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					-->
					<div class="col-lg-12">
						<input type="text" class="form-control validate[required,custom[integer]]" id="Fono1_Paciente" name="Fono1_Paciente" placeholder="Ingrese los 7 Digitos" value="<?php echo set_value('Fono1_Paciente', $user_data['fono_princ_paciente']); ?>">
					</div>
			  	</div> 
		  	</div> 
		  	<div class="form-group" style="width:23%">
		    	<label for="Fono2_Paciente">Teléfono 2</label>
		      	<div class="row">
					<!--
					<div class="col-lg-4">
						<select class="form-control" id="prefijo_Fono2_Paciente" name="prefijo_Fono2_Paciente">
							<?php foreach($phone_prefixes as $prefix): ?>
							<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono2_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					-->
					<div class="col-lg-12">
						<input type="text" class="form-control" id="Fono2_Paciente" name="Fono2_Paciente" placeholder="Ingrese los 7 Digitos" value="<?php echo set_value('Fono2_Paciente', $user_data['fono_alter_paciente']); ?>">
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
						<input type="text" class="form-control validate[required,custom[integer]]" id="FonoMovil1" name="FonoMovil1" placeholder="Ingrese los 8 Digitos" value="<?php echo set_value('FonoMovil1', $user_data['numero_celular1']); ?>">
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
						<input type="text" class="form-control validate[custom[integer]]" id="FonoMovil2" name="FonoMovil2" placeholder="Ingrese los 8 Digitos" value="<?php echo set_value('FonoMovil2', $user_data['numero_celular2']); ?>">
					</div>
			  	</div> 
		  	</div>
		  	<div class="form-group">
		    	<label for="Email_Paciente">Email</label>
		    	<input type="text" class="form-control validate[required,custom[email]]" id="Email_Paciente" name="Email_Paciente" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente', $user_data['email_paciente']); ?>">
		  	</div> 
		  	<div class="form-group">
		    	<label for="Email_Paciente-confirma">Confirmar Email</label>
		    	<input type="text" class="form-control validate[required,equals[Email_Paciente]]" id="Email_Paciente-confirma" name="Email_Paciente-confirma" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente-confirma', $user_data['email_paciente']); ?>">
		  	</div> 
		  	<hr />
		  	<h4>¿C&oacute;mo prefieres que te recordemos tu hora m&eacute;dica?</h4>
		  	<div class="form-group">
		    	<label style="font-weight:normal">A trav&eacute;s de Mensaje de texto</label>
				<label class="radio-inline">
			  		<input type="radio" name="SMS_notificacion" value="2" <?php echo set_radio('SMS_notificacion', '2', $receive_via_sms); ?>> Si
				</label>
				<label class="radio-inline">
			  		<input type="radio" name="SMS_notificacion" value="" <?php echo set_radio('SMS_notificacion', '', !$receive_via_sms); ?>> No
				</label>
		  	</div> 
		  	<div class="form-group">
		    	<label style="font-weight:normal">A través de correo electrónico</label>
				<label class="radio-inline">
			  		<input type="radio" name="EMAIL_notificacion" value="3" <?php echo set_radio('EMAIL_notificacion', '3', $receive_via_mail); ?>> Si
				</label>
				<label class="radio-inline">
			  		<input type="radio" name="EMAIL_notificacion" value="" <?php echo set_radio('EMAIL_notificacion', '', !$receive_via_mail); ?>> No
				</label>
		  	</div>
		  	<hr /> 
		  	<h4>Si luego de enviado el mensaje no confirmas la hora, te llamaremos para verificar la cita m&eacute;dica.</h4>
		  	<div class="form-group">
			    <label style="font-weight:normal">¿Quisiera recibir informaci&oacute;n de la cl&iacute;nica D&aacute;vila?</label>
				<label class="radio-inline">
			  		<input type="radio" class="validate[required]" name="Op_InfoClinica" value="S" <?php echo set_radio('Op_InfoClinica', 'S', $receive_information); ?>> Si
				</label>
				<label class="radio-inline">
			  		<input type="radio" class="validate[required]" name="Op_InfoClinica" value="N" <?php echo set_radio('Op_InfoClinica', 'N', !$receive_information); ?>> No
				</label>
		  	</div>
		  	<div class="divclear">&nbsp;</div>
		  	<div style="text-align:center"><button type="submit" class="btn btn-primary">Guardar Cambios</button></div>

		  	<input type="hidden" name="action" value="save_contact_info" />
		</form>
		<!--/ Información de Contacto -->

		<!-- Información de Seguridad -->
		<?php
			$security_tab_class = $active_tab == 'security_info' ? 'tab-pane active' : 'tab-pane';
			echo form_open('', array(
				'id'	=> 'security_info',
				'class'	=> $security_tab_class
			));
		?>
		  	<h2>Informaci&oacute;n de Seguridad</h2>
		  	<p>Modificaci&oacute;n de la contrase&Nu;a de 8 d&iacute;gitos.</p>
		  	<div class="form-group">
				<label for="Clave_Usuario">Clave</label>
		    	<input type="password" class="form-control validate[required]" id="Clave_Usuario" name="Clave_Usuario" value="<?php echo set_value('Clave_Usuario'); ?>">
		  	</div> 
		  	<div class="form-group">
		    	<label for="Clave_Usuario-confirma">Confirmar Clave</label>
		    	<input type="password" class="form-control validate[required,equals[Clave_Usuario]]" id="Clave_Usuario-confirma" name="Clave_Usuario-confirma" value="<?php echo set_value('Clave_Usuario-confirma'); ?>">
		  	</div> 
		  	<p>Modificaci&oacute;n de la pregunta secreta para recuperaci&oacute;n de contrase&ntilde;a.</p>
		  	<div class="form-group">
		    	<label for="Pregunta_Clave">Pregunta</label>
		    	<input type="text" class="form-control validate[required]" id="Pregunta_Clave" name="Pregunta_Clave" placeholder="Ingrese su pregunta clave" value="<?php echo set_value('Pregunta_Clave', $secret_question); ?>">
		  	</div> 
		  	<div class="form-group">
		    	<label for="RespuestaClave">Respuesta</label>
		    	<input type="text" class="form-control validate[required]" id="RespuestaClave" name="Respuesta_Clave" placeholder="Ingrese su respuesta clave" value="<?php echo set_value('Respuesta_Clave', $secret_answer); ?>" />
		  	</div>
		  	<div class="divclear">&nbsp;</div>
		  	<div style="text-align:center"><button type="submit" class="btn btn-primary">Guardar Cambios</button></div>

		  	<?php foreach ($user_data as $key => $value): ?>
		  		<input type="hidden" name="user[<?php echo $key; ?>]" value="<?php echo $value;?>" />
		  	<?php endforeach; ?>
		  	<input type="hidden" name="action" value="save_security_info" />
		</form>
		<!--/ Información de Seguridad -->

		<!-- Cargas -->
		<?php $family_tab_class = $active_tab == 'family_info' ? 'tab-pane active' : 'tab-pane'; ?>
		<div id="family_info" class="<?php echo $family_tab_class; ?>">
			<form>
				<h2>Informaci&oacute;n Familiar</h2>
			  	<p>Informaci&oacute;n de cargas y agregar cargas nuevas.</p>
			  	<div class="form-group">
			  		<button id="add_family_btn" type="button" class="btn btn-primary">Agregar Carga</button>
			  	</div>
			  	<div class="divclear">&nbsp;</div>
			</form>

			<?php
				echo form_open('', array(
					'id'	=> 'family_info_f',
					'class'	=> 'tab-pane',
					'style'	=> 'display:none;'
				));
			?>
				<div class="form-group">
			    	<label for="rut">RUT*</label>
			    	<input type="text" class="form-control validate[required,rut]" id="Carga_Rut_Paciente" name="Carga_Rut_Paciente" placeholder="Ej: 12345678-K" value="<?php echo set_value('Carga_Rut_Paciente'); ?>"> 
			  	</div> 
			  	<div class="form-group">
			    	<label for="Fechanac_Paciente">Fecha Nacimiento*</label>
					<div class='input-group date'>
			    		<input class="form-control validate[required] text-input datepicker" id="Carga_Fechanac_Paciente" name="Carga_Fechanac_Paciente" data-format="DD/MM/YYYY" placeholder="DD/MM/YYYY" value="<?php echo set_value('Carga_Fechanac_Paciente'); ?>" style="border-right:none"/>
			        	<span class="input-group-addon" style="border-left:none;background:#fff"><span class="glyphicon glyphicon-calendar"></span></span>
			    	</div>    
			  	</div>  
			  	<div class="form-group">
			    	<label for="Nombre_Paciente">Nombres*</label>
			    	<input type="text" class="form-control validate[required]" id="Carga_Nombre_Paciente" name="Carga_Nombre_Paciente" placeholder="Ingrese su nombre" value="<?php echo set_value('Carga_Nombre_Paciente'); ?>">
			  	</div>  
			  	<div class="form-group">
			    	<label for="Apepat_Paciente">Primer Apellido*</label>
			    	<input type="text" class="form-control validate[required]" id="Carga_Apepat_Paciente" name="Carga_Apepat_Paciente" placeholder="Ingrese su primer apellido" value="<?php echo set_value('Carga_Apepat_Paciente'); ?>" >
			  	</div>  
			  	<div class="form-group">
			    	<label for="Apemat_Paciente">Segundo Apellido</label>
			    	<input type="text" class="form-control" id="Carga_Apemat_Paciente" name="Carga_Apemat_Paciente" placeholder="Ingrese su segundo apellido" value="<?php echo set_value('Carga_Apemat_Paciente'); ?>">
			  	</div> 
			  	<div class="form-group">
			    	<label>Sexo</label><br>
					<label class="radio-inline">
				  		<input type="radio" class="validate[required]" id="Carga_Sexo_Paciente_M" name="Carga_Sexo_Paciente" value="M" <?php echo set_radio('Carga_Sexo_Paciente', 'M'); ?>> Masculino
					</label>
					<label class="radio-inline">
				  		<input type="radio" class="validate[required]" id="Carga_Sexo_Paciente_F" name="Carga_Sexo_Paciente" value="F" <?php echo set_radio('Carga_Sexo_Paciente', 'F'); ?>> Femenino
					</label>
			  	</div>
			  	<div class="form-group">
			    	<label for="Familiar_Paciente">Parentesco</label>
			    	<select id="Carga_Parentesco_Paciente" name="Carga_Parentesco_Paciente" class="form-control validate[required]">
			    		<option>Seleccione el parentesco</option>
			    		<option value="C">Esposo(a)</option>
			    		<option value="H">Hijo(a)</option>
			    		<option value="O">Otro</option>
			    	</select>
			  	</div>
			  	<div class="form-group">
			    	<label for="Prevision_Paciente">Previsi&oacute;n</label>
					<select id="Carga_Prevision_Paciente" name="Carga_Prevision_Paciente" class="form-control validate[required]">
						<option value="">Selecciona una previsora</option>
						<?php foreach($medical_services as $id => $name): ?>
						<option value="<?php echo $id ?>"><?php echo $name ?></option>
						<?php endforeach; ?>
					</select>
			  	</div>
			  	<div class="divclear">&nbsp;</div>
			  	<div style="text-align:center">
			  		<button id="cancel_family_btn" type="button" class="btn btn-danger">Cancelar</button>
			  		<button type="submit" class="btn btn-primary">Guardar</button>
			  	</div>
			  	<input type="hidden" name="action" value="save_family_info" />
			  	<input id="carga_member_action" type="hidden" name="member_action" value="I" />
			  	<input id="Carga_Id_Ambulatorio" type="hidden" name="Carga_Id_Ambulatorio" value="0" />
			</form>

			<!-- Listado de Cargas Actuales -->
			<ul>
				<?php foreach ($cargas as $index => $carga): ?>
					<li class="carga">
						<?php
							echo form_open();
						?>
							<p>
							<span>Nombre: </span><?php echo $carga['nombre_paciente'] . ' ' . $carga['apepat_paciente'] . ' ' . $carga['apemat_paciente']; ?>
							<br>
							<span>Rut: </span> <?php echo $carga['rut_paciente'] . '-' . $carga['dv_paciente']; ?>
							<br>
							<span>Parentesco: </span> <?php echo $carga['desc_parentesco']; ?>
							<br>
							</p>
							<button id="edit_carga_btn" type="button" data-carga-index="<?php echo $index; ?>" class="btn btn-primary">Editar Carga</button>
							<button type="submit" class="btn btn-danger">Disasociar Carga</button>

							<!-- Carga Hidden Inputs -->
							<input type="hidden" id="<?php echo $index; ?>_Carga_Rut_Paciente" name="Carga_Rut_Paciente" value="<?php echo $carga['rut_paciente_str']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Fechanac_Paciente" name="Carga_Fechanac_Paciente" value="<?php echo $carga['fecha_nac_paciente']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Nombre_Paciente" name="Carga_Nombre_Paciente" value="<?php echo $carga['nombre_paciente']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Apepat_Paciente" name="Carga_Apepat_Paciente" value="<?php echo $carga['apepat_paciente']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Apemat_Paciente" name="Carga_Apemat_Paciente" value="<?php echo $carga['apemat_paciente']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Sexo_Paciente" name="Carga_Sexo_Paciente" value="<?php echo $carga['sexo_paciente']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Parentesco_Paciente" name="Carga_Parentesco_Paciente" value="<?php echo $carga['parentesco']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Prevision_Paciente" name="Carga_Prevision_Paciente" value="<?php echo $carga['prevision']; ?>" />
							<input type="hidden" id="<?php echo $index; ?>_Carga_Id_Ambulatorio" name="Carga_Id_Ambulatorio" value="<?php echo $carga['id_ambulatorio']; ?>" />
							<input type="hidden" name="action" value="save_family_info" />
							<input id="carga_member_action" type="hidden" name="member_action" value="E" />
							<!--/ Carga Hidden Inputs -->
						</form>
					</li>
				<?php endforeach; ?>
			</ul>
			<!--/ Listado de Cargas Actuales -->

		</div>
		<!--/ Cargas -->

	</div>
	<!--/ Tab Content -->

</div>
<!--/ Wrapper -->