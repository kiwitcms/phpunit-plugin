#PHPUnit plugin for Kiwi TCMS

##Configuration and environment

#### 1. Copy `kiwi.tcms.conf.dist` as `kiwi.tcms.conf` and configure

```
TCMS_URL =
TCMS_USERNAME =
TCMS_PASSWORD =
TCMS_PRODUCT =
TCMS_PRODUCT_VERSION =
TCMS_BUILD =
```

You can set all of them as enviroment variables.

#### 2. Add listener configuration to phpunit.xml

```
<listeners>
    <listener class="\KiwiTcmsPhpUnitPlugin\PHPUnit\PHPUnitTestListener" file="/PATH/TO/src/PHPUnit/PHPUnitTestListener.php">
        <arguments>
            <!-- path relative to the working directory phpunit is executed from-->
            <string>kiwi.tcms.conf</string>
        </arguments>
    </listener>
</listeners>
```

### Other

If the product version or build do no exist, they will be created.

A new test run and test plan will be created on each run. You can set `TCMS_RUN_ID` in the config file, if you want to update a single run.

### License

Distributed under the terms of the `GNU GPL v3.0`_ license, "kiwitcms-phpunit-plugin" is free and open source software


###Issues

If you encounter any problems, please `file an issue`_ along with a detailed description.

.. _`GNU GPL v3.0`: http://www.gnu.org/licenses/gpl-3.0.txt
.. _`file an issue`: https://github.com/kiwitcms/phpunit-plugin/issues

