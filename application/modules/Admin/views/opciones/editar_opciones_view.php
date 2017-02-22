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
                    {opcion}
                        <form style="background: #FFF; padding: 10px;" class="form col-md-8 col-md-offset-2" method="post" id="addOpcion">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="nombre">Nombre: </label>
                                <input class="form-control" type="text" name="nombre" id="nombre" value="{nombre}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="url">URL: </label>
                                <input class="form-control" type="text" name="url" id="url" value="{friendly_url}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="seccion">Sección: </label>
                                <select class="form-control" name="seccion" id="seccion">
                                    <option value="0">Seleccione Sección</option>
                                    {secciones}
                                        <option value="{id_admin_secciones}" {selected}>{nombre_seccion}</option>
                                    {/secciones}
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="orden">Orden: </label>
                                <select class="form-control" name="orden" id="orden">
                                    <option value="0">Seleccione Orden</option>
                                    {orden}
                                        <option value="{id_orden}" {selected}>{n_orden}</option>
                                    {/orden}
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="menu">Mostrar en Menú: </label>
                                <br>
                                <input type="radio" name="menu" id="menu_si" {menu_si} value="1" />&nbsp;&nbsp;Sí
                                &nbsp;&nbsp;
                                <input type="radio" name="menu" id="menu_no" {menu_no} value="0" />&nbsp;&nbsp;No
                            </div>
                            <div class="form-group col-md-6">
                                &nbsp;
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="icono">Icono</label>
                                <br>
                                {iconos}
                                    <img class="iconos {icono-seleccionado}" style="margin: 3px; cursor: pointer;" src="{base_url}data/upload/imagenes_secciones/{icono}" width="24" height="24" />
                                {/iconos}
                            </div>
                            <div class="form-group col-md-6 col-md-offset-6 text-right">
                                <input type="hidden" name="id" id="id" value="{id_admin_opciones}" />
                                <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-check"></i>&nbsp;&nbsp;Crear</button>
                            </div>
                        </form>
                    {/opcion}
                </section>
            </section>
        </section>
        {footer}
        {scripts}
        <script type="text/javascript">
            var icono_seleccionado = false;
            seleccionarIcono();

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
            });

            function seleccionarIcono(){
                var icono = $('.icono-seleccionado');
                var icono_click = icono.attr('src');
                $('.iconos').css('filter', 'grayscale(100%)');
                icono.css('filter', 'grayscale(0%)');
                icono_seleccionado = icono_click;
            }

            $('#addOpcion').submit(function(e){
                e.preventDefault();
                var success = false;
                var error   = false;
                var id      = $('#id').val();
                var nombre  = $('#nombre').val();
                var url     = $('#url').val();
                var seccion = $('#seccion').val();
                var orden = $('#orden').val();
                var menu    = $('input[type="radio"][name="menu"]').prop('checked');
                var icono   = icono_seleccionado.replace(base_url + "data/upload/imagenes_secciones/", "");

                if(validaCampos('agregar-opcion')){
                    $.ajax({
                        url: base_url + '_ajaxadm/editarOpcion',
                        data: {
                            id      : id,
                            nombre  : nombre,
                            url     : url,
                            seccion : seccion,
                            orden   : orden,
                            menu    : menu,
                            icono   : icono
                        },
                        type: 'POST',
                        success: function(res){
                            success = true;
                            res = $.parseJSON(res);

                            if(!res.error){
                                location.href = base_url + 'admin/opciones/listar';
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

            $('#seccion').change(function(e){
                e.preventDefault();
                var seccion = $('#seccion').val();
                
                $.ajax({
                    url: base_url + '_ajaxadm/getTotalOpciones',
                    data: {
                        seccion  : seccion
                    },
                    type: 'POST',
                    success: function(res){
                        success = true;
                        res = $.parseJSON(res);

                        if(!res.error){
                            $('#orden').html("");
                            var desde = parseInt(res['data']['tot']) + 1;
                            for(i = desde; i > 0; i--){
                                $('#orden').append($('<option>', {
                                    value: i,
                                    text: i 
                                }));
                            }
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
            })
        </script>
    </body>
</html>