<?php

namespace AydinHassan\CliMdRenderer\InlineRenderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use AydinHassan\CliMdRenderer\CliRenderer;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class LinkRenderer implements InlineRendererInterface
{
    public function render(AbstractInline $inline, ElementRendererInterface $renderer): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($inline instanceof Link)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: "%s"', get_class($inline)));
        }

        return $renderer->style($renderer->renderInlines($inline->children()), ['underline', 'bold', 'light_blue']);
    }
}
