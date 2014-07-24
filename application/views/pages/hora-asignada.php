<div id="wrapper" class="confirmacion">
	<h2><b>Reserva de hora existosa.</h2>
	<div id="PrintArea">
	<h2>Informaci&oacute;n de la reserva</h2>
	<table class="table">
		<thead>
			<tr>
				<th> C&oacute;digo Correlativo de Reserva </th>
				<th><?php echo $result['CORRELATIVO_RESERVA']?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Paciente</td>
				<td><?php echo $result['NOMBRE_PACIENTE']?></td>
			</tr>
			<tr>
				<td>Profesional</td>
				<td><?php echo $result['NOMBRE_PROFESIONAL']?></td>
			</tr>
			<tr>
				<td>&Aacute;rea</td>
				<td><?php echo $result['ESPECIALIDAD']?></td>
			</tr>
			<tr>
				<td>Fecha</td>
				<td><?php echo $result['FECHA_RESERVA']?></td>
			</tr>
			<tr>
				<td>Hora</td>
				<td><?php echo $result['HORA_RESERVA']?></td>
			</tr>
			<tr>
				<td>Isapre</td>
				<td><?php echo $result['ISAPRE']?></td>
			</tr>
			<tr>
				<td>Direcci&oacute;n de atenci&oacute;n</td>
				<td><?php echo $company_address?></td>
			</tr>
		</tbody>
	</table>
	<h2>Valor de los servicios</h2>
	<p style="font-weight:bold">
		Tu previsi&oacute;n de salud tiene convenio con Cl&iacute;nica D&aacute;vila para consultas m&eacute;dicas.
		<br />
		El valor puede variar dependiendo del plan que tengas con tu Isapre.
	</p>
	<table class="table">
		<thead>
			<tr>
				<th>C&oacute;digo</th>
				<th>Descripci&oacute;n</th>
				<th>Valor</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $result['COD_PRESTACION']?></td>
				<td><?php echo $result['ESPECIALIDAD']?></td>
				<td>$<?php echo number_format ( $result['MONTO_PAGAR'] , 0, "," , "." ) ?></td>
			</tr>
			<tr>
				<td colspan="2">Total</td>
				<td>$<?php echo number_format ( $result['MONTO_PAGAR'] , 0, "," , "." ) ?></td>
			</tr>
		</tbody>
	</table>
	<div class="alert">
		<b>Estimado paciente: </b>Te recordamos llegar con 15 minutos de anticipación. Muchas gracias.
	</div>
	</div>
	<!-- END printable area -->
	
	<div style="text-align:center">
		<button type="button" class="btn btn-default" id="print-doc">
			Imprimir
		</button>
	</div>
</div>
