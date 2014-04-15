$(function() {

	$('#Fechanac_Paciente').datetimepicker({
		pickTime : false
	});


	jQuery("#registro").validationEngine('attach', {promptPosition:"inline", scroll:true});

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

	$('.space1 .sgte').click(function() {
		
		alert( $("#Fechanac_Paciente").validationEngine('validate') );
		$('#Fechanac_Paciente').validationEngine('showPrompt', 'This a custom msg');
		return true;
		
		$('.space1').removeClass('tabactive');
    	$('.space2').addClass('tabactive');
    	$('.taba').removeClass('tabactive');
    	$('.tabb').addClass('tabactive');
	});
	
	$('.space2 .sgte').click(function() {
		$('.space2').removeClass('tabactive');
    	$('.space3').addClass('tabactive');
    	$('.tabb').removeClass('tabactive');
    	$('.tabc').addClass('tabactive');
	});
	$('.space2 .ante').click(function() {
		$('.space2').removeClass('tabactive');
    	$('.space1').addClass('tabactive');
    	$('.tabb').removeClass('tabactive');
    	$('.taba').addClass('tabactive');
	});
	$('.space3 .ante').click(function() {
		$('.space3').removeClass('tabactive');
    	$('.space2').addClass('tabactive');
    	$('.tabc').removeClass('tabactive');
    	$('.tabb').addClass('tabactive');
	});
	/*$('.space3 .fin').click(function() {
    	$('.enviando').fadeIn('1500');
    	setTimeout(function() { $(".enviando p.uno").fadeIn(1500); })
    	setTimeout(function() { $(".enviando p.uno").fadeOut(1500); }, 3000)
    	setTimeout(function() { $(".enviando p.dos").fadeIn(1500); }, 4500)
	});*/

});
