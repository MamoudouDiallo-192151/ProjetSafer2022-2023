<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\BienRepository;




/**
 * @ORM\Entity(repositoryClass=BienRepository::class)
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: BienRepository::class)]
#[Vich\Uploadable]
class Bien
{
    const STATUSBIEN = [
        0 => "En Location",
        1 => "En Vente"
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\Column(type: 'integer')]
    private $codePostal;

    #[ORM\Column(type: 'string', length: 255)]
    private $surface;

    #[ORM\Column(type: 'float')]
    private $prix;

    #[ORM\Column(type: 'integer')]
    private $statusBien;

    //Cette colonne permet de supprimer tous les biens affectés  à une categorie qui vas être supprimer
    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="biens")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'biens')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $categorie;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    /**
     * @var File|null
     * @Assert\Image( 
     *     maxSize="400k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage="Formats autorisés : .png, .jpeg, .jpg - Poids autorisé : < 400Ko."
     * )
     * @Vich\UploadableField(mapping="biens_image", fileNameProperty="image")
     */
    #[Assert\Image(
        maxSize: "400k",
        mimeTypes: ["image/png", "image/jpeg", "image/jpg"],
        mimeTypesMessage: "Formats autorisés : .png, .jpeg, .jpg - Poids autorisé : < 400Ko."
    )]
    #[Vich\UploadableField(mapping: 'biens_image', fileNameProperty: 'image')]
    private $imageFile = null;
    #[ORM\Column(type: 'datetime')]
    private $dateModification;

    #[ORM\Column(type: 'boolean', options: [false])]
    private $isFavoris;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStatusBien(): ?int
    {
        return $this->statusBien;
    }

    public function getStatusBienType(): ?String
    {
        return self::STATUSBIEN[$this->statusBien];
    }

    public function setStatusBien(int $statusBien): self
    {
        $this->statusBien = $statusBien;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    /**
     * ce setter permet de spécifier la date d'upload de l'image sans cela 
     * l'image ne vas pas être stocker dans le dossier public/image/sous_services
     *
     * @param [type] $imageFile
     * @return void
     */
    public  function  setImageFile($imageFile)
    {

        $this->imageFile = $imageFile;
        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($this->imageFile instanceof UploadedFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->dateModification = new \DateTime('now');
        }

        return $this;
    }
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getIsFavoris(): ?bool
    {
        return $this->isFavoris;
    }

    public function setIsFavoris(bool $isFavoris): self
    {
        $this->isFavoris = $isFavoris;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
