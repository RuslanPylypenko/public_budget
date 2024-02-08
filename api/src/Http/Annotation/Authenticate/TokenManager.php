<?php

namespace App\Http\Annotation\Authenticate;

use App\Utils\DateTime;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\HasClaimWithValue;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Token as TokenInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Clock\ClockInterface as Clock;
use Symfony\Component\HttpKernel\KernelInterface;

class TokenManager
{
    public const CLMS_LIST = 'clms';

    public const IP_CLM           = 'user_ip';
    public const USER_AGENT_CLM   = 'user_agent';
    public const USER_EMAIL_CLM   = 'user_email';
    public const HASH_SESSION_CLM = 'hash_session';

    private Validator $validator;
    private Configuration $conf;

    public function __construct(
        private readonly Clock $clock,
        private readonly ParameterBagInterface $parameterBag,
        string $jwtSignerKey
    ) {
        $this->validator = new Validator();
        $this->conf = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($jwtSignerKey)
        );
    }

    public function build(
        ?UserInterface $user = null,
        int $lifetime = 3600 * 24,
        array $claims = [self::IP_CLM, self::USER_AGENT_CLM, self::USER_EMAIL_CLM, self::HASH_SESSION_CLM],
        ?string $issuedBy = null,
        ?string $identifiedBy = null,
    ): TokenInterface {
        $currentDate = \DateTimeImmutable::createFromMutable(DateTime::current());

        $builder = ($this->conf->builder())
            ->issuedBy($issuedBy ?? (string)$this->parameterBag->get('app.name'))
            ->identifiedBy($identifiedBy ?? Uuid::uuid4()->toString())
            ->issuedAt($currentDate)
            ->withClaim(self::USER_EMAIL_CLM, $user->getEmail())
            ->canOnlyBeUsedAfter($currentDate)
            ->expiresAt($currentDate->modify("+ $lifetime seconds"));


        $builder->withClaim('_token', $user->getEmail());

        return $builder->getToken($this->conf->signer(), $this->conf->signingKey());
    }

    public function parse(string $token): TokenInterface
    {
        return $this->conf->parser()->parse($token);
    }

    /**
     * @param UnencryptedToken $token
     */
    public function validate(TokenInterface $token, Request $request, array $issList): void
    {
        $constraints = [
            new SignedWith($this->conf->signer(), $this->conf->signingKey()),
            new IssuedBy(...$issList),
            new StrictValidAt($this->clock, new \DateInterval('PT5S')),
        ];

        $this->validator->assert($token, ...$constraints);
    }
}