<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class BienSearch
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $titre;
    /**
     * Undocumented variable
     *
     * @var int|null
     */
    private $maxPrice;

    /**
     * Undocumented variable
     *
     * @var int|null
     * @Assert\Range(min=10, max=400)
     */
    private $minSurface;


    /**
     * Get undocumented variable
     *
     * @return  int|null
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set undocumented variable
     *
     * @param  int|null  $maxPrice  Undocumented variable
     *
     * @return  self
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  int|null
     */
    public function getMinSurface()
    {
        return $this->minSurface;
    }

    /**
     * Set undocumented variable
     *
     * @param  int|null  $minSurface  Undocumented variable
     *
     * @return  self
     */
    public function setMinSurface($minSurface)
    {
        $this->minSurface = $minSurface;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $titre  Undocumented variable
     *
     * @return  self
     */
    public function setTitre(string $titre)
    {
        $this->titre = $titre;

        return $this;
    }
}
