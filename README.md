# DWESSERV_4.3_Miniproyecto-Taskshield
Modulo desarrollo web entorno servidor 1Trimestre actividad 4.3. Miniproyecto: Taskshield

## 2. Requisitos previos

Antes de empezar, es necesario tener instalado:

- **XAMPP:** incluye Apache, PHP y MySQL (MariaDB).
- **Navegador web:** 
- **Editor de código:**
- **Proyecto Taskshield:**

## 3. Descargar e instalar el proyecto

Copiamos la carpeta del proyecto dentro de la carpeta `htdocs` de XAMPP.

Es importante que los archivos PHP estén dentro de esa carpeta para que Apache pueda ejecutarlos.

## 4. Iniciar los servicios

1. Abrir el panel de control de XAMPP.
2. Iniciar el servicio **Apache**.
3. Iniciar el servicio **MySQL**.
4. Comprobar que ambos servicios aparecen como iniciados.

## 5. Crear la base de datos

Taskshield necesita una base de datos MySQL para almacenar las incidencias y los técnicos.

Para crearla primero entraremos en nuestro xampp con los servicios previos iniciados y entraremos en el servicio MySQL en la parte de admin.

Esto nos abrirá phpMyAdmin y aqui le daremos donde pone sql sin entrar a ninguna base de datos.

Aqui copiaremos el codigo que se encuentra en el archivo bdd.sql.

En el caso de que no funcione copiado todo del tiron fijate en los comentarios de dicho documento y primero copia la estructura y luego las consultas para añadir una incidencia y tecnicos de prueba.

## 6. Configurar la conexión

Abrir el archivo `config.php` y comprobar que los datos de conexión coinciden con la instalación local de MySQL.
Si tienes la configuracion por defecto de xampp no te deberia dar ningun tipo de problema si no la tienes deberas modificar tu documento php.ini en xampp


## 7. Ejecutar Taskshield

Una vez iniciados Apache y MySQL y creada la base de datos:

Abrimos el navegador y en la URL ponemos http://localhost/CARPETA_DONDE_TIENES_EL_PROYECTO/index.php

y ya estariamos utilizando la aplicacion.