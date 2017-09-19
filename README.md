# Mozify

[![Build Status](https://travis-ci.org/karster/mozify.svg?branch=master)][travis]
[![Latest Stable Version](https://poser.pugx.org/karster/image/v/stable)][version]
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)][license]

> Email client blocks images by default. Mozify is way how to serve images to your customers.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```shell
composer require karster/mozify:"dev-master"
```

or add

```
"karster/mozify": "dev-master"
```

to the require section of your composer.json.

## Usage

```php
use karster\image\Mozify;

$mozify = new Mozify();
$result = $mozify->setImageSrc('path/to/image.jpg')
        ->setSearchWindow(10)
        ->setColorDepth(8)
        ->setTest(true)
        ->generate();

echo $result;
```

To change image dimensions set width or height. Second attribute will calculate from image ratio.
```php
use karster\image\Mozify;

$mozify = new Mozify();
$result = $mozify->setImageSrc('path/to/image.jpg')
        ->setSearchWindow(10)
        ->setColorDepth(8)
        ->setWidth(500)
        //->setHeight(300)
        ->generate();

echo $result;
```

![Safari][safari]

![Coffee][coffee]


## Tests

```
./vendor/bin/phpunit -c phpunit.xml
```

## Contribution
Have an idea? Found a bug? See [how to contribute][contributing].

## License
MIT see [LICENSE][] for the full license text.

[version]: https://packagist.org/packages/karster/mozify
[travis]: https://travis-ci.org/karster/mozify
[license]: LICENSE.md
[contributing]: CONTRIBUTING.md
[safari]: docs/safari.jpg
[coffee]: docs/coffee.jpg