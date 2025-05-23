<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class MercureJwtGenerator
{
    private string $mercureSecret;

    public function __construct(
    string $mercureSecret
)
{
    $this->mercureSecret = $mercureSecret;
}
    public function generate(User $user)
    {
        if(!$user){throw new \Exception('User not connected');}


    $allowedTopics = [];

    foreach ($user->getConversations() as $conversation) {

        $allowedTopics[]= 'conversations/' . $conversation->getId();

    }
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($this->mercureSecret)
        );
        $tokenBuilder = $config->builder();
        $token = $tokenBuilder
            ->withClaim('mercure', [
                'subscribe'=>$allowedTopics,
                'publish'=>$allowedTopics
            ])
            ->issuedAt((new \DateTimeImmutable()))
            ->expiresAt((new \DateTimeImmutable())->modify('+1 hour'))
            ->getToken(new Sha256(), InMemory::plainText($this->mercureSecret));

        return $token->toString();
    }

}