# PHPUnit plugin for Kiwi TCMS

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

#### 2. Create `kiwi.tcms.conf` with the following contents:

```
TCMS_API_URL =
TCMS_USERNAME =
TCMS_PASSWORD =
TCMS_PRODUCT =
TCMS_PRODUCT_VERSION =
TCMS_BUILD =

TCMS_VERIFY_SSL_CERTIFICATES = true
```

Set the appropriate values.

You can set all of them as environment variables.

#### 3. Add listener configuration to phpunit.xml

```
<listeners>
    <listener class="\KiwiTcmsPhpUnitPlugin\PHPUnit\PHPUnitTestListener" file="src/PHPUnit/PHPUnitTestListener.php" />
</listeners>
```

### Other

If the product, product version or build do no exist, they will be created.

A new test run and test plan will be created on each run. You can set `TCMS_RUN_ID` in the config file, if you want to update a single run.

### License

Distributed under the terms of the [`GNU GPL v3.0`](http://www.gnu.org/licenses/gpl-3.0.txt) license, "kiwitcms/phpunit-plugin" is free and open source software


### Issues

If you encounter any problems, please [file an issue](https://github.com/kiwitcms/phpunit-plugin/issues) along with a detailed description.

