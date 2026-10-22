<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

class HeaderRenderer implements CliBlockRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($childRenderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($childRenderer)));
        }

        if (!($node instanceof Heading)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($node)));
        }

        $level  = $node->getLevel();
        $text   = $childRenderer->renderInlines($node->children());

        return sprintf(
            "\n%s %s\n",
            $childRenderer->style(str_repeat('#', $level), 'dark_gray'),
            $childRenderer->style($text, ['bold', 'cyan'])
        );
    }
}
