<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use PhpSchool\CliMdRenderer\SyntaxHighlighterInterface;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\FencedCode;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class FencedCodeRenderer implements BlockRendererInterface
{
    /**
     * @var array<SyntaxHighlighterInterface>
     */
    private $highlighters;

    /**
     * @param array<SyntaxHighlighterInterface> $syntaxHighlighters
     */
    public function __construct(array $syntaxHighlighters = [])
    {
        foreach ($syntaxHighlighters as $language => $syntaxHighlighter) {
            $this->addSyntaxHighlighter($language, $syntaxHighlighter);
        }
    }

    public function addSyntaxHighlighter(string $language, SyntaxHighlighterInterface $highlighter): void
    {
        $this->highlighters[$language] = $highlighter;
    }

    /**
     * @return array<SyntaxHighlighterInterface>
     */
    public function getSyntaxHighlighters(): array
    {
        return $this->highlighters;
    }

    public function render(AbstractBlock $block, ElementRendererInterface $renderer, bool $inTightList = false): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($block instanceof FencedCode)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($block)));
        }

        $infoWords = $block->getInfoWords();
        $codeType = null;
        if (count($infoWords) !== 0 && strlen($infoWords[0]) !== 0) {
            $codeType = $infoWords[0];
        }

        if (null === $codeType || !isset($this->highlighters[$codeType])) {
            return $this->indent($renderer->style($block->getStringContent(), 'yellow'));
        }

        return $this->indent(
            sprintf("%s\n", $this->highlighters[$codeType]->highlight($block->getStringContent()))
        );
    }

    private function indent(string $string): string
    {
        return implode(
            "\n",
            array_map(
                function ($row) {
                    return sprintf("    %s", $row);
                },
                explode("\n", $string)
            )
        );
    }
}
