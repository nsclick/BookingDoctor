$( document ).ready(function() {
		
	$('.anular-hora').click(function (e){
		e.preventDefault();
		
		var form_id = $( this ).attr( 'form-id' );
		var htmlBlock = $('#block-' + form_id ).html();
		$( '#hora-a-nular' ).html( htmlBlock );
		
		$( '#confirma-ok' ).attr('form-id', form_id);
		$('#modal-confirmation').modal('show');
		
	});

	$('.display-control').click(function (e){
		e.preventDefault();
		
		$('.display-control').removeClass( 'seleccionado' );
		$( this ).addClass( 'seleccionado' );
		
		var display = $( this ).attr( 'display' );
		
		$('p.hora').hide();
		
		switch( display ){
			case 'all':
				$('p.hora').show();
				break;
			case 'active':
				$('p.hora.past').hide();
				$('p.hora.active').show();
				break;
			case 'past':
				$('p.hora.active').hide();
				$('p.hora.past').show();
				break;
		}
	});
	
	$('#confirma-ok').click(function (e){
		e.preventDefault();
		
		var form_id = $( this ).attr( 'form-id' );
		$('#' + form_id ).submit();
		
	});
		
});

