<?php

namespace PhpSchool\CliMdRenderer\InlineRenderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Node\Inline\Text;
use PhpSchool\CliMdRenderer\CliRenderer;

class TextRenderer implements CliInlineRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Text)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: "%s"', get_class($node)));
        }

        return $node->getLiteral();
    }
}
