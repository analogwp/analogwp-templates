<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

$wp_classes   = json_decode( file_get_contents( 'vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-classes.json' ), true );
$wp_functions = json_decode( file_get_contents( 'vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-functions.json' ), true );
$wp_constants = json_decode( file_get_contents( 'vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-constants.json' ), true );

return [
	'prefix'            => 'Analog\\Dependencies',
	'finders'           => array(
		// Enshrined/SVG-Sanitize.
		Finder::create()
		      ->files()
		      ->ignoreVCS( true )
		      ->ignoreDotFiles( true )
		      ->name( '*.php' )
		      ->exclude(
			      array(
				      'tests',
			      )
		      )
		      ->in( 'vendor/enshrined/svg-sanitize' )
		      ->append( [ 'vendor/enshrined/svg-sanitize/composer.json' ] ),
		Finder::create()->append([
			'composer.json',
		]),
	),
	'patchers' => array(
		static function ( string $filePath, string $prefix, string $contents ): string {
			// Change the contents here.

			return $contents;
		},
	),

	'exclude-classes'   => $wp_classes,
	'exclude-functions' => $wp_functions,
	'exclude-constants' => $wp_constants,
];
