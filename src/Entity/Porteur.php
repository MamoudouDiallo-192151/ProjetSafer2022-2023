<?php

namespace App\Entity;

use App\Repository\PorteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PorteurRepository::class)]
class Porteur extends Utilisateur
{
}
