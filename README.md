# Logic

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

Laravel's String Logic Converter

## Install (Composer)

Via Composer or copy source files to vendor folder

``` bash
$ composer require theartgames/logic
```
## Install (manually @required)

Copy package to folder `packages` into root directory then add
the following to your `composer.json`:

``` json
"autoload": {
    "psr-4": {
        "Theartgames\\Logic\\": "packages/Theartgames/Logic/src",
        "App\\": "app/"
    }
},
```
### After Install

Add the following to config/app providers `Theartgames\Logic\LogicServiceProvider::class,`.

Add the following to config/app alias `'Logic' => Theartgames\Logic\Facades\Logic::class,`.

## Usage

``` php
Logic::registerVariable('a', true);
Logic::registerVariable('b', false);
Logic::registerVariable('c', true);
Logic::registerVariable('d', true);

$answer = Logic::execute('$a and ($b or ($a and $c))');
var_dump($answer);
```
###### OR

``` php
$varRegister = array(
  'a' => true,
  'b' => false,
  'c' => true,
  'd' => true
);
$answer = Logic::execute('$a and ($b or ($a and $c))', $varRegister, $masterKey);
var_dump($answer);
```
###### OR Collections

``` php
$varRegister = array(
  array(
    'string' => '{1921} AND ({1923} OR ({1925} AND {1924}))',
    'vars' => array(
      '1921' => true,
      '1923' => false,
      '1924' => true,
      '1925' => true
    ),
    'masterKey' => '1929',
    'eval' => true
  ),
  array(
    'string' => '{1921} AND ({1923} OR ({1925} AND {1924}))',
    'vars' => array(
      '1921' => true,
      '1923' => false,
      '1924' => true,
      '1925' => true
    ),
    'masterKey' => '1922',
    'eval' => false
  )
);
$answer = Logic::massExecute($varRegister);
```



## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email daniel@artgames.ro instead of using the issue tracker.

## Credits

- [Daniel Placinta][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/theartgames/logic.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/theartgames/logic/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/theartgames/logic.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/theartgames/logic.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/theartgames/logic.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/theartgames/logic
[link-travis]: https://travis-ci.org/theartgames/logic
[link-scrutinizer]: https://scrutinizer-ci.com/g/theartgames/logic/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/theartgames/logic
[link-downloads]: https://packagist.org/packages/theartgames/logic
[link-author]: https://github.com/akizor
[link-contributors]: ../../contributors
