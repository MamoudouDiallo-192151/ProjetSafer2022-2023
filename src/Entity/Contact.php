<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100, minMessage: "Le nom doit contenir au  moins {{ limit }} caractères")]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, minMessage: "Le nom doit contenir au  moins {{ limit }} caractères")]
    private $prenom;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^[0-9]{8}$/")]
    private $numeroTelephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email]
    private $email;
    /**
     * Undocumented variable
     *
     * @var Bien
     */
    private $bien;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, minMessage: "Le message doit contenir au  moins {{ limit }} caractères")]
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(string $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  Bien
     */
    public function getBien()
    {
        return $this->bien;
    }

    /**
     * Set undocumented variable
     *
     * @param  Bien  $bien  Undocumented variable
     *
     * @return  self
     */
    public function setBien(Bien $bien)
    {
        $this->bien = $bien;

        return $this;
    }
}
