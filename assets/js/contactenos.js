$(function() {

	jQuery("#contacto").validationEngine('attach', {promptPosition:"inline", scroll:false});

	$.getJSON(base_url + "assets/js/comunas.json", function(data) {
		var items = [];

		for ( x = 0; x < data.length; x++) {
			items.push(JSON.stringify(data[x]));
		}

		$("#Comuna_Paciente-label").typeahead({
			source : items,
			highlighter : function(item) {
				return JSON.parse(item).name;
			},
			matcher : function(item) {
				return JSON.parse(item).name.toLocaleLowerCase().indexOf(this.query.toLocaleLowerCase()) != -1;
			},
			updater : function(item) {
				//alert(JSON.parse(item).value);
				$("#Comuna_Paciente").val(JSON.parse(item).value);
				return JSON.parse(item).name;
			}
		});

	});
	
	$('#enviar').click(function(e) {
		if( $("#contacto").validationEngine('validate') ){
			
			
			
		}
	});

});
