<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\UserMeController;
use App\Controller\UserMeVerbController;
use App\Controller\UserMeWordController;
use App\Controller\UserMeWordsController;
use App\State\UserPasswordHasher;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\UuidV7 as Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(processor: UserPasswordHasher::class, validationContext: ['groups' => ['Default', 'user:create']]),
        new GetCollection(
            name: 'me',
            uriTemplate: '/users/me',
            controller: UserMeController::class,
            read: false,
            write: false,
            validate: false
        ),
        new GetCollection(
            name: 'getMeWords',
            uriTemplate: '/users/me/words',
            controller: UserMeWordsController::class,
            read: false,
            write: false,
            validate: false
        ),
        new Get(),
        new Patch(processor: UserPasswordHasher::class),
        new Put(
            name: 'newLearnedWord',
            uriTemplate: '/users/me/word/{id}',
            controller: UserMeWordController::class,
            read: false,
            write: false,
            validate: false
        ),
        new Put(
            name: 'newLearnedWord',
            uriTemplate: '/users/me/verb/{id}',
            controller: UserMeVerbController::class,
            read: false,
            write: false,
            validate: false
        ),
        new Put(processor: UserPasswordHasher::class),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:read'])]
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    private ?string $plainPassword = null;

    #[ORM\ManyToMany(targetEntity: Word::class, inversedBy: 'users')]
    private Collection $wordLearned;

    #[ORM\OneToMany(targetEntity: WordError::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $wordErrors;

    #[ORM\ManyToMany(targetEntity: Verb::class, mappedBy: 'user')]
    private Collection $verbs;

    public function __construct()
    {
        $this->wordLearned = new ArrayCollection();
        $this->wordErrors = new ArrayCollection();
        $this->verbs = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Word>
     */
    public function getWordLearned(): Collection
    {
        return $this->wordLearned;
    }

    public function addWordLearned(Word $wordLearned): static
    {
        if (!$this->wordLearned->contains($wordLearned)) {
            $this->wordLearned->add($wordLearned);
        }

        return $this;
    }

    public function removeWordLearned(Word $wordLearned): static
    {
        $this->wordLearned->removeElement($wordLearned);

        return $this;
    }

    /**
     * @return Collection<int, WordError>
     */
    public function getWordErrors(): Collection
    {
        return $this->wordErrors;
    }

    public function addWordError(WordError $wordError): static
    {
        if (!$this->wordErrors->contains($wordError)) {
            $this->wordErrors->add($wordError);
            $wordError->setUser($this);
        }

        return $this;
    }

    public function removeWordError(WordError $wordError): static
    {
        if ($this->wordErrors->removeElement($wordError)) {
            // set the owning side to null (unless already changed)
            if ($wordError->getUser() === $this) {
                $wordError->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Verb>
     */
    public function getVerbs(): Collection
    {
        return $this->verbs;
    }

    public function addVerb(Verb $verb): static
    {
        if (!$this->verbs->contains($verb)) {
            $this->verbs->add($verb);
            $verb->addUser($this);
        }

        return $this;
    }

    public function removeVerb(Verb $verb): static
    {
        if ($this->verbs->removeElement($verb)) {
            $verb->removeUser($this);
        }

        return $this;
    }
}
