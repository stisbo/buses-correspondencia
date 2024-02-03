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

 Date: 03/02/2024 18:37:54
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
  [fecha_estimada] datetime  NULL
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
-- Auto increment value for tblEnvio
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblEnvio]', RESEED, 4)
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
DBCC CHECKIDENT ('[dbo].[tblUsuario]', RESEED, 3)
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


-- ----------------------------
-- Foreign Keys structure for table tblUsuario
-- ----------------------------
ALTER TABLE [dbo].[tblUsuario] ADD CONSTRAINT [fk_lugar_usuario] FOREIGN KEY ([idLugar]) REFERENCES [dbo].[tblLugar] ([idLugar]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

