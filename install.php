<?php
namespace Viduc\Metromposer;

use Viduc\Metromposer\Exception\MetromposerException;
use Viduc\Metromposer\Installation\Installation;

require_once('Installation/InstallationInterface.php');
require_once('Installation/Installation.php');
require_once('Installation/Message.php');
require_once('Git/GitInterface.php');
require_once('Git/Git.php');
require_once('Configuration/Configuration.php');
require_once('Configuration/ConfigurationInterface.php');
require_once('Composer/Composer.php');
require_once('Composer/ComposerInterface.php');

$install = new Installation();
try {
    $install->installer();
} catch (MetromposerException $e) {

}




