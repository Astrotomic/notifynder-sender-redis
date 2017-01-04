# Notifynder 4 Redis Sender - Laravel 5

[![GitHub release](https://img.shields.io/github/release/astrotomic/notifynder-sender-redis.svg?style=flat-square)](https://github.com/astrotomic/notifynder-sender-redis/releases)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/astrotomic/notifynder-sender-redis/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/astrotomic/notifynder-sender-redis.svg?style=flat-square)](https://github.com/astrotomic/notifynder-sender-redis/issues)
[![Total Downloads](https://img.shields.io/packagist/dt/astrotomic/notifynder-sender-redis.svg?style=flat-square)](https://packagist.org/packages/astrotomic/notifynder-sender-redis)

[![StyleCI](https://styleci.io/repos/78025534/shield)](https://styleci.io/repos/78025534)

[![Code Climate](https://img.shields.io/codeclimate/github/Astrotomic/notifynder-sender-redis.svg?style=flat-square)](https://codeclimate.com/github/Astrotomic/notifynder-sender-redis)

[![Slack Team](https://img.shields.io/badge/slack-astrotomic-orange.svg?style=flat-square)](https://astrotomic.slack.com)
[![Slack join](https://img.shields.io/badge/slack-join-green.svg?style=social)](https://notifynder.signup.team)


Documentation: **[Notifynder Docu](http://notifynder.info)**

-----

## Installation

### Step 1

```
composer require astrotomic/notifynder-sender-redis
```

### Step 2

Add the following string to `config/app.php`

**Providers array:**

```
Astrotomic\Notifynder\NotifynderSenderRedisServiceProvider::class,
```

### Step 3

Add the following array to `config/notifynder.php`

```php
'senders' => [
    'redis' => [
        'channel' => 'notifynder.{type}.{id}.{category}',
        'store' => false, // wether you want to also store the notifications in database
    ],
],
```