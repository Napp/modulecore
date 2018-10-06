<?php

namespace Napp\Core\Module\Extension;

/**
 * Class ExtensionRegistrar
 * @package Napp\Core\Module\Extension
 */
class ExtensionRegistrar implements ExtensionRegistrarInterface
{
    /** @var array  */
    protected static $extensions = [];

    /** @var array  */
    protected static $features = [];

    /** @var array  */
    protected static $settings = [];

    /** @var \Napp\Core\Module\Extension\ExtensionRegistrar  */
    private static $instance;

    /**
     * \Napp\Core\Module\Extension\ExtensionRegistrar constructor.
     */
    private function __construct()
    {
    }

    /**
     * Singleton class instance.
     * @return \Napp\Core\Module\Extension\ExtensionRegistrar
     */
    public static function getInstance()
    {
        if (!null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @param string $label
     * @param bool $hasSettings
     */
    public static function addExtension(string $key, string $label, bool $hasSettings = false): void
    {
        self::$extensions[] = [
            'key' => $key,
            'label' => $label
        ];

        if ($hasSettings) {
            self::$settings[] = $key;
        }
    }

    /**
     * @param string $key
     * @param string $label
     * @param bool $hasSettings
     */
    public static function addFeature(string $key, string $label, bool $hasSettings = false): void
    {
        self::$features[] = [
            'key' => $key,
            'label' => $label
        ];

        if ($hasSettings) {
            self::$settings[] = $key;
        }
    }

    /**
     * @return array
     */
    public static function getAllFeatures(): array
    {
        return self::$features;
    }

    /**
     * @return array
     */
    public static function getAllExtensions(): array
    {
        return self::$extensions;
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function validateFeatureType(string $key): bool
    {
        return \in_array($key, \array_column(self::$features, 'key'), true);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function validateExtensionType(string $key): bool
    {
        return \in_array($key, \array_column(self::$extensions, 'key'), true);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function hasSettings($key): bool
    {
        return \in_array($key, self::$settings, true);
    }
}