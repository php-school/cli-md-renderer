<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

class ListBlockRenderer implements CliBlockRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof ListBlock)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($node)));
        }

        return $childRenderer->renderBlocks($node->children());
    }
}
