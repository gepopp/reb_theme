<?php
/**
 * Kickoff theme setup and build
 */

namespace reb_livestream_theme;

use reb_livestream_classes\Boot;

define('reb_livestream_theme_VERSION', wp_get_theme()->version);
define('reb_livestream_theme_DIR', __DIR__);
define('reb_livestream_theme_URL', get_template_directory_uri());


$loader = require_once( reb_livestream_theme_DIR . '/vendor/autoload.php' );
$loader->addPsr4('reb_livestream_classes\\', __DIR__ . '/classes');

\A7\autoload(__DIR__ . '/src');

new Boot();