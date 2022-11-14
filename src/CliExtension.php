<?php

declare(strict_types=1);

namespace AydinHassan\CliMdRenderer;

use AydinHassan\CliMdRenderer\Highlighter\PhpHighlighter;
use AydinHassan\CliMdRenderer\InlineRenderer\CodeRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\EmphasisRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\LinkRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\NewlineRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\StrongRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\TextRenderer;
use AydinHassan\CliMdRenderer\Renderer\DocumentRenderer;
use AydinHassan\CliMdRenderer\Renderer\FencedCodeRenderer;
use AydinHassan\CliMdRenderer\Renderer\HeaderRenderer;
use AydinHassan\CliMdRenderer\Renderer\HorizontalRuleRenderer;
use AydinHassan\CliMdRenderer\Renderer\ListBlockRenderer;
use AydinHassan\CliMdRenderer\Renderer\ListItemRenderer;
use AydinHassan\CliMdRenderer\Renderer\ParagraphRenderer;
use Kadet\Highlighter\KeyLighter;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Block\Element\ThematicBreak;
use League\CommonMark\Block\Parser as BlockParser;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Element\Code;
use League\CommonMark\Inline\Element\Emphasis;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Element\Newline;
use League\CommonMark\Inline\Element\Strong;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Inline\Parser as InlineParser;

class CliExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment): void
    {
        $codeRender = new FencedCodeRenderer();
        $keyLighter = new KeyLighter();
        $keyLighter->init();
        $codeRender->addSyntaxHighlighter('php', new PhpHighlighter($keyLighter));

        $environment
            ->addBlockParser(new BlockParser\BlockQuoteParser(), 70)
            ->addBlockParser(new BlockParser\ATXHeadingParser(), 60)
            ->addBlockParser(new BlockParser\FencedCodeParser(), 50)
            ->addBlockParser(new BlockParser\HtmlBlockParser(), 40)
            ->addBlockParser(new BlockParser\SetExtHeadingParser(), 30)
            ->addBlockParser(new BlockParser\ThematicBreakParser(), 20)
            ->addBlockParser(new BlockParser\ListParser(), 10)
            ->addBlockParser(new BlockParser\IndentedCodeParser(), -100)
            ->addBlockParser(new BlockParser\LazyParagraphParser(), -200)

            ->addInlineParser(new InlineParser\NewlineParser(), 200)
            ->addInlineParser(new InlineParser\BacktickParser(), 150)
            ->addInlineParser(new InlineParser\EscapableParser(), 80)
            ->addInlineParser(new InlineParser\EntityParser(), 70)
            ->addInlineParser(new InlineParser\AutolinkParser(), 50)
            ->addInlineParser(new InlineParser\HtmlInlineParser(), 40)
            ->addInlineParser(new InlineParser\CloseBracketParser(), 30)
            ->addInlineParser(new InlineParser\OpenBracketParser(), 20)
            ->addInlineParser(new InlineParser\BangParser(), 10)

            ->addBlockRenderer(Document::class, new DocumentRenderer())
            ->addBlockRenderer(Heading::class, new HeaderRenderer())
            ->addBlockRenderer(ThematicBreak::class, new HorizontalRuleRenderer())
            ->addBlockRenderer(Paragraph::class, new ParagraphRenderer())
            ->addBlockRenderer(FencedCode::class, $codeRender)
            ->addBlockRenderer(ListBlock::class, new ListBlockRenderer())
            ->addBlockRenderer(ListItem::class, new ListItemRenderer())

            ->addInlineRenderer(Text::class, new TextRenderer())
            ->addInlineRenderer(Code::class, new CodeRenderer())
            ->addInlineRenderer(Emphasis::class, new EmphasisRenderer())
            ->addInlineRenderer(Strong::class, new StrongRenderer())
            ->addInlineRenderer(Newline::class, new NewlineRenderer())
            ->addInlineRenderer(Link::class, new LinkRenderer());
    }
}
