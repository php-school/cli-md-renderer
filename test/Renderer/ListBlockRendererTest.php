<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRenderer\Renderer\ListBlockRenderer;
use PhpSchool\CliMdRenderer\Renderer\ListItemRenderer;
use PhpSchool\CliMdRenderer\Renderer\ParagraphRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\ListData;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Node\Inline\Text;

class ListBlockRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return ListBlockRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $list = new ListBlock(new ListData());

        $color = new Color();
        $color->setForceStyle(true);
        $environment = new Environment();
        $environment->addRenderer(Text::class, new TextRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals('', $renderer->render($list, $cliRenderer));
    }

    public function testRenderWithChildren(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $list = new ListBlock(new ListData());

        $listItem1 = new ListItem(new ListData());
        $paragraph = new Paragraph();
        $paragraph->appendChild(new Text('Item 1'));
        $listItem1->appendChild($paragraph);

        $listItem2 = new ListItem(new ListData());
        $paragraph = new Paragraph();
        $paragraph->appendChild(new Text('Item 2'));
        $listItem2->appendChild($paragraph);

        $list->appendChild($listItem1);
        $list->appendChild($listItem2);

        $color = new Color();
        $color->setForceStyle(true);

        $environment = (new Environment())
            ->addRenderer(Paragraph::class, new ParagraphRenderer())
            ->addRenderer(ListItem::class, new ListItemRenderer())
            ->addRenderer(Text::class, new TextRenderer());

        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals("\e[33m * \e[0mItem 1\n\n\e[33m * \e[0mItem 2\n", $renderer->render($list, $cliRenderer));
    }
}
