
USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'cccol')      
     EXEC (N'CREATE SCHEMA cccol')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_accessadmin')      
     EXEC (N'CREATE SCHEMA db_accessadmin')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_backupoperator')      
     EXEC (N'CREATE SCHEMA db_backupoperator')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_datareader')      
     EXEC (N'CREATE SCHEMA db_datareader')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_datawriter')      
     EXEC (N'CREATE SCHEMA db_datawriter')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_ddladmin')      
     EXEC (N'CREATE SCHEMA db_ddladmin')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_denydatareader')      
     EXEC (N'CREATE SCHEMA db_denydatareader')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_denydatawriter')      
     EXEC (N'CREATE SCHEMA db_denydatawriter')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_owner')      
     EXEC (N'CREATE SCHEMA db_owner')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'db_securityadmin')      
     EXEC (N'CREATE SCHEMA db_securityadmin')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'dbo')      
     EXEC (N'CREATE SCHEMA dbo')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'guest')      
     EXEC (N'CREATE SCHEMA guest')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'INFORMATION_SCHEMA')      
     EXEC (N'CREATE SCHEMA INFORMATION_SCHEMA')                                   
 GO                                                               

USE cccol
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'sys')      
     EXEC (N'CREATE SCHEMA sys')                                   
 GO                                                               

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'actividad_empresa'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'actividad_empresa'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[actividad_empresa]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[actividad_empresa]
(
   [codigo_actividad] int IDENTITY(31, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_actividad] varchar(200)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_actividad] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.actividad_empresa',
        N'SCHEMA', N'cccol',
        N'TABLE', N'actividad_empresa'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'acuerdos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'acuerdos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[acuerdos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[acuerdos]
(
   [codigo_acuerdos] int IDENTITY(14, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_peticion] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_oferta] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_reunion] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [fecha_acuerdo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nro_articulo] varchar(11)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [resumen_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_titulo_comparativo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [campo_comparativo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to CHAR according to character set mapping for latin1 character set
   */

   [estatus_acuerdo] char(1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [titulo_articulo] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.acuerdos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'acuerdos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'anexos_contratos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'anexos_contratos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[anexos_contratos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[anexos_contratos]
(
   [codigo_anexo] int IDENTITY(50, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_contrato] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_anexo] varchar(250)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_anexo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_anexo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [pdf_anexo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [status_publicacion] varchar(15)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.anexos_contratos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'anexos_contratos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'articulos_contratos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'articulos_contratos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[articulos_contratos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[articulos_contratos]
(
   [codigo_articulo] int IDENTITY(6426, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_contratos] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nro_articulos] varchar(11)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [resumen_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_titulo_comparativo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [campo_comparativo] varchar(250)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [status_articulo] varchar(12)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [titulo_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to CHAR according to character set mapping for latin1 character set
   */

   [articulo_cerrado] char(1)  NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.articulos_contratos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'articulos_contratos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'articulos_ley_trabajo'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'articulos_ley_trabajo'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[articulos_ley_trabajo]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[articulos_ley_trabajo]
(

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [nro_articulo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_articulos] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [resumen_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_titulo_comparativo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [campo_comparativo] varchar(200)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [titulo_articulo] varchar(200)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.articulos_ley_trabajo',
        N'SCHEMA', N'cccol',
        N'TABLE', N'articulos_ley_trabajo'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'articulos_otras_leyes'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'articulos_otras_leyes'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[articulos_otras_leyes]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[articulos_otras_leyes]
(
   [codigo_articulo] int IDENTITY(4071, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_otra_ley] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nro_articulo] varchar(11)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [resumen_articulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_titulo_comparativo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [campo_comparativo] varchar(200)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [titulo_articulo] varchar(200)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.articulos_otras_leyes',
        N'SCHEMA', N'cccol',
        N'TABLE', N'articulos_otras_leyes'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'bitacoras'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'bitacoras'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[bitacoras]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[bitacoras]
(
   [codigo_bitacora] int IDENTITY(24, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_empresa] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [fecha_inicio_disc] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [representante_empresa] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [telefono_representante_empresa] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [email_representante_empresa] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [cargo_representante_empresa] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [representante_sindicato] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [telefono_representante_sindicato] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [email_representante_sindicato] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [cargo_representante_sindicato] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [representante_min_trab] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [telefono_representante_min_trab] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [email_representante_min_trab] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [cargo_representante_min_trab] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [fecha_ultima_reunion] date  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [fecha_proxima_reunion] date  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [pdf_peticiones_sindicato] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [pdf_ofertas_patrono] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to CHAR according to character set mapping for latin1 character set
   */

   [estatus_bitacora] char(3)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [estatu_pase_contratos] varchar(50)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.bitacoras',
        N'SCHEMA', N'cccol',
        N'TABLE', N'bitacoras'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'categoria_noticias'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'categoria_noticias'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[categoria_noticias]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[categoria_noticias]
(
   [codigo_categoria] int IDENTITY(52, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_categoria] varchar(50)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [descripcion_categoria] varchar(250)  NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.categoria_noticias',
        N'SCHEMA', N'cccol',
        N'TABLE', N'categoria_noticias'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'categoria_sector'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'categoria_sector'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[categoria_sector]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[categoria_sector]
(
   [codigo_categoria] int IDENTITY(31, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_categoria] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_categoria] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.categoria_sector',
        N'SCHEMA', N'cccol',
        N'TABLE', N'categoria_sector'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'categorias'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'categorias'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[categorias]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[categorias]
(
   [Id_categoria] int IDENTITY(9, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_categoria] varchar(50)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Imagen] varchar(50)  NULL,
   [Prioridad] int  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [desc] varchar(250)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.categorias',
        N'SCHEMA', N'cccol',
        N'TABLE', N'categorias'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'categorias_titulos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'categorias_titulos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[categorias_titulos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[categorias_titulos]
(
   [codigo_categoria_titulos] int IDENTITY(20, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_categoria] varchar(250)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_categoria] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to CHAR according to character set mapping for latin1 character set
   */

   [requiere_campo_comparacion_economica] char(2)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.categorias_titulos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'categorias_titulos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'ciudades'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'ciudades'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[ciudades]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[ciudades]
(
   [Id_ciudad] int IDENTITY(1, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [Id_pais] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_ciudad] varchar(20)  NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.ciudades',
        N'SCHEMA', N'cccol',
        N'TABLE', N'ciudades'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'contratos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'contratos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[contratos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[contratos]
(
   [codigo_contrato] int IDENTITY(122, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [pdf_auto_acta] varchar(max)  NOT NULL,
   [fecha_de_inicio] date  NULL,
   [fecha_de_termino] date  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [duracion] varchar(15)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [ambito_aplicacion] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_empresa] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to CHAR according to character set mapping for latin1 character set
   */

   [status_publicacion] char(2)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.contratos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'contratos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'empresas'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'empresas'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[empresas]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[empresas]
(
   [id] int IDENTITY(196, 1)  NOT NULL,
   [codigo_empresa] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_empresa] varchar(200)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [direccion_empresa] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_pais] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_estado] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_localidad] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [telefonos_empresa] varchar(20)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [fax_empresa] varchar(20)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [email_empresa] varchar(60)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [url_empresa] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [rif_empresa] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nit_empresa] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [contacto_empresa] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [cargo_contacto] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [telefonos_contacto] varchar(200)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [email_contacto] varchar(60)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_sector] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_tipo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_categoria] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_actividad] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [cantidad_obreros_empresa] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [cantidad_empleados_empresa] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [tamano_empresa] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [origen] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [logo] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.empresas',
        N'SCHEMA', N'cccol',
        N'TABLE', N'empresas'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'estados'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'estados'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[estados]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[estados]
(
   [Id_estado] int IDENTITY(36, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [Id_pais] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_estado] varchar(50)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Usuario_cre] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [Fecha_cre] datetime2(0)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Usuario_mod] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [Fecha_mod] datetime2(0)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.estados',
        N'SCHEMA', N'cccol',
        N'TABLE', N'estados'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'ley_trabajo'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'ley_trabajo'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[ley_trabajo]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[ley_trabajo]
(

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [fecha_publicacion] varchar(11)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [pdf_asociado] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_resumen] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [total_articulos] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.ley_trabajo',
        N'SCHEMA', N'cccol',
        N'TABLE', N'ley_trabajo'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'links_interes'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'links_interes'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[links_interes]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[links_interes]
(
   [lit_IdLink] int IDENTITY(46, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [lit_Sector] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [lit_Detalle] varchar(40)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [lit_Link] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [lit_UsuarioCrea] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [lit_FechaCrea] datetime2(0)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [lit_UsuarioMod] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [lit_FechaMod] datetime2(0)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.links_interes',
        N'SCHEMA', N'cccol',
        N'TABLE', N'links_interes'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'localidades'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'localidades'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[localidades]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[localidades]
(
   [Id_localidad] int IDENTITY(448, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [Id_estado] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [Id_pais] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_localidad] varchar(100)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Usuario_cre] varchar(50)  NULL,
   [Fecha_cre] datetime2(0)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Usuario_mod] varchar(50)  NULL,
   [Fecha_mod] datetime2(0)  NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.localidades',
        N'SCHEMA', N'cccol',
        N'TABLE', N'localidades'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'modulos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'modulos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[modulos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[modulos]
(
   [Id_modulo] int IDENTITY(31, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_modulo] varchar(50)  NULL,
   [Prioridad] int  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [Id_categoria] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [url] varchar(255)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.modulos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'modulos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'noticias'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'noticias'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[noticias]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[noticias]
(
   [codigo_noticia] int IDENTITY(87, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [resumen_noticia] varchar(max)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [noticia_completa] varchar(max)  NULL,
   [codigo_categoria] int  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [titulo] varchar(250)  NULL,
   [fecha_publicacion] date  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [estatus_noticia] varchar(11)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [imagen_1] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [imagen_2] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [origen] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.noticias',
        N'SCHEMA', N'cccol',
        N'TABLE', N'noticias'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'ofertas_patrono'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'ofertas_patrono'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[ofertas_patrono]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[ofertas_patrono]
(
   [codigo_ofertas] int IDENTITY(25, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nro_oferta] varchar(11)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_oferta] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [estatus_oferta] varchar(15)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_titulo_comparativo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_peticion] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [titulo_oferta] varchar(250)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.ofertas_patrono',
        N'SCHEMA', N'cccol',
        N'TABLE', N'ofertas_patrono'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'otras_leyes'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'otras_leyes'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[otras_leyes]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[otras_leyes]
(
   [codigo_otra_ley] int IDENTITY(97, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [nombre_ley] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [fecha_publicacion_ley] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_ley] varchar(max)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [pdf_ley] varchar(max)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [total_articulos_ley] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [origen] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.otras_leyes',
        N'SCHEMA', N'cccol',
        N'TABLE', N'otras_leyes'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'paises'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'paises'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[paises]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[paises]
(
   [Id_pais] int IDENTITY(259, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_pais] varchar(50)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Usuario_cre] varchar(50)  NULL,
   [Fecha_cre] datetime2(0)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Usuario_mod] varchar(50)  NULL,
   [Fecha_mod] datetime2(0)  NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.paises',
        N'SCHEMA', N'cccol',
        N'TABLE', N'paises'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'pantallas'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'pantallas'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[pantallas]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[pantallas]
(
   [id_pantalla] int IDENTITY(151, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_pantalla] varchar(100)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [url_pantalla] varchar(150)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [prioridad] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [id_modulo] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.pantallas',
        N'SCHEMA', N'cccol',
        N'TABLE', N'pantallas'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'peticiones_sindicato'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'peticiones_sindicato'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[peticiones_sindicato]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[peticiones_sindicato]
(
   [codigo_peticion] int IDENTITY(52, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nro_peticion] varchar(11)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [texto_completo_peticion] varchar(max)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE latin1_bin.

   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to CHAR according to character set mapping for latin1 character set
   */

   [estatus_peticion] char(1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_titulo_comparativo] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [titulo_peticion] varchar(250)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.peticiones_sindicato',
        N'SCHEMA', N'cccol',
        N'TABLE', N'peticiones_sindicato'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'reuniones'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'reuniones'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[reuniones]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[reuniones]
(
   [codigo_reunion] int IDENTITY(24, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [fecha_reunion] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [hora_reunion] varchar(8)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [duracion_reunion] varchar(15)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [resumen_reunion] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [detalles_reunion] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [asistentes_reunion] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.reuniones',
        N'SCHEMA', N'cccol',
        N'TABLE', N'reuniones'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'reuniones_acuerdos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'reuniones_acuerdos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[reuniones_acuerdos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[reuniones_acuerdos]
(

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_reuniones] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_acuerdos] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.reuniones_acuerdos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'reuniones_acuerdos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'reuniones_ofertas'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'reuniones_ofertas'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[reuniones_ofertas]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[reuniones_ofertas]
(

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_reuniones] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_ofertas] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.reuniones_ofertas',
        N'SCHEMA', N'cccol',
        N'TABLE', N'reuniones_ofertas'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'reuniones_peticiones'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'reuniones_peticiones'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[reuniones_peticiones]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[reuniones_peticiones]
(

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_reuniones] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_bitacora] int  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_peticiones] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.reuniones_peticiones',
        N'SCHEMA', N'cccol',
        N'TABLE', N'reuniones_peticiones'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'sectores_empresas'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'sectores_empresas'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[sectores_empresas]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[sectores_empresas]
(
   [codigo_sector] int IDENTITY(14, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [nombre_sector] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_sector] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.sectores_empresas',
        N'SCHEMA', N'cccol',
        N'TABLE', N'sectores_empresas'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'seguridades'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'seguridades'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[seguridades]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[seguridades]
(

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [Id_tipo_Usuario] int  NOT NULL,
   [id_pantalla] int  NULL,
   [Id_seguridad] int IDENTITY(829, 1)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.seguridades',
        N'SCHEMA', N'cccol',
        N'TABLE', N'seguridades'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'tipo_empresa'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'tipo_empresa'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[tipo_empresa]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[tipo_empresa]
(
   [codigo_tipo] int IDENTITY(24, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_tipo] varchar(200)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_tipo] varchar(max)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.tipo_empresa',
        N'SCHEMA', N'cccol',
        N'TABLE', N'tipo_empresa'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'tipos_de_usuarios'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'tipos_de_usuarios'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[tipos_de_usuarios]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[tipos_de_usuarios]
(
   [Id_tipo_Usuario] int IDENTITY(30, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_tipo] varchar(50)  NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.tipos_de_usuarios',
        N'SCHEMA', N'cccol',
        N'TABLE', N'tipos_de_usuarios'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'titulos'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'titulos'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[titulos]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[titulos]
(
   [codigo_titulo_comparativo] int IDENTITY(88, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [nombre_titulo] varchar(250)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR(MAX) according to character set mapping for latin1 character set
   */

   [descripcion_titulo] varchar(max)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0052: string literal was converted to NUMERIC literal
   */

   [codigo_categoria_titulo] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.titulos',
        N'SCHEMA', N'cccol',
        N'TABLE', N'titulos'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'usuarios'  AND sc.name = N'cccol'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'usuarios'  AND sc.name = N'cccol'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [cccol].[usuarios]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[cccol].[usuarios]
(
   [id] int IDENTITY(41, 1)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Login_usuario] varchar(50)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Nombre_usuario] varchar(100)  NULL,
   [Codigo_empresa] int  NULL,
   [Codigo_tipo_usuario] int  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Direccion_usuario] varchar(200)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Telefono_usuario] varchar(50)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Email_usuario] varchar(80)  NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [Clave_usuario] varchar(20)  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0046: The Date value is Zero
   *   M2SS0231: Zero-date, zero-in-date and invalid dates to not null columns has been replaced with GetDate()/Constant date
   */

   [fech_venc] date  NOT NULL,

   /*
   *   SSMA informational messages:
   *   M2SS0055: Data type was converted to VARCHAR according to character set mapping for latin1 character set
   */

   [estatus] varchar(20)  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'cccol.usuarios',
        N'SCHEMA', N'cccol',
        N'TABLE', N'usuarios'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_actividad_empresa_codigo_actividad'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[actividad_empresa] DROP CONSTRAINT [PK_actividad_empresa_codigo_actividad]
 GO



ALTER TABLE [cccol].[actividad_empresa]
 ADD CONSTRAINT [PK_actividad_empresa_codigo_actividad]
   PRIMARY KEY
   CLUSTERED ([codigo_actividad] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_acuerdos_codigo_acuerdos'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[acuerdos] DROP CONSTRAINT [PK_acuerdos_codigo_acuerdos]
 GO



ALTER TABLE [cccol].[acuerdos]
 ADD CONSTRAINT [PK_acuerdos_codigo_acuerdos]
   PRIMARY KEY
   CLUSTERED ([codigo_acuerdos] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_anexos_contratos_codigo_anexo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[anexos_contratos] DROP CONSTRAINT [PK_anexos_contratos_codigo_anexo]
 GO



ALTER TABLE [cccol].[anexos_contratos]
 ADD CONSTRAINT [PK_anexos_contratos_codigo_anexo]
   PRIMARY KEY
   CLUSTERED ([codigo_anexo] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_articulos_contratos_codigo_articulo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[articulos_contratos] DROP CONSTRAINT [PK_articulos_contratos_codigo_articulo]
 GO



ALTER TABLE [cccol].[articulos_contratos]
 ADD CONSTRAINT [PK_articulos_contratos_codigo_articulo]
   PRIMARY KEY
   CLUSTERED ([codigo_articulo] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_articulos_ley_trabajo_nro_articulo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[articulos_ley_trabajo] DROP CONSTRAINT [PK_articulos_ley_trabajo_nro_articulo]
 GO



ALTER TABLE [cccol].[articulos_ley_trabajo]
 ADD CONSTRAINT [PK_articulos_ley_trabajo_nro_articulo]
   PRIMARY KEY
   CLUSTERED ([nro_articulo] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_articulos_otras_leyes_codigo_articulo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[articulos_otras_leyes] DROP CONSTRAINT [PK_articulos_otras_leyes_codigo_articulo]
 GO



ALTER TABLE [cccol].[articulos_otras_leyes]
 ADD CONSTRAINT [PK_articulos_otras_leyes_codigo_articulo]
   PRIMARY KEY
   CLUSTERED ([codigo_articulo] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_bitacoras_codigo_bitacora'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[bitacoras] DROP CONSTRAINT [PK_bitacoras_codigo_bitacora]
 GO



ALTER TABLE [cccol].[bitacoras]
 ADD CONSTRAINT [PK_bitacoras_codigo_bitacora]
   PRIMARY KEY
   CLUSTERED ([codigo_bitacora] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_categoria_noticias_codigo_categoria'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[categoria_noticias] DROP CONSTRAINT [PK_categoria_noticias_codigo_categoria]
 GO



ALTER TABLE [cccol].[categoria_noticias]
 ADD CONSTRAINT [PK_categoria_noticias_codigo_categoria]
   PRIMARY KEY
   CLUSTERED ([codigo_categoria] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_categoria_sector_codigo_categoria'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[categoria_sector] DROP CONSTRAINT [PK_categoria_sector_codigo_categoria]
 GO



ALTER TABLE [cccol].[categoria_sector]
 ADD CONSTRAINT [PK_categoria_sector_codigo_categoria]
   PRIMARY KEY
   CLUSTERED ([codigo_categoria] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_categorias_Id_categoria'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[categorias] DROP CONSTRAINT [PK_categorias_Id_categoria]
 GO



ALTER TABLE [cccol].[categorias]
 ADD CONSTRAINT [PK_categorias_Id_categoria]
   PRIMARY KEY
   CLUSTERED ([Id_categoria] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_categorias_titulos_codigo_categoria_titulos'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[categorias_titulos] DROP CONSTRAINT [PK_categorias_titulos_codigo_categoria_titulos]
 GO



ALTER TABLE [cccol].[categorias_titulos]
 ADD CONSTRAINT [PK_categorias_titulos_codigo_categoria_titulos]
   PRIMARY KEY
   CLUSTERED ([codigo_categoria_titulos] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_ciudades_Id_ciudad'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[ciudades] DROP CONSTRAINT [PK_ciudades_Id_ciudad]
 GO



ALTER TABLE [cccol].[ciudades]
 ADD CONSTRAINT [PK_ciudades_Id_ciudad]
   PRIMARY KEY
   CLUSTERED ([Id_ciudad] ASC, [Id_pais] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_contratos_codigo_contrato'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[contratos] DROP CONSTRAINT [PK_contratos_codigo_contrato]
 GO



ALTER TABLE [cccol].[contratos]
 ADD CONSTRAINT [PK_contratos_codigo_contrato]
   PRIMARY KEY
   CLUSTERED ([codigo_contrato] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_empresas_id'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[empresas] DROP CONSTRAINT [PK_empresas_id]
 GO



ALTER TABLE [cccol].[empresas]
 ADD CONSTRAINT [PK_empresas_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_estados_Id_estado'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[estados] DROP CONSTRAINT [PK_estados_Id_estado]
 GO



ALTER TABLE [cccol].[estados]
 ADD CONSTRAINT [PK_estados_Id_estado]
   PRIMARY KEY
   CLUSTERED ([Id_estado] ASC, [Id_pais] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_links_interes_lit_IdLink'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[links_interes] DROP CONSTRAINT [PK_links_interes_lit_IdLink]
 GO



ALTER TABLE [cccol].[links_interes]
 ADD CONSTRAINT [PK_links_interes_lit_IdLink]
   PRIMARY KEY
   CLUSTERED ([lit_IdLink] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_localidades_Id_localidad'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[localidades] DROP CONSTRAINT [PK_localidades_Id_localidad]
 GO



ALTER TABLE [cccol].[localidades]
 ADD CONSTRAINT [PK_localidades_Id_localidad]
   PRIMARY KEY
   CLUSTERED ([Id_localidad] ASC, [Id_estado] ASC, [Id_pais] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_modulos_Id_modulo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[modulos] DROP CONSTRAINT [PK_modulos_Id_modulo]
 GO



ALTER TABLE [cccol].[modulos]
 ADD CONSTRAINT [PK_modulos_Id_modulo]
   PRIMARY KEY
   CLUSTERED ([Id_modulo] ASC, [Id_categoria] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_noticias_codigo_noticia'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[noticias] DROP CONSTRAINT [PK_noticias_codigo_noticia]
 GO



ALTER TABLE [cccol].[noticias]
 ADD CONSTRAINT [PK_noticias_codigo_noticia]
   PRIMARY KEY
   CLUSTERED ([codigo_noticia] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_ofertas_patrono_codigo_ofertas'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[ofertas_patrono] DROP CONSTRAINT [PK_ofertas_patrono_codigo_ofertas]
 GO



ALTER TABLE [cccol].[ofertas_patrono]
 ADD CONSTRAINT [PK_ofertas_patrono_codigo_ofertas]
   PRIMARY KEY
   CLUSTERED ([codigo_ofertas] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_otras_leyes_codigo_otra_ley'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[otras_leyes] DROP CONSTRAINT [PK_otras_leyes_codigo_otra_ley]
 GO



ALTER TABLE [cccol].[otras_leyes]
 ADD CONSTRAINT [PK_otras_leyes_codigo_otra_ley]
   PRIMARY KEY
   CLUSTERED ([codigo_otra_ley] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_paises_Id_pais'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[paises] DROP CONSTRAINT [PK_paises_Id_pais]
 GO



ALTER TABLE [cccol].[paises]
 ADD CONSTRAINT [PK_paises_Id_pais]
   PRIMARY KEY
   CLUSTERED ([Id_pais] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_pantallas_id_pantalla'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[pantallas] DROP CONSTRAINT [PK_pantallas_id_pantalla]
 GO



ALTER TABLE [cccol].[pantallas]
 ADD CONSTRAINT [PK_pantallas_id_pantalla]
   PRIMARY KEY
   CLUSTERED ([id_pantalla] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_peticiones_sindicato_codigo_peticion'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[peticiones_sindicato] DROP CONSTRAINT [PK_peticiones_sindicato_codigo_peticion]
 GO



ALTER TABLE [cccol].[peticiones_sindicato]
 ADD CONSTRAINT [PK_peticiones_sindicato_codigo_peticion]
   PRIMARY KEY
   CLUSTERED ([codigo_peticion] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_reuniones_codigo_reunion'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[reuniones] DROP CONSTRAINT [PK_reuniones_codigo_reunion]
 GO



ALTER TABLE [cccol].[reuniones]
 ADD CONSTRAINT [PK_reuniones_codigo_reunion]
   PRIMARY KEY
   CLUSTERED ([codigo_reunion] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_sectores_empresas_codigo_sector'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[sectores_empresas] DROP CONSTRAINT [PK_sectores_empresas_codigo_sector]
 GO



ALTER TABLE [cccol].[sectores_empresas]
 ADD CONSTRAINT [PK_sectores_empresas_codigo_sector]
   PRIMARY KEY
   CLUSTERED ([codigo_sector] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_seguridades_Id_seguridad'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[seguridades] DROP CONSTRAINT [PK_seguridades_Id_seguridad]
 GO



ALTER TABLE [cccol].[seguridades]
 ADD CONSTRAINT [PK_seguridades_Id_seguridad]
   PRIMARY KEY
   CLUSTERED ([Id_seguridad] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_tipo_empresa_codigo_tipo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[tipo_empresa] DROP CONSTRAINT [PK_tipo_empresa_codigo_tipo]
 GO



ALTER TABLE [cccol].[tipo_empresa]
 ADD CONSTRAINT [PK_tipo_empresa_codigo_tipo]
   PRIMARY KEY
   CLUSTERED ([codigo_tipo] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_tipos_de_usuarios_Id_tipo_Usuario'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[tipos_de_usuarios] DROP CONSTRAINT [PK_tipos_de_usuarios_Id_tipo_Usuario]
 GO



ALTER TABLE [cccol].[tipos_de_usuarios]
 ADD CONSTRAINT [PK_tipos_de_usuarios_Id_tipo_Usuario]
   PRIMARY KEY
   CLUSTERED ([Id_tipo_Usuario] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_titulos_codigo_titulo_comparativo'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[titulos] DROP CONSTRAINT [PK_titulos_codigo_titulo_comparativo]
 GO



ALTER TABLE [cccol].[titulos]
 ADD CONSTRAINT [PK_titulos_codigo_titulo_comparativo]
   PRIMARY KEY
   CLUSTERED ([codigo_titulo_comparativo] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_usuarios_id'  AND sc.name = N'cccol'  AND type in (N'PK'))
ALTER TABLE [cccol].[usuarios] DROP CONSTRAINT [PK_usuarios_id]
 GO



ALTER TABLE [cccol].[usuarios]
 ADD CONSTRAINT [PK_usuarios_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE cccol
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'articulos_ley_trabajo$nro_articulo'  AND sc.name = N'cccol'  AND type in (N'UQ'))
ALTER TABLE [cccol].[articulos_ley_trabajo] DROP CONSTRAINT [articulos_ley_trabajo$nro_articulo]
 GO



ALTER TABLE [cccol].[articulos_ley_trabajo]
 ADD CONSTRAINT [articulos_ley_trabajo$nro_articulo]
 UNIQUE 
   NONCLUSTERED ([nro_articulo] ASC)

GO


USE cccol
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N'modulos'  AND sc.name = N'cccol'  AND si.name = N'Id_categoria' AND so.type in (N'U'))
   DROP INDEX [Id_categoria] ON [cccol].[modulos] 
GO
CREATE NONCLUSTERED INDEX [Id_categoria] ON [cccol].[modulos]
(
   [Id_categoria] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE cccol
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N'localidades'  AND sc.name = N'cccol'  AND si.name = N'Id_ciudad' AND so.type in (N'U'))
   DROP INDEX [Id_ciudad] ON [cccol].[localidades] 
GO
CREATE NONCLUSTERED INDEX [Id_ciudad] ON [cccol].[localidades]
(
   [Id_estado] ASC,
   [Id_pais] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE cccol
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N'ciudades'  AND sc.name = N'cccol'  AND si.name = N'Id_pais' AND so.type in (N'U'))
   DROP INDEX [Id_pais] ON [cccol].[ciudades] 
GO
CREATE NONCLUSTERED INDEX [Id_pais] ON [cccol].[ciudades]
(
   [Id_pais] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE cccol
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N'seguridades'  AND sc.name = N'cccol'  AND si.name = N'Id_tipo_Usuario' AND so.type in (N'U'))
   DROP INDEX [Id_tipo_Usuario] ON [cccol].[seguridades] 
GO
CREATE NONCLUSTERED INDEX [Id_tipo_Usuario] ON [cccol].[seguridades]
(
   [Id_tipo_Usuario] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE cccol
GO
ALTER TABLE  [cccol].[actividad_empresa]
 ADD DEFAULT N'' FOR [nombre_actividad]
GO


USE cccol
GO
ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT 0 FOR [codigo_peticion]
GO

ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT 0 FOR [codigo_oferta]
GO

ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT 0 FOR [codigo_reunion]
GO

ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT N'' FOR [nro_articulo]
GO

ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT 0 FOR [codigo_titulo_comparativo]
GO

ALTER TABLE  [cccol].[acuerdos]
 ADD DEFAULT N'' FOR [estatus_acuerdo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[anexos_contratos]
 ADD DEFAULT 0 FOR [codigo_contrato]
GO

ALTER TABLE  [cccol].[anexos_contratos]
 ADD DEFAULT N'' FOR [nombre_anexo]
GO

ALTER TABLE  [cccol].[anexos_contratos]
 ADD DEFAULT N'Pendiente' FOR [status_publicacion]
GO


USE cccol
GO
ALTER TABLE  [cccol].[articulos_contratos]
 ADD DEFAULT 0 FOR [codigo_contratos]
GO

ALTER TABLE  [cccol].[articulos_contratos]
 ADD DEFAULT N'0' FOR [nro_articulos]
GO

ALTER TABLE  [cccol].[articulos_contratos]
 ADD DEFAULT 0 FOR [codigo_titulo_comparativo]
GO

ALTER TABLE  [cccol].[articulos_contratos]
 ADD DEFAULT N'' FOR [campo_comparativo]
GO

ALTER TABLE  [cccol].[articulos_contratos]
 ADD DEFAULT N'Pendiente' FOR [status_articulo]
GO

ALTER TABLE  [cccol].[articulos_contratos]
 ADD DEFAULT NULL FOR [articulo_cerrado]
GO


USE cccol
GO
ALTER TABLE  [cccol].[articulos_ley_trabajo]
 ADD DEFAULT 0 FOR [nro_articulo]
GO

ALTER TABLE  [cccol].[articulos_ley_trabajo]
 ADD DEFAULT 0 FOR [codigo_titulo_comparativo]
GO

ALTER TABLE  [cccol].[articulos_ley_trabajo]
 ADD DEFAULT N'' FOR [campo_comparativo]
GO

ALTER TABLE  [cccol].[articulos_ley_trabajo]
 ADD DEFAULT N'' FOR [titulo_articulo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[articulos_otras_leyes]
 ADD DEFAULT 0 FOR [codigo_otra_ley]
GO

ALTER TABLE  [cccol].[articulos_otras_leyes]
 ADD DEFAULT N'' FOR [nro_articulo]
GO

ALTER TABLE  [cccol].[articulos_otras_leyes]
 ADD DEFAULT 0 FOR [codigo_titulo_comparativo]
GO

ALTER TABLE  [cccol].[articulos_otras_leyes]
 ADD DEFAULT N'' FOR [campo_comparativo]
GO

ALTER TABLE  [cccol].[articulos_otras_leyes]
 ADD DEFAULT N'' FOR [titulo_articulo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT 0 FOR [codigo_empresa]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [representante_empresa]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [telefono_representante_empresa]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [email_representante_empresa]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [cargo_representante_empresa]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [representante_sindicato]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [telefono_representante_sindicato]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [email_representante_sindicato]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [cargo_representante_sindicato]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [representante_min_trab]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [telefono_representante_min_trab]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [email_representante_min_trab]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [cargo_representante_min_trab]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT getdate() FOR [fecha_ultima_reunion]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT getdate() FOR [fecha_proxima_reunion]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [pdf_peticiones_sindicato]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [pdf_ofertas_patrono]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [estatus_bitacora]
GO

ALTER TABLE  [cccol].[bitacoras]
 ADD DEFAULT N'' FOR [estatu_pase_contratos]
GO


USE cccol
GO
ALTER TABLE  [cccol].[categoria_noticias]
 ADD DEFAULT NULL FOR [nombre_categoria]
GO

ALTER TABLE  [cccol].[categoria_noticias]
 ADD DEFAULT NULL FOR [descripcion_categoria]
GO


USE cccol
GO
ALTER TABLE  [cccol].[categoria_sector]
 ADD DEFAULT N'' FOR [nombre_categoria]
GO


USE cccol
GO
ALTER TABLE  [cccol].[categorias]
 ADD DEFAULT NULL FOR [Nombre_categoria]
GO

ALTER TABLE  [cccol].[categorias]
 ADD DEFAULT NULL FOR [Imagen]
GO

ALTER TABLE  [cccol].[categorias]
 ADD DEFAULT NULL FOR [Prioridad]
GO

ALTER TABLE  [cccol].[categorias]
 ADD DEFAULT N'' FOR [desc]
GO


USE cccol
GO
ALTER TABLE  [cccol].[categorias_titulos]
 ADD DEFAULT N'' FOR [nombre_categoria]
GO

ALTER TABLE  [cccol].[categorias_titulos]
 ADD DEFAULT N'' FOR [requiere_campo_comparacion_economica]
GO


USE cccol
GO
ALTER TABLE  [cccol].[ciudades]
 ADD DEFAULT 0 FOR [Id_pais]
GO

ALTER TABLE  [cccol].[ciudades]
 ADD DEFAULT NULL FOR [Nombre_ciudad]
GO


USE cccol
GO
ALTER TABLE  [cccol].[contratos]
 ADD DEFAULT NULL FOR [fecha_de_inicio]
GO

ALTER TABLE  [cccol].[contratos]
 ADD DEFAULT NULL FOR [fecha_de_termino]
GO

ALTER TABLE  [cccol].[contratos]
 ADD DEFAULT N'' FOR [duracion]
GO

ALTER TABLE  [cccol].[contratos]
 ADD DEFAULT N'' FOR [ambito_aplicacion]
GO

ALTER TABLE  [cccol].[contratos]
 ADD DEFAULT 0 FOR [codigo_empresa]
GO

ALTER TABLE  [cccol].[contratos]
 ADD DEFAULT N'PE' FOR [status_publicacion]
GO


USE cccol
GO
ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [nombre_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_pais]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_estado]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_localidad]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [telefonos_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [fax_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [email_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [url_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [rif_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [nit_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [contacto_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [cargo_contacto]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [telefonos_contacto]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [email_contacto]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_sector]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_tipo]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_categoria]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [codigo_actividad]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [cantidad_obreros_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT 0 FOR [cantidad_empleados_empresa]
GO

ALTER TABLE  [cccol].[empresas]
 ADD DEFAULT N'' FOR [tamano_empresa]
GO


USE cccol
GO
ALTER TABLE  [cccol].[estados]
 ADD DEFAULT 0 FOR [Id_pais]
GO

ALTER TABLE  [cccol].[estados]
 ADD DEFAULT NULL FOR [Nombre_estado]
GO

ALTER TABLE  [cccol].[estados]
 ADD DEFAULT N'' FOR [Usuario_cre]
GO

ALTER TABLE  [cccol].[estados]
 ADD DEFAULT getdate() FOR [Fecha_cre]
GO

ALTER TABLE  [cccol].[estados]
 ADD DEFAULT N'' FOR [Usuario_mod]
GO

ALTER TABLE  [cccol].[estados]
 ADD DEFAULT getdate() FOR [Fecha_mod]
GO


USE cccol
GO
ALTER TABLE  [cccol].[ley_trabajo]
 ADD DEFAULT N'' FOR [fecha_publicacion]
GO

ALTER TABLE  [cccol].[ley_trabajo]
 ADD DEFAULT 0 FOR [total_articulos]
GO


USE cccol
GO
ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT 0 FOR [lit_Sector]
GO

ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT N'' FOR [lit_Detalle]
GO

ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT N'' FOR [lit_Link]
GO

ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT N'' FOR [lit_UsuarioCrea]
GO

ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT getdate() FOR [lit_FechaCrea]
GO

ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT N'' FOR [lit_UsuarioMod]
GO

ALTER TABLE  [cccol].[links_interes]
 ADD DEFAULT getdate() FOR [lit_FechaMod]
GO


USE cccol
GO
ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT 0 FOR [Id_estado]
GO

ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT 0 FOR [Id_pais]
GO

ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT NULL FOR [Nombre_localidad]
GO

ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT NULL FOR [Usuario_cre]
GO

ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT NULL FOR [Fecha_cre]
GO

ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT NULL FOR [Usuario_mod]
GO

ALTER TABLE  [cccol].[localidades]
 ADD DEFAULT NULL FOR [Fecha_mod]
GO


USE cccol
GO
ALTER TABLE  [cccol].[modulos]
 ADD DEFAULT NULL FOR [Nombre_modulo]
GO

ALTER TABLE  [cccol].[modulos]
 ADD DEFAULT NULL FOR [Prioridad]
GO

ALTER TABLE  [cccol].[modulos]
 ADD DEFAULT 0 FOR [Id_categoria]
GO


USE cccol
GO
ALTER TABLE  [cccol].[noticias]
 ADD DEFAULT NULL FOR [codigo_categoria]
GO

ALTER TABLE  [cccol].[noticias]
 ADD DEFAULT NULL FOR [titulo]
GO

ALTER TABLE  [cccol].[noticias]
 ADD DEFAULT NULL FOR [fecha_publicacion]
GO

ALTER TABLE  [cccol].[noticias]
 ADD DEFAULT N'Pendiente' FOR [estatus_noticia]
GO


USE cccol
GO
ALTER TABLE  [cccol].[ofertas_patrono]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[ofertas_patrono]
 ADD DEFAULT N'0' FOR [nro_oferta]
GO

ALTER TABLE  [cccol].[ofertas_patrono]
 ADD DEFAULT N'' FOR [estatus_oferta]
GO

ALTER TABLE  [cccol].[ofertas_patrono]
 ADD DEFAULT 0 FOR [codigo_titulo_comparativo]
GO

ALTER TABLE  [cccol].[ofertas_patrono]
 ADD DEFAULT 0 FOR [codigo_peticion]
GO

ALTER TABLE  [cccol].[ofertas_patrono]
 ADD DEFAULT N'' FOR [titulo_oferta]
GO


USE cccol
GO
ALTER TABLE  [cccol].[otras_leyes]
 ADD DEFAULT 0 FOR [total_articulos_ley]
GO


USE cccol
GO
ALTER TABLE  [cccol].[paises]
 ADD DEFAULT NULL FOR [Nombre_pais]
GO

ALTER TABLE  [cccol].[paises]
 ADD DEFAULT NULL FOR [Usuario_cre]
GO

ALTER TABLE  [cccol].[paises]
 ADD DEFAULT NULL FOR [Fecha_cre]
GO

ALTER TABLE  [cccol].[paises]
 ADD DEFAULT NULL FOR [Usuario_mod]
GO

ALTER TABLE  [cccol].[paises]
 ADD DEFAULT NULL FOR [Fecha_mod]
GO


USE cccol
GO
ALTER TABLE  [cccol].[pantallas]
 ADD DEFAULT N'' FOR [nombre_pantalla]
GO

ALTER TABLE  [cccol].[pantallas]
 ADD DEFAULT N'' FOR [url_pantalla]
GO

ALTER TABLE  [cccol].[pantallas]
 ADD DEFAULT 0 FOR [prioridad]
GO

ALTER TABLE  [cccol].[pantallas]
 ADD DEFAULT 0 FOR [id_modulo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[peticiones_sindicato]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[peticiones_sindicato]
 ADD DEFAULT N'0' FOR [nro_peticion]
GO

ALTER TABLE  [cccol].[peticiones_sindicato]
 ADD DEFAULT N'' FOR [estatus_peticion]
GO

ALTER TABLE  [cccol].[peticiones_sindicato]
 ADD DEFAULT 0 FOR [codigo_titulo_comparativo]
GO

ALTER TABLE  [cccol].[peticiones_sindicato]
 ADD DEFAULT N'' FOR [titulo_peticion]
GO


USE cccol
GO
ALTER TABLE  [cccol].[reuniones]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[reuniones]
 ADD DEFAULT N'' FOR [hora_reunion]
GO

ALTER TABLE  [cccol].[reuniones]
 ADD DEFAULT N'' FOR [duracion_reunion]
GO


USE cccol
GO
ALTER TABLE  [cccol].[reuniones_acuerdos]
 ADD DEFAULT 0 FOR [codigo_reuniones]
GO

ALTER TABLE  [cccol].[reuniones_acuerdos]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[reuniones_acuerdos]
 ADD DEFAULT 0 FOR [codigo_acuerdos]
GO


USE cccol
GO
ALTER TABLE  [cccol].[reuniones_ofertas]
 ADD DEFAULT 0 FOR [codigo_reuniones]
GO

ALTER TABLE  [cccol].[reuniones_ofertas]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[reuniones_ofertas]
 ADD DEFAULT 0 FOR [codigo_ofertas]
GO


USE cccol
GO
ALTER TABLE  [cccol].[reuniones_peticiones]
 ADD DEFAULT 0 FOR [codigo_reuniones]
GO

ALTER TABLE  [cccol].[reuniones_peticiones]
 ADD DEFAULT 0 FOR [codigo_bitacora]
GO

ALTER TABLE  [cccol].[reuniones_peticiones]
 ADD DEFAULT 0 FOR [codigo_peticiones]
GO


USE cccol
GO
ALTER TABLE  [cccol].[seguridades]
 ADD DEFAULT 0 FOR [Id_tipo_Usuario]
GO

ALTER TABLE  [cccol].[seguridades]
 ADD DEFAULT NULL FOR [id_pantalla]
GO


USE cccol
GO
ALTER TABLE  [cccol].[tipo_empresa]
 ADD DEFAULT N'' FOR [nombre_tipo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[tipos_de_usuarios]
 ADD DEFAULT NULL FOR [Nombre_tipo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[titulos]
 ADD DEFAULT N'' FOR [nombre_titulo]
GO

ALTER TABLE  [cccol].[titulos]
 ADD DEFAULT 0 FOR [codigo_categoria_titulo]
GO


USE cccol
GO
ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT N'' FOR [Login_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT NULL FOR [Nombre_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT NULL FOR [Codigo_empresa]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT NULL FOR [Codigo_tipo_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT NULL FOR [Direccion_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT NULL FOR [Telefono_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT NULL FOR [Email_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT N'' FOR [Clave_usuario]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT getdate() FOR [fech_venc]
GO

ALTER TABLE  [cccol].[usuarios]
 ADD DEFAULT N'' FOR [estatus]
GO

