<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\VerbsRandomController;
use App\Repository\VerbRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7 as Uuid;

#[ORM\Entity(repositoryClass: VerbRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            name: 'getRandomVerbs',
            uriTemplate: '/verbs/random',
            controller: VerbsRandomController::class,
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
class Verb
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[ORM\Column(length: 255)]
    private ?string $s1 = null;

    #[ORM\Column(length: 255)]
    private ?string $s2 = null;

    #[ORM\Column(length: 255)]
    private ?string $s3 = null;

    #[ORM\Column(length: 255)]
    private ?string $p1 = null;

    #[ORM\Column(length: 255)]
    private ?string $p2 = null;

    #[ORM\Column(length: 255)]
    private ?string $p3 = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'verbs')]
    private Collection $user;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->createdAt = new \DateTime();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getS1(): ?string
    {
        return $this->s1;
    }

    public function setS1(string $s1): static
    {
        $this->s1 = $s1;

        return $this;
    }

    public function getS2(): ?string
    {
        return $this->s2;
    }

    public function setS2(string $s2): static
    {
        $this->s2 = $s2;

        return $this;
    }

    public function getS3(): ?string
    {
        return $this->s3;
    }

    public function setS3(string $s3): static
    {
        $this->s3 = $s3;

        return $this;
    }

    public function getP1(): ?string
    {
        return $this->p1;
    }

    public function setP1(string $p1): static
    {
        $this->p1 = $p1;

        return $this;
    }

    public function getP2(): ?string
    {
        return $this->p2;
    }

    public function setP2(string $p2): static
    {
        $this->p2 = $p2;

        return $this;
    }

    public function getP3(): ?string
    {
        return $this->p3;
    }

    public function setP3(string $p3): static
    {
        $this->p3 = $p3;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

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
}
