<?php

namespace PhpSchool\CliMdRenderer\InlineRenderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

interface CliInlineRendererInterface extends NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string;
}
