<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use PhpSchool\CliMdRenderer\SyntaxHighlighterInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

class FencedCodeRenderer implements CliBlockRendererInterface
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

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof FencedCode)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($node)));
        }

        $infoWords = $node->getInfoWords();
        $codeType = null;
        if (count($infoWords) !== 0 && strlen($infoWords[0]) !== 0) {
            $codeType = $infoWords[0];
        }

        if (null === $codeType || !isset($this->highlighters[$codeType])) {
            return $this->indent($childRenderer->style($node->getLiteral(), 'yellow'));
        }

        return $this->indent(
            sprintf("%s\n", $this->highlighters[$codeType]->highlight($node->getLiteral()))
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
