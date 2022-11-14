<?php

namespace AydinHassan\CliMdRenderer\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\ElementRendererInterface;
use AydinHassan\CliMdRenderer\CliRenderer;

class DocumentRenderer implements CliBlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $renderer, bool $inTightList = false): string
    {
        if (!($renderer instanceof CliRenderer)) {
            throw new \InvalidArgumentException(sprintf('Incompatible renderer type: "%s"', get_class($renderer)));
        }

        if (!($block instanceof Document)) {
            throw new \InvalidArgumentException(sprintf('Incompatible block type: "%s"', get_class($block)));
        }

        /** @var array<AbstractBlock> $nodes */
        $nodes = $block->children();
        $wholeDoc = $renderer->renderBlocks($nodes);
        return $wholeDoc === '' ? '' : $wholeDoc . "\n";
    }
}
