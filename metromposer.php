<?php
namespace Viduc\Metromposer;

use Viduc\Metromposer\Composer\Composer;
use Viduc\Metromposer\Git\Git;

require_once('Composer/Composer.php');
require_once('Composer/ComposerInterface.php');
require_once('Git/GitInterface.php');
require_once('Git/Git.php');
require_once('Configuration/Configuration.php');
require_once('Configuration/ConfigurationInterface.php');

$composer = new Composer();
$composer->genererLaListeDesLibrairiesAmettreAjour();

$git = new Git();
//$git->envoyerLeRapport();
