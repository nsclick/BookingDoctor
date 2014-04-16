$(function() {

	$('#Fechanac_Paciente').datetimepicker({
		pickTime : false
	});


	jQuery("#registro1").validationEngine('attach', {promptPosition:"inline", scroll:false});
	jQuery("#registro2").validationEngine('attach', {promptPosition:"inline", scroll:false});
	jQuery("#registro3").validationEngine('attach', {promptPosition:"inline", scroll:false});

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
		
		if( $("#registro1").validationEngine('validate') ){
			$('.space1').removeClass('tabactive');
			$('.space2').addClass('tabactive');
			$('.taba').removeClass('tabactive');
			$('.tabb').addClass('tabactive');		
		}		
	});
	
	$('.space2 .sgte').click(function() {
		if( $("#registro2").validationEngine('validate') ){
			$('.space2').removeClass('tabactive');
			$('.space3').addClass('tabactive');
			$('.tabb').removeClass('tabactive');
			$('.tabc').addClass('tabactive');
		}
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
	
	$('.space3 .fin').click(function(e) {
		if( $("#registro3").validationEngine('validate') ){
			
			$('.enviando').fadeIn('1500');
			$(".enviando p.uno").fadeIn();
			
			//Sending data
			var queryString1 = $('#registro1').formSerialize(); 
			var queryString2 = $('#registro2').formSerialize(); 
			var queryString3 = $('#registro3').formSerialize(); 
			var queryString = queryString1 + '&' + queryString2 + '&' + queryString3;
			
			$.post(site_url + '/registro/guardar', queryString,function( data ) {
				//console.log( data ); // 2pm
				if(data.state){
					$(".enviando p.uno").fadeOut();
					$(".enviando p.dos").html('Error al crear el registro, intente mas tarde.');
					$(".enviando p.dos").fadeIn(1500);
					//setTimeout(function() { $(".enviando").fadeOut(1500); }, 4000);
					//$(".enviando p.dos").fadeOut();
					return false;
				}
				
				$(".enviando p.uno").fadeOut(1500);
				setTimeout(function() { $(".enviando p.dos").fadeIn(1500); }, 1500);
			}, "json"); 

			//setTimeout(function() { $(".enviando p.uno").fadeIn(1500); })
			//setTimeout(function() { $(".enviando p.uno").fadeOut(1500); }, 3000)
			//setTimeout(function() { $(".enviando p.dos").fadeIn(1500); }, 4500)
		}
	});

});
