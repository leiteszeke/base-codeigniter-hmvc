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
					<h3><i class="fa fa-angle-right"></i> Agregar Permisos en Opciones</h3>
					{breadcrumb}
					{error}
						<div>{mensaje}</div>
					{/error}
					<form style="background: #FFF; padding: 10px;" class="form col-md-8 col-md-offset-2" method="post" id="addOpcionPermisos">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="usuario">Usuario: </label>
                            <select class="form-control" name="usuario" id="usuario">
                                <option value="0">Seleccione Usuario</option>
                                {usuarios}
                                    <option value="{id_admin_user}">{username}</option>
                                {/usuarios}
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="usuario">Secci&oacute;n: </label>
                            <select class="form-control" name="secciones" id="secciones">
                                <option value="0">Seleccione Sección</option>
                                {secciones}
                                    <option value="{id_admin_secciones}">{nombre}</option>
                                {/secciones}
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <label class="control-label" for="opciones">Opciones: </label>
                        </div>
                        <div class="form-group col-md-12" id="lista_opciones"></div>
                        <div class="clearfix"></div>
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
            function getPermisos(){
                var usuario = $('#usuario').val();
                var seccion = $('#secciones').val();
                var success = false;
                var error   = false;
                resetearCheckbox();

                $.ajax({
                    url: base_url + '_ajaxadm/getPermisosByUser',
                    data: {
                        id: usuario,
                        type: 'opcion'
                    },
                    type: 'POST',
                    success: function(res){
                        success = true;
                        res = $.parseJSON(res);

                        if(!res.error){
                            var opciones = res.data;

                            for(var i = 0; i < opciones.length; i++){
                                $('#perm_opcion_' + opciones[i].id_admin_opciones).prop('checked', true);
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
            }

            $('#usuario').change(getPermisos);

            $('#secciones').change(function(){
                var success = false;
                var error   = false;
                var seccion = $('#secciones').val();
                resetearCheckbox();
                 $('#lista_opciones').html("");
                
                if(seccion > 0){
                    $.ajax({
                        url: base_url + '_ajaxadm/getOpcionesBySeccion',
                        data: {
                            seccion: seccion
                        },
                        type: 'POST',
                        success: function(res){
                            success = true;
                            res = $.parseJSON(res);

                            if(!res.error){
                                var opciones = res.data;
                                var html = '';

                                for(var i = 0; i < opciones.length; i++){
                                    html += '<div class="col-md-6">';
                                        html += '<input type="checkbox" name="opciones" id="perm_opcion_' + opciones[i].id_admin_opciones + '" />';
                                        html += '&nbsp;&nbsp;' + opciones[i].nombre;
                                    html += '</div>';
                                }

                                $('#lista_opciones').html(html);
                                getPermisos();
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

            $('#addOpcionPermisos').submit(function(e){
                e.preventDefault();
                var usuario  = $('#usuario').val();
                var permisos = [];
                var valores  = $('input[type="checkbox"][name="opciones"]');
                var success  = false;
                var error    = false;

                for(var i = 0; i < valores.length; i++){
                    if(valores[i].checked){
                        var id = valores[i].id;
                        id = id.replace("perm_opcion_", "");
                        permisos.push(id);
                    }
                }

                if(usuario > 0){
                    $.ajax({
                        url: base_url + '_ajaxadm/asignarPermisos',
                        data: {
                            user: usuario,
                            tipo: 'opcion',
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
                $('input[type="checkbox"][name="opciones"]').prop('checked', false);
            }
        </script>
	</body>
</html>