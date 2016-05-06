<?php
function asset($path)
{
    return env('APP_URL').'/'.trim($path, '/');
}
