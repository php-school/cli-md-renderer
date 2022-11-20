<?php

declare(strict_types=1);

namespace PhpSchool\CliMdRenderer;

use PhpSchool\CliMdRenderer\Highlighter\PhpHighlighter;
use PhpSchool\CliMdRenderer\InlineRenderer\CodeRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\EmphasisRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\LinkRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\NewlineRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\StrongRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRenderer\Renderer\DocumentRenderer;
use PhpSchool\CliMdRenderer\Renderer\FencedCodeRenderer;
use PhpSchool\CliMdRenderer\Renderer\HeaderRenderer;
use PhpSchool\CliMdRenderer\Renderer\HorizontalRuleRenderer;
use PhpSchool\CliMdRenderer\Renderer\ListBlockRenderer;
use PhpSchool\CliMdRenderer\Renderer\ListItemRenderer;
use PhpSchool\CliMdRenderer\Renderer\ParagraphRenderer;
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
