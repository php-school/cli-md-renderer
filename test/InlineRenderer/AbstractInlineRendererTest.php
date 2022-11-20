<?php

namespace PhpSchool\CliMdRendererTest\InlineRenderer;

use PhpSchool\CliMdRendererTest\RendererTestInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use PHPUnit\Framework\TestCase;
use PhpSchool\CliMdRenderer\CliRenderer;
use InvalidArgumentException;

abstract class AbstractInlineRendererTest extends TestCase
{
    public function testExceptionIsThrownIfNotCorrectBlock(): void
    {
        if (!$this instanceof RendererTestInterface) {
            $this->markTestSkipped('Not a Renderer');
        }

        $block = $this->createMock(AbstractInline::class);
        $class = $this->getRendererClass();

        $cliRenderer = $this->getMockBuilder(CliRenderer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Incompatible inline type: "%s"', get_class($block)));

        (new $class())->render($block, $cliRenderer);
    }
}
