<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Node\Block\Paragraph;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

class ParagraphRenderer implements CliBlockRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Paragraph)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($node)));
        }

        return $childRenderer->renderInlines($node->children()) . "\n";
    }
}
