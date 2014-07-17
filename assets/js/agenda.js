$( document ).ready(function() {
		
		//Activating the curren time list
		var currentId = $('select[name="available-days"]').val();
		$('#' + currentId).removeClass('hide');
		
		$('select[name="available-days"]').change(function (e){
			var current = $(this).val();
			$('.table').addClass('hide');
			$('#' + current).removeClass('hide');
			
		});
		
		$('.time-chooser').click(function (e){
			e.preventDefault();
			$('input[name="time"]').val($(this).attr('ct-time'));
			$('input[name="box"]').val($(this).attr('ct-box'));
			$('input[name="id_schedule"]').val($(this).attr('ct-schedule'));
			$('input[name="multiplicity"]').val($(this).attr('ct-multi'));
			
			var strDay = $('select[name="available-days"] option:selected').text() ;
			var dayParts = strDay.split(' - ');
			strDay = dayParts[1] + ' ' + dayParts[0];
			//console.log(strDay);

			$('#txt-time').html($(this).attr('ct-time'));
			$('#txt-date').html(strDay);
			
			$('#modal-confirmation').modal('show');
			
		});
		
		$( '.volver' ).click(function (e){ 
			window.history.back();
		});
		
		$('#confirma-ok').click(function (e){
			e.preventDefault();
			
			$('#form-agenda').submit();
		});

});
