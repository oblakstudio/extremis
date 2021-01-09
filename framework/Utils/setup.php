<?php declare(strict_types = 1);

namespace Extremis;

use Extremis\Bootstrap;
use Oblak\AssetManager\AssetManager;

add_action('after_setup_theme', function() {

    extremis()->singleton('extremis.assets', function() {
        return AssetManager::getInstance();
    });

    extremis()->singleton('extremis.bootstrap', function() {
        return new Bootstrap(config('modules'));
    });

    extremis('assets')->registerNamespace('extremis', config('assets'));
    extremis('bootstrap')->run();
});
