<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\WordsErrorUserController;
use App\Repository\WordErrorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7 as Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WordErrorRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            name: 'getUserError',
            uriTemplate: '/words_error/{id}',
            controller: WordsErrorUserController::class,
            read: false,
            write: false,
            validate: false,
            normalizationContext: ['groups' => ['wordError:read']]
        ),
        new GetCollection(),
        new Post(),
        new Delete()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['user.username' => 'exact'])]
class WordError
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['wordError:read'])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'wordErrors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['wordError:read'])]
    private ?Word $word = null;

    #[ORM\Column(length: 255)]
    #[Groups(['wordError:read'])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'wordErrors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWord(): ?Word
    {
        return $this->word;
    }

    public function setWord(?Word $word): static
    {
        $this->word = $word;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
