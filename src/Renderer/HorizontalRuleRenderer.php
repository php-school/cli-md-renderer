<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\ThematicBreak;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class HorizontalRuleRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $renderer, bool $inTightList = false): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        $width = $renderer->getOption('width', 30);

        if (!($block instanceof ThematicBreak)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($block)));
        }

        return $renderer->style(str_repeat('-', $width), 'dark_gray');
    }
}
