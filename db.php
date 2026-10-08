<?php 

class DB
{
    public static function connect(): mysqli
    {
        $env = is_file(__DIR__ . '/.env')
            ? parse_ini_file(__DIR__ . '/.env')
            : [];

        $host = getenv('DB_HOST') ?: ($env['DB_HOST'] ?? '127.0.0.1');
        $port = (int) (getenv('DB_PORT') ?: ($env['DB_PORT'] ?? 3306));
        $user = getenv('DB_USER') ?: ($env['DB_USER'] ?? '');
        $pass = getenv('DB_PASS') ?: ($env['DB_PASS'] ?? '');
        $name = getenv('DB_NAME') ?: ($env['DB_NAME'] ?? '');

        return new mysqli($host, $user, $pass, $name, $port);
    }
}