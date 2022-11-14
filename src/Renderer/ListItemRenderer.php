<?php

namespace AydinHassan\CliMdRenderer\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use AydinHassan\CliMdRenderer\CliRenderer;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class ListItemRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $renderer, bool $inTightList = false): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($block instanceof ListItem)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($block)));
        }

        return $renderer->style(' * ', 'yellow') . $renderer->renderBlocks($block->children());
    }
}
