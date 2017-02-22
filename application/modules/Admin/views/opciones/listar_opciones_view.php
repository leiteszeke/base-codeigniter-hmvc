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
					<h3><i class="fa fa-angle-right"></i> Opciones</h3>
					{breadcrumb}
					{error}
						<div>{mensaje}</div>
					{/error}

					<div class="row mt">
						<form action="{base_url}admin/{seccion}/{accion}" method="post" role="form">
							<div class="cont-formulario">
								<div class="header-form"><p>B&uacute;squeda</p></div>
								<div class="form-group row">
									<div class="col-md-2 col-xs-12">
										<label for="sch_seccion">Secci&oacute;n</label>
									</div>
									<div class="col-md-3 col-xs-12">
										<select class="form-control" id="sch_seccion" name="sch_seccion">
											<option value="">Seleccione</option>
											{secciones}
											<option value="{id_admin_secciones}" {sch_seccion}>{nombre}</option>
											{/secciones}
										</select>
									</div>
									<div class="col-md-3 col-xs-12 text-right">
										<input class="btn btn-primary" type="submit" name="Buscar" />
									</div>
								</div>
							</div>
						</form>
					</div>
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
										<th>Nombre</th>
										<th class="text-center" style="width: 50px;">Orden</th>
										<th class="text-center" style="width: 50px;">Menú</th>
										<th class="text-center" style="width: 100px;">Acciones</th>
									</tr>
								</thead>
								<tbody>
									{opciones}
										<tr>
											<td class="text-center">
                                                <img src="{base_url}data/upload/imagenes_secciones/{icono}" width="32" />
                                            </td>
											<td class="text-center">{seccion}</td>
											<td>
                                                <a href="{base_url}admin/{seccion}/{friendly_url}">{nombre}</a>
                                            </td>
											<td class="text-center">{orden}</td>
											<td class="text-center">{mostrar_menu}</td>
											<td class="text-center">
												<a href="{base_url}admin/opciones/editar/{id_admin_opciones}">
												<button class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></button>
												</a>
												<button class="btn btn-sm btn-danger" onclick="borrarOpcion({id_admin_opciones});"><i class="fa fa-trash-o"></i></button>
											</td>
										</tr>
									{/opciones}
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
			function borrarOpcion(id_opcion){
				if(confirm('Desea eliminar?')){
					var opcion = false;
					var error   = false;

					if(id_opcion > 0){
						$.ajax({
							url: base_url + '_ajaxadm/borrarOpcion',
							data: {
								opcion: id_opcion
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