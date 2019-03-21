<?php 
		
	/**
	* DEPENDENCIAS
	*/
		session_start ();
		$session_control_config = array(
			// duracion (en segundos) de la sesion de un usuario durante inactividad
			'expiration_time' => 60 * 5,

			// ruta del archivo o texto que se mostrara cuando se alcanza el maximo de licencias
			'template_maximun_licences' => __DIR__ . "/" . "session_control_template.html",

			// el template a mostrar es un archivo?
			'template_is_file' => true,
		);

		// IMPORT MANAGER
		require_once "SessionControlManager.php";

		// CREATE AND RUN MANAGER
		(new SessionControlManager())->session_control();

 ?>


 <?php

	/* 
		CREATE TABLE licencia (
			[id] [int] IDENTITY(1,1) PRIMARY KEY NOT NULL,
			[tipo] varchar(300) NULL,
			[cantidad] [int] NULL,
			[inicio] [datetime] NOT NULL,
			[expiracion] [datetime] NOT NULL,
			[expirada] [int] NULL,
			[creacion] [datetime] NOT NULL,
			[actualizacion] [datetime] NOT NULL,
			[nomusuario] varchar(300) NULL,
			[codusuario] varchar(300) NULL,
			[observacion] varchar(MAX) NULL
		)
		GO
		ALTER TABLE [dbo].[licencia] ADD DEFAULT getdate() FOR [inicio]
		GO
		ALTER TABLE [dbo].[licencia] ADD DEFAULT getdate() FOR [creacion]
		GO
		ALTER TABLE [dbo].[licencia] ADD DEFAULT getdate() FOR [actualizacion]
		GO

		CREATE TABLE session_control (
			[id] [int] IDENTITY(1,1) PRIMARY KEY NOT NULL,
			[token] varchar(MAX) NOT NULL,
			[creacion] [datetime] NOT NULL,
			[actualizacion] [datetime] NOT NULL,
			[duracion] [int] NULL,
			[expiracion] [datetime] NOT NULL,
			[expirada] [int] NULL,
			[maquina] varchar(300) NULL,
			[user_agent] varchar(MAX) NULL,
			[nomusuario] varchar(300) NULL,
			[codusuario] varchar(300) NULL,
			[observacion] varchar(MAX) NULL
		)
		GO
		ALTER TABLE [dbo].[session_control] ADD DEFAULT getdate() FOR [creacion]
		GO
		ALTER TABLE [dbo].[session_control] ADD DEFAULT getdate() FOR [actualizacion]
		GO	
	*/

 ?>