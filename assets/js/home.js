$(function() {

	$('.input-group-addon').css( 'cursor', 'pointer' );
		
	$( ".input-group-addon" ).on( "click", function(e) {
		e.preventDefault();
		$(this).closest("form").submit();
	});
		
	$.getJSON( "assets/js/areas.json", function( data ) {
		var items = [];
		
		for(x=0; x < data.length; x++){
			items.push(JSON.stringify( data[x] ));
		}

		$("#area-label").typeahead({ 
			source:items,
			highlighter: function(item) {
				return JSON.parse(item).name;
			},
			matcher: function (item) {
				return JSON.parse(item).name.toLocaleLowerCase().indexOf(this.query.toLocaleLowerCase()) != -1;
			},
			updater: function (item) {
				$( "#area" ).val(JSON.parse(item).value);
				return JSON.parse(item).name;
			}
		});
				
	});	

/*var items = [];
$("#areass option").each(function(){
	var item = {
		value: $(this).val(),
		name: $(this).text()
	};
	
	items.push(item);
});
console.log(JSON.stringify( items ));
*/

});
