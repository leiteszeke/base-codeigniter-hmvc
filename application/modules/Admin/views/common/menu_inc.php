<div id="sidebar" class="nav-collapse ">
	<ul class="sidebar-menu" id="nav-accordion">
		<li class="sub-menu1">
			<a href="{base_url}admin/panel">
				<i class="fa fa-home" aria-hidden="true"></i>
				<span>
					Inicio
				</span>
			</a>
		</li>
		{menu_secc_opc}
			<li class="sub-menu" id="seccion_{id_admin_secciones}">
				<a href="javascript:;" class="{active}">
					<i class="fa fa-list"></i>
					<span>	{nombre} </span>
				</a>
				<ul class="sub" id="opciones_{id_admin_secciones}" style="{display}">
					{opciones_con_permiso}
						<li id="opcion_{id_admin_opciones}">
							<a href="{base_url}admin/{seccion}/{friendly_url}" id="link_{id_admin_opciones}">{nombre}</a>
						</li>
					{/opciones_con_permiso}
				</ul>
			</li>
		{/menu_secc_opc}
		<li class="sep-a"></li>
		<li class="sub-menu">
			<a href="{base_url}" target="_blank">
				<i class="fa fa-home">
				</i>
				<span>
					Ver sitio
				</span>
			</a>
		</li>
		<li class="sub-menu2">
			<a href="{base_url}admin/logout">
				<i class="fa fa-sign-out">
				</i>
				<span>
					Salir
				</span>
			</a>
		</li>
	</ul>
</div>