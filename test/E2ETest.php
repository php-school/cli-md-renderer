<?php

namespace PhpSchool\CliMdRendererTest;

use PhpSchool\CliMdRenderer\CliRendererFactory;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Parser\MarkdownParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class E2ETest extends TestCase
{
    #[DataProvider('e2eProvider')]
    public function testFullRender(string $markdown, string $expected): void
    {
        $factory    = new CliRendererFactory();
        $renderer   = $factory->__invoke();
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $parser     = new MarkdownParser($environment);

        $this->assertEquals($expected, $renderer->renderBlock($parser->parse($markdown)));
    }

    /**
     * @return \Generator
     */
    public static function e2eProvider()
    {
        foreach (glob(__DIR__ . '/res/e2e/*.md') as $markdownFile) {
            $markdownContent = file_get_contents($markdownFile);
            $expected        = file_get_contents(substr($markdownFile, 0, -2) . 'expected');
            yield [$markdownContent, $expected];
        }
    }
}
