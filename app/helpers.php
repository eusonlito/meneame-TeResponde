<?php
function asset($path)
{
    return env('APP_URL').'/'.trim($path, '/');
}

function back($default)
{
    if (!($referer = getenv('HTTP_REFERER'))) {
        return route($default);
    }

    $url = parse_url($referer);

    if (($url['path'] !== getenv('REQUEST_URI')) && ($url['host'] === getenv('SERVER_NAME'))) {
        return $referer;
    }

    return route($default);
}
