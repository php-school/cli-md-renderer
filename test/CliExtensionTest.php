<?php

declare(strict_types=1);

namespace PhpSchool\CliMdRendererTest;

use PhpSchool\CliMdRenderer\CliExtension;
use PhpSchool\CliMdRenderer\InlineRenderer\CliInlineRendererInterface;
use PhpSchool\CliMdRenderer\InlineRenderer\CodeRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\EmphasisRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\LinkRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\NewlineRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\StrongRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRenderer\Renderer\CliBlockRendererInterface;
use PhpSchool\CliMdRenderer\Renderer\DocumentRenderer;
use PhpSchool\CliMdRenderer\Renderer\FencedCodeRenderer;
use PhpSchool\CliMdRenderer\Renderer\HeaderRenderer;
use PhpSchool\CliMdRenderer\Renderer\HorizontalRuleRenderer;
use PhpSchool\CliMdRenderer\Renderer\ListBlockRenderer;
use PhpSchool\CliMdRenderer\Renderer\ListItemRenderer;
use PhpSchool\CliMdRenderer\Renderer\ParagraphRenderer;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Extension\CommonMark\Node\Block\ThematicBreak;
use League\CommonMark\Extension\CommonMark\Parser\Block\HeadingStartParser;
use League\CommonMark\Extension\CommonMark\Parser\Block\BlockQuoteStartParser;
use League\CommonMark\Extension\CommonMark\Parser\Block\FencedCodeStartParser;
use League\CommonMark\Extension\CommonMark\Parser\Block\HtmlBlockStartParser;
use League\CommonMark\Extension\CommonMark\Parser\Block\IndentedCodeStartParser;
use League\CommonMark\Extension\CommonMark\Parser\Block\ListBlockStartParser;
use League\CommonMark\Parser\Block\SkipLinesStartingWithLettersParser;
use League\CommonMark\Extension\CommonMark\Parser\Block\ThematicBreakStartParser;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Emphasis;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Extension\CommonMark\Parser\Inline\AutolinkParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\BacktickParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\BangParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\CloseBracketParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\EntityParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\EscapableParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\HtmlInlineParser;
use League\CommonMark\Parser\Inline\NewlineParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline\OpenBracketParser;
use League\CommonMark\Node\Node;
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

        $blockParsers = array_map('get_class', iterator_to_array($environment->getBlockStartParsers()));
        self::assertSame([
            SkipLinesStartingWithLettersParser::class,
            BlockQuoteStartParser::class,
            HeadingStartParser::class,
            FencedCodeStartParser::class,
            HtmlBlockStartParser::class,
            ThematicBreakStartParser::class,
            ListBlockStartParser::class,
            IndentedCodeStartParser::class,
        ], $blockParsers);

        $inlineParsers = array_map('get_class', iterator_to_array($environment->getInlineParsers()));
        self::assertSame([
            NewlineParser::class,
            BacktickParser::class,
            EscapableParser::class,
            EntityParser::class,
            AutolinkParser::class,
            HtmlInlineParser::class,
            CloseBracketParser::class,
            OpenBracketParser::class,
            BangParser::class,
        ], $inlineParsers);
    }

    /**
     * @param Environment $environment
     * @param class-string<Node> $blockClass
     * @param class-string<CliBlockRendererInterface> $rendererClass
     * @return void
     */
    private function assertBlockRenderer(Environment $environment, string $blockClass, string $rendererClass): void
    {
        $this->assertRenderer($environment, $blockClass, $rendererClass);
    }

    /**
     * @param Environment $environment
     * @param class-string<Node> $nodeClass
     * @param class-string<CliInlineRendererInterface> $rendererClass
     * @return void
     */
    private function assertInlineRenderer(Environment $environment, string $nodeClass, string $rendererClass): void
    {
        $this->assertRenderer($environment, $nodeClass, $rendererClass);
    }

    /**
     * Our renderer must be the first one tried, the CommonMark HTML renderers are only fallbacks.
     *
     * @param class-string<Node> $nodeClass
     * @param class-string $rendererClass
     */
    private function assertRenderer(Environment $environment, string $nodeClass, string $rendererClass): void
    {
        $classes = array_map('get_class', iterator_to_array($environment->getRenderersForClass($nodeClass), false));
        self::assertSame($rendererClass, $classes[0]);
    }
}
