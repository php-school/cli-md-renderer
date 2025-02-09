<?php

namespace PhpSchool\CliMdRenderer\InlineRenderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Text;
use PhpSchool\CliMdRenderer\CliRenderer;

class TextRenderer implements CliInlineRendererInterface
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
