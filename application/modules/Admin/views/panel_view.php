<!DOCTYPE html>
<html lang="es">
	<head>
		{head}
	</head>
	<body>
		<section id="container" >
			{header}
			{menu}
			<section id="main-content">
				<section class="wrapper site-min-height">
					<h3><i class="fa fa-tags" aria-hidden="true"></i> Panel de Control</h3>
					{breadcrumb}
					<div class="col-lg-12">
						{sinsecciones}
							<div class="alert alert-warning">A&uacute;n no hay secciones activas.</div>
						{/sinsecciones}
						{secciones_home}
							<div class="col-lg-4 col-md-4 col-sm-4 bt-section">
								<a href="{base_url}admin/{friendly_url}">
									<div class="white-panel pn">
										<div class="sub-pn">
											<img src="{base_url}data/upload/imagenes_secciones/{icono}" alt="{friendly_url}">	
										</div>
										<h4 class="titulo-pest">{nombre}</h4>
									</div>
								</a>
							</div>
						{/secciones_home}
					</div>
				</section>
				<div class="clearfix"></div>
			</section>
		</section>
		{scripts}
	</body>
</html>