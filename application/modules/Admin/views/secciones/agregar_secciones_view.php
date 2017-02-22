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
					<form style="background: #FFF; padding: 10px;" class="form col-md-8 col-md-offset-2" method="post" id="addSeccion">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="nombre">Nombre: </label>
                            <input class="form-control" type="text" name="nombre" id="nombre" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="url">URL: </label>
                            <input class="form-control" type="text" name="url" id="url" />
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label" for="menu">Mostrar en Menú: </label>
                            <br>
                            <input type="radio" name="menu" id="menu_si" value="1" />&nbsp;&nbsp;Sí
                            &nbsp;&nbsp;
                            <input type="radio" name="menu" id="menu_no" value="0" />&nbsp;&nbsp;No
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label" for="menu">Mostrar en Home: </label>
                            <br>
                            <input type="radio" name="home" id="home_si" value="1" />&nbsp;&nbsp;Sí
                            &nbsp;&nbsp;
                            <input type="radio" name="home" id="home_no" value="0" />&nbsp;&nbsp;No
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label" for="orden">Orden: </label>
                            <select class="form-control" name="orden" id="orden">
                                <option value="0">Seleccione Orden</option>
                                {orden}
                                    <option value="{id_orden}">{n_orden}</option>
                                {/orden}
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label" for="icono">Icono</label>
                            <br>
                            {iconos}
                                <img class="iconos" style="margin: 3px; cursor: pointer;" src="{base_url}data/upload/imagenes_secciones/{icono}" width="24" height="24" />
                            {/iconos}
                        </div>
                        <div class="form-group col-md-6 col-md-offset-6 text-right">
                            <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-check"></i>&nbsp;&nbsp;Crear</button>
                        </div>
                    </form>
			    </section>
			</section>
		</section>
		{footer}
		{scripts}
        <script type="text/javascript">
            var icono_seleccionado = false;

            $('.iconos').click(function(e){
                var seleccionado = $('.icono-seleccionado');
                var icono_select = seleccionado.attr('src');
                var icono_click  = $(this).attr('src');

                if(icono_select != undefined && (icono_click == icono_select)){
                    $('.iconos').removeClass('icono-seleccionado');
                    $('.iconos').css('filter', 'grayscale(0%)');
                    icono_seleccionado = false;
                }else{
                    $('.iconos').removeClass('icono-seleccionado');
                    $('.iconos').css('filter', 'grayscale(0%)');

                    $(this).addClass('icono-seleccionado');
                    $('.iconos').css('filter', 'grayscale(100%)');
                    $('.icono-seleccionado').css('filter', 'grayscale(0%)');
                    icono_seleccionado = icono_click;
                }
            })

            $('#addSeccion').submit(function(e){
                e.preventDefault();
                var success = false;
                var error   = false;
                var seccion = $('#nombre').val();
                var url     = $('#url').val();
                var orden   = $('#orden').val();
                var home    = $('input[type="radio"][name="home"]').prop('checked');
                var menu    = $('input[type="radio"][name="menu"]').prop('checked');
                var icono   = icono_seleccionado.replace(base_url + "data/upload/imagenes_secciones/", "");

                if(validaCampos('agregar-seccion')){
                    $.ajax({
                        url: base_url + '_ajaxadm/agregarSeccion',
                        data: {
                            nombre : seccion,
                            url    : url,
                            home   : home,
                            menu   : menu,
                            icono  : icono,
                            orden  : orden
                        },
                        type: 'POST',
                        success: function(res){
                            success = true;
                            res = $.parseJSON(res);

                            if(!res.error){
                                location.href = base_url + 'admin/secciones/listar';
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
        </script>
	</body>
</html>