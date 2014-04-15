# phpWowErrorHandler

Wow ! Error Handler, caught fatal error message in error_log.

## Requirement

PHP 5.3+

## Usage

### Standalone WowLog library

```
include '../src/Wow/Exception/WowErrorHandler.php';

$logDir = = __DIR__ . '/log';

// log to file
new \Wow\Exception\WowErrorHandler(0, $logDir);
```

### Work with Composer

#### Edit `composer.json`

```
{
    "require": {
        "yftzeng/wow-error-handler": "dev-master"
    }
}
```

#### Update composer

```
$ php composer.phar update
```

#### Sample code
```
include 'vendor/autoload.php';

$logDir = = __DIR__ . '/log';

// log to file
new \Wow\Exception\WowErrorHandler(0, $logDir);
```

## Format

```
[Time][Execution Time][Memory Usage][__FILE__:__LINE__] Message
```

## License

the MIT License
