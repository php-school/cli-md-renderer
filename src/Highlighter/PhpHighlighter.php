<?php

namespace PhpSchool\CliMdRenderer\Highlighter;

use PhpSchool\CliMdRenderer\SyntaxHighlighterInterface;
use Kadet\Highlighter\Formatter\CliFormatter;
use Kadet\Highlighter\KeyLighter;
use Kadet\Highlighter\Language\Php;

class PhpHighlighter implements SyntaxHighlighterInterface
{
    /**
     * @var KeyLighter
     */
    private $keyLighter;

    public function __construct(KeyLighter $keyLighter)
    {
        $this->keyLighter = $keyLighter;
    }

    public function highlight(string $code): string
    {
        /** @var string $highlighted KeyLighter is untyped, the CLI formatter returns a string */
        $highlighted = $this->keyLighter->highlight($code, new Php(), new CliFormatter());

        return $highlighted;
    }
}
