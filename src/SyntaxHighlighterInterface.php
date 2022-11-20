<?php

namespace PhpSchool\CliMdRenderer;

interface SyntaxHighlighterInterface
{
    public function highlight(string $code): string;
}
