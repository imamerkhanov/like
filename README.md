:+1: Like
# Like extension
==============

This extension has been made to test the auto likes


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist imamerkhanov/like "*"
```

or add

```json
"imamerkhanov/like": "*"
```
and 
```
"repositories": [{
    "type": "vcs",
    "url": "https://github.com/imamerkhanov/like"
}]
```
to the require section of your composer.json.


Usage
-----

```php
            $likeOff = new LikeOff(
                [
                    'url'      => 'https://example.com/api',
                    'login'    => $login,
                    'password' => $pass,
                    'debug'    => $debug
                ]
            );
            $likeOff->send('global.start');
```
Credits
-----

Author: Ilshat Amerkhanov

Email: imamerkhanov@bars-open.ru