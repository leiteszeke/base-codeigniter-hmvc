README ADMIN
------------
Implementación del ADMIN.
Con estructura armada y la base de datos cargada en su estado inicial.

1- Iniciar sesión con admin|admin.

2- Dar de alta Secciones / Luego Asignarle permisos de usuarios a esas Secciones.
	2a- Las secciones nuevas son los niveles más altos de agrupación de tareas por realizar. Por ejemplo: Bandas.
	
	2b- La opción de Orden en Secciones es el orden que se va a mostrar en pantalla al listarlos por un usuario.
	
	2c- La opción de Mostrar Menú es para mostrarlo o no en pantalla. Si el usuario tiene permisos podrá accederlo por URL.

3- Dar de alta Opciones / Luego Asignarle permisos de usuarios a esas Opciones.
	2a- Las opciones nuevas son los niveles más bajos de agrupación de tareas por realizar. Por ejemplo: Listar Bandas - Editar Bandas.
	
	2b- La opción de Orden en Opciones es el orden que se va a mostrar en pantalla al listarlos por un usuario.
	
	2c- La opción de Mostrar Menú es para mostrarlo o no en pantalla. Si el usuario tiene permisos podrá accederlo por URL.
	Nota: En este caso se pueden o bien deben configurar opciones de Edición o Eliminación como NO mostrados en Menú - estos casos se acceden por Ajax post y no hay URL que efectúe la acción.

4- Cada Sección creada debe corresponderse con un Controlador en el módulo Admin.
	4a- Si la sección lleva espacios, la clase controlador debe reemplazarlos por guiones bajos. Ejemplo "Quienes Somos" - "Quienes_somos.php". La clase tendrá como apertura "class Quienes_somos extends MX_Controller {".
	Un ejemplo de estructura de esta clase se observa en "admin_example/Quienes_somos.php.example".
	
	4b- En el constructor se debe incluir todos los modelos que se vayan a utilizar.
	Cada controlador deberá tener un modelo asociado, si tiene interacción con base de datos. Ejemplo de estructura "admin_example/Quienes_somos_model.php.example".
	
	4c- Cada vez que se quiera ingresar a una sección, por URL se debe ingresar a "admin/" el nombre de la sección. En caso de tener espacios se deben reemplazar por guiones medios. Ejemplo: admin/quienes-somos.
	Cada acceso a una sección está configurado por un route.
		$route['admin/(:any)'] = "Admin/listaropciones/$1";
	La sección que va definida como 1er parámetro luego del "admin/" pasa por una validación para conocer si la Sección fue definida por un controlador.
	De ser existente la sección se listarán las opciones, si las hay, habilitados por el usuario.
	
	4d- NOTA: Se debe modificar en cada invocación a "view" el nombre de la carpeta que contiene dichas vistas acorde al controlador que se está utilizando.

5- Cada Opción creada debe corresponderse con un Método del Controlador.
	5a- Si la opción lleva espacios, el método los quitará. Ejemplo "Listar Quienes Somos" - "listarQuienesSomos()".
	
	5b- Cada vez que se quiera ingresar a una opción, por URL se debe ingresar a "admin/[nombre de la sección]/" el nombre de la opción. En caso de tener espacios se deben reemplazar por guiones medios. Ejemplo: admin/quienes-somos/listar-quienes-somos.
	Cada acceso a una opción está configurado por un route.
		$route['admin/(:any)/(:any)'] = "Admin/abriropcion/$1";
	La opción que va definida como 2do parámetro luego del "admin/(:any)" pasa por una validación para conocer si la Opción fue definida por un controlador.
	De ser existente la opción, el validador (abriropcion) invocará al método friendly() del controlador de la Sección en cuestión.
	Si la opción existe y se tienen permisos se efectuará lo que el método indique.

	5c- En la estructura del example, se puede visualizar como se implementa el listar, agregar, editar y eliminar.
		5c1- En caso del listar, se prepara el contenido para que pueda paginarse si se supera el límite que se indica en el config (items_per_page).
		Mientras se recorre el contenido se preparan los links de Edición y de Eliminación. En este hay que modificar el nombre de la sección que se está utilizando y el parámetro id de la tabla en base de datos en cuestión.
		
		5c2- En caso del agregar, se prepara el formulario. Hay en principio 2 opciones - que haya post (se envió el formulario), que no haya post (se accede al formulario en blanco).
		En caso de acceder a formulario en blanco se define un hash aleatorio para hacer único el formulario y no se almacenen registros repetidos o bien se produzca un bucle y se debe definir la "accion", o sea la URL de envío del formulario.
		En caso de enviar el formulario completo se valida que el hash corresponda, y en caso positivo se preparan validaciones del lado del servidor que sean necesarias.
		Se prepara un Array que contenga los campos de la base de datos y los valores que estos van a contener, para así enviarlo al "Model" que se encarga de hacer la inserción. Por último se define un mensaje de éxito o error.
		En caso que cualquier validación al envío sea errónea se vuelve a cargar un formulario en blanco.
		
		5c3- En caso de acceder a la edición o eliminación de un registro se lo accede por URL:
			$route['admin/(:any)/(:any)/(:any)'] = "Admin/abriropcion/$1/$2";
		Se envía como 3er parámetro de la ruta.
		Este parámetro puede ser el ID del registro o el Friendly si fue definido. Este valor se lo compara (y puede ser modificado) desde el modelo => método buscarByFriendly().
		Si el usuario no tiene permisos para cualquiera de estas 2 acciones se lo redireccionará a una pantalla que le indicará la situación. Esto lo define el Admin/abriropcion.
		* En caso de Editar se procede de manera similar al Agregar.
		* En caso de Eliminar se accede por Ajax Post desde la vista y se le envía el ID de registro de la tabla de la base de datos.
			** Al método eliminar del ajax tendrá 2 parámetros: accion y valor.
				Accion será: "eliminar", "habilitar", "deshabilitar", u otro que se le defina desde el controlador.
				Valor será un ID.
				
6- Cada Opción tendrá relacionada una vista en la carpeta views del módulo.
	Por cada sección habrá una carpeta que agrupe las vistas.
	Por cada opción habrá una vista, cuyos nombres son definidos por el controlador. Ejemplo: quienes_somos_view.php para listar y quienes_somos_agregar_view.php para el formulario de agregar/editar.
	
	6a- Las vistas de listados debe tener en show_contents el nombre del array que registra el contenido. Suele ser el mismo nombre que la base de datos, pero hay que checkearlo, esto se define en el modelo. Ejemplo: "admin_example/quienes_somos_view.php.example"
	
	6b- Las vistas de formularios debe modificar el nombre del ID de registro de la tabla de la base de datos, cada campo que se relacione con un campo de la base de datos no debe tener obligatorio como "name" el mismo que el de la base de datos, debido a que el controlador puede modificarlo antes del envío insert/update. Ejemplo: "admin_example/quienes_somos_agregar_view.php.example"