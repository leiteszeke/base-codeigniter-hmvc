<!DOCTYPE html>
<html lang="es">
	<head>
		{head}
	</head>
	<body class="bg-login">
		<div id="login-page">
			<div class="container">
				<form class="form-login" action="{base_url}admin/validar_credenciales" method="post">
					<h2 class="title-blanco" >
						Inicie sesi&oacute;n
					</h2>
					<div class="login-wrap">
					<input autofocus="true" class="form-control input-login" type="text" id="username" name="username" placeholder="Username" required="true" />
					<br />
					<input class="form-control input-login" type="password" id="password" name="password" placeholder="Contrase&ntilde;a" required="true" />
					<br />
					<button class="btn btn-theme btn-block btn-ingresar" type="submit">
						<i class="fa fa-lock"></i>&nbsp;&nbsp;INGRESAR
					</button>
					<div class="registration">
						{error}
							<p class="alert-login">	{mensaje} </p>
						{/error}
					</div>
					<div class="login-social-link centered">
						<div class="registration">
							<img class="animated infinite pulse" src="{base_url}images/logo-0111.png" />
						</div>
					</div>
				</form>
			</div>
		</div>
		<div aria-hidden="true" aria-labelledby="myModalMsgLabel" role="dialog" tabindex="-1" id="myModalMsg" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header modal-header-danger">
						ALERTA
					</div>
					<div class="modal-body">
						<p>
							La confirmaci&oacute;n es distinta a la clave nueva.
						</p>
					</div>
					<div class="modal-footer centered">
						<button data-dismiss="modal" class="btn btn-theme04" type="button">
							Continuar
						</button>
					</div>
				</div>
			</div>
		</div>
		<script src="{base_url}assets/jquery/jquery-2.2.4.min.js"></script>
		<script src="{base_url}assets/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>