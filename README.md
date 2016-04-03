# phpab-module

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A module that makes it possible to use phpab/phpab in Zend Framework 2 applications.

## Install

Via Composer

``` bash
$ composer require phpab/phpab-module
```

## Usage

After installation add the module name to `application.config.php`:

``` php
<?php
return [
    'modules' => [
        'Application',
        'PhpAbModule',
    ],
];
```

Next copy `vendor/phpab/phpab-module/config/phpab.global.php.dist` to `config/autoload/phpab.global.php` 
and adjust the configuration that you want. For the available options take a look at the documentation.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please open an issue in the issue tracker. We realize 
this is not ideal but it's the fastest way to get the issue solved.

## Credits

- [Walter Tamboer](https://github.com/waltertamboer)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/phpab/phpab-module.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/phpab/phpab-module/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/phpab/phpab-module.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/phpab/phpab-module.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phpab/phpab-module.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/phpab/phpab-module
[link-travis]: https://travis-ci.org/phpab/phpab-module
[link-scrutinizer]: https://scrutinizer-ci.com/g/phpab/phpab-module/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/phpab/phpab-module
[link-downloads]: https://packagist.org/packages/phpab/phpab-module
[link-contributors]: https://github.com/phpab/phpab-module/graphs/contributors
