<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliExtension;
use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\Renderer\HorizontalRuleRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Extension\CommonMark\Node\Block\ThematicBreak;
use League\CommonMark\Environment\Environment;

class HorizontalRuleRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return HorizontalRuleRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $rule = new ThematicBreak();

        $color = new Color();
        $color->setForceStyle(true);
        $cliRenderer = new CliRenderer(new Environment(), $color);

        $this->assertEquals(
            "[90m------------------------------[0m",
            $renderer->render($rule, $cliRenderer)
        );
    }

    public function testRendererWithWidthOptionOnEnvironment(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $rule = new ThematicBreak();

        $color = new Color();
        $color->setForceStyle(true);
        $environment = new Environment(['renderer' => ['width' => 10]]);
        $environment->addExtension(new CliExtension());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "[90m----------[0m",
            $renderer->render($rule, $cliRenderer)
        );
    }
}
