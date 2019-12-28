<?php
declare(strict_types = 1);
/**
 * This file has been generated for sami/sami
 */
use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()->files()->name("*.php")->in(__DIR__."/../src");

$description = json_decode(file_get_contents(__DIR__."/../composer.json"))->description;

return new Sami(
    $iterator, array(
    "title"                => $description,
    "build_dir"            => __DIR__."/../docs",
    "cache_dir"            => __DIR__."/../tmp"
    )
);
