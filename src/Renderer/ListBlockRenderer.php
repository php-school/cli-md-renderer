<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use PhpSchool\CliMdRenderer\CliRenderer;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;

class ListBlockRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $renderer, bool $inTightList = false): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($block instanceof ListBlock)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($block)));
        }

        return $renderer->renderBlocks($block->children());
    }
}
