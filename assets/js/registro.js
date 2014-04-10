  $(function() {
    
    $('#Rut_Paciente').Rut({
		format_on: 	'keyup'
	});
		
    $('#datetimepicker1').datetimepicker({
    	pickTime: false
    });
    
    jQuery("#registro").validationEngine('attach'); 

	$.getJSON( "assets/js/comunas.json", function( data ) {
		var items = [];
		
		for(x=0; x < data.length; x++){
			items.push(JSON.stringify( data[x] ));
		}

		$("#Comuna_Paciente-label").typeahead({ 
			source:items,
			highlighter: function(item) {
				return JSON.parse(item).name;
			},
			matcher: function (item) {
				return JSON.parse(item).name.toLocaleLowerCase().indexOf(this.query.toLocaleLowerCase()) != -1;
			},
			updater: function (item) {
				//alert(JSON.parse(item).value);
				$( "#Comuna_Paciente" ).val(JSON.parse(item).value);
				return JSON.parse(item).name;
			}
		});
				
	});	       
       
/*var items = [];
$("#Comuna_Paciente option").each(function(){
	var item = {
		value: $(this).val(),
		name: $(this).text()
	};
	
	items.push(item);
});
console.log(JSON.stringify( items ));
*/
        
  });
