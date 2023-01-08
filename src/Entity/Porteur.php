<?php

namespace App\Entity;

use App\Repository\PorteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PorteurRepository::class)]
class Porteur extends Utilisateur
{
    #[ORM\ManyToMany(targetEntity: Bien::class, mappedBy: 'favoris')]
    private Collection $favoris;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
    }

    /**
     * @return Collection<int, Bien>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Bien $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->addFavori($this);
        }

        return $this;
    }

    public function removeFavori(Bien $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            $favori->removeFavori($this);
        }

        return $this;
    }
}
