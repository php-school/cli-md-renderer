<?php

namespace PhpSchool\CliMdRenderer;

use PhpSchool\CliMdRenderer\InlineRenderer\CliInlineRendererInterface;
use PhpSchool\CliMdRenderer\Renderer\CliBlockRendererInterface;
use Colors\Color;
use League\CommonMark\Environment\EnvironmentInterface;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Inline\AbstractInline;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\Config\Exception\ConfigurationExceptionInterface;
use RuntimeException;

class CliRenderer implements ChildNodeRendererInterface
{
    /**
     * @var EnvironmentInterface
     */
    private $environment;

    /**
     * @var Color
     */
    private $color;

    public function __construct(EnvironmentInterface $environment, Color $color)
    {
        $this->environment = $environment;
        $this->color = $color;
    }

    /**
     * @param string $option
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getOption(string $option, $default = null)
    {
        try {
            return $this->environment->getConfiguration()->get('renderer/' . $option) ?? $default;
        } catch (ConfigurationExceptionInterface $e) {
            return $default;
        }
    }

    /**
     * @param string $string
     * @param array<string>|string $colourOrStyle
     *
     * @return string
     */
    public function style(string $string, $colourOrStyle): string
    {
        if (is_array($colourOrStyle)) {
            $this->color->__invoke($string);

            while ($style = array_shift($colourOrStyle)) {
                $this->color->apply($style);
            }
            return $this->color->__toString();
        }

        /** @var string $styled colors.php is untyped, with a text argument it always returns a string */
        $styled = $this->color->apply($colourOrStyle, $string);

        return $styled;
    }

    public function renderInline(Node $inline): string
    {
        $renderers = $this->environment->getRenderersForClass(get_class($inline));

        foreach ($renderers as $renderer) {
            if (($result = $renderer->render($inline, $this)) !== null) {
                return $result;
            }
        }

        throw new RuntimeException(
            sprintf('Unable to find corresponding renderer for inline type: "%s"', get_class($inline))
        );
    }

    /**
     * @param iterable<Node> $inlines
     *
     * @return string
     */
    public function renderInlines(iterable $inlines): string
    {
        $inlines = is_array($inlines) ? $inlines : iterator_to_array($inlines);

        return implode(
            "",
            array_map(
                function (Node $inline) {
                    return $this->renderInline($inline);
                },
                $inlines
            )
        );
    }

    public function renderBlock(Node $block): string
    {
        $renderers = $this->environment->getRenderersForClass(get_class($block));

        foreach ($renderers as $renderer) {
            if (($result = $renderer->render($block, $this)) !== null) {
                return $result;
            }
        }

        throw new RuntimeException(
            sprintf('Unable to find corresponding renderer for block type: "%s"', get_class($block))
        );
    }

    /**
     * @param iterable<Node> $blocks
     *
     * @return string
     */
    public function renderBlocks(iterable $blocks): string
    {
        $blocks = is_array($blocks) ? $blocks : iterator_to_array($blocks);

        return implode(
            "\n",
            array_map(
                function (Node $block) {
                    return $this->renderBlock($block);
                },
                $blocks
            )
        );
    }

    /**
     * Renders a list of nodes. Block nodes are separated by a newline, inline nodes are concatenated.
     *
     * @param iterable<Node> $nodes
     */
    public function renderNodes(iterable $nodes): string
    {
        $output = '';
        $previous = null;

        foreach ($nodes as $node) {
            if ($node instanceof AbstractBlock) {
                $output .= ($previous instanceof AbstractBlock ? $this->getBlockSeparator() : '') .
                    $this->renderBlock($node);
            } elseif ($node instanceof AbstractInline) {
                $output .= $this->renderInline($node);
            } else {
                throw new RuntimeException(
                    sprintf('Unable to find corresponding renderer for node type: "%s"', get_class($node))
                );
            }

            $previous = $node;
        }

        return $output;
    }

    public function getBlockSeparator(): string
    {
        return "\n";
    }

    public function getInnerSeparator(): string
    {
        return "\n";
    }
}
