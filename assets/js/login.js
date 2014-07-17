$(function() {
	
	jQuery("#login-form").validationEngine('attach', {promptPosition:"inline", scroll:false});
	
	$('#session-start').click(function(e) {
		if( $("#login-form").validationEngine('validate') ){
			var endPoint = $("#login-form").attr('action');

			$('.enviando').fadeIn('1500');
			$(".enviando p.uno").fadeIn();
			
			var queryString = $('#login-form').formSerialize();
			
			$.post( endPoint , queryString,function( data ) {
				$(".enviando p.uno").fadeOut();
				if(!data.state){
					$(".enviando p.error").html( data.message );
					$(".enviando p.error").fadeIn(1500);
					setTimeout(function() { 
						$(".enviando p.error").fadeOut();
						$(".enviando").fadeOut(1500); 
					}, 4000);
					return false;
				}
				
				$(".enviando p.dos").fadeIn(1500);
				
				setTimeout(function() { 
					location.href = site_url + '/' + $('input[name="redirect"]').val();
				}, 3500);
				
			}, "json");
			
			
		}

	});
});
