<?php
namespace Cnect\TaskRunner\Commands;

use Drupal\Core\Site\Settings;
use Drupal\Core\DrupalKernel;
use DrupalFinder\DrupalFinder;
use OpenEuropa\TaskRunner\Commands\AbstractCommands;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\PathUtil\Path;

/**
 * Class CnectCommands.
 *
 * @package Cnect\TaskRunner\Commands
 */
class CnectCommands extends AbstractCommands {

  /**
   * Setup additional environment config.
   *
   * @command cnect:setup-extra
   */
  public function extraConfig($target_file = 'settings.override.php') {
    $fs = new Filesystem();
    $settings_folder = "web/sites/default";
    $settings_file = "$settings_folder/$target_file";

    if ($fs->exists($settings_file)) {
      $fs->remove($settings_file);
    }

    $fs->copy('./settings.override.php.dist', $settings_file);
  }

}
