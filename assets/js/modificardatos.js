(function(window, $, undefined) {
	$(document).ready(function() {

		var addFamilyForm 		= $('#family_info_f'),
			addFamilyBtn		= $('#add_family_btn'),
			cancelFamilyBtn		= $('#cancel_family_btn'),
			editFamilyBtn		= $('#edit_carga_btn'),
			patientBirthDate	= $('#Fechanac_Paciente'),
			familyirthDate		= $('#Carga_Fechanac_Paciente');

		// Date Type Inputs
		patientBirthDate.datetimepicker({
			pickTime : false
		});

		familyirthDate.datetimepicker({
			pickTime : false
		});

		// ValidationEngine
		$('#personal_info').validationEngine('attach', {
			promptPosition:"inline", scroll:false
		});

		$('#contact_info').validationEngine('attach', {
			promptPosition:"inline", scroll:false
		});

		$('#security_info').validationEngine('attach', {
			promptPosition:"inline", scroll:false
		});

		$('#family_info_f').validationEngine('attach', {
			promptPosition:"inline", scroll:false
		});

		// Form Fields
		var cargaRut		= $('#Carga_Rut_Paciente'),
			cargaFecha		= $('#Carga_Fechanac_Paciente'),
			cargaName		= $('#Carga_Nombre_Paciente'),
			cargaApepat		= $('#Carga_Apepat_Paciente'),
			cargaApemat		= $('#Carga_Apemat_Paciente'),
			cargaSexM		= $('#Carga_Sexo_Paciente_M'),
			cargaSexF		= $('#Carga_Sexo_Paciente_F'),
			cargaRelat		= $('#Carga_Parentesco_Paciente'),
			cargaPrev		= $('#Carga_Prevision_Paciente'),
			cargaAction		= $('#carga_member_action'),
			cargaId 		= $('#Carga_Id_Ambulatorio');


		addFamilyBtn.on('click', function(ev) {
			addFamilyForm[0].reset();
			addFamilyForm.show();
			cargaAction.val('I');
			cargaId.val(0);
		});

		cancelFamilyBtn.on('click', function(ev) {
			addFamilyForm.hide();
			addFamilyForm[0].reset();
			cargaAction.val('I');
			cargaId.val(0);
		});

		editFamilyBtn.on('click', function(ev) {
			var currentCargaIndex	= $(this).attr('data-carga-index'),
				currentCargaRut 	= $('#' + currentCargaIndex + '_Carga_Rut_Paciente').val(),
				currentCargaFecha 	= $('#' + currentCargaIndex + '_Carga_Fechanac_Paciente').val(),
				currentCargaName 	= $('#' + currentCargaIndex + '_Carga_Nombre_Paciente').val(),
				currentCargaApepat 	= $('#' + currentCargaIndex + '_Carga_Apepat_Paciente').val(),
				currentCargaApemat 	= $('#' + currentCargaIndex + '_Carga_Apemat_Paciente').val(),
				currentCargaSex 	= $('#' + currentCargaIndex + '_Carga_Sexo_Paciente').val(),
				currentCargaRel 	= $('#' + currentCargaIndex + '_Carga_Parentesco_Paciente').val(),
				currentCargaId 		= $('#' + currentCargaIndex + '_Carga_Id_Ambulatorio').val(),
				currentCargaPrev 	= $('#' + currentCargaIndex + '_Carga_Prevision_Paciente').val();

			addFamilyForm[0].reset();
			// Fill inputs
			cargaRut.val(currentCargaRut);
			cargaFecha.val(currentCargaFecha);
			cargaName.val(currentCargaName);
			cargaApepat.val(currentCargaApepat);
			cargaApemat.val(currentCargaApemat);
			cargaRelat.val(currentCargaRel);
			cargaPrev.val(currentCargaPrev);
			cargaId.val(currentCargaId);
			cargaAction.val('M');

			switch (currentCargaSex) {
				case 'FEMENINO':
				case 'femenino':
				case 'F':
				case 'f':
					cargaSexF.attr('checked', true);
					break;
				case 'MASCULINO':
				case 'masculino':
				case 'M':
				case 'm':
					cargaSexM.attr('checked', true);
					break;
			}

			addFamilyForm.show();
		});

	});
})(window, jQuery);
