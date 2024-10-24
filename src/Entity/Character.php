<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character
{
    public function __construct(
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        public ?int $id = null,

        #[ORM\Column(length: 255)]
        public ?string $name = null,

        #[ORM\Column(type: Types::TEXT)]
        public ?string $description = null,

        #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
        public ?DateTimeImmutable $date = null,

        #[ORM\Column(nullable: true)]
        public ?array $comics = null
    ) {
    }
}
