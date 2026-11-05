<?php

declare(strict_types=1);

namespace App\View;

use RuntimeException;
use Smarty\Smarty;

final class SmartyFactory
{
    private function __construct() {}

    public static function create(string $projectDirectory): Smarty
    {
        $compileDirectory = $projectDirectory . '/var/cache/smarty/compile';
        $cacheDirectory = $projectDirectory . '/var/cache/smarty/cache';
        foreach ([$compileDirectory, $cacheDirectory] as $directory) {
            if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
                throw new RuntimeException('Cannot create Smarty directory: ' . $directory);
            }
            if (!is_writable($directory)) {
                throw new RuntimeException('Smarty directory is not writable: ' . $directory);
            }
        }

        $smarty = new Smarty();
        $smarty->setTemplateDir($projectDirectory . '/src/templates');
        $smarty->setCompileDir($compileDirectory);
        $smarty->setCacheDir($cacheDirectory);
        $smarty->setCaching(Smarty::CACHING_OFF);
        $smarty->setEscapeHtml(true);
        $smarty->registerPlugin('modifier', 'date_utc', static fn (int $timestamp): string => gmdate('d.m.Y', $timestamp));
        $smarty->registerPlugin('modifier', 'date_iso', static fn (int $timestamp): string => gmdate('Y-m-d\TH:i:s\Z', $timestamp));

        return $smarty;
    }
}
