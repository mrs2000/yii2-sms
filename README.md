yii2-sms
=

Extension for sending SMS

[![Latest Stable Version](https://img.shields.io/packagist/v/mrssoft/yii2-sms.svg)](https://packagist.org/packages/mrssoft/yii2-sms)
![PHP](https://img.shields.io/packagist/php-v/mrssoft/yii2-sms.svg)
![Total Downloads](https://img.shields.io/packagist/dt/mrssoft/yii2-sms.svg)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mrssoft/yii2-sms "*"
```

or add

```
"mrssoft/yii2-sms": "*"
```

to the require section of your `composer.json` file.

Usage
-----

Configuration:

```php
'components' => [
    ...
    'sms' => [
        'class' => 'mrssoft\sms\drivers\tele2\Sms',
        'login' => '',
        'password' => '******',
        'naming' => 'BRAND',
    ],
    ...
    'sms' => [
        'class' => 'mrssoft\sms\drivers\mts\Sms',
        'token' => '',
        'naming' => 'BRAND',
    ],
    ....
]
```

Usage:

```php
Yii::$app->sms->sendMessage('Message', '79830000000');
Yii::$app->sms->sendMessages('Message', ['79830000000', '79830000001']);
```