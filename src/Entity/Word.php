<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\WordRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\UuidV7 as Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\WordsRandomController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WordRepository::class)]
#[ApiResource(
        operations: [
        new GetCollection(
            name: 'getRandomWords',
            uriTemplate: '/words/random',
            controller: WordsRandomController::class,
            read: false,
            write: false,
            validate: false
            ),
        new GetCollection(),
        new Get(),
        new Patch(),
        new Put(),
        new Post(),
        new Delete(),
        ]
)]
class Word
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['wordError:read'])]
    private ?string $value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $level = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'wordLearned')]
    private Collection $users;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: WordError::class, mappedBy: 'word', orphanRemoval: true)]
    private Collection $wordErrors;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->wordErrors = new ArrayCollection();
    }
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): static
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addWordLearned($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeWordLearned($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

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
            $wordError->setWord($this);
        }

        return $this;
    }

    public function removeWordError(WordError $wordError): static
    {
        if ($this->wordErrors->removeElement($wordError)) {
            // set the owning side to null (unless already changed)
            if ($wordError->getWord() === $this) {
                $wordError->setWord(null);
            }
        }

        return $this;
    }
}
