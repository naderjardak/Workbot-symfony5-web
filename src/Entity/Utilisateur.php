<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * Utilisateur
 *
 *
 */

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
 class Utilisateur implements UserInterface,PasswordAuthenticatedUserInterface
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\OneToMany(targetEntity: 'Reclamation')]
    private int $id;


    #[ORM\Column(name: 'mdpsymfony', type: 'string', length: 255, nullable: true)]
    private ?string $mdpsymfony = null;



    #[ORM\Column(name: 'nom', type: 'string', length: 25, nullable: true)]
    #[Assert\NotBlank(message: "Please fill in this field")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your last name must be at least {{ limit }} characters long',
        maxMessage: 'Your last name cannot be longer than {{ limit }} characters',
    )]
    private ?string $nom = null;

    #[ORM\Column(name: 'prenom', type: 'string', length: 25, nullable: true)]
    #[Assert\NotBlank(message: "Please fill in this field")]
    private ?string $prenom = null;


    #[Assert\Length(
        min: 8,
        max: 15,
        minMessage: 'Your num must be at least {{ limit }} ',
        maxMessage: 'Your num cannot be longer than {{ limit }} ',
    )]
    #[ORM\Column(name: 'tel', type: 'string', length: 30, nullable: true)]
    private ?string $tel = null;

    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank(message: "Please fill in this field")]
    #[ORM\Column(name: 'email', type: 'string', length: 200, nullable: true)]
    private ?string $email = null;
    #[Assert\Length(
        min: 8,
        max: 50,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern:"/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        message: 'Your password must be Strength',
    )]

    #[ORM\Column(name: 'mdp', type: 'string', length: 355, nullable: true)]
    private ?string $password = null;
    #[ORM\Column(name: 'adresse', type: 'string', length: 30, nullable: true)]
    private ?string $adresse = null;

    #[Assert\File(
        maxSize: '10000k',
        mimeTypes: [ 'image/gif', 'image/jpeg','image/png',
            'image/jpg',
            'video/mp4',],
        mimeTypesMessage: 'Please upload a valid image',
    )]
    #[ORM\Column(name: 'photo', type: 'string', length: 300, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(name: 'questionSecu', type: 'string', length: 300, nullable: true)]
    private ?string $questionsecu = null;

    #[ORM\Column(name: 'reponseSecu', type: 'string', length: 300, nullable: true)]
    private ?string $reponsesecu = null;

    #[ORM\Column(name: 'methode', type: 'string', length: 200, nullable: true)]
    private ?string $methode = null;

    #[ORM\Column(name: 'formeJuridique', type: 'string', length: 300, nullable: true)]
    private ?string $formejuridique = null;

    #[ORM\Column(name: 'raisonSociale', type: 'string', length: 300, nullable: true)]
    private ?string $raisonsociale = null;

    #[ORM\Column(name: 'domaine', type: 'string', length: 300, nullable: true)]
    private ?string $domaine = null;

    #[ORM\Column(name: 'pattente', type: 'string', length: 300, nullable: true)]
    private ?string $pattente = null;

    #[ORM\Column(name: 'nomSociete', type: 'string', length: 300, nullable: true)]
    private ?string $nomsociete = null;

    #[ORM\Column(name: 'diplome', type: 'string', length: 300, nullable: true)]
    private ?string $diplome = null;

    #[ORM\Column(name: 'experience', type: 'string', length: 250, nullable: true)]
    private ?string $experience = null;

    #[ORM\Column(name: 'niveauFr', type: 'string', length: 20, nullable: true)]
    private ?string $niveaufr = null;

    #[ORM\Column(name: 'niveauAng', type: 'string', length: 20, nullable: true)]
    private ?string $niveauang = null;

    #[ORM\Column(name: 'competance', type: 'string', length: 250, nullable: true)]
    private ?string $competance = null;

    #[ORM\Column(name: 'cv', type: 'string', length: 350, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(name: 'portfolio', type: 'string', length: 350, nullable: true)]
    private ?string $portfolio = null;

    #[ORM\Column(name: 'bio', type: 'string', length: 500, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(name: 'typeCandidat', type: 'string', length: 50, nullable: true)]
    private ?string $typecandidat = null;

    #[ORM\Column(name: 'note', type: 'string', length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(name: 'role', type: 'string', length: 25, nullable: true)]
    private string $role;


    #[ORM\Column(name: 'roles', type: 'json', length: 25, nullable: true)]
    private  $roles= [];

    #[ORM\Column(name: 'resetToken', type: 'string', length: 300, nullable: true)]
    private ?string $resetToken = null;
    #[ORM\Column(name: 'googleId', type: 'integer', length: 255, nullable: true)]
    private ?string $googleId = null;
    #[ORM\Column(name: 'facebookId', type: 'integer', length: 255, nullable: true)]
    private ?string $facebookId = null;
    #[ORM\Column(name: 'photoGoogleFb', type: 'string', length: 255, nullable: true)]
    private ?string $photoGoogleFb = null;
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AdsLike::class)]
    private Collection $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }


    /**
     * @return Collection<int, AdsLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(AdsLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(AdsLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }
    /**
     * @return string|null
     */
    public function getPhotoGoogleFb(): ?string
    {
        return $this->photoGoogleFb;
    }

    /**
     * @param string|null $photoGoogleFb
     */
    public function setPhotoGoogleFb(?string $photoGoogleFb): void
    {
        $this->photoGoogleFb = $photoGoogleFb;
    }


    /**
     * @return string|null
     */
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    /**
     * @param string|null $facebookId
     */
    public function setFacebookId(?string $facebookId): void
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string|null
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * @param string|null $googleId
     */
    public function setGoogleId(?string $googleId): void
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string|null
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string|null $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $mdp): self
    {
        $this->password = $mdp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getQuestionsecu(): ?string
    {
        return $this->questionsecu;
    }

    public function setQuestionsecu(?string $questionsecu): self
    {
        $this->questionsecu = $questionsecu;

        return $this;
    }

    public function getReponsesecu(): ?string
    {
        return $this->reponsesecu;
    }

    public function setReponsesecu(?string $reponsesecu): self
    {
        $this->reponsesecu = $reponsesecu;

        return $this;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(?string $methode): self
    {
        $this->methode = $methode;

        return $this;
    }

    public function getFormejuridique(): ?string
    {
        return $this->formejuridique;
    }

    public function setFormejuridique(?string $formejuridique): self
    {
        $this->formejuridique = $formejuridique;

        return $this;
    }

    public function getRaisonsociale(): ?string
    {
        return $this->raisonsociale;
    }

    public function setRaisonsociale(?string $raisonsociale): self
    {
        $this->raisonsociale = $raisonsociale;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getPattente(): ?string
    {
        return $this->pattente;
    }

    public function setPattente(?string $pattente): self
    {
        $this->pattente = $pattente;

        return $this;
    }

    public function getNomsociete(): ?string
    {
        return $this->nomsociete;
    }

    public function setNomsociete(?string $nomsociete): self
    {
        $this->nomsociete = $nomsociete;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getNiveaufr(): ?string
    {
        return $this->niveaufr;
    }

    public function setNiveaufr(?string $niveaufr): self
    {
        $this->niveaufr = $niveaufr;

        return $this;
    }

    public function getNiveauang(): ?string
    {
        return $this->niveauang;
    }

    public function setNiveauang(?string $niveauang): self
    {
        $this->niveauang = $niveauang;

        return $this;
    }

    public function getCompetance(): ?string
    {
        return $this->competance;
    }

    public function setCompetance(?string $competance): self
    {
        $this->competance = $competance;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getPortfolio(): ?string
    {
        return $this->portfolio;
    }

    public function setPortfolio(?string $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getTypecandidat(): ?string
    {
        return $this->typecandidat;
    }

    public function setTypecandidat(?string $typecandidat): self
    {
        $this->typecandidat = $typecandidat;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }




    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }


    public function getUsername(): string
    {

        return (string) $this->email;

    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
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
     * @return string|null
     */
    public function getMdpsymfony(): ?string
    {
        return $this->mdpsymfony;
    }

    /**
     * @param string|null $mdpsymfony
     */
    public function setMdpsymfony(?string $mdpsymfony): void
    {
        $this->mdpsymfony = $mdpsymfony;
    }

}
