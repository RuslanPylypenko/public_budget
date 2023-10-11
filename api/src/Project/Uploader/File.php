<?php

namespace App\Project\Uploader;

class File
{
    public function __construct(
        public readonly string $path,
        public readonly string $name,
        public readonly int $size,
    ) {
    }
}