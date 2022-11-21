<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRenderer\Renderer\HeaderRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Text;

class HeaderRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return HeaderRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $header = new Heading(2, 'HEADING!!');
        $header->appendChild(new Text('HEADING!!'));


        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())->addInlineRenderer(Text::class, new TextRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "\n[90m##[0m [36m[1mHEADING!![0m[0m\n",
            $renderer->render($header, $cliRenderer)
        );
    }
}
