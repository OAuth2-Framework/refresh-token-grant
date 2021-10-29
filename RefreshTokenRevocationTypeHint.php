<?php

declare(strict_types=1);

namespace OAuth2Framework\Component\RefreshTokenGrant;

use OAuth2Framework\Component\TokenRevocationEndpoint\TokenTypeHint;

final class RefreshTokenRevocationTypeHint implements TokenTypeHint
{
    public function __construct(
        private RefreshTokenRepository $refreshTokenRepository
    ) {
    }

    public function hint(): string
    {
        return 'refresh_token';
    }

    public function find(string $token): ?RefreshToken
    {
        $id = new RefreshTokenId($token);

        return $this->refreshTokenRepository->find($id);
    }

    public function revoke(mixed $token): void
    {
        if (! $token instanceof RefreshToken || $token->isRevoked() === true) {
            return;
        }

        $token->markAsRevoked();
        $this->refreshTokenRepository->save($token);
    }
}
