[![Latest Stable Version](https://poser.pugx.org/arrilot/sessions/v/stable.svg)](https://packagist.org/packages/arrilot/sessions/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/sessions.svg?style=flat)](https://packagist.org/packages/Arrilot/sessions)
[![Build Status](https://img.shields.io/travis/arrilot/sessions/master.svg?style=flat)](https://travis-ci.org/arrilot/sessions)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/arrilot/sessions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arrilot/sessions/)

#Laravel-like framework agnostic session package

*Use Laravel session Api to work with plain-old php sessions in any project*

## Installation

1) ```composer require arrilot/sessions```

2) Register a service provider anywhere in your bootstrap code.

```php 
Arrilot\Sessions\SessionProvider::register();
```

## Usage

```Arrilot\Sessions\Session``` is the main class provided by the package.
You can treat this class just like the Laravel Session facade and call literally any method listed [here](http://laravel.com/docs/5.0/session) + `Session::now()` from 5.1.

Example:
```php
use Arrilot\Sessions\Session;

Session::flash('message', 'Email was sent');
```

*Note that the package does not actually require laravel session component. It provides Laravel API to work with built-in php sessions ($_SESSION) instead.*
