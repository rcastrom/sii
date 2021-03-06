# SII vr 2.0

*Versi贸n del Sistema Integral de Informaci贸n (SII) de los Institutos Tecnol贸gicos totalmente
desarrollado en Laravel*.

## Comenzando 馃殌

_Es necesario migrar primero la base de datos; en particular, se recomienda PostgreSQL. 
Sin embargo, el proyecto al estar totalmente desarrollado como PDO 
le permitir铆a emplear otro tipo de manejador._

Dentro del proyecto [BDTEC](https://github.com/rcastrom/bdtec) se encuentra una base
de datos con la estructura en PostgreSQL (sin valores) as铆 como las definiciones de 
tablas y procedimientos que, hasta el momento, cuenta el sistema.

Hasta el momento, los m贸dulos que se han migrado son:
* Servicios Escolares (90%).
* Estudiantes (90%).
* Divisi贸n de Estudios Profesionales (80%).
* Jefaturas Acad茅micas (60%).
* Planeaci贸n (40%).
* Coordinaci贸n de Verano (90%).
* Desarrollo Acad茅mico (1%).

### Pre-requisitos 馃搵

_Versi贸n m铆nima de PHP: 7.3 y se recomienda a PostgreSQL como manejador de base de datos, en
cuyo caso, deber谩 contar con la extensi贸n php7.3_pgsql_

```
* sudo apt install php7.3-pgsql
* sudo service apache2 restart
```
>
> En caso de emplear Ningx (*RECOMENDADO*), favor de seguir las indicaciones del 
> siguiente: [Nginx](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-20-04-es)
>
>Posteriormente, deber谩 habilitar la extensi贸n de pgsql en fpm.
>

_Adicionalmente, debe contar con composer instalado_
`https://getcomposer.org/download/`

### Instalaci贸n 馃敡

Desde terminal, dirigirse a un punto donde descargar谩 el proyecto; por ejemplo, suponga 
_/home/<su_usuario>/Escritorio/_ y ah铆 crear谩 una carpeta llamada _sii_; entonces 

````
cd /home/<su_usuario>/Escritorio
mkdir sii

````

Posteriormente, ingresar a dicha carpeta y descargar el proyecto 

```
git clone https://github.com/rcastrom/escolares.git 
```

Una vez descargados los archivos que conforman tanto a Laravel as铆 como al proyecto de 
SII, mueva la carpeta _escolares_ hacia la ruta donde se carga la informaci贸n web 
(t铆picamente _/var/www/html/_); a煤n en terminal

````
cd /var/www/html/
sudo mv /home/<su_usuario>/Escritorio/sii/escolares .
sudo chown -R www-data:www-data escolares
````

Existen tres carpetas que _composer_ requiere ser escribibles

````
cd escolares
sudo chmod -R 777 vendor
sudo chmod -R 777 bootstrap
sudo chmod -R 777 storage

````

Se deben actualizar e instalar los paquetes necesarios para su ejecuci贸n 
(declarados en composer.json); para ello, emplee la instrucci贸n

```
composer update
```

Hecho eso, debe copiarse el archivo ".env.example" como ".env"

```
sudo cp .env.example .env
sudo chown www-data:www-data .env
```

En el archivo reci茅n creado (_.env_) debe indicar los datos necesarios para
su proyecto (tales como URL, usuario y contrase帽a para la base de datos del proyecto);
por ejemplo

````

 APP_ENV=production
 APP_DEBUG=false
 APP_URL=<indicar la URL que emplear谩 para SII>
 DB_CONNECTION=pgsql #Si emplea PostgreSQL como manejador de la BD
 DB_HOST=127.0.0.1
 DB_PORT=5432        #Si emplea PostgreSQL como manejador de la BD
 DB_DATABASE=<su base de datos>
 DB_USERNAME=<su usuario>
 DB_PASSWORD=<su contrase帽a>
 
````

### En caso de emplear Nginx (recomendado)

Laravel emite recomendaciones referentes a la configuraci贸n que se recomienda emplear si 
decide emplear este sistema; por favor, verifique dicha informaci贸n en el siguiente 
[enlace](https://laravel.com/docs/8.x/deployment)

## Despliegue 馃摝

Esta versi贸n ha sido creada (_por el momento_) para los siguientes tipos de usuarios 
(tambi茅n conocidos como "roles"):
* escolares
* alumno
* docente
* verano
* division
* acad
* planeacion
* desacad

Por lo que, deben crearse los usuarios de acuerdo al tipo de rol que van a emplear; para ello, 
desde _<ruta_proyecto>/database/seeders/_ encontrar谩 el archivo *UserTableSeeder.php*, 
mismo que debe usar para dar de alta a todos los usuarios (incluyendo estudiantes). 

En dicho archivo, encontrar谩 un ejemplo del c贸mo se debe crear al usuario tomando como ejemplo
un determinado perfil. 

Por 煤ltimo, solamente debe migrar la informaci贸n hacia la base de datos; para
  ello, desde consola (y estando en la ra铆z del proyecto; por ejemplo, 
  /var/www/html/escolares/), teclee

```
  php artisan db:seed --class=UserTableSeeder
```

  De encontrarse alg煤n error, el sistema le indicar谩 el dato; caso contrario, el sistema
  estar谩 listo para ser empleado. Posteriormente y por seguridad, se le recomienda
  borrar la informaci贸n de los usuarios creados.

## Construido con 馃洜锔?

Herramientas empleadas:

* [Laravel](https://laravel.com/) - El framework web usado
* [PostgreSQL](https://www.postgresql.org/) - Manejador de base de datos
* [Bootstrap](https://getbootstrap.com/) - Usado para el CSS
* [Laravel Angular Admin](https://github.com/silverbux/laravel-angular-admin) - Template administrativo

## Autores 鉁掞笍

* **Ricardo Castro M茅ndez** - *Trabajo Inicial* - [rcastrom](https://github.com/rcastrom)
* **Julia Ch谩vez Remigio** - *Colaboradora y revisora* - [jchavez](mailto:jchavez@ite.edu.mx)

## Licencia 馃搫

Este proyecto est谩 bajo la Licencia (MIT) - mira el archivo [LICENSE.md](LICENSE.md) para 
detalles.

El objetivo del proyecto, es que los institutos tecnol贸gicos que deseen participar con 
observaciones y mejoras, realicen las aportaciones y/o sugerencias necesarias para as铆 
poder contar con un sistema creado por y para los Tecnol贸gicos.
---
