<br/>
	<!-- Block 1 -->
	<?php echo form_open('contactenos/enviar', array('id' => 'contacto', 'class' => 'tabactive')); ?>
	<div class="tabspace space1">
		<div class="form-group">
	    	<label for="Rut">RUT*</label>
	    	<input type="text" class="form-control validate[required,rut]" id="Rut" name="Rut" placeholder="Ej: 12345678-K" value="<?php echo set_value('Rut'); ?>"> 
	  	</div> 
	  	<div class="form-group">
	    	<label for="Nombre">Nombre*</label>
	    	<input type="text" class="form-control validate[required]" id="Nombre" name="Nombre" placeholder="Ingrese su nombre" value="<?php echo set_value('Nombre'); ?>">
	  	</div>  
	  	<div class="form-group">
	    	<label for="Email">Email</label>
	    	<input type="text" class="form-control validate[required,custom[email]]" id="Email" name="Email" placeholder="Ingrese su email" value="<?php echo set_value('Email'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="Comuna">Comuna</label>
	    	<input type="text" class="form-control validate[required]" id="Comuna_Paciente-label" name="Comuna" placeholder="Ingrese su comuna" value="<?php echo set_value('Comuna'); ?>">
	  	</div> 
	  	<div class="form-group">
		    <label for="Ciudad">Ciudad</label>
	    	<input type="text" class="form-control validate[required]" id="Ciudad" name="Ciudad" placeholder="Ingrese la cuidad" value="<?php echo set_value('Ciudad'); ?>">
	  	</div> 
	  	<div class="form-group">
	    	<label for="Prevision">Previsión</label>
			<select id="Prevision" name="Prevision" class="form-control validate[required]">
				<option value="" <?php echo set_select('Prevision_Paciente', '', TRUE); ?>></option>
				<?php foreach($medical_services as $id => $name): ?>
				<option value="<?php echo $id ?>" <?php echo set_select('Prevision_Paciente', $id); ?> ><?php echo $name ?></option>
				<?php endforeach; ?>
			</select>
	  	</div>

	  	<div class="form-group" style="width:23%">
	    	<label for="Fono_Paciente">Teléfono</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="prefijo_Fono_Paciente" name="prefijo_Fono_Paciente">
						<?php foreach($phone_prefixes as $prefix): ?>
						<option value="<?php echo $prefix ?>" <?php echo set_select('prefijo_Fono_Paciente', $prefix); ?> ><?php echo $prefix ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[custom[integer],maxSize[7]]" id="Fono_Paciente" name="Fono_Paciente" placeholder="Ingrese los 7 Digitos" value="<?php echo set_value('Fono_Paciente'); ?>">
				</div>
		  	</div> 
	  	</div> 
	  	<div class="form-group" style="width:23%">
	    	<label for="Fono_Movil">Celular</label>
	      	<div class="row">
				<div class="col-lg-4">
					<select class="form-control" id="Prefijo_Movil" name="Prefijo_Movil">
						<option value="9" selected="selected">9</option>
					</select>
				</div>
				<div class="col-lg-8">
					<input type="text" class="form-control validate[required,custom[integer],minSize[8],maxSize[8]]" id="Fono_Movil" name="Fono_Movil" placeholder="Ingrese los 8 Digitos" value="<?php echo set_value('Fono_Movil'); ?>">
				</div>
		  	</div> 
	  	</div>



	  	<div class="divclear">&nbsp;</div>
	  	<div class="btnspace">
	  		<button type="button" class="btn btn-primary sgte" id="enviar">Enviar</button>
	  	</div>
	</div>
	</form>
