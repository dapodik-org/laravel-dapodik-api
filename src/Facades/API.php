<?php

namespace Dapodik\Laravel\API\Facades;

use Dapodik\Laravel\API\APIManager;
use Dapodik\Laravel\API\Connection;
use Dapodik\Laravel\API\Contracts\ResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Connection connection(string $name = null)
 * @method static ResponseContract request(string $method, $uri, array $options = [])
 * @method static ResponseContract query($uri, array $query = [], string $method = 'GET')
 * @method static ResponseContract getSekolah()
 * @method static ResponseContract getPengguna()
 * @method static ResponseContract getRombonganBelajar()
 * @method static ResponseContract getRombel()
 * @method static ResponseContract getPesertaDidik()
 * @method static ResponseContract getPd()
 * @method static ResponseContract getGtk()
 * @method static bool isConnect()
 * @method static string getDefaultConnection()
 *
 * @see APIManager
 */
class API extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'dapodik.api.laravel';
    }
}
