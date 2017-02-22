<!DOCTYPE html>
<html lang="es">
	<head>
		{head} <!-- Head -->
	</head>
	<body>
		<section id="container">
			{header}
			{menu}
			<section id="main-content">
				<section class="wrapper site-min-height">
					<h3><i class="fa fa-angle-right"></i> Usuarios</h3>
					{breadcrumb}
					{error}
						<div>{mensaje}</div>
					{/error}
					<form style="background: #FFF; padding: 10px;" class="form col-md-8 col-md-offset-2" method="post" id="addUser">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="nombre">Nombre: </label>
                            <input class="form-control" type="text" name="nombre" id="nombre" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="apellido">Apellido: </label>
                            <input class="form-control" type="text" name="apellido" id="apellido" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="usuario">Usuario: </label>
                            <input class="form-control" type="text" name="usuario" id="usuario" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="email">Email: </label>
                            <input class="form-control" type="email" name="email" id="email" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="password">Contrase&ntilde;a: </label>
                            <input class="form-control" type="password" name="password" id="password" />
                        </div>
                        <div class="form-group col-md-6 col-md-offset-6 text-right">
                            <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-check"></i>&nbsp;&nbsp;Crear</button>
                        </div>
                    </form>
			    </section>
			</section>
		</section>
		{footer}
		{scripts}
		<script type="text/javascript">
			$('#addUser').submit(function(e){
				e.preventDefault();
				var success  = false;
				var error    = false;
				var nombre   = $('#nombre').val();
				var apellido = $('#apellido').val();
				var usuario  = $('#usuario').val();
				var email    = $('#email').val();
				var password = $('#password').val();

				if(validaCampos('agregar-administrador')){
					$.ajax({
						url: base_url + '_ajaxadm/agregarAdministrador',
						data: {
							nombre   : nombre,
							apellido : apellido,
							usuario  : usuario,
							email    : email,
							password : password
						},
						type: 'POST',
						success: function(res){
							success = true;
							res = $.parseJSON(res);

							if(!res.error){
								location.href = base_url + 'admin/administradores/listar';
							}else{
								alert(res.message);
							}
						},
						error: function(err){
							error = true;
							alert(JSON.stringify(err.statusText));
						},
						complete: function(res){
							if(!success && !error){
								alert(JSON.stringify(res.statusText));
							}
						}
					})
				}
			})
		</script>
	</body>
</html>