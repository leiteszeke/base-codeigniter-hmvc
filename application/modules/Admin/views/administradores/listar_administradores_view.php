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
					{noencontrado}
						<div class="row mt">
							<div class="col-lg-12">
								<p>Usuarios no encontrados.</p>
							</div>
						</div>
					{/noencontrado}
					{encontrado}
						<div class="row mt table-responsive">
							<table class="table table-striped" border="1">
								<thead>
									<tr>
										<th>Nombre y Apellido</th>
										<th>Usuario</th>
										<th>Email</th>
										<th class="text-center" style="width: 100px;">Acciones</th>
									</tr>
								</thead>
								<tbody>
									{administradores}
										<tr>
											<td>{first_name} {last_name}</td>
											<td>{username}</td>
											<td>{email_address}</td>
											<td class="text-center">
												<a href="{base_url}admin/administradores/editar/{id_admin_user}"><button class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></button></a>
												<button class="btn btn-sm btn-danger" onclick="borrarAdministrador({id_admin_user});"><i class="fa fa-trash-o"></i></button>
											</td>
										</tr>
									{/administradores}
								</tbody>
							</table>
						</div>
					{/encontrado}
					<div class="col-md-12 text-center">
						{paginador}
					</div>
				</section>
			</section>
		{footer}
		{scripts}
		<script type="text/javascript">
			function borrarAdministrador(id){
				var success = false;
				var error   = false;

				if(id > 0){
					$.ajax({
						url: base_url + '_ajaxadm/borrarAdministrador',
						data: {
							id: id
						},
						type: 'POST',
						success: function(res){
							success = true;
							res = $.parseJSON(res);

							if(!res.error){
								location.reload();
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
			}
		</script>
	</body>
</html>