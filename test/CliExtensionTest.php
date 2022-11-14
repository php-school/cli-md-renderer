<?php

declare(strict_types=1);

namespace AydinHassan\CliMdRendererTest;

use AydinHassan\CliMdRenderer\CliExtension;
use AydinHassan\CliMdRenderer\InlineRenderer\CliInlineRendererInterface;
use AydinHassan\CliMdRenderer\InlineRenderer\CodeRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\EmphasisRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\LinkRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\NewlineRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\StrongRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\TextRenderer;
use AydinHassan\CliMdRenderer\Renderer\CliBlockRendererInterface;
use AydinHassan\CliMdRenderer\Renderer\DocumentRenderer;
use AydinHassan\CliMdRenderer\Renderer\FencedCodeRenderer;
use AydinHassan\CliMdRenderer\Renderer\HeaderRenderer;
use AydinHassan\CliMdRenderer\Renderer\HorizontalRuleRenderer;
use AydinHassan\CliMdRenderer\Renderer\ListBlockRenderer;
use AydinHassan\CliMdRenderer\Renderer\ListItemRenderer;
use AydinHassan\CliMdRenderer\Renderer\ParagraphRenderer;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Block\Element\ThematicBreak;
use League\CommonMark\Block\Parser\ATXHeadingParser;
use League\CommonMark\Block\Parser\BlockQuoteParser;
use League\CommonMark\Block\Parser\FencedCodeParser;
use League\CommonMark\Block\Parser\HtmlBlockParser;
use League\CommonMark\Block\Parser\IndentedCodeParser;
use League\CommonMark\Block\Parser\LazyParagraphParser;
use League\CommonMark\Block\Parser\ListParser;
use League\CommonMark\Block\Parser\SetExtHeadingParser;
use League\CommonMark\Block\Parser\ThematicBreakParser;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Code;
use League\CommonMark\Inline\Element\Emphasis;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Element\Newline;
use League\CommonMark\Inline\Element\Strong;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Inline\Parser\AutolinkParser;
use League\CommonMark\Inline\Parser\BacktickParser;
use League\CommonMark\Inline\Parser\BangParser;
use League\CommonMark\Inline\Parser\CloseBracketParser;
use League\CommonMark\Inline\Parser\EntityParser;
use League\CommonMark\Inline\Parser\EscapableParser;
use League\CommonMark\Inline\Parser\HtmlInlineParser;
use League\CommonMark\Inline\Parser\NewlineParser;
use League\CommonMark\Inline\Parser\OpenBracketParser;
use PHPUnit\Framework\TestCase;

class CliExtensionTest extends TestCase
{
    public function testExtensionConfiguresEnvironment(): void
    {
        $environment = new Environment();
        $environment->addExtension(new CliExtension());

        $this->assertBlockRenderer($environment, Document::class, DocumentRenderer::class);
        $this->assertBlockRenderer($environment, Heading::class, HeaderRenderer::class);
        $this->assertBlockRenderer($environment, ThematicBreak::class, HorizontalRuleRenderer::class);
        $this->assertBlockRenderer($environment, Paragraph::class, ParagraphRenderer::class);
        $this->assertBlockRenderer($environment, FencedCode::class, FencedCodeRenderer::class);
        $this->assertBlockRenderer($environment, ListBlock::class, ListBlockRenderer::class);
        $this->assertBlockRenderer($environment, ListItem::class, ListItemRenderer::class);
        $this->assertInlineRenderer($environment, Text::class, TextRenderer::class);
        $this->assertInlineRenderer($environment, Code::class, CodeRenderer::class);
        $this->assertInlineRenderer($environment, Emphasis::class, EmphasisRenderer::class);
        $this->assertInlineRenderer($environment, Strong::class, StrongRenderer::class);
        $this->assertInlineRenderer($environment, Newline::class, NewlineRenderer::class);
        $this->assertInlineRenderer($environment, Link::class, LinkRenderer::class);

        $blockParsers = array_map('get_class', iterator_to_array($environment->getBlockParsers()));
        self::assertSame([
            BlockQuoteParser::class,
            ATXHeadingParser::class,
            FencedCodeParser::class,
            HtmlBlockParser::class,
            SetExtHeadingParser::class,
            ThematicBreakParser::class,
            ListParser::class,
            IndentedCodeParser::class,
            LazyParagraphParser::class,
        ], $blockParsers);

        $characters = ["\n", '`', '\\', '&', '<', '[', ']', '!'];
        $expectedParsers = [
            NewlineParser::class,
            BacktickParser::class,
            EscapableParser::class,
            EntityParser::class,
            AutolinkParser::class,
            HtmlInlineParser::class,
            CloseBracketParser::class,
            OpenBracketParser::class,
            BangParser::class,
        ];
        foreach ($characters as $character) {
            $inlineParsers = array_map(
                'get_class',
                iterator_to_array($environment->getInlineParsersForCharacter($character))
            );
            foreach ($inlineParsers as $inlineParser) {
                self::assertContains($inlineParser, $expectedParsers);
            }
        }
    }

    /**
     * @param Environment $environment
     * @param class-string<AbstractBlock> $blockClass
     * @param class-string<CliBlockRendererInterface> $rendererClass
     * @return void
     */
    private function assertBlockRenderer(Environment $environment, string $blockClass, string $rendererClass): void
    {
        $classes = $environment->getBlockRenderersForClass($blockClass);
        $classes = $classes instanceof \Traversable ? iterator_to_array($classes) : $classes;
        $classes = array_map('get_class', $classes);
        self::assertSame([$rendererClass], $classes);
    }

    /**
     * @param Environment $environment
     * @param class-string<AbstractBlock> $blockClass
     * @param class-string<CliInlineRendererInterface> $rendererClass
     * @return void
     */
    private function assertInlineRenderer(Environment $environment, string $blockClass, string $rendererClass): void
    {
        $classes = $environment->getInlineRenderersForClass($blockClass);
        $classes = $classes instanceof \Traversable ? iterator_to_array($classes) : $classes;
        $classes = array_map('get_class', $classes);
        self::assertSame([$rendererClass], $classes);
    }
}
