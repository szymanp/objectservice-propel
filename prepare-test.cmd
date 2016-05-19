@echo off
mkdir test\propel
mkdir test\propel\conf
mkdir test\propel\sql
call vendor\bin\propel build --config-dir test
call vendor\bin\propel build-sql --config-dir test
call vendor\bin\propel insert-sql --config-dir test
call vendor\bin\propel config:convert --config-dir test
php -f test\testdata.php
