<?php

namespace AydinHassan\CliMdRenderer;

use Colors\Color;
use League\CommonMark\Environment;

class CliRendererFactory
{
    public function __invoke(array $environmentConfig = []): CliRenderer
    {
        $environment = new Environment($environmentConfig);
        $environment->addExtension(new CliExtension());

        $colors = new Color();
        $colors->setForceStyle(true);

        return new CliRenderer($environment, $colors);
    }
}
