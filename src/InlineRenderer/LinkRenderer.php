<?php

namespace PhpSchool\CliMdRenderer\InlineRenderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use PhpSchool\CliMdRenderer\CliRenderer;

class LinkRenderer implements CliInlineRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Link)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: "%s"', get_class($node)));
        }

        return $childRenderer->style(
            $childRenderer->renderInlines($node->children()),
            ['underline', 'bold', 'light_blue']
        );
    }
}
