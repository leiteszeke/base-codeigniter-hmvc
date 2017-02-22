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
					<h3><i class="fa fa-angle-right"></i> Permisos en Secciones</h3>
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
										<th class="text-center">Sección</th>
										<th class="text-center">Usuario</th>
										<th class="text-center" style="width: 100px;">Acciones</th>
									</tr>
								</thead>
								<tbody>
									{secciones}
										<tr>
											<td class="text-center">
                                                <img src="{base_url}data/upload/imagenes_secciones/{icono}" width="32" />
                                            </td>
											<td class="text-center">{seccion}</td>
											<td class="text-center">{usuario}</td>
											<td class="text-center">
												<button class="btn btn-sm btn-danger" onclick="eliminarPermiso({id_admin_secciones});"><i class="fa fa-trash-o"></i></button>
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
			function eliminarPermiso(id_permiso){
				var success = false;
				var error   = false;
				if(id_permiso > 0){
					$.ajax({
						url: base_url + '_ajaxadm/eliminarPermiso',
						data: {
							permiso: id_permiso,
							tipo: 'seccion'
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