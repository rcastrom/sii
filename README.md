# SII vr 3.0

*Versión del Sistema Integral de Información (SII) para los Institutos Tecnológicos,
realizado totalmente en [Laravel](https://laravel.com/)*.

## Comenzando 🚀

_Es necesario migrar primero la base de datos; en particular, se recomienda PostgreSQL como
manejador para la base de datos; sin embargo, el proyecto al estar totalmente desarrollado 
como PDO, le permitiría emplear otro tipo de manejador._

Dentro del proyecto [BDTEC](https://github.com/rcastrom/bdtec) se encuentra una base
de datos con la estructura en PostgreSQL (sin valores) así como las definiciones de
tablas y procedimientos que, hasta el momento, cuenta el sistema.

Los módulos que se han migrado son:
* Servicios Escolares (98%).
* Estudiantes (100%).
* División de Estudios Profesionales (100%).
* Jefaturas Académicas (100%).
* Coordinación de Verano (90%).
* Desarrollo Académico (98%).
* Personal docente (100%).
* Recursos Humanos (99%).
* Administrativo (50%).

#### Pendientes
En Servicios Escolares:
* Inscripción
* Cierre de semestre

En Desarrollo Académico
* Aceptar aspirantes

En Recursos Humanos
* Migrar histórico de plazas, así como su consulta

### Pre-requisitos 📋

#### PHP
Versión de PHP: 8.4 y se recomienda a PostgreSQL como manejador de base de datos, en
cuyo caso, deberá contar con la extensión _pgsql_.

>
> En caso de emplear Ningx (*RECOMENDADO*), favor de seguir las indicaciones del
> siguiente enlace: [Nginx](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu)
>
>Posteriormente, deberá habilitar la extensión de pgsql en fpm.
>

#### Composer
Deberá contar con [composer](https://getcomposer.org/download/) instalado

#### Node
El enlace mostrado [NPM](https://www.freecodecamp.org/espanol/news/como-instalar-nodejs-en-ubuntu-y-actualizar-npm-a-la-ultima-version/) es especialmente si su sistema operativo es en Ubuntu; de no ser así, 
favor de consultar de acuerdo a su distro.

#### Curl
La versión mínima requerida es la 7.34.0. Verifique su instalación de acuerdo con la 
distribución que maneje.

#### Git
Deberá contar con git para descargar el proyecto.


### Instalación en Apache 🔧

Desde terminal, cree un subdirectorio llamado _sii_ en la ruta _/var/www/html_

```
sudo mkdir /var/www/html/sii
```

Posteriormente, diríjase hacia dicha carpeta y permita que su usuario pueda descargar el proyecto

```
cd /var/www/html
sudo chown -R $USER:USER sii
git clone https://github.com/rcastrom/sii.git 
```

Ingrese a la carpeta _sii_ que el sistema acaba de crear, e instale los paquetes y dependencias
que el sistema requiera

```
composer install
```

Posteriormente, algunas dependencias necesarias de node

```
npm install
```

Una vez terminadas estas acciones, debe copiar al archivo".env.example" como".env".

```
cp .env.example .env
```

En el archivo recién creado (_.env_) debe indicar los datos necesarios para
su proyecto (tales como URL, usuario y contraseña para la base de datos del proyecto);
por ejemplo

```
 APP_NAME=sii
 APP_ENV=production
 APP_DEBUG=false
 APP_URL=<indicar la URL que empleará para SII>
 DB_CONNECTION=pgsql #Si emplea PostgreSQL como manejador de la BD
 DB_HOST=127.0.0.1
 DB_PORT=<puerto>        #Si emplea PostgreSQL como manejador de la BD. Ejem, 5432
 DB_DATABASE=<su base de datos>
 DB_USERNAME=<su usuario>
 DB_PASSWORD=<su contraseña>
 RUTA_IMG_TECNM="/var/www/html/sii/public/img/tecnm.jpg" #Ejemplo de la ruta para indicar el escudo del TECNM
 NOMBRE_TEC="Instituto Tecnológico de Ensenada" #El nombre de su tecnológico
 RUTA_IMG_GOBFED="/var/www/html/sii/public/img/gobfederal.jpg" #Logo del Gob Federal
 UBICACION_CREAR_IMAGENES="/var/www/html/sii/storage/img/" #Ruta temporal para gráficos
 RUTA_IMG_PIE_PAGINA="/var/www/html/sii/public/img/logo_pie_pagina.jpg" #Ruta para pie de página de documentos
 FPDF_FONTPATH="/var/www/html/sii/public/fuentes" #Ruta para las fuentes a emplear en la impresión de documentos PDF
 CIUDAD_OFICIOS="Ensenada, B.C.," #Ciudad y Estado. Ésta información se utiliza para generar los oficios
 CCT="02DIT0023K"        #Clave Centro de Trabajo. Se utiliza para oficios
 LEMA_TEC="POR LA TECNOLOGÍA DE HOY Y DEL FUTURO"  #Lema del Tecnológico 
 DOMICILIO_TEC="Blvd. Tecnológico No. 150 Col. Ex Ejido Chapultepec"    #Domicilio del Tecnológico
 TELEFONO_TEC="(646)177-56-80"    #Teléfono o teléfonos. Se utiliza para el pie de página de los oficios
 CORREO_ESCOLARES="escolares@ite.edu.mx"   #Correo institucional de Escolares. Se utiliza para los oficios
 SITIO_WEB="https://www.ensenada.tecnm.mx"  #Sitio web. Se utiliza para el pie de página de los oficios
```
Es importante hacer notar que la declaración de las variables de entorno siempre son en mayúsculas, y
que no existe espacio en blanco en el signo de igual; es decir, deben definirse como se muestra en los
ejemplos.

#### Con respecto a la base de datos
Debido a que el proyecto fue elaborado en el Instituto Tecnológico de Ensenada (ITE),
el schema empleado no es el habitual (_public_); sino que se llama ITE. 

En caso de que quiera cambiar el schema a otro nombre, deberá cambiar los procedimientos
almacenados, ya que estos buscan a algunas tablas localizadas precisamente en el schema 
llamado ITE.


Verifique entonces que en el archivo _database.php_ ubicado dentro de la carpeta _config_, 
se encuentre presente la línea

```
'search_path' => 'ITE',
```
Dentro del array 'pgsql'.

### Por último
La primera ocasión en que ejecute el proyecto en el navegador, le indicará que la 
carpeta _storage_ (así como sus subdirectorios), requieren de permiso especial de escritura.
Permita que el usuario de apache (o nginx), tenga las facultades sobre el mismo

```
sudo chown -R www-data:www-data storage
```
Dependiendo de su distribución, es posible que deba cambiarle las facultades a las carpetas,
por lo que es posible que le solicite el cambio a 

```
sudo chmod 775 storage
```
Se le recomienda lea el siguiente artículo
[5 consejos para una mejor revisión del código de Laravel](https://diegooo.com/revision-5-consejos-para-codigos-de-laravel/)

Posteriormente, el sistema le indicará que el proyecto requiere contar con una llave
de seguridad (para los formularios); por lo que, en terminal

```
php artisan key:generate
```

Su proyecto debería estar listo para ser empleado :)

### Instalación en Nginx (recomendado)

Laravel emite recomendaciones referentes a la configuración que se recomienda emplear si
decide emplear este sistema; por favor, verifique dicha información en el siguiente
[enlace](https://laravel.com/docs/11.x/deployment)

## Despliegue 📦

Esta versión ha sido creada hasta el momento para los siguientes perfiles de usuarios
(también conocidos como _roles_):
* escolares
* alumno
* verano
* division
* acad
* planeacion
* desacad
* personal
* rechumanos

Por lo que, deben crearse los usuarios de acuerdo al tipo de rol que van a emplear; para ello,
desde _<ruta_proyecto>/database/seeders/_ encontrará el archivo *UserTableSeeder.php*,
mismo que debe usar para dar de alta a todos los usuarios (incluyendo estudiantes).

En dicho archivo, encontrará un ejemplo del cómo se debe crear al usuario tomando como ejemplo
un determinado perfil.

Por último, solamente debe migrar la información hacia la base de datos; para
ello, desde consola (y estando en la raíz del proyecto; por ejemplo,
/var/www/html/sii/), teclee

```
  php artisan db:seed --class=UserTableSeeder
```

De encontrarse algún error, el sistema le indicará el dato; caso contrario, el sistema
estará listo para ser empleado. Posteriormente y por seguridad, se le recomienda
borrar la información de los usuarios creados.


## Construido con 🛠️

Herramientas empleadas:

* [Laravel](https://laravel.com/) - El framework web usado
* [PostgreSQL](https://www.postgresql.org/) - Manejador de base de datos
* [AdminLTE](https://github.com/ColorlibHQ/AdminLTE) - Template administrativo

## Autor ✒️

* **Ricardo Castro Méndez** - *Trabajo Inicial* - [rcastrom](https://github.com/rcastrom)

## Licencia 📄

Este proyecto está bajo la Licencia (MIT) - mira el archivo [LICENSE.md](LICENSE.md) para
detalles.

El objetivo del proyecto, es que los institutos tecnológicos que deseen participar con
observaciones y mejoras, realicen las aportaciones y/o sugerencias necesarias para así
poder contar con un sistema creado por y para los Tecnológicos.
---
