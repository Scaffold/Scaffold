<?php defined('SCAFFOLD') or die();

/**
 * Scaffold Framework bootstrap
 *
 * Do not edit this file, instead, create a custom
 * bootstrap.php file in the application folder.
 * Editing this file could lead to unexpected results.
 */

/**
 * Check if a custom bootstrap exists. If it does,
 * use that, and not the system one.
 */
if (file_exists(APPLICATION . 'bootstrap.php')) {
    require(APPLICATION . 'bootstrap.php');
    die();
}
