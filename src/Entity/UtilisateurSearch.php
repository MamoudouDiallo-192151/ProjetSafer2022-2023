<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UtilisateurSearch
{
    /**
     * Undocumented variable
     *
     * @var string|null
     */
    private $nomUtilisateur;




    /**
     * Get undocumented variable
     *
     * @return  int|null
     */
    public function getNomUtilisateur()
    {
        return $this->nomUtilisateur;
    }

    /**
     * Set undocumented variable
     *
     * @param  int|null  $maxPrice  Undocumented variable
     *
     * @return  self
     */
    public function setNomUtilisateur($nomUtilisateur)
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }
}
