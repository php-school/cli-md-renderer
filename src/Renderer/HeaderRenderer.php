<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Heading;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class HeaderRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $renderer, bool $inTightList = false): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($block instanceof Heading)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($block)));
        }

        $level  = $block->getLevel();
        $text   = $renderer->renderInlines($block->children());

        return sprintf(
            "\n%s %s\n",
            $renderer->style(str_repeat('#', $level), 'dark_gray'),
            $renderer->style($text, ['bold', 'cyan'])
        );
    }
}
