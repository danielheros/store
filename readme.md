

## Instalación

Clonar el repositorio

    git clone https://github.com/danielheros/store.git

Cambiar al directorio del proyecto

    cd store

Instalar las dependencias usando composer

    composer install

Generar los ficheros 'autoload' cargando las clases agregadas

	composer dump-autoload


Copiar el archivo env de ejemplo y realice los cambios de configuración necesarios en el archivo .env (definir la conexión a la base de datos)

    cp .env.example .env

Generar una nueva clave de aplicación

    php artisan key:generate


Ejecutar migraciones de base de datos

    php artisan migrate



Iniciar el servidor de desarrollo local.

    php artisan serve

Ahora puede acceder al servidor en http://localhost:8000.
