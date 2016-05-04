## Menéame Responde

Este es uno de esos típicos proyectos que se te pasan por la cabeza y loas haces en tus ratos libres.

La idea es recopilar los <a href="https://www.meneame.net/m/TeRespondo">TeRespondo</a> y agrupar las preguntas y respuestas para que se visualice en formato entrevista.

La ordenación es de preguntas más valoradas a menos, hasta un mínimo de 10 de karma.

Puedes ver una demo aquí https://meneame-responde.lito.com.es/

Para instalar:

```
$> git clone https://github.com/eusonlito/meneame-responde.git
$> cd meneame-response
$> composer install
$> cp .env.example .env

# Configurar la base de datos en el fichero .env

$> php artisan migrate
$> php artisan post:read
```