SELECT l.lugar as origgen, l2.lugar as destino, u.usuario, u.nombre, e.* FROM tblEnvio e 
INNER JOIN tblLugar l ON e.id_lugar_origen = l.idLugar
INNER JOIN tblLugar l2 ON e.id_lugar_destino = l2.idLugar
INNER JOIN tblUsuario u ON u.idUsuario = e.id_usuario_envio;

SELECT * FROM tblEnvio 
-- 1 -> LA PAZ
-- 4 -> UYUNU 


SELECT * FROM tblLugar 

SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'tblEnvio' AND TABLE_SCHEMA = 'dbo'

-- 8 UYUNI
-- 4 LA PAZ
-- 13 USuario abigail
INSERT INTO correspondencia_bolivar.dbo.tblEnvio() 
VALUES()


SELECT * FROM tblUsuario 


SELECT * FROM correspondencia_bolivar.dbo.tblUsuario 

SELECT * FROM correspondencia_bolivar.dbo.tblUsuario a 
INNER JOIN correspondencia_bolivar.dbo.tblEnvio e 
ON a.idUsuario = e.id_usuario_envio OR a.idUsuario = e.id_usuario_entrega
WHERE a.idUsuario = 5

SELECT DISTINCT e.id_usuario_recibe FROM correspondencia_bolivar.dbo.tblEnvio e 


SELECT * FROM correspondencia_bolivar.dbo.tblLugar 

SELECT u.nombre, u.usuario, l.idLugar, l.lugar FROM correspondencia_bolivar.dbo.tblUsuario u 
INNER JOIN correspondencia_bolivar.dbo.tblLugar l ON l.idLugar = u.idLugar



INSERT INTO correspondencia_bolivar.dbo.tblEnvio (
    id_usuario_envio,
    id_usuario_recibe,
    estado,
    detalle_envio,
    fecha_envio,
    fecha_llegada,
    observacion_llegada,
    nombre_origen,
    ci_origen,
    id_lugar_origen,
    nombre_destino,
    ci_destino,
    id_lugar_destino,
    codigo,
    celular_origen,
    celular_destino,
    fecha_estimada,
    fecha_entrega,
    id_usuario_entrega,
    capturas,
    costo,
    observacion_envio,
    peso,
    cantidad,
    pagado,
    saldado,
    trip_id
)
SELECT
    13 AS id_usuario_envio,          -- valor fijo
    id_usuario_recibe,
    estado,
    detalle_envio,
    fecha_envio,
    fecha_llegada,
    observacion_llegada,
    nombre_origen,
    ci_origen,
    4 AS id_lugar_origen,            -- valor fijo
    nombre_destino,
    ci_destino,
    8 AS id_lugar_destino,           -- valor fijo
    codigo,
    celular_origen,
    celular_destino,
    fecha_estimada,
    fecha_entrega,
    id_usuario_entrega,
    capturas,
    costo,
    observacion_envio,
    peso,
    cantidad,
    pagado,
    saldado,
    trip_id
FROM correspondencia_25_diciembre_lp.dbo.tblEnvio;