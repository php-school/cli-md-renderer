<?php

namespace PhpSchool\CliMdRendererTest;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\CliInlineRendererInterface;
use PhpSchool\CliMdRenderer\Renderer\CliBlockRendererInterface;
use Colors\Color;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Node\Inline\AbstractInline;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CliRendererTest extends TestCase
{
    public function testRenderBlockThrowsExceptionIfNoRenderer(): void
    {
        $block = $this->createStub(AbstractBlock::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('Unable to find corresponding renderer for block type: "%s"', get_class($block))
        );

        $renderer = new CliRenderer(new Environment(), new Color());
        $renderer->renderBlock($block);
    }

    public function testRenderBlock(): void
    {
        $block = $this->createStub(AbstractBlock::class);
        $class = get_class($block);
        $blockRenderer = $this->createMock(CliBlockRendererInterface::class);
        $environment = new Environment();
        $environment->addRenderer($class, $blockRenderer);
        $renderer = new CliRenderer($environment, new Color());

        $blockRenderer
            ->expects($this->once())
            ->method('render')
            ->with($block, $renderer)
            ->willReturn('block');

        $renderer->renderBlock($block);
    }

    public function testRenderBlocks(): void
    {
        $block1         = $this->createStub(AbstractBlock::class);
        $block2         = $this->createStub(AbstractBlock::class);
        $blockRenderer  = $this->createMock(CliBlockRendererInterface::class);

        $environment = new Environment();
        $environment
            ->addRenderer(get_class($block1), $blockRenderer)
            ->addRenderer(get_class($block2), $blockRenderer);

        $renderer = new CliRenderer($environment, new Color());

        $blockRenderer
            ->expects($this->exactly(2))
            ->method('render')
            ->willReturnMap([
                [$block1, $renderer, 'block1'],
                [$block2, $renderer, 'block2'],
            ]);

        $this->assertSame("block1\nblock2", $renderer->renderBlocks([$block1, $block2]));
    }

    public function testRenderInlineBlocksThrowsExceptionIfNoRenderer(): void
    {
        $block = $this->createStub(AbstractInline::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('Unable to find corresponding renderer for inline type: "%s"', get_class($block))
        );

        $renderer = new CliRenderer(new Environment(), new Color());
        $renderer->renderInlines([$block]);
    }

    public function testRenderInlineBlocks(): void
    {
        $block1 = $this->createStub(AbstractInline::class);
        $block2 = $this->createStub(AbstractInline::class);
        $inlineRenderer  = $this->createMock(CliInlineRendererInterface::class);

        $environment = new Environment();
        $environment
            ->addRenderer(get_class($block1), $inlineRenderer)
            ->addRenderer(get_class($block2), $inlineRenderer);

        $renderer = new CliRenderer($environment, new Color());

        $inlineRenderer
            ->expects($this->exactly(2))
            ->method('render')
            ->willReturnMap([
                [$block1, $renderer, 'inline1'],
                [$block2, $renderer, 'inline2'],
            ]);

        $this->assertEquals('inline1inline2', $renderer->renderInlines([$block1, $block2]));
    }
}
