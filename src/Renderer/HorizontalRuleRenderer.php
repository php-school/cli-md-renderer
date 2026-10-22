<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Extension\CommonMark\Node\Block\ThematicBreak;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

class HorizontalRuleRenderer implements CliBlockRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        /** @var int $width validated as an int by the schema registered in CliExtension */
        $width = $childRenderer->getOption('width', 30);

        if (!($node instanceof ThematicBreak)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($node)));
        }

        return $childRenderer->style(str_repeat('-', $width), 'dark_gray');
    }
}
