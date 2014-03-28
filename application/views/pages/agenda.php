<?php
/**
 * 5A1 : Día lleno
 * 000 : No hay horas
 * otro: Si hay horas?
 */
?>
<?php var_dump($agenda); ?>
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

		if ($agenda_days[$index]['state'])
			var_dump($agenda_days[$index]);
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
<div id="calendar"></div>