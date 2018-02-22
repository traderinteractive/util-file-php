# util-file-php

[![Build Status](https://travis-ci.org/traderinteractive/util-file-php.svg?branch=master)](https://travis-ci.org/traderinteractive/util-file-php)
[![Code Quality](https://scrutinizer-ci.com/g/traderinteractive/util-file-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/traderinteractive/util-file-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/traderinteractive/util-file-php/badge.svg?branch=master)](https://coveralls.io/github/traderinteractive/util-file-php?branch=master)

[![Latest Stable Version](https://poser.pugx.org/traderinteractive/util-file/v/stable)](https://packagist.org/packages/traderinteractive/util-file)
[![Latest Unstable Version](https://poser.pugx.org/traderinteractive/util-file/v/unstable)](https://packagist.org/packages/traderinteractive/util-file)
[![License](https://poser.pugx.org/traderinteractive/util-file/license)](https://packagist.org/packages/traderinteractive/util-file)

[![Total Downloads](https://poser.pugx.org/traderinteractive/util-file/downloads)](https://packagist.org/packages/traderinteractive/util-file)
[![Monthly Downloads](https://poser.pugx.org/traderinteractive/util-file/d/monthly)](https://packagist.org/packages/traderinteractive/util-file)
[![Daily Downloads](https://poser.pugx.org/traderinteractive/util-file/d/daily)](https://packagist.org/packages/traderinteractive/util-file)

A collection of general util-fileities for making common programming tasks easier.

## Requirements

util-file-php requires PHP 5.4 (or later).

##Composer
To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on
`traderinteractive/util-file` to your project's `composer.json` file such as:

```json
{
    "require": {
        "traderinteractive/util-file": "~1.0"
    }
}
```
##Documentation
Found in the [source](src) itself, take a look!

##Contact
Developers may be contacted at:

 * [Pull Requests](https://github.com/traderinteractive/util-file-php/pulls)
 * [Issues](https://github.com/traderinteractive/util-file-php/issues)

##Project Build
With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:

```sh
./build.php
```

There is also a [docker](http://www.docker.com/)-based
[fig](http://www.fig.sh/) configuration that will execute the build inside a
docker container.  This is an easy way to build the application:
```sh
fig run build
```

For more information on our build process, read through out our [Contribution Guidelines](CONTRIBUTING.md).
