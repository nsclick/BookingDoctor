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

	$('#sgte1').click(function() {
		
		if( $("#registro1").validationEngine('validate') ){
			$('#registro1').removeClass('tabactive');
			$('#registro2').addClass('tabactive');
			$('.taba').removeClass('tabactive');
			$('.tabb').addClass('tabactive');		
		}		
	});
	
	$('#sgte2').click(function() {
		if( $("#registro2").validationEngine('validate') ){
			$('#registro2').removeClass('tabactive');
			$('#registro3').addClass('tabactive');
			$('.tabb').removeClass('tabactive');
			$('.tabc').addClass('tabactive');
		}
	});
	$('#ante2').click(function() {
		$('#registro2').removeClass('tabactive');
    	$('#registro1').addClass('tabactive');
    	$('.tabb').removeClass('tabactive');
    	$('.taba').addClass('tabactive');
	});
	$('#ante3').click(function() {
		$('#registro3').removeClass('tabactive');
		$('#registro2').addClass('tabactive');
		$('.tabc').removeClass('tabactive');
		$('.tabb').addClass('tabactive');
	});
	
	$('#fin3').click(function(e) {
		if( $("#registro3").validationEngine('validate') ){
			
			$('.enviando').fadeIn('1500');
			$(".enviando p.uno").fadeIn();
			
			//Sending data
			var queryString1 = $('#registro1').formSerialize(); 
			var queryString2 = $('#registro2').formSerialize(); 
			var queryString3 = $('#registro3').formSerialize(); 
			var queryString = queryString1 + '&' + queryString2 + '&' + queryString3;
			
			$.post(site_url + '/registro/guardar', queryString,function( data ) {
				$(".enviando p.uno").fadeOut();
				if(!data.state){
					$(".enviando p.error").html('Error al crear el registro, intente más tarde.');
					$(".enviando p.error").fadeIn(1500);
					setTimeout(function() { 
						$(".enviando p.error").fadeOut();
						$(".enviando").fadeOut(1500); 
					}, 4000);
					return false;
				}
				
				setTimeout(function() { $(".enviando p.dos").fadeIn(1500); }, 1500);
			}, "json"); 

			//setTimeout(function() { $(".enviando p.uno").fadeIn(1500); })
			//setTimeout(function() { $(".enviando p.uno").fadeOut(1500); }, 3000)
			//setTimeout(function() { $(".enviando p.dos").fadeIn(1500); }, 4500)
		}
	});

});
