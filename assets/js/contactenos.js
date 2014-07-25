$(function() {

	jQuery("#contacto").validationEngine('attach', {promptPosition:"inline", scroll:false});

	
	$('#enviar').click(function(e) {
		if( $("#contacto").validationEngine('validate') ){
			
			$("#contacto").submit();
			
		}
	});

});
