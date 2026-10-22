<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRendererTest\RendererTestInterface;
use League\CommonMark\Node\Block\AbstractBlock;
use PHPUnit\Framework\TestCase;
use PhpSchool\CliMdRenderer\CliRenderer;
use InvalidArgumentException;

abstract class AbstractRendererTest extends TestCase
{
    public function testExceptionIsThrownIfNotCorrectBlock(): void
    {
        if (!$this instanceof RendererTestInterface) {
            $this->markTestSkipped('Not a Renderer');
        }

        $block = $this->createStub(AbstractBlock::class);
        $class = $this->getRendererClass();

        $cliRenderer = $this->createStub(CliRenderer::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Incompatible block type: "%s"', get_class($block)));
        (new $class())->render($block, $cliRenderer);
    }
}
