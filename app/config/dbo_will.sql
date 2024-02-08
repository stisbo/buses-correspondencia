/*
 Navicat Premium Data Transfer

 Source Server         : Local Laptop
 Source Server Type    : SQL Server
 Source Server Version : 16001000
 Source Host           : localhost:1433
 Source Catalog        : correspondencia
 Source Schema         : dbo

 Target Server Type    : SQL Server
 Target Server Version : 16001000
 File Encoding         : 65001

 Date: 08/02/2024 14:47:05
*/


-- ----------------------------
-- Table structure for tblEnvio
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblEnvio]') AND type IN ('U'))
	DROP TABLE [dbo].[tblEnvio]
GO

CREATE TABLE [dbo].[tblEnvio] (
  [idEnvio] int  IDENTITY(1,1) NOT NULL,
  [id_usuario_envio] int  NULL,
  [id_usuario_recibe] int  NULL,
  [estado] varchar(80) COLLATE Modern_Spanish_CI_AS  NULL,
  [detalle_envio] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [fecha_envio] datetime DEFAULT getdate() NULL,
  [fecha_llegada] datetime  NULL,
  [observacion_llegada] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [nombre_origen] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [ci_origen] varchar(40) COLLATE Modern_Spanish_CI_AS  NULL,
  [id_lugar_origen] int  NULL,
  [nombre_destino] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [ci_destino] varchar(40) COLLATE Modern_Spanish_CI_AS  NULL,
  [id_lugar_destino] int  NULL,
  [codigo] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [celular_origen] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [celular_destino] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [fecha_estimada] datetime  NULL,
  [fecha_entrega] datetime  NULL,
  [id_usuario_entrega] int  NULL,
  [capturas] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblEnvio] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Usuario que registro el envio',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'id_usuario_envio'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Usuario que recibio el paquete',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'id_usuario_recibe'
GO

EXEC sp_addextendedproperty
'MS_Description', N'ENVIADO | RECIBIDO',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'estado'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Nombre de la persona',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'nombre_origen'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Id del lugar origen',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'id_lugar_origen'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Nombre de la persona que recibe',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'nombre_destino'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Codigo UNICO de envio',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'codigo'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Id del usuario que entrega el paquete',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'id_usuario_entrega'
GO

EXEC sp_addextendedproperty
'MS_Description', N'nombres de los archivos de capturas',
'SCHEMA', N'dbo',
'TABLE', N'tblEnvio',
'COLUMN', N'capturas'
GO


-- ----------------------------
-- Records of tblEnvio
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblEnvio] ON
GO

INSERT INTO [dbo].[tblEnvio] ([idEnvio], [id_usuario_envio], [id_usuario_recibe], [estado], [detalle_envio], [fecha_envio], [fecha_llegada], [observacion_llegada], [nombre_origen], [ci_origen], [id_lugar_origen], [nombre_destino], [ci_destino], [id_lugar_destino], [codigo], [celular_origen], [celular_destino], [fecha_estimada], [fecha_entrega], [id_usuario_entrega], [capturas]) VALUES (N'1', N'1', NULL, N'ENVIADO', N'Envio Paquete BOA dos correspondencias un auto a acontrol remoto y una nueva verengena de entregas de nuevo aviso para pruebas de las solicitudes de las intenciones', N'2024-02-01 10:50:32.110', NULL, NULL, N'Eduardo Jose', N'1222212', N'1', N'Referencio mamani', N'12123PT', N'2', N'FD84AA', NULL, NULL, NULL, NULL, NULL, NULL)
GO

INSERT INTO [dbo].[tblEnvio] ([idEnvio], [id_usuario_envio], [id_usuario_recibe], [estado], [detalle_envio], [fecha_envio], [fecha_llegada], [observacion_llegada], [nombre_origen], [ci_origen], [id_lugar_origen], [nombre_destino], [ci_destino], [id_lugar_destino], [codigo], [celular_origen], [celular_destino], [fecha_estimada], [fecha_entrega], [id_usuario_entrega], [capturas]) VALUES (N'2', N'1', NULL, N'RECIBIDO', N'Envio Paquete BOA', N'2024-02-01 12:46:38.057', N'2024-02-20 13:41:50.000', NULL, N'Eduardo Jose', N'1222212', N'2', N'Referencio mamani', N'12123PT', N'1', N'DE5251', N'79656767', N'7885754', N'2023-01-02 14:30:26.000', NULL, NULL, NULL)
GO

INSERT INTO [dbo].[tblEnvio] ([idEnvio], [id_usuario_envio], [id_usuario_recibe], [estado], [detalle_envio], [fecha_envio], [fecha_llegada], [observacion_llegada], [nombre_origen], [ci_origen], [id_lugar_origen], [nombre_destino], [ci_destino], [id_lugar_destino], [codigo], [celular_origen], [celular_destino], [fecha_estimada], [fecha_entrega], [id_usuario_entrega], [capturas]) VALUES (N'3', N'1', NULL, N'ENTREGADO', N'Envio Paquete BOA', N'2024-02-01 12:49:03.563', N'2024-02-05 16:03:47.000', N'Datos de paquetes en las opciones', N'Eduardo Jose', N'1222212', N'2', N'Referencio mamani', N'12123PT', N'1', N'254DE5', N'79656767', N'7885754', N'2023-01-02 00:00:00.000', N'2024-02-05 19:25:39.000', N'1', NULL)
GO

INSERT INTO [dbo].[tblEnvio] ([idEnvio], [id_usuario_envio], [id_usuario_recibe], [estado], [detalle_envio], [fecha_envio], [fecha_llegada], [observacion_llegada], [nombre_origen], [ci_origen], [id_lugar_origen], [nombre_destino], [ci_destino], [id_lugar_destino], [codigo], [celular_origen], [celular_destino], [fecha_estimada], [fecha_entrega], [id_usuario_entrega], [capturas]) VALUES (N'4', N'3', NULL, N'ENVIADO', N'Detalle de envio', N'2024-01-02 00:00:00.000', NULL, NULL, N'sadfasdf', N'324234', N'3', N'Fabrizio romano', N'213123', N'2', N'EC61FD', N'79846546', N'', N'2024-01-02 16:04:00.000', NULL, NULL, NULL)
GO

INSERT INTO [dbo].[tblEnvio] ([idEnvio], [id_usuario_envio], [id_usuario_recibe], [estado], [detalle_envio], [fecha_envio], [fecha_llegada], [observacion_llegada], [nombre_origen], [ci_origen], [id_lugar_origen], [nombre_destino], [ci_destino], [id_lugar_destino], [codigo], [celular_origen], [celular_destino], [fecha_estimada], [fecha_entrega], [id_usuario_entrega], [capturas]) VALUES (N'5', N'3', N'4', N'RECIBIDO', N'Paquete de enfermeria', N'2024-05-02 00:00:00.000', N'2024-02-05 18:54:41.000', NULL, N'Platano Segundo', N'789654 OR', N'3', N'Federico Maestrencio', N'7896548', N'1', N'C2B396', N'79989799', N'', N'2024-05-02 19:09:00.000', NULL, NULL, NULL)
GO

INSERT INTO [dbo].[tblEnvio] ([idEnvio], [id_usuario_envio], [id_usuario_recibe], [estado], [detalle_envio], [fecha_envio], [fecha_llegada], [observacion_llegada], [nombre_origen], [ci_origen], [id_lugar_origen], [nombre_destino], [ci_destino], [id_lugar_destino], [codigo], [celular_origen], [celular_destino], [fecha_estimada], [fecha_entrega], [id_usuario_entrega], [capturas]) VALUES (N'10', N'1', NULL, N'ENVIADO', N'Caja de papeletas', N'2024-09-03 00:00:00.000', NULL, NULL, N'Fermin Lopez', N'7879877', N'1', N'Ramiro vaca', N'7987987', N'2', N'6C7318', N'', N'65656478', N'2024-10-03 00:00:00.000', NULL, NULL, NULL)
GO

SET IDENTITY_INSERT [dbo].[tblEnvio] OFF
GO


-- ----------------------------
-- Table structure for tblLugar
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblLugar]') AND type IN ('U'))
	DROP TABLE [dbo].[tblLugar]
GO

CREATE TABLE [dbo].[tblLugar] (
  [idLugar] int  IDENTITY(1,1) NOT NULL,
  [lugar] varchar(120) COLLATE Modern_Spanish_CI_AS  NULL,
  [observacion] varchar(255) COLLATE Modern_Spanish_CI_AS DEFAULT '' NULL
)
GO

ALTER TABLE [dbo].[tblLugar] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of tblLugar
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblLugar] ON
GO

INSERT INTO [dbo].[tblLugar] ([idLugar], [lugar], [observacion]) VALUES (N'1', N'LA PAZ', N'')
GO

INSERT INTO [dbo].[tblLugar] ([idLugar], [lugar], [observacion]) VALUES (N'2', N'PATACAMAYA', N'')
GO

INSERT INTO [dbo].[tblLugar] ([idLugar], [lugar], [observacion]) VALUES (N'3', N'CHUQUISACA', N'')
GO

INSERT INTO [dbo].[tblLugar] ([idLugar], [lugar], [observacion]) VALUES (N'4', N'POTOSI', N'')
GO

SET IDENTITY_INSERT [dbo].[tblLugar] OFF
GO


-- ----------------------------
-- Table structure for tblUsuario
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblUsuario]') AND type IN ('U'))
	DROP TABLE [dbo].[tblUsuario]
GO

CREATE TABLE [dbo].[tblUsuario] (
  [idUsuario] int  IDENTITY(1,1) NOT NULL,
  [nombre] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [usuario] varchar(30) COLLATE Modern_Spanish_CI_AS  NULL,
  [rol] varchar(30) COLLATE Modern_Spanish_CI_AS  NULL,
  [password] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [idLugar] int  NOT NULL,
  [color] varchar(12) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblUsuario] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'ADMIN | ',
'SCHEMA', N'dbo',
'TABLE', N'tblUsuario',
'COLUMN', N'rol'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Lugar asignado al usuario',
'SCHEMA', N'dbo',
'TABLE', N'tblUsuario',
'COLUMN', N'idLugar'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Color hexadecimal',
'SCHEMA', N'dbo',
'TABLE', N'tblUsuario',
'COLUMN', N'color'
GO


-- ----------------------------
-- Records of tblUsuario
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblUsuario] ON
GO

INSERT INTO [dbo].[tblUsuario] ([idUsuario], [nombre], [usuario], [rol], [password], [idLugar], [color]) VALUES (N'1', N'Carlos Chambi Calizaya mamani', N'admin', N'ADMIN', N'03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', N'1', N'#294765')
GO

INSERT INTO [dbo].[tblUsuario] ([idUsuario], [nombre], [usuario], [rol], [password], [idLugar], [color]) VALUES (N'3', N'Gibrill Cissé', N'user', N'VENTANILLA', N'04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb', N'3', N'#470342')
GO

INSERT INTO [dbo].[tblUsuario] ([idUsuario], [nombre], [usuario], [rol], [password], [idLugar], [color]) VALUES (N'4', N'almacen1', N'almacen', N'ALMACEN', N'9d8cb2a3bec6cdb078ef56c799ea0fc81f3005f528ab08d446ebc14e800f6cbf', N'1', N'#212529')
GO

SET IDENTITY_INSERT [dbo].[tblUsuario] OFF
GO


-- ----------------------------
-- Auto increment value for tblEnvio
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblEnvio]', RESEED, 14)
GO


-- ----------------------------
-- Primary Key structure for table tblEnvio
-- ----------------------------
ALTER TABLE [dbo].[tblEnvio] ADD CONSTRAINT [PK__tblEnvio__527F831F6CAFDCBB] PRIMARY KEY CLUSTERED ([idEnvio])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblLugar
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblLugar]', RESEED, 4)
GO


-- ----------------------------
-- Primary Key structure for table tblLugar
-- ----------------------------
ALTER TABLE [dbo].[tblLugar] ADD CONSTRAINT [PK__tblLugar__F7460D5FCB998DA9] PRIMARY KEY CLUSTERED ([idLugar])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblUsuario
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblUsuario]', RESEED, 4)
GO


-- ----------------------------
-- Primary Key structure for table tblUsuario
-- ----------------------------
ALTER TABLE [dbo].[tblUsuario] ADD CONSTRAINT [PK__tblUsuar__645723A60C2CD2C6] PRIMARY KEY CLUSTERED ([idUsuario])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Foreign Keys structure for table tblEnvio
-- ----------------------------
ALTER TABLE [dbo].[tblEnvio] ADD CONSTRAINT [fk_usuario_envio] FOREIGN KEY ([id_usuario_envio]) REFERENCES [dbo].[tblUsuario] ([idUsuario]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblEnvio] ADD CONSTRAINT [fk_usuario_recibio] FOREIGN KEY ([id_usuario_recibe]) REFERENCES [dbo].[tblUsuario] ([idUsuario]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblEnvio] ADD CONSTRAINT [fk_lugar_origen] FOREIGN KEY ([id_lugar_origen]) REFERENCES [dbo].[tblLugar] ([idLugar]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblEnvio] ADD CONSTRAINT [fk_lugar_destino] FOREIGN KEY ([id_lugar_destino]) REFERENCES [dbo].[tblLugar] ([idLugar]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblEnvio] ADD CONSTRAINT [fk_usuario_entrega] FOREIGN KEY ([id_usuario_entrega]) REFERENCES [dbo].[tblUsuario] ([idUsuario]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblUsuario
-- ----------------------------
ALTER TABLE [dbo].[tblUsuario] ADD CONSTRAINT [fk_lugar_usuario] FOREIGN KEY ([idLugar]) REFERENCES [dbo].[tblLugar] ([idLugar]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

