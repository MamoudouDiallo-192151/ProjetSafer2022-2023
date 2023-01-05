<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * InheritanceType, DiscriminatorColumn et DiscriminatorMap doivent être spécifiés sur la classe la plus élevée faisant partie de la hiérarchie des entités mappées.
 *Le DiscriminatorMap spécifie quelles valeurs de la colonne de discriminateur identifient une ligne comme étant de quel type.
 * Dans le cas ci-dessous, une valeur de "proteur" identifie une ligne comme étant de type Porteur etc.
 */
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "TypeUtilisateur", type: "string")]
#[DiscriminatorMap(["utilisateur" => Utilisateur::class, "porteur" => Porteur::class, "admin" => Administrateur::class])]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
/**
 * @Vich\Uploadable
 */
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_ADMIN = "ROLE_ADMIN";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?int $numeroTelephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateModification;

    /**
     * @var File|null
     * @Assert\Image( 
     *     maxSize="1500k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage="Formats autorisés : .png, .jpeg, .jpg - Poids autorisé : < 1500Ko."
     * )
     * @Vich\UploadableField(mapping="user_profil_image", fileNameProperty="image")
     */
    private $imageFile = null;



    /**
     * ces deux fonctions  serialize et unserialize permettent de serialiser seulement id, email et password sans cela l'uplaode 
     * de la photo de profil ne marche pas puisqu'on peut pas  serialiser une entité qui a un attribut de type File.
     * Exeception généré: Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed

     *
     * @return array
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password
            //......
        ];
    }

    public function __unserialize(array $serialized)
    {
        $this->id = $serialized['id'];
        $this->email = $serialized['email'];
        $this->password = $serialized['password'];
        // .....
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNumeroTelephone(): ?int
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(int $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
     * l'image ne vas pas être stocker dans le dossier public/image_safer/user_profil
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


    /**
     * Méthodes supplementaire
     */
    /**
     * @see UserInterface
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return in_array(self::ROLE_ADMIN, $this->getRoles());
    }
}
