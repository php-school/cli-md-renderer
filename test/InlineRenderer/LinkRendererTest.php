<?php

namespace AydinHassan\CliMdRendererTest\InlineRenderer;

use AydinHassan\CliMdRenderer\CliRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\LinkRenderer;
use AydinHassan\CliMdRenderer\InlineRenderer\TextRenderer;
use AydinHassan\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Element\Text;

class LinkRendererTest extends AbstractInlineRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return LinkRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $link = new Link('http://www.google.com');
        $link->appendChild(new Text('http://www.google.com'));

        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())->addInlineRenderer(Text::class, new TextRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertSame(
            "[94m[1m[4mhttp://www.google.com[0m[0m[0m",
            $renderer->render($link, $cliRenderer)
        );
    }
}
