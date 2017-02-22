<!DOCTYPE html>
<html lang="ES">
    <head>
        {head}
    </head>
    <body>
        <div style="align-items: center; display: flex; height: 100%; justify-content: center; width: 100%;">
            <form class="form col-md-6 bg-info" id="configForm" method="post" style="align-self: center;">
                <h2 class="text-center">Instalación de Estructura HMVC</h2>
                <p> 
                    Estas a punto de intalar la Estructura HMVC de Sitio y Administrador del mismo. Para esto, necesitamos que configures algunas cosas.
                </p>
                <hr style="border: 1px dashed black;">
                <div id="paso1">
                    <h3>Paso 1 | Configuración de la Base de Datos</h3>
                    <div class="form-group">
                        <label class="control-label" for="db_host">Host de la Base de Datos</label>
                        <input class="form-control" type="text" name="db_host" id="db_host" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="db_user">Usuario de la Base de Datos</label>
                        <input class="form-control" type="text" name="db_user" id="db_user" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="db_pass">Contrase&ntilde;a de la Base de Datos</label>
                        <input class="form-control" type="password" name="db_pass" id="db_pass" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="db_name">Nombre de la Base de Datos</label>
                        <input class="form-control" type="text" name="db_name" id="db_name" />
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-info">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>