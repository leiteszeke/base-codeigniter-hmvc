// validaCampos(string) valida los campos del formulario pasado como parametro.
function validaCampos(formulario){
    // fields[string, string, ?string] : Este array espera los valores id, type y msj a utilizarse en el proceso de validación.
    var fields = [];
    var resp   = true;
    var msj    = "";
    
    switch(formulario){
        case "agregar-seccion":
            fields.push({id: 'nombre', type: 'text', msj: 'Debes completar con el nombre de la sección.'});
            fields.push({id: 'url', type: 'text', msj: 'Debes completar con la url de la sección.'});
            break;
    }

    // Itera sobre fields[] y segun su tipo aplica la validación correspondiente y escribe el mensaje de error en caso
    // de encontrarse alguno.
    // Por el momento solo soporta [text, password, email (sin validación de formato), number, select].
    for(var i = 0; i < fields.length; i++){
        var field = $('#' + fields[i].id);

        if(fields[i].type == "text" || fields[i].type == "password" || fields[i].type == "email"){
            if(field == null || field == undefined || field.val() == ""){
                resp = false;
                if(fields[i].msj != undefined && fields[i].msj != ""){
                    msj += fields[i].msj;
                    msj += '<br>';
                }
            }   
        }

        if(fields[i].type == "number"){
            if(field == null || field == undefined || field.val() == ""){
                resp = false;
                if(fields[i].msj != undefined && fields[i].msj != ""){
                    msj += fields[i].msj;
                    msj += '<br>';
                }
            }
        }

        if(fields[i].type == "select"){
            if(field == null || field == undefined || field.val() <= 0){
                resp = false;
                if(fields[i].msj != undefined && fields[i].msj != ""){
                    msj += fields[i].msj;
                    msj += '<br>';
                }
            }
        }
    }

    // Si hay error, muestro los mensajes correspondientes.
    if(!resp){
        alert(msj);
    }
    
    return resp;
}