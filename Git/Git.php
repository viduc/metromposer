<?php

namespace Viduc\Metromposer\Git;

use Viduc\Metromposer\Configuration\Configuration;
use Viduc\Metromposer\Configuration\ConfigurationInterface;
use Viduc\Metromposer\Exception\MetromposerException;

class Git implements GitInterface
{
    private string $urlServeur;
    private ConfigurationInterface $configuration;

    public function __construct(String $urlServeur = null)
    {
        if ($urlServeur !== null) {
            $this->urlServeur = $urlServeur;
        }
        $this->configuration = new Configuration();
    }

    /**
     * Setter pour la variable urlServeur
     * @param string $urlServeur
     * @codeCoverageIgnore
     */
    final public function setUrlServeur(string $urlServeur) : void
    {
        $this->urlServeur = $urlServeur;
    }

    /**
     * Vérifie si le dépot git est accessible
     * @return bool
     * @test testVerifierSiLeDepotEstAccessible()
     */
    final public function verifierSiLeDepotEstAccessible() : bool
    {
        $output=null;
        $retval=null;
        $commande = 'git ls-remote ' . $this->urlServeur;
        $commande .= ' HEAD 2> /dev/null';
        exec($commande, $output, $retval);

        return count($output) > 0 && $retval === 0;
    }

    /**
     * Clone le dépot
     * @return bool
     * @test testClonerLeDepot()
     */
    final public function clonerLeDepot(): bool
    {
        $output=null;
        $retval=null;
        $commande = 'git clone ' . $this->urlServeur . ' ';
        $commande .= $this->configuration->recupererPathApplication();
        $commande .= '/metromposer 2> /dev/null';
        exec($commande, $output, $retval);

        return $retval === 0;
    }

    /**
     * Envoie les modifications sur le dépôt git
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    final public function envoyerLeRapport() : void
    {
        $commande = 'cd ' . $this->configuration->recupererPathApplication() ;
        $commande .= '/metromposer; git add ';
        $commande .= $this->configuration->recupereUnParametre('application');
        $commande .= '.html; ';
        $commande .= 'git commit  -m "maj"; git push 2> /dev/null';
        exec($commande, $output, $retval);
    }
}