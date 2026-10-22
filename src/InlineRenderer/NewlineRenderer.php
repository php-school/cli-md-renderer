<?php

namespace PhpSchool\CliMdRenderer\InlineRenderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Node\Inline\Newline;
use PhpSchool\CliMdRenderer\CliRenderer;

class NewlineRenderer implements CliInlineRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Newline)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: "%s"', get_class($node)));
        }

        return "\n";
    }
}
