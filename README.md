# PHPUnit plugin for Kiwi TCMS

[![Build Status](https://travis-ci.org/kiwitcms/phpunit-plugin.svg?branch=master)](https://travis-ci.org/kiwitcms/phpunit-plugin)
[![Tidelift](https://tidelift.com/badges/package/packagist/kiwitcms%2Fphpunit-plugin)](https://tidelift.com/subscription/pkg/packagist-kiwitcms-phpunit-plugin?utm_source=packagist-kiwitcms-phpunit-plugin&utm_medium=github&utm_campaign=readme)
[![Become-a-sponsor](https://opencollective.com/kiwitcms/tiers/sponsor/badge.svg?label=sponsors&color=brightgreen)](https://opencollective.com/kiwitcms#contributors)
[![Twitter](https://img.shields.io/twitter/follow/KiwiTCMS.svg)](https://twitter.com/KiwiTCMS)

## Configuration and environment

#### 1. Install package
Add
```
"minimum-stability": "dev",
```
to your composer.json and execute

```
composer require kiwitcms/phpunit-plugin
```

#### 2. Create `~/.tcms.conf` with the following contents:

```
[tcms]
url =
username =
password =

product =
product_version =
build =

```
The filename `~/.tcms.conf` is expanded to something like `/home/tcms-bot/.tcms.conf` on Linux
and `C:\Users\tcms-bot\.tcms.conf` on Windows, where tcms-bot is the username on the local computer.

It’s also possible to provide system-wide config in `/etc/tcms.conf`, which is valid only on Linux!
On Windows it would be `C:\tcms.conf`.

Execute the following command to find the exact location on your system:
```
php configFilePath.php
```

Set the appropriate values.

You can set all of them as environment variables (config file values have precedence):

```
TCMS_API_URL =
TCMS_USERNAME =
TCMS_PASSWORD =
TCMS_PRODUCT =
TCMS_PRODUCT_VERSION =
TCMS_BUILD =
TCMS_RUN_ID =
```

#### 3. Add listener configuration to phpunit.xml

```
<listeners>
    <listener class="\KiwiTcmsPhpUnitPlugin\PHPUnit\PHPUnitTestListener" file="vendor/kiwitcms/phpunit-plugin/src/PHPUnit/PHPUnitTestListener.php" />
</listeners>
```

### Other

If the product, product version or build do no exist, they will be created.

A new test run and test plan will be created on each run. You can set `run_id` in the config file or `TCMS_RUN_ID` env var, if you want to update a single run.

### License

Distributed under the terms of the [`GNU GPL v3.0`](http://www.gnu.org/licenses/gpl-3.0.txt) license, "kiwitcms/phpunit-plugin" is free and open source software


### Issues

If you encounter any problems, please [file an issue](https://github.com/kiwitcms/phpunit-plugin/issues) along with a detailed description.
