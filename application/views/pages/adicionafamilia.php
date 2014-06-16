<div id="wrapper" class="modificar">
<h1>Adicionar Carga</h1>
<form>
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
	    	<label>Sexo</label><br>
			<label class="radio-inline">
		  		<input type="radio" class="validate[required]" id="Sexo_Paciente" name="Sexo_Paciente" value="M" <?php echo set_radio('Sexo_Paciente', 'M'); ?>> Masculuno
			</label>
			<label class="radio-inline">
		  		<input type="radio" class="validate[required]" id="Sexo_Paciente" name="Sexo_Paciente" value="F" <?php echo set_radio('Sexo_Paciente', 'F'); ?>> Femenino
			</label>
	  	</div>
	  	<div class="form-group">
	    	<label for="Familiar_Paciente">Parentesco</label>
	    	<select name"Familiar_Paciente">
	    		<option>Esposa</option>
	    		<option>Hijo</option>
	    		<option>Hija</option>
	    		<option>Otro</option>
	    	</select>
	  	</div>
	  	<div class="divclear">&nbsp;</div>
	  	<div style="text-align:center"><button type="button" class="btn btn-primary">Guardar Cambios</button></div>
</form>
</div>