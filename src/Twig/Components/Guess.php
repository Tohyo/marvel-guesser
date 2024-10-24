<?php

namespace App\Twig\Components;

use App\Entity\Character;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Guess
{
    use DefaultActionTrait;

    #[LiveProp]
    public Character $character;

    #[LiveProp]
    public int $tries = 1;

    public bool $guessed = false;

    #[LiveProp(writable: true)]
    public string $nameGuessed = '';

    public int $maxTries = 4;

    #[LiveAction]
    public function guess(): void
    {
        if ($this->nameGuessed === $this->character->name) {
            $this->guessed = true;
        }

        $this->tries++;
    }
}
