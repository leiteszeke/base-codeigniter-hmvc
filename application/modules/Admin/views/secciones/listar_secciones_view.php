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
					<h3><i class="fa fa-angle-right"></i> Secciones</h3>
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
										<th class="text-center" style="width: 32px;">Icono</th>
										<th class="text-center">Nombre</th>
										<th>URL</th>
										<th class="text-center" style="width: 50px;">Orden</th>
										<th class="text-center" style="width: 50px;">Menú</th>
										<th class="text-center" style="width: 50px;">Home</th>
										<th class="text-center" style="width: 100px;">Acciones</th>
									</tr>
								</thead>
								<tbody>
									{secciones}
										<tr>
											<td class="text-center">
                                                <img src="{base_url}data/upload/imagenes_secciones/{icono}" width="32" />
                                            </td>
											<td class="text-center">{nombre}</td>
											<td>
                                                <a href="{base_url}admin/{friendly_url}">{base_url}admin/{friendly_url}</a>
                                            </td>
											<td class="text-center">{orden}</td>
											<td class="text-center">{menu}</td>
											<td class="text-center">{home}</td>
											<td class="text-center">
												<a href="{base_url}admin/secciones/editar/{id_admin_secciones}">
													<button class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></button>
												</a>
												<button class="btn btn-sm btn-danger" onclick="borrarSeccion({id_admin_secciones});"><i class="fa fa-trash-o"></i></button>
											</td>
										</tr>
									{/secciones}
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
			function borrarSeccion(id_seccion){
				if(confirm('Desea eliminar?')){
					var seccion = false;
					var error   = false;

					if(id_seccion > 0){
						$.ajax({
							url: base_url + '_ajaxadm/borrarSeccion',
						data: {
							seccion: id_seccion
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
			}
		</script>
	</body>
</html>