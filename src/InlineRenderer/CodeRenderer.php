<?php

namespace PhpSchool\CliMdRenderer\InlineRenderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class CodeRenderer implements CliInlineRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Code)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: "%s"', get_class($node)));
        }

        return $childRenderer->style($node->getLiteral(), 'yellow');
    }
}
