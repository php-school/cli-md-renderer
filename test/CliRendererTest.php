<?php

namespace PhpSchool\CliMdRendererTest;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\CliInlineRendererInterface;
use PhpSchool\CliMdRenderer\Renderer\CliBlockRendererInterface;
use Colors\Color;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\AbstractInline;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CliRendererTest extends TestCase
{
    public function testRenderBlockThrowsExceptionIfNoRenderer(): void
    {
        $block = $this->createMock(AbstractBlock::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('Unable to find corresponding renderer for block type: "%s"', get_class($block))
        );

        $renderer = new CliRenderer(new Environment(), new Color());
        $renderer->renderBlock($block);
    }

    public function testRenderBlock(): void
    {
        $block = $this->createMock(AbstractBlock::class);
        $class = get_class($block);
        $blockRenderer = $this->createMock(CliBlockRendererInterface::class);
        $environment = new Environment();
        $environment->addBlockRenderer($class, $blockRenderer);
        $renderer = new CliRenderer($environment, new Color());

        $blockRenderer
            ->expects($this->once())
            ->method('render')
            ->with($block, $renderer);

        $renderer->renderBlock($block);
    }

    public function testRenderBlocks(): void
    {
        $block1         = $this->createMock(AbstractBlock::class);
        $block2         = $this->createMock(AbstractBlock::class);
        $blockRenderer  = $this->createMock(CliBlockRendererInterface::class);

        $environment = new Environment();
        $environment
            ->addBlockRenderer(get_class($block1), $blockRenderer)
            ->addBlockRenderer(get_class($block2), $blockRenderer);

        $renderer = new CliRenderer($environment, new Color());

        $blockRenderer
            ->expects($this->exactly(2))
            ->method('render')
            ->withConsecutive([$block1, $renderer], [$block2, $renderer])
            ->willReturnOnConsecutiveCalls(
                'block1',
                'block2'
            );

        $renderer->renderBlocks([$block1, $block2]);
    }

    public function testRenderInlineBlocksThrowsExceptionIfNoRenderer(): void
    {
        $block = $this->createMock(AbstractInline::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('Unable to find corresponding renderer for inline type: "%s"', get_class($block))
        );

        $renderer = new CliRenderer(new Environment(), new Color());
        $renderer->renderInlines([$block]);
    }

    public function testRenderInlineBlocks(): void
    {
        $block1 = $this->createMock(AbstractInline::class);
        $block2 = $this->createMock(AbstractInline::class);
        $inlineRenderer  = $this->createMock(CliInlineRendererInterface::class);

        $environment = new Environment();
        $environment
            ->addInlineRenderer(get_class($block1), $inlineRenderer)
            ->addInlineRenderer(get_class($block2), $inlineRenderer);

        $renderer = new CliRenderer($environment, new Color());

        $inlineRenderer
            ->expects($this->exactly(2))
            ->method('render')
            ->withConsecutive([$block1, $renderer], [$block2, $renderer])
            ->willReturnOnConsecutiveCalls(
                'inline1',
                'inline2'
            );

        $this->assertEquals('inline1inline2', $renderer->renderInlines([$block1, $block2]));
    }
}
