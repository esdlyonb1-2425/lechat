<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Entity\Message as MessageEntity;

#[AsTwigComponent]
final class Message
{
    public MessageEntity $message;
}
