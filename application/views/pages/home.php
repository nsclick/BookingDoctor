<div class="webhome"><h1>Reserva de Horas</h1>

<ul>
	<li>Si conoces el apellido del médico con el cual te quieres atender, escríbelo en el espacio asignado.</li>
	<li>Si no tienes médico definido, elige el área en la que te quieres atender.</li>
</ul>

<?php $attributes = array('role' => 'form'); ?>
<?php echo form_open('buscarmedico', $attributes); ?>
  <div class="form-group">
    <label for="apellido">Apellido del profesional</label>
    
	<div class='input-group'>
    	<input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el apellido del profesional">
        <span class="input-group-addon" >
        	<span class="glyphicon glyphicon-search"></span>
        </span>
    </div> 
  </div>  
</form>

<?php $attributes['id'] = 'buscaarea'; ?>
<?php echo form_open('buscarmedico', $attributes); ?>
	<input type="hidden" name="area" id="area" value="">
  <div class="form-group">
    <label for="area-label">Área</label>
	<div class='input-group'>
    	<input type="text" class="form-control" id="area-label" name="area-label" placeholder="Ingrese el área">
        <span class="input-group-addon" >
        	<span class="glyphicon glyphicon-search"></span>
        </span>
    </div> 
	
  </div>
</form>

<p>
Para anular horas reservadas anteriormente, haz click <a href="<?php echo base_url("anulacion/");?>">aqu&iacute;</a>.<br>
Para consultar por horas reservadas, haz click <a href="<?php echo base_url("consulta/");?>">aqu&iacute;</a>.
</p>

<p>Para recibir información de Clínica Dávila sobre temas de salud y otros, en tu correo electrónico, presiona <a href="javascript:void(0)" id="emailme" data-toggle="modal" data-target="#myModal">aqu&iacute;</a>.</p>



 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Registra tu correo electrónico</h4>
      </div>
	  <form method="POST" role="form">
      <div class="modal-body">
		  <div class="form-group">
		    <label for="apellido">Correo Electrónico</label>
		    <input type="email" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el apellido">
		  </div>  
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Enviar</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>