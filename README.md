# SII vr 3.0

*Versión del Sistema Integral de Información (SII) de los Institutos Tecnológicos totalmente
desarrollado en [Laravel](https://laravel.com/)*.

## Comenzando 🚀

_Es necesario migrar primero la base de datos; en particular, se recomienda PostgreSQL.
Sin embargo, el proyecto al estar totalmente desarrollado como PDO
le permitiría emplear otro tipo de manejador._

Dentro del proyecto [BDTEC](https://github.com/rcastrom/bdtec) se encuentra una base
de datos con la estructura en PostgreSQL (sin valores) así como las definiciones de
tablas y procedimientos que, hasta el momento, cuenta el sistema.

Hasta el momento, los módulos que se han migrado son:
* Servicios Escolares (90%).
* Estudiantes (90%).
* División de Estudios Profesionales (80%).
* Jefaturas Académicas (60%).
* Planeación (40%).
* Coordinación de Verano (90%).
* Desarrollo Académico (1%).
* Personal docente (90%)

### Pre-requisitos 📋

#### PHP
Versión mínima de PHP: 8.2 y se recomienda a PostgreSQL como manejador de base de datos, en
cuyo caso, deberá contar con la extensión _pgsql_

```
sudo apt install php8.2-pgsql
sudo service apache2 restart
```
>
> En caso de emplear Ningx (*RECOMENDADO*), favor de seguir las indicaciones del
> siguiente enlace: [Nginx](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-20-04-es)
>
>Posteriormente, deberá habilitar la extensión de pgsql en fpm.
>

#### Composer
Deberá contar con composer instalado_
`https://getcomposer.org/download/`

#### Node
El enlace mostrado [NPM](https://www.freecodecamp.org/espanol/news/como-instalar-nodejs-en-ubuntu-y-actualizar-npm-a-la-ultima-version/),
es especialmente si su sistema operativo es en Ubuntu; de no ser así, favor de consultar de 
acuerdo a su distro.

#### Curl
La versión mínima requerida es la 7.34.0
```
sudo apt install curl
```

### Instalación 🔧

Desde terminal, dirigirse a un punto donde descargará el proyecto; por ejemplo, suponga
_/home/<su_usuario>/Escritorio/_; entonces

````
cd /home/<su_usuario>/Escritorio
````

Posteriormente, ingresar a dicha carpeta y descargar el proyecto

```
git clone https://github.com/rcastrom/sii.git 
```

Ingrese a la carpeta _sii_ que el sistema acaba de crear, y realice una actualización de la información

```
composer update
```

Hecho eso, debe copiarse el archivo ".env.example" como ".env"

```
sudo cp .env.example .env
sudo chown www-data:www-data .env
```

En el archivo recién creado (_.env_) debe indicar los datos necesarios para
su proyecto (tales como URL, usuario y contraseña para la base de datos del proyecto);
por ejemplo

````

 APP_ENV=production
 APP_DEBUG=false
 APP_URL=<indicar la URL que empleará para SII>
 DB_CONNECTION=pgsql #Si emplea PostgreSQL como manejador de la BD
 DB_HOST=127.0.0.1
 DB_PORT=5432        #Si emplea PostgreSQL como manejador de la BD
 DB_DATABASE=<su base de datos>
 DB_USERNAME=<su usuario>
 DB_PASSWORD=<su contraseña>
 
````
Por último, mueva la carpeta _sii_ hacia la ruta donde se carga la información web
(típicamente _/var/www/html/_); por lo que, aún en terminal

````
cd /var/www/html/
sudo mv /home/<su_usuario>/Escritorio/sii/ .
sudo chown -R www-data:www-data sii
````
### En caso de emplear Nginx (recomendado)

Laravel emite recomendaciones referentes a la configuración que se recomienda emplear si
decide emplear este sistema; por favor, verifique dicha información en el siguiente
[enlace](https://laravel.com/docs/8.x/deployment)

## Despliegue 📦

Esta versión ha sido creada hasta el momento para los siguientes perfiles de usuarios
(también conocidos como _roles_):
* escolares
* alumno
* docente
* verano
* division
* acad
* planeacion
* desacad
* personal

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

## Autores ✒️

* **Ricardo Castro Méndez** - *Trabajo Inicial* - [rcastrom](https://github.com/rcastrom)
* **Julia Chávez Remigio** - *Colaboradora y revisora* - [jchavez](mailto:jchavez@ite.edu.mx)

## Licencia 📄

Este proyecto está bajo la Licencia (MIT) - mira el archivo [LICENSE.md](LICENSE.md) para
detalles.

El objetivo del proyecto, es que los institutos tecnológicos que deseen participar con
observaciones y mejoras, realicen las aportaciones y/o sugerencias necesarias para así
poder contar con un sistema creado por y para los Tecnológicos.
---
