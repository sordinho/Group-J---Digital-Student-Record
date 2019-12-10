# How to run test and code coverage for generating report for sonarQube

- Mettere PHPunit.phar dentro la cartella Tests (Settings->Languages&Frameworks->PHP->TestFrameworks path to php unit and click download)
- Mettere php nel path di windows
- Installare xDebug [Guida](https://xdebug.org/docs/install)
- Run command
    - ``` php -dzend_extension=xdebug phpunit.phar --no-configuration --coverage-clover phpunit.coverage.xml --log-junit phpunit.report.xml C:\xampp\htdocs\Group-J---Digital-Student-Record\Tests --whitelist C:/xampp/htdocs/Group-J---Digital-Student-Record ``` 
- Su SONARQUBE
    - Seleziona progetto
    - ```Administration->General Settings->Languages->PHP``` 
    - In fondo settare:
        - Coverage Reports = ```Tests/phpunit.coverage.xml```
        - Unit Test Report = ```Tests/phpunit.report.xml```
