# CodeIgniter Test Application for ci-phpunit-test

This CodeIgniter Application is for testing of [ci-phpunit-test](https://github.com/kenjis/ci-phpunit-test).

## Requirements

* PHP 7.2 or later

## How to Run Tests

~~~
$ git clone -b 2.x https://github.com/kenjis/ci-app-for-ci-phpunit-test.git
$ cd ci-app-for-ci-phpunit-test
$ composer update
$ ./install.php
$ ./test.sh
~~~

## Related Projects for CodeIgniter 3.0

* [CodeIgniter Composer Installer](https://github.com/kenjis/codeigniter-composer-installer)
* [Cli for CodeIgniter 3.0](https://github.com/kenjis/codeigniter-cli)
* [ci-phpunit-test](https://github.com/kenjis/ci-phpunit-test)
* [CodeIgniter Simple and Secure Twig](https://github.com/kenjis/codeigniter-ss-twig)
* [CodeIgniter Doctrine](https://github.com/kenjis/codeigniter-doctrine)
* [CodeIgniter Deployer](https://github.com/kenjis/codeigniter-deployer)
* [CodeIgniter3 Filename Checker](https://github.com/kenjis/codeigniter3-filename-checker)
* [CodeIgniter Widget (View Partial) Sample](https://github.com/kenjis/codeigniter-widgets)
* [CodeIgniter3 Namespaced Controller](https://github.com/kenjis/codeigniter3-namespaced-controller)

## PDF Summarizer

This project now includes a simple PDF summarizer page:

1. Open `/index.php/pdf-summary` in your browser.
2. Upload a PDF file (text-based PDF, max 10 MB).
3. The app extracts text using `pdftotext` and returns a short summary.

> Note: `pdftotext` must be available on the server PATH.
