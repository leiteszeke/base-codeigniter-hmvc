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
					<h3>
						<i class="fa fa-angle-right"></i> 
					</h3>
					{breadcrumb}
					<div class="row mt">
						<div class="col-lg-12">
							<div class="row">
								{error}
								<h1>
									<span class="label label-info">{mensaje}</span>
								</h1>
								{/error}
							</div>
						</div>
					</div>
				</section>
			</section>
		</section>
		{footer}
		{scripts}
		<script type="text/javascript">
			{redireccionar}
			setTimeout(function(){
				location.href = '{url}';
			},1500);
			{/redireccionar}
		</script>
	</body>
</html>