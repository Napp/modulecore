<?php

namespace Napp\Core\Module\Extension;

/**
 * Interface ExtensionRegistrarInterface
 * @package Napp\Core\Module\Extension
 */
interface ExtensionRegistrarInterface
{
    /**
     * @param string $key
     * @param string $label
     * @param bool $hasSettings
     */
    public static function addExtension(string $key, string $label, bool $hasSettings = false): void;

    /**
     * @param string $key
     * @param string $label
     * @param bool $hasSettings
     */
    public static function addFeature(string $key, string $label, bool $hasSettings = false): void;

    /**
     * @return array
     */
    public static function getAllExtensions(): array;

    /**
     * @return array
     */
    public static function getAllFeatures(): array;


}