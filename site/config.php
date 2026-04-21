<?php

function getSecret($path) {
    return trim(file_get_contents($path));
}

$config = [
    "db" => [
        "host" => getenv('MYSQL_HOST'),
        "database" => getenv('MYSQL_DATABASE'),
        "username" => getSecret('/run/secrets/user'),
        "password" => getSecret('/run/secrets/secret'),
    ]
];