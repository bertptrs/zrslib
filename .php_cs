<?php

$finder = \Symfony\CS\Finder\DefaultFinder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return \Symfony\CS\Config\Config::create()
    ->level(\Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->finder($finder);