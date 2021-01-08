<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace Viduc\Metromposer\Composer;

use Viduc\Metromposer\Configuration\Configuration;
use Viduc\Metromposer\Exception\MetromposerException;

require_once('ComposerInterface.php');

class Composer implements ComposerInterface
{
    private Configuration $configuration;

    public function __construct()
    {
        $this->configuration = new Configuration();
    }
    /**
     * Récupère les versions des fichiers phar disponibles
     * @return array
     * @test testRecupererLesVersions()
     */
    final public function recupererLesVersions() : array
    {
        $versions = [];
        $files = scandir(
            $this->configuration->recupererPathLibrairie()
            . 'Composer'
        );
        foreach ($files as $file) {
            if (strpos($file, 'composer-') === 0) {
                $version = str_replace(
                    ['composer-', '.phar'],
                    '',
                    $file
                );
                $versions[] = $version;
            }
        }

        return $versions;
    }

    /**
     * Génère le fichier des librairies à mettre à jour
     * @throws MetromposerException
     * @test testGenererLaListeDesLibrairiesAmettreAjour()
     */
    final public function genererLaListeDesLibrairiesAmettreAjour() : void
    {
        $output=null;
        $retval=null;
        $commande = 'cd ' . $this->configuration->recupererPathApplication() ;
        //$commande = 'cd /var/www/blop; ';
        $commande .= 'php -d memory_limit=-1 ';
        $commande .= $this->configuration->recupererPathLibrairie();
        $commande .= 'Composer/composer-';
        $commande .= $this->configuration->recupereUnParametre('composer');
        $commande .= '.phar outdated 2> /dev/null';

        exec($commande, $output, $retval);
        $html = "<!DOCTYPE html>";
        $html .= '<html lang="fr"><head><meta charset="utf-8">';
        $html .= '<title>METROMPOSER</title></head><body>';
        $html .= '<link rel="stylesheet" ';
        $html .= 'href="./ressources/bootstrap.min.css">';
        $html .= '<script type="text/javascript" ';
        $html .= 'src="./ressources/jquery.js"></script>';
        $html .= '<script type="text/javascript" ';
        $html .= 'src="./ressources/dataTables.bootstrap4.min.js"></script>';
        $html .= '<script type="text/javascript" ';
        $html .= 'src="./ressources/jquery.dataTables.min.js"></script>';
        $html .= '<div class="container-fluid">';
        $html .= '<div class="card text-white bg-secondary"><div class="card-body">';
        $html .= '<h3 class="card-title">Application: ';
        $html .= $this->configuration->recupereUnParametre('application');
        $html .= '</h3><h4 class="card-title">Metromposer [version: ';
        $html .= $this->configuration->recupererLaVersionDeLaLibrairie();
        $html .= ']</h4><p class="card-text">Serveur: ';
        $html .= $this->configuration->recupereUnParametre('serveur') . '</p>';
        $html .= '</h4><p class="card-text">Date du rapport: ';
        $html .= date("d/m/Y H:i:s") . '</p>';
        $html .= '<a href="';
        $html .= $this->configuration->recupereUnParametre('url');
        $html .= '" class="card-link text-danger">Lien vers l\'application</a>';
        $html .= '<a href="#" class="card-link float-right text-success">metromposer</a>';
        $html .= '<table id="rapport" class="table table-striped table-bordered">';
        $html .= '<thead><tr>';
        $html .= '<th scope="col">TYPE</th>';
        $html .= '<th scope="col">Librairie</th>';
        $html .= '<th scope="col">Version</th>';
        $html .= '<th scope="col">Commentaire</th>';
        $html .= '</tr></thead>';
        foreach ($output as $ligne) {
            $html .= $this->formaterLigneComposer($ligne);
        }
        $html .= '</table></div></div>';
        $html .= '<script>$(document).ready(function() ';
        $html .= '{$("#rapport").DataTable();} );</script>';
        $html .= '</body></html>';
        file_put_contents(
            $this->configuration->recupererPathApplication() .
            'metromposer/' .
            $this->configuration->recupereUnParametre('application') . '.html',
            $html
        );
    }

    /**
     * Formate une ligne
     * @param string $ligne
     * @return string
     * @test testFormaterLigneComposer()
     */
    final public function formaterLigneComposer(string $ligne) : string
    {
        $ligne = preg_replace('/\s+/', ' ', $ligne);
        $ligne = preg_replace('/\t+/', ' ', $ligne);
        $ligne = preg_replace('/\n\r+/', ' ', $ligne);
        $tabLigne = explode(' ', $ligne);
        $retour = '';
        if (isset($tabLigne[0]) && strpos($tabLigne[0], '/')) {
            $retour .= '<tr>';
            if ($tabLigne[2] === '!') {
                $retour .= '<td>MIN</td>';
            } else {
                $retour .= '<td>MAJ</td>';
            }
            $retour .= '<td>' . $tabLigne[0] . '</td>';
            $retour .= '<td style="width: 10%">' . $tabLigne[1] . ' -> ' . $tabLigne[3] . '</td>';
            $commentaire = '';
            for ($i = 4, $iMax = count($tabLigne); $i < $iMax; $i++) {
                $commentaire .= $tabLigne[$i] . ' ';
            }
            $retour .= '<td>' . $commentaire . '</td>';
            $retour .= '</tr>';
        } /*else {
            $retour .= '<tr>';
            $retour .= '<td>INFO</td>';
            $retour .= '<td colspan="3">' . $ligne . '</td>';
            $retour .= '</tr>';
        }*/
        return $retour;
    }
}