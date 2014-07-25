	<!-- Block 1 -->
	<?php echo form_open('contactenos/enviar', array('id' => 'contacto', 'class' => 'tabactive')); ?>
	<div class="tabspace space1">
		<div class="form-group">
	    	<label for="rut">RUT*</label>
	    	<input type="text" class="form-control validate[required,rut]" id="Rut_Paciente" name="Rut_Paciente" placeholder="Ej: 12345678-K" value="<?php echo set_value('Rut_Paciente'); ?>"> 
	  	</div> 
	  	<div class="form-group">
	    	<label for="Nombre_Paciente">Nombre*</label>
	    	<input type="text" class="form-control validate[required]" id="Nombre_Paciente" name="Nombre_Paciente" placeholder="Ingrese su nombre" value="<?php echo set_value('Nombre_Paciente'); ?>">
	  	</div>  
	  	<div class="form-group">
	    	<label for="Email_Paciente">Email</label>
	    	<input type="text" class="form-control validate[required,custom[email]]" id="Email_Paciente" name="Email_Paciente" placeholder="Ingrese su email" value="<?php echo set_value('Email_Paciente'); ?>">
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
	    	<label for="Prevision_Paciente">Previsión</label>
			<select id="Prevision_Paciente" name="Prevision_Paciente" class="form-control validate[required]">
				<option value="" <?php echo set_select('Prevision_Paciente', '', TRUE); ?>></option>
				<?php foreach($medical_services as $id => $name): ?>
				<option value="<?php echo $id ?>" <?php echo set_select('Prevision_Paciente', $id); ?> ><?php echo $name ?></option>
				<?php endforeach; ?>
			</select>
	  	</div>

	  	<div class="form-group" style="width:23%">
	    	<label for="Fono1_Paciente">Teléfono</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="prefijo_Fono1_Paciente" name="prefijo_Fono1_Paciente">
						<?php foreach($phone_prefixes as $prefix): ?>
						<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono1_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[required,custom[integer],maxSize[7]]" id="Fono1_Paciente" name="Fono1_Paciente" placeholder="Ingrese los 7 Digitos" value="<?php echo set_value('Fono1_Paciente'); ?>">
				</div>
		  	</div> 
	  	</div> 
	  	<div class="form-group" style="width:23%">
	    	<label for="FonoMovil1">Celular</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="PrefMovil1" name="PrefMovil1">
						<option value="9" selected="selected">9</option>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[required,custom[integer],minSize[8],maxSize[8]]" id="FonoMovil1" name="FonoMovil1" placeholder="Ingrese los 8 Digitos" value="<?php echo set_value('FonoMovil1'); ?>">
				</div>
		  	</div> 
	  	</div>



	  	<div class="divclear">&nbsp;</div>
	  	<div class="btnspace">
	  		<button type="button" class="btn btn-primary sgte" id="enviar">Enviar</button>
	  	</div>
	</div>
	</form>
