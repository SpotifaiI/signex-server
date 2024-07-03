<?php

    namespace Signex\Lib;

    class Upload {
        public function __construct(
            public readonly string $name,
            public readonly string $path,
            public readonly string $extension
        ) {}
    }
