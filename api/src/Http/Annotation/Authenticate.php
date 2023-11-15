<?php

declare(strict_types=1);

namespace App\Http\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Authenticate
{
    public const USER  = '_auth_user';
    public const TOKEN = '_auth_token';

    public function __construct(
        public readonly bool $authUser = true,
        public readonly array $issuers = []
    ) {
    }
}