## Menéame Responde

Este es uno de esos típicos proyectos que se te pasan por la cabeza y loas haces en tus ratos libres.

La idea es recopilar los <a href="https://www.meneame.net/m/TeRespondo">TeRespondo</a> y agrupar las preguntas y respuestas para que se visualice en formato entrevista.

La ordenación es de preguntas más valoradas a menos, hasta un mínimo de 10 de karma.

Puedes ver una demo aquí https://meneame-responde.lito.com.es/

### Instalación

```
$> git clone https://github.com/eusonlito/meneame-responde.git
$> cd meneame-response
$> composer install
$> cp .env.example .env

# Configurar la base de datos en el fichero .env

$> php artisan migrate
$> php artisan post:read
```

### Configuración de Cachés

El `.htaccess` existente en `public/` permite a Apache recuperar cachés en HTML de cada página. Estas cachés se generan desde PHP y se almacenan en `storage/cache/` evitando así realizar peticiones a código PHP de páginas prácticamente estáticas.

Para poder utilizar estas cachés, simplemente configura la variable `APP_CACHE=true` del fichero `.env`. Sólo se generan estáticos HTML de peticiones válidas.

Para configurarlo en nginx, puedes utilizar el siguiente código:

```
set $cachefile "";

if ($uri ~ ^[a-z0-9/-]+$) {
    set $cachefile $uri;
}

if ($uri = "/") {
    set $cachefile "index";
}

if ($uri = "") {
    set $cachefile "index";
}

if ($query_string) {
    set $cachefile "";
}

if ($cachefile) {
    set $cachefile "/storage/cache/$cachefile.html";
}

location / {
    try_files $cachefile $uri $uri/ /index.php?$query_string;
}
```

### Actualización de contenidos

Para refrescar los contenidos puedes configurar el comando `php artisan post:read` en un cronjob:

```
MR_PATH="/var/www/dominio.com/httpdocs"

# m h  dom mon dow   command

05 * * * * cd $MR_PATH; php artisan post:read >> $MR_PATH/storage/logs/cron-post-read.log 2>&1
```

El propio comando limpia las cachés existentes hasta el momento.