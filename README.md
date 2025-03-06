
## Acerca de la test CRUD Laravel React 

La finalidad de esta prueba es crear un crud con backend Laravel v12 y por parte de frontend se utilizo
React con Inertia para la comunicacion entre sus rutas y componentes 


##  Laravel Skills

Los skills que se utilizaron en el backend fueron los siguientes:

- PHP 8.2 >= 
- Laravel version 12
- Eloquent
- Sqlite
- node
- tailwind
- vite
- Service Provider


## Laravel Composer 

Se instalaron 2 paquetes para poder hacer la autenticacion y procesar correctamente el frontend
react con vite, ademas de cambio del idioma en español 

- laravel/breeze
- laravel-lang/lang

## Pasos para deploy proyecto

Estos son los pasos que se debe  de seguir para poder correr el proyecto correctamente
y asi poder hacer las pruebas requeridas:

- Se clona el proyecto de la rama [main]() 
- Se ejecuta el comando [composer install]() para crear la carpeta vendor
- Se ejecuta el comando [npm install -f]() para crear la carpeta node_modules
- Para que compile react este se ejecuta el comando [npm run dev]() 
- Se debe de configurar el archivo [.env]() en este caso se debe de colocar asi [DB_CONNECTION=sqlite]()
- Se ejecuta el comando para las migraciones [php artisan migrate]() este debe crear las tablas de autenticacion y la tabla que se utilizo llamada posts
- Se ejecuta el comando [php artisan serve]() este para ejecutar el proyecto en el [127.0.0.1:8000]()


## Pasos para revisar el proyecto

Para poder hacer testing del proyecto y revisar la funcionalidad de este mismo CRUD, se debe de seguir lo siguiente:

- En la pantalla principal de Inicio del proyecto aparecen dos enlaces [Log In]() y [Register]() se debe primero hacer un registro.
- Al registrase se loguea en automatico al dashbord del proyecto.
- En la vista del dashboard debe de ver 2 menus [Dashboard]() y  [Posts]()
- Al dirigirnos al enlace de posts este nos muestra el consumo del service provider de [https://publicapis.io/jsonp-laceholder-api](https://publicapis.io/jsonp-laceholder-api) 
- Si no hay informacion en la base de datos de la tabla posts este hace un consumo e inserta los posts relacionadolos por usuarios
- Si hay informacion en la base de datos en la tabla posts, este solo hace una consulta en la tabla.
- Este mostrar el listado, un boton para crear, un boton para editar y otro boton para eliminar.

## Contribucion

Si se tiene cualquier duda dejo mis datos:

- Jorge Martinez Quezada
- +525511308629
- jorge.martinez.developer@gmail.com
- Desarrollador Backend Laravel PHP
