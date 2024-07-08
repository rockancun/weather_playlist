## Acerca de este proyecto

Este proyecto utiliza el Framework laravel 11.x y aprovecha los recursos y patrones que este
framework provee.

La arquitectura usada es la propuesta por el propio framework adicionando un
folder Src dentro de de app que contiene la lógica de negocio de la aplicación.

La solución ofrece dos URL para hacer las peticiones de listas de reproducción
una para obtener la lista por ciudad 

    localhost/api/v1/playlist/getByCity/London // ejemplo

y otra por coordenadas

    localhost/api/v1/playlist/getByCoordinates/51.5074/0.1278

Como formato de respuesta Json se utiliza el estándar
Jsend - https://github.com/omniti-labs/jsend

La solución registra las solicitudes realizadas en la tabla play_list_statistics generación de estadísticas.
Para desacoplar esta funcionalidad de la lógica principal se utilizan eventos (proporcionados por laravel)
Queda pendiente para futuras mejoras el que estos eventos se ejecuten de manera asíncrona mandándolas a una cola de espera.

La solución recurre a la inyección de dependencias para favorecer el diseño por
composición y así mantener el acoplamiento al mínimo.

Nota: En la clase AppServiceProvider se pueden encontrar las implementaciones usadas para el ambiente productivo.

Se utiliza el concepto de Repositorio para encapsular la funcionalidad se peticiones
a los servicios externos (Openweather y Spotify) y poder falsear las peticiones en tiempo de Testing.
Adicionalmente se dejan un par de tests como pruebas de integración con los servicios:
SpotifyRepositoryAerniTest y OpenWeatherRepositoryTest

La solución utiliza dependencias para obtener el clima y la lista de de canciones
de spotify para agilizar la implementación. Las cuales son:

aerni/laravel-spotify
rakibdevs/openweather-laravel-api



## Iniciar

### Instalación de sail

Este proyecto utiliza laravel sail con interfaz para interactuar con el proyecto
en un ambiente de desarrollo usando Docker.
Para instalar sail se ejecta el siguiente comando 

    php artisan sail:install

A continuación selecciona la opción mysql como base de datos.

### Iniciar los contenedores

Para comenzar se tiene que ejecutar uno de los siguientes comandos para levantar el entorno

    ./vendor/bin/sail up
    ./vendor/bin/sail up -d  #modo silencioso

### Instalación de dependencias

    ./vendor/bin/sail composer install


### Migración de la base de datos

Para migrar la base de datos se ejecuta en raíz del proyecto el comando

    ./vendor/bin/sail artisan migrate


## Configuración

La aplicación ya viene preconfigurada solo es requiere que se establezcan
las variables de entorno en el archivo .env:

Credenciales de acceso para openwather.

    OPENWEATHER_API_KEY=
    OPENWEATHER_API_LANG=en


Credenciales de acceso para Spotify.

    SPOTIFY_CLIENT_ID=
    SPOTIFY_CLIENT_SECRET=

## Detener los contenedores
    ./vendor/bin/sail stop

## Testing

Para ejecutar las pruebas se ejecuta en raíz del proyecto el comando

    ./vendor/bin/sail  artisan test

