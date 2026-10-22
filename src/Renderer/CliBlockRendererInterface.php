<?php

namespace PhpSchool\CliMdRenderer\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

interface CliBlockRendererInterface extends NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string;
}
