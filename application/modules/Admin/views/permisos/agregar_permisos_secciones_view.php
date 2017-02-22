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
					<form style="background: #FFF; padding: 10px;" class="form col-md-8 col-md-offset-2" method="post" id="addSeccionPermisos">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="usuario">Usuario: </label>
                            <select class="form-control" name="usuario" id="usuario">
                                <option value="0">Seleccione Usuario</option>
                                {usuarios}
                                    <option value="{id_admin_user}">{username}</option>
                                {/usuarios}
                            </select>
                        </div>
                        <div class="form-group col-md-6"></div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <label class="control-label" for="secciones">Secciones: </label>
                            <br>
                            {secciones}
                                <div class="col-md-3">
                                    <input type="checkbox" name="secciones" id="perm_seccion_{id_admin_secciones}" />&nbsp;&nbsp;{nombre}
                                </div>
                            {/secciones}
                        </div>
                        <div class="form-group col-md-6 col-md-offset-6 text-right">
                            <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-check"></i>&nbsp;&nbsp;Dar Permisos</button>
                        </div>
                    </form>
			    </section>
			</section>
		</section>
		{footer}
		{scripts}
        <script type="text/javascript">
            $('#usuario').change(function(){
                var success = false;
                var error   = false;
                resetearCheckbox();

                $.ajax({
                    url: base_url + '_ajaxadm/getPermisosByUser',
                    data: {
                        id: $('#usuario').val(),
                        type: 'seccion'
                    },
                    type: 'POST',
                    success: function(res){
                        success = true;
                        res = $.parseJSON(res);

                        if(!res.error){
                            var secciones = res.data;

                            for(var i = 0; i < secciones.length; i++){
                                $('#perm_seccion_' + secciones[i].id_admin_secciones).prop('checked', true);
                            }
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
                });
            });

            $('#addSeccionPermisos').submit(function(e){
                e.preventDefault();
                var usuario  = $('#usuario').val();
                var permisos = [];
                var valores  = $('input[type="checkbox"][name="secciones"]');
                var success  = false;
                var error    = false;

                for(var i = 0; i < valores.length; i++){
                    if(valores[i].checked){
                        var id = valores[i].id;
                        id = id.replace("perm_seccion_", "");
                        permisos.push(id);
                    }
                }

                if(usuario > 0){
                    $.ajax({
                        url: base_url + '_ajaxadm/asignarPermisos',
                        data: {
                            user: usuario,
                            tipo: 'seccion',
                            valor: permisos
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
            })

            function resetearCheckbox(){
                $('input[type="checkbox"][name="secciones"]').prop('checked', false);
            }
        </script>
	</body>
</html>