<?php

namespace AydinHassan\CliMdRenderer\InlineRenderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

interface CliInlineRendererInterface extends InlineRendererInterface
{
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer): string;
}
