#How to PhpUnit (on PhpStorm)

## Ingredients

- MOOOOOLTA PAZIENZA
- PhpStorm
- Php version >= 7.3
- PhpUnit version 8.4.1

## Steps
Prima di tutto scaricate e installate un [composer](https://getcomposer.org/).

Sul sito al link precedente troverete anche le istruzioni per installarlo su linux e su windows (io ho usato l'installer su windows,
voi se volete usate l'installazione manuale, cambia poco).

Assicurarsi che il PHP interpreter sia settato. (vedi [qui](https://www.jetbrains.com/help/phpstorm/configuring-local-interpreter.html)).

Scaricate [PhpUnit.phar](https://phpunit.de/getting-started/phpunit-8.html) e salvatelo in una cartella (e.g. C:\bin). Fatto questo aggiungete la cartella alle variabili di sistema.
Da linea di comando nella cartella contenente phpunit.phar scrivete :
echo @php "%~dp0phpunit.phar" %* > phpunit.cmd
exit

Sulla console di phpstorm (o da linea di comando nella cartella del nostro progetto)
digitare il seguente comando:

composer require --dev phpunit/phpunit ^8

Dovrebbero comparire i seguenti file:

- composer.json
- composer.lock
- directory "vendor"

A questo punto andate su settings (di phpstorm naturalmente), poi languages&frameworks/php/composer e mettete in "path to composer.json"
il path al file composer punto json precedentemente creato( quello all'interno del nostro progetto insomma).

Spostandoci su test frameworks spuntare use composer autoloader e inserire it path
al file autoload.php della cartella vendor (sempre quella interna al repository del nostro progetto).

Se tutto è andato bene dovrebbe funzionare.
In caso contrario boh, contattatemi e proviamo a risolverlo.
Vi linko un po di roba utile in generale per installare phpunit su phpstom :
- [istruzioni jetbrains](https://www.jetbrains.com/help/phpstorm/using-phpunit-framework.html)
- [istruzioni composer](https://getcomposer.org/doc/00-intro.md)

NB in languages&frameworks/php dovremmo avere come language level almeno 7.3, io sto usando l'interpreter di xampp (7.3.5).