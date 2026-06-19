# Dapodik API for Laravel Framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dapodik-org/laravel-dapodik-api.svg?style=flat-square)](https://packagist.org/packages/dapodik-org/laravel-dapodik-api)
[![GitHub Tests Action Status](https://github.com/dapodik-org/laravel-dapodik-api/actions/workflows/run-tests.yml/badge.svg)](https://github.com/dapodik-org/laravel-dapodik-api/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/dapodik-org/laravel-dapodik-api/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/dapodik-org/laravel-dapodik-api/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/dapodik-org/laravel-dapodik-api.svg?style=flat-square)](https://packagist.org/packages/dapodik-org/laravel-dapodik-api)

## Informasi
Dalam penggunaan API Dapodik berarti Anda secara sadar memberikan data individu setiap entitas Dapodik kepada pihak ketiga. Segala bentuk penyalahgunaan dapat diancam dengan hukuman pidana sesuai dengan UU Perlindungan Data Pribadi No 27 Tahun 2022 Pasal 67. Mohon anda benar-benar telah paham dan yakin akan hal tersebut.

## Requirement
Pastikan [Dapodik](https://dapo.dikdasmen.go.id/unduhan) sudah terinstal di komputer Anda atau di VPS.

## Installation

You can install the package via composer:

```bash
composer require dapodik-org/laravel-dapodik-api
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="dapodik-api-config"
```

## Configuration
Configuration in the `.env` file:

```dotenv
# REST CONNECTION
DAPODIK_API_CONNECTION=authentication
DAPODIK_API_HOST=http://localhost:5774
DAPODIK_API_USERNAME=
DAPODIK_API_PASSWORD=
DAPODIK_API_KODE_REGISTRASI=

# WEBSERVICE CONNECTION
#DAPODIK_API_CONNECTION=authorization
#DAPODIK_API_HOST=http://localhost:5774
DAPODIK_API_NPSN=
DAPODIK_API_TOKEN=
```

## Usage
Example in your route `web.php` file:

```php
use Dapodik\Laravel\API\Facades\API;
use Illuminate\Support\Facades\Route;

// REST EXAMPLE
Route::get('/dapodik-api', function () {
    dd(
        API::query('PesertaDidik', [
            'pd_module' => 'pdterdaftar',
            'limit' => 20,
        ])->toArray()
    );
});

// WEBSERVICE EXAMPLE
Route::get('/dapodik-api', function () {
    dd(
        API::connection('authorization')
            ->query('getSekolah')->toArray(),
        API::connection('authorization')
        ->getSekolah()->toCollection(),
        API::connection('authorization')
        ->getSekolah()->toJson(),
    );
});
```
Run
```bash
php artisan serve
```

Open in browser http://localhost:8000/dapodik-api

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Development Setup

```bash
# Clone repository
git clone https://github.com/dapodik-org/laravel-dapodik-api.git
cd laravel-dapodik-api

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Run tests
composer test
```

### Pull Request

Sebelum membuat pull request, pastikan Anda membuat branch baru dari `main`:

```bash
# Buat branch baru
git checkout -b nama-branch-anda

# Lakukan perubahan dan commit
git add .
git commit -m "deskripsi perubahan"

# Push ke remote
git push origin nama-branch-anda
```

Kemudian buka pull request dari branch tersebut ke branch `main`.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dapodik ORG](https://github.com/dapodik-org)
- [Ade Reksi Susanto](https://github.com/adereksisusanto)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
