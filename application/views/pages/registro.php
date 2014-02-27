<h1>Registro</h1>

<!-- cada tres caracteres es un dia.

000 no hay horas

5A1 -- dia lleno

cualquier otra cosa disponible

fecha de inicio inicio de la agenda desde donde donde inicias -->


<p>Para registrarte ingresa los siguientes datos:</p>
<p>Al registrarte podrás acceder al servicio de <b>Reserva de Horas.</b></p>

<?php 
	$attributes = array(
	'id' => 'registro', 
	//'class' => 'form-inline'
); ?>
<?php echo form_open('registro', $attributes); ?>

<div class="form-group">
    <label for="rut">RUT*</label>
    <input type="text" class="form-control validate[required]" id="Rut_Paciente" name="Rut_Paciente" placeholder="Ej: 333333333-K" value="<?php echo set_value('Rut_Paciente'); ?>"> 
  </div>  
  <div class="form-group">
    <label for="Fechanac_Paciente">Fecha Nacimiento*</label>
  
	<div class='input-group date' id='datetimepicker1'>
    	<input type='date' class="form-control validate[required] text-input datepicker" id="Fechanac_Paciente" name="Fechanac_Paciente" data-format="DD/MM/YYYY" placeholder="DD/MM/YYYY">
        <span class="input-group-addon" >
        	<span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>    
  
  </div>  
  <div class="form-group">
    <label for="Nombre_Paciente">Nombres*</label>
    <input type="text" class="form-control validate[required]" id="Nombre_Paciente" name="Nombre_Paciente" placeholder="Ingrese su nombre">
  </div>  
  <div class="form-group">
    <label for="Apepat_Paciente">Primer Apellido*</label>
    <input type="text" class="form-control validate[required]" id="Apepat_Paciente" name="Apepat_Paciente" placeholder="Ingrese su primer apellido" data-validation-matches-match="email">
  </div>  
  <div class="form-group">
    <label for="Apemat_Paciente">Segundo Apellido</label>
    <input type="text" class="form-control" id="Apemat_Paciente" name="Apemat_Paciente" placeholder="Ingrese su segundo apellido">
  </div> 

  <div class="form-group">
    <label for="Direccion_Paciente">Dirección</label>
    <input type="text" class="form-control validate[required]" id="Direccion_Paciente" name="Direccion_Paciente" placeholder="Ingrese su dirección" >
  </div> 
  
  <div class="form-group">
    <label for="Comuna_Paciente">Comuna</label>
    <input type="hidden" id="Comuna_Paciente" name="Comuna_Paciente">
    <input type="text" class="form-control validate[required]" id="Comuna_Paciente-label" name="Comuna_Paciente-label" placeholder="Ingrese su comuna" >
  </div> 

  <div class="form-group">
    <label for="Ciudad_Paciente">Ciudad</label>
    <input type="text" class="form-control validate[required]" id="Ciudad_Paciente" name="Ciudad_Pacient" placeholder="Ingrese la cuidad" >
  </div> 

  <div class="form-group">
    <label for="Fono1_Paciente">Teléfono 1</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="prefijo_Fono1_Paciente" name="prefijo_Fono1_Paciente">
				<option value="2" selected="selected">2</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="41">41</option>
				<option value="42">42</option>
				<option value="43">43</option>
				<option value="45">45</option>
				<option value="51">51</option>
				<option value="52">52</option>
				<option value="53">53</option>
				<option value="55">55</option>
				<option value="57">57</option>
				<option value="58">58</option>
				<option value="61">61</option>
				<option value="63">63</option>
				<option value="64">64</option>
				<option value="65">65</option>
				<option value="67">67</option>
				<option value="71">71</option>
				<option value="72">72</option>
				<option value="73">73</option>
				<option value="75">75</option>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[required,custom[integer],minSize[7],maxSize[7]]" id="Fono1_Paciente" name="Fono1_Paciente" placeholder="Ingrese los 7 Digitos">
		</div>
	  </div> 
  </div> 

  <div class="form-group">
    <label for="Fono2_Paciente">Teléfono 2</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="prefijo_Fono2_Paciente" name="prefijo_Fono2_Paciente">
				<option value="2" selected="selected">2</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="41">41</option>
				<option value="42">42</option>
				<option value="43">43</option>
				<option value="45">45</option>
				<option value="51">51</option>
				<option value="52">52</option>
				<option value="53">53</option>
				<option value="55">55</option>
				<option value="57">57</option>
				<option value="58">58</option>
				<option value="61">61</option>
				<option value="63">63</option>
				<option value="64">64</option>
				<option value="65">65</option>
				<option value="67">67</option>
				<option value="71">71</option>
				<option value="72">72</option>
				<option value="73">73</option>
				<option value="75">75</option>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[custom[integer],minSize[7],maxSize[7]]" id="Fono2_Paciente" name="Fono2_Paciente" placeholder="Ingrese los 7 Digitos">
		</div>
	  </div> 
  </div>

  <div class="form-group">
    <label for="FonoMovil1">Celular 1</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="PrefMovil1" name="PrefMovil1">
				<option value="2" selected="selected">9</option>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[required,custom[integer],minSize[8],maxSize[8]]" id="FonoMovil1" name="FonoMovil1" placeholder="Ingrese los 8 Digitos">
		</div>
	  </div> 
  </div>

  <div class="form-group">
    <label for="FonoMovil2">Celular 2</label>
      <div class="row">
		<div class="col-lg-2">
			<select class="form-control" id="PrefMovil2" name="PrefMovil2">
				<option value="2" selected="selected">9</option>
			</select>
		</div>
		<div class="col-lg-8">
			<input type="text" class="form-control validate[custom[integer],minSize[8],maxSize[8]]" id="FonoMovil2" name="FonoMovil2" placeholder="Ingrese los 8 Digitos">
		</div>
	  </div> 
  </div>

  <div class="form-group">
    <label for="Email_Paciente">Email</label>
    <input type="text" class="form-control validate[required,custom[email]]" id="Email_Paciente" name="Email_Paciente" placeholder="Ingrese su email">
  </div> 

  <div class="form-group">
    <label for="Email_Paciente-confirma">Confirmar Email</label>
    <input type="text" class="form-control validate[required,equals[Email_Paciente]]" id="Email_Paciente-confirma" name="Email_Paciente-confirma" placeholder="Ingrese su email">
  </div> 

  <div class="form-group">
    <label>Sexo</label><br>
	<label class="radio-inline">
	  <input type="radio" id="Sexo_Paciente" name="Sexo_Paciente" value="M"> Masculuno
	</label>
	<label class="radio-inline">
	  <input type="radio" id="Sexo_Paciente" name="Sexo_Paciente" value="F"> Femenino
	</label>
  </div> 

  <div class="form-group">
    <label for="Prevision_Paciente">Previsión</label>
	<select id="Prevision_Paciente" name="Prevision_Paciente" class="form-control validate[required]">
			<option value="">-- SELECCIONE --</option>
			<option value="20001">FONASA</option>
			<option value="30101">ISAPRE BANMEDICA S.A.</option>
			<option value="32101">ISAPRE CHUQUICAMATA LTDA.</option>
			<option value="30501">ISAPRE COLMENA GOLDEN CROSS S.A.</option>
			<option value="30201">ISAPRE CONSALUD</option>
			<option value="30901">ISAPRE CRUZ BLANCA S.A.</option>
			<option value="32901">ISAPRE CRUZ DEL NORTE LTDA.</option>
			<option value="33301">ISAPRE FERROSALUD S.A.</option>
			<option value="31201">ISAPRE FUNDACION BANCO ESTADO</option>
			<option value="30120">ISAPRE FUNDACION SALUD EL TENIENTE</option>
			<option value="31101">ISAPRE ISTEL S.A.</option>
			<option value="30401">ISAPRE MAS VIDA S.A.</option>
			<option value="32001">ISAPRE NORMEDICA S.A.</option>
			<option value="30601">ISAPRE PROMEPART</option>
			<option value="30801">ISAPRE RIO BLANCO</option>
			<option value="31301">ISAPRE SAN LORENZO LTDA</option>
			<option value="33501">ISAPRE SFERA S.A.</option>
			<option value="30701">ISAPRE VIDA TRES S.A.</option>
			<option value="10001">PARTICULAR</option>
	</select>
  </div> 
  
  <br>
  <p>¿Cómo prefieres que te recordemos tu hora médica?</p>

  <div class="form-group">
    <label>A través de Mensaje de texto</label><br>
	<label class="radio-inline">
	  <input type="radio" name="SMS_notificacion" value="S" checked> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="SMS_notificacion" value="N"> No
	</label>
  </div> 

  <div class="form-group">
    <label>A través de correo electrónico</label><br>
	<label class="radio-inline">
	  <input type="radio" name="EMAIL_notificacion" value="S" checked> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="EMAIL_notificacion" value="N"> No
	</label>
  </div> 
  
  <p>Si luego de enviado el mensaje no confirmas la hora, te llamaremos para verificar la cita médica. </p><br/>

  <div class="form-group">
    <label>¿Quisiera recibir infocrmación de la clínica Dávila?</label><br>
	<label class="radio-inline">
	  <input type="radio" name="Op_InfoClinica" value="S" checked> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="Op_InfoClinica" value="N"> No
	</label>
  </div> 
  
  <br/>
  <p>Para poder reservar horas, consultar o anular horas reservadas, debes establecer una clave personal de máximo 8  digitos </p>
  <br/>

  <div class="form-group">
    <label for="Clave_Usuario">Clave</label>
    <input type="password" class="form-control validate[required]" id="Clave_Usuario" name="Clave_Usuario">
  </div> 
  <div class="form-group">
    <label for="Clave_Usuario-confirma">Confirmar Clave</label>
    <input type="password" class="form-control validate[required,equals[Clave_Usuario]]" id="Clave_Usuario-confirma" name="Clave_Usuario-confirma">
  </div> 
  
  <br/>
  <p>Escribe una pregunta y una respuesta para que en caso de olvidar tu clave, logres recuperarla.</p>
  
  <div class="form-group">
    <label for="Pregunta_Clave">Pregunta</label>
    <input type="text" class="form-control validate[required]" id="Pregunta_Clave" name="Pregunta_Clave" placeholder="Ingrese su pregunta clave">
  </div> 
  <div class="form-group">
    <label for="RespuestaClave">Respuesta</label>
    <input type="text" class="form-control validate[required]" id="RespuestaClave" placeholder="RespuestaClave">
  </div> 
  
  <br/><br/>
  <button type="submit" class="btn btn-primary">Registrar</button>
  <br/><br/>
  
</form>
