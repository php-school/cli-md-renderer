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
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Delimiter\Processor\EmphasisDelimiterProcessor;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use League\CommonMark\Extension\CommonMark\Node\Block\ThematicBreak;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Emphasis;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use League\CommonMark\Extension\CommonMark\Parser\Block as BlockParser;
use League\CommonMark\Extension\CommonMark\Parser\Inline as InlineParser;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\NewlineParser;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

class CliExtension implements ConfigurableExtensionInterface
{
    /**
     * Take precedence over any HTML renderers registered by other extensions (e.g. the CommonMark core extension).
     */
    private const RENDERER_PRIORITY = 10;

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        //required by the CommonMark list parser, identical to the schema of the CommonMark core extension
        $builder->addSchema('commonmark', Expect::structure([
            'use_asterisk' => Expect::bool(true),
            'use_underscore' => Expect::bool(true),
            'enable_strong' => Expect::bool(true),
            'enable_em' => Expect::bool(true),
            'unordered_list_markers' => Expect::listOf('string')
                ->min(1)
                ->default(['*', '+', '-'])
                ->mergeDefaults(false),
        ]));

        $builder->addSchema('renderer', Expect::structure([
            'width' => Expect::int(30),
        ])->otherItems(Expect::mixed()));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $codeRender = new FencedCodeRenderer();
        $keyLighter = new KeyLighter();
        $keyLighter->init();
        $codeRender->addSyntaxHighlighter('php', new PhpHighlighter($keyLighter));

        $environment
            ->addBlockStartParser(new BlockParser\BlockQuoteStartParser(), 70)
            ->addBlockStartParser(new BlockParser\HeadingStartParser(), 60)
            ->addBlockStartParser(new BlockParser\FencedCodeStartParser(), 50)
            ->addBlockStartParser(new BlockParser\HtmlBlockStartParser(), 40)
            ->addBlockStartParser(new BlockParser\ThematicBreakStartParser(), 20)
            ->addBlockStartParser(new BlockParser\ListBlockStartParser(), 10)
            ->addBlockStartParser(new BlockParser\IndentedCodeStartParser(), -100)

            ->addInlineParser(new NewlineParser(), 200)
            ->addInlineParser(new InlineParser\BacktickParser(), 150)
            ->addInlineParser(new InlineParser\EscapableParser(), 80)
            ->addInlineParser(new InlineParser\EntityParser(), 70)
            ->addInlineParser(new InlineParser\AutolinkParser(), 50)
            ->addInlineParser(new InlineParser\HtmlInlineParser(), 40)
            ->addInlineParser(new InlineParser\CloseBracketParser(), 30)
            ->addInlineParser(new InlineParser\OpenBracketParser(), 20)
            ->addInlineParser(new InlineParser\BangParser(), 10)

            ->addDelimiterProcessor(new EmphasisDelimiterProcessor('*'))
            ->addDelimiterProcessor(new EmphasisDelimiterProcessor('_'))

            ->addRenderer(Document::class, new DocumentRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Heading::class, new HeaderRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(ThematicBreak::class, new HorizontalRuleRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Paragraph::class, new ParagraphRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(FencedCode::class, $codeRender, self::RENDERER_PRIORITY)
            ->addRenderer(ListBlock::class, new ListBlockRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(ListItem::class, new ListItemRenderer(), self::RENDERER_PRIORITY)

            ->addRenderer(Text::class, new TextRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Code::class, new CodeRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Emphasis::class, new EmphasisRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Strong::class, new StrongRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Newline::class, new NewlineRenderer(), self::RENDERER_PRIORITY)
            ->addRenderer(Link::class, new LinkRenderer(), self::RENDERER_PRIORITY);
    }
}
