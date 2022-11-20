<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRenderer\Renderer\ParagraphRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Text;

class ParagraphRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return ParagraphRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $paragraph = new Paragraph();
        $paragraph->appendChild(new Text('Some Text 1'));
        $paragraph->appendChild(new Text('Some Text 2'));

        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())->addInlineRenderer(Text::class, new TextRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "Some Text 1Some Text 2\n",
            $renderer->render($paragraph, $cliRenderer)
        );
    }
}
