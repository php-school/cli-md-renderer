<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use PhpSchool\CliMdRenderer\CliRenderer;

class DocumentRenderer implements CliBlockRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Document)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($node)));
        }

        $wholeDoc = $childRenderer->renderBlocks($node->children());
        return $wholeDoc === '' ? '' : $wholeDoc . "\n";
    }
}
