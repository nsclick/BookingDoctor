<?php
/**
 * 5A1 : Día lleno
 * 000 : No hay horas
 * otro: Si hay horas?
 */
?>
<?php
	// var_dump($agenda['pfa']);
	$t = strtotime( substr($agenda['pfa'], 0, 16) );
	$d = date('Y m d h:i:s', $t);

	$agenda_days = array();
?>
<?php
	$splitted_agenda_strings = str_split ( $agenda['a'], 3 );

	// var_dump(count($splitted_agenda_strings));

	function parse_day_state ( $c ) {
		switch ( $c ) {
			case '5A1':
				return false; // Everything is reserved
				break;
			case '000':
				return false; // Not available
				break;
			default:
				return true; // Available
				break;
		}
	}

	foreach ( $splitted_agenda_strings as $index => $splitted_agenda_string ) {
		$agenda_days[$index] = array(
			'code'	=> $splitted_agenda_string,
			'state'	=> parse_day_state ($splitted_agenda_string),
			'time'	=> strtotime( '+' . $index . 'day', $t )
		);
		$agenda_days[$index]['date'] = date ( 'Y m d h:i:s',  $agenda_days[$index]['time']);
		$agenda_days[$index]['cls']	 = date ( 'D M d Y',  $agenda_days[$index]['time']);

		//if ($agenda_days[$index]['state'])
			//var_dump($agenda_days[$index]);
	}

	ob_start();
?>
	(function(w, $, undefined) {
		w.calendar_agenda_days = <?php echo json_encode($agenda_days); ?>;
		
		w.getProfesionalAgenda = function(data, fn) {
			var options = {
				ce: 	'<?php echo $agenda['ce']; ?>',
				cp: 	'<?php echo $agenda['cp']; ?>',
				cu: 	'<?php echo $agenda['cu']; ?>',
				appp: 	'<?php echo $agenda['appp']; ?>',
				corr: 	'<?php echo $agenda['corr']; ?>'
			};
			
			$.extend(options, data);
			
			jQuery.ajax('/nsclick/davila/reservadehoras/agenda/getagendaprofesional', {
				data: options,
				type: 'GET',
				dataType: 'json',
				success: function(r) {
					console.log(r);
					if (typeof(fn) == 'function') {
						fn(r);
					}
				}
			})
		};

	})(window, jQuery);
<?php
	$agenda_days_script = ob_get_contents();
	ob_clean();
	$this->template->add_js( $agenda_days_script, 'embed' );
?>
<div class="agendamed">
	<h1>Horas Disponibles</h1>
	<button type="button" class="btn btn-default volver"><span class="glyphicon glyphicon-circle-arrow-left"></span> Volver a Resultados</button>
	<h4>Nombre del Doctor</h4>
	<p>A continuaci&oacute;n se muestras las horas disponbles para el profesional seleccionado. Puede ver los d&iacute;as disponibles en el men&uacute; despleglable. <b>Se muestran exclusivamente los d&iacute;as y horas disponibles.</b></p>
	<table class="table">
		<thead>
  			<tr>
     			<th>
     				<select>
						<option value="volvo">Lunes 17 de Abril</option>
					  	<option value="saab">Martes 18 de Abril</option>
					  	<option value="mercedes">Jueves 20 de Abril</option>
					</select>
				</th>
  			</tr>
 		</thead>
 		<tbody>
  			<tr>
     			<td><a href="#">14:00 <span class="glyphicon glyphicon-ok-sign"></span> <span>Agendar</span></a></td>
     		</tr>			<tr>
     			<td><a href="#">14:30 <span class="glyphicon glyphicon-ok-sign"></span> <span>Agendar</span></a></td>
     		</tr>
     		<tr>
     			<td><a href="#">15:00 <span class="glyphicon glyphicon-ok-sign"></span> <span>Agendar</span></a></td>
     		</tr>
     		<tr>
     			<td><a href="#">16:00 <span class="glyphicon glyphicon-ok-sign"></span> <span>Agendar</span></a></td>
     		</tr>
     		<tr>
     			<td><a href="#">17:00 <span class="glyphicon glyphicon-ok-sign"></span> <span>Agendar</span></a></td>
  			</tr>
 		</tbody>
	</table>
	<button type="button" class="btn btn-default volver"><span class="glyphicon glyphicon-circle-arrow-left"></span> Volver a Resultados</button>
</div>
<script>
	$( document ).ready(function() {
		
		$( '.volver' ).click(function (e){ 
			window.history.back();
		});

	});
</script>