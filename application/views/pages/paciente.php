<div id="wrapper">
	<div class="webhome">

		<h2><b>Paso 3:</b> Selecci&oacute;n de Paciente</h2>
		<p>
			Selecciona entre los afiliados al plan, la persona para quien buscas reservar una hora.
		</p>
		<?php $attributes = array('role' => 'form', 'id' => 'form-paciente'); ?>
		<input type="hidden" name="patient-choice" value="1">
		<?php echo form_open('agenda/confirmacion', $attributes); ?>
			<div class="form-group">
				<select name="patient">
					<?php foreach($familyMembers as $member): ?>
						<option value="<?php echo $member['id_ambulatorio'] ?>"><?php echo $member['desc_parentesco'] ?>: <?php echo "{$member['nombre_paciente']} {$member['apepat_paciente']} {$member['apemat_paciente']}" ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-primary" id="confirma-ok">Aceptar</button>
			</div>
		</form>
	
	</div>
</div>
