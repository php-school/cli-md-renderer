<?php

namespace AydinHassan\CliMdRendererTest\Renderer;

use AydinHassan\CliMdRenderer\CliRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\TextRenderer;
use AydinHassan\CliMdRenderer\Renderer\ListItemRenderer;
use AydinHassan\CliMdRenderer\Renderer\ParagraphRenderer;
use AydinHassan\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Block\Element\ListData;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Text;

class ListItemRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return ListItemRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $list = new ListItem(new ListData());

        $paragraph = new Paragraph();
        $paragraph->appendChild(new Text('Item 1'));
        $list->appendChild($paragraph);

        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())
            ->addBlockRenderer(Paragraph::class, new ParagraphRenderer())
            ->addInlineRenderer(Text::class, new TextRenderer());

        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals("\e[33m * \e[0mItem 1\n", $renderer->render($list, $cliRenderer));
    }
}
