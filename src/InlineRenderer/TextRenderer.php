<?php

namespace AydinHassan\CliMdRenderer\InlineRenderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Text;
use AydinHassan\CliMdRenderer\CliRenderer;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class TextRenderer implements InlineRendererInterface
{
    public function render(AbstractInline $inline, ElementRendererInterface $renderer): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($inline instanceof Text)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: "%s"', get_class($inline)));
        }

        return $inline->getContent();
    }
}
