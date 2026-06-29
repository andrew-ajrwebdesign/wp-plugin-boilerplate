<?php
/**
 * PHPUnit bootstrap — loads the Composer autoloader and initialises WP_Mock.
 *
 * Run tests with: ./vendor/bin/phpunit
 *
 * @package AJR_My_Plugin
 * @since   1.0.0
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

WP_Mock::bootstrap();
