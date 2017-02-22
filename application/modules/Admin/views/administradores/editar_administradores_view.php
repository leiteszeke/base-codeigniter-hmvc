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
                    {administrador}
                        <form style="background: #FFF; padding: 10px;" class="form col-md-8 col-md-offset-2" method="post" id="editUser">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="nombre">Nombre: </label>
                                <input class="form-control" type="text" name="nombre" id="nombre" value="{first_name}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="apellido">Apellido: </label>
                                <input class="form-control" type="text" name="apellido" id="apellido" value="{last_name}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="usuario">Usuario: </label>
                                <input class="form-control" type="text" name="usuario" id="usuario" value="{username}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="email">Email: </label>
                                <input class="form-control" type="email" name="email" id="email" value="{email_address}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="password">Contrase&ntilde;a: </label>
                                <input class="form-control" type="password" name="password" id="password" />
                            </div>
                            <div class="form-group col-md-6 col-md-offset-6 text-right">
                                <input type="hidden" name="id" id="id" value="{id_admin_user}" />
                                <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-check"></i>&nbsp;&nbsp;Editar</button>
                            </div>
                        </form>
                    {/administrador}
			    </section>
			</section>
		</section>
		{footer}
		{scripts}
		<script type="text/javascript">
			$('#editUser').submit(function(e){
				e.preventDefault();
				var success  = false;
				var error    = false;
				var id       = $('#id').val();
				var nombre   = $('#nombre').val();
				var apellido = $('#apellido').val();
				var usuario  = $('#usuario').val();
				var email    = $('#email').val();
				var password = $('#password').val();

				if(validaCampos('editar-administrador')){
					$.ajax({
						url: base_url + '_ajaxadm/editarAdministrador',
						data: {
                            id       : id,
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