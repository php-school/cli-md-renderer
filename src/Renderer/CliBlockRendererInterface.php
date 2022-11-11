<?php

namespace AydinHassan\CliMdRenderer\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;

interface CliBlockRendererInterface extends BlockRendererInterface
{
    public function render(
        AbstractBlock $block,
        ElementRendererInterface $htmlRenderer,
        bool $inTightList = false
    ): string;
}
