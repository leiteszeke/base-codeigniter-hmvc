<!DOCTYPE html>
<html lang="es">
	<head>
		{head}
	</head>
	<body>
		<section id="container">
			{header}
			{menu}
			<section id="main-content">
				<section class="wrapper site-min-height">
					<h3>
						<i class="fa fa-angle-right">
						</i>Opciones
					</h3>
					{breadcrumb}
					<div class="row mt">
						<div class="col-lg-12">
							<div class="row">
								{sinopciones}
								<p>
									A&uacute;n no hay opciones activas.
								</p>
								{/sinopciones}

								{opciones}
								<div class="col-lg-4 col-md-4 col-sm-4 mb">
										<a href="{base_url}admin/{seccion}/{friendly_url}">
										<div class="white-panel pn">
											<div class="sub-pn">
												<h3>{seccion}</h3>
												<p><img src="{base_url}data/upload/imagenes_secciones/{icono}" alt="{friendly_url}"></p>
												<p>{nombre}</p>
											</div>
										</div>
									</a>
								</div>
								{/opciones}
							</div>
						</div>
					</div>
				</section>
			</section>
		</section>
		{footer}
		{scripts}
	</body>
</html>