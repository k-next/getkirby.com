<?php

namespace Kirby\Reference;

use Kirby\Cms\Page;
use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

use \ReferenceClassPage;
use \ReferenceClassMethodPage;
use \ReferenceFieldMethodPage;
use \ReferenceHelperPage;
use ReflectionClass;

class Types
{

    /**
     * Returns the proper CSS classes for type
     *
     * @param string $type
     * @return string
     */
    public static function class(string $type): string
    {
        return 'type type-' . $type;
    }

    public static function default(?string $value = null): string
    {
        if ($value !== null) {
            return static::format($value);
        }

        return '<span>–</span>';
    }

    public static function factory(string $string, $model): string
    {
        $types = array_map(function ($type) use ($model) {

            if ($type === 'static' || $type === '$this') {
                return $model->parent()->class();
            }

            if ($type === 'self') {
                return $model->inheritedFrom() ?? $model->parent()->class();
            }

            return substr($type, 0, 1) === '\\' ? substr($type, 1) : $type;

        }, explode('|', $string));

        return implode('|', array_unique($types));
    }

    /**
     * Parses the string and tries to find and return a matching
     * reference page for the class, class method, field method or helper
     *
     * @param string $string
     * @return ReferenceClassPage|ReferenceClassMethodPage|ReferenceFieldMethodPage|null
     */
    public static function findReferencePage(string $string): ?Page
    {
        //:: or -> separating class and method
        $chain  = preg_split('/::|->/', $string);
        $class  = array_shift($chain);

        if (count($chain) > 0) {
            // Remove leading $
            $class = ltrim($class, '$');

            if (strtolower($class) === 'field') {
                return ReferenceFieldMethodPage::findByName($chain[0]);
            }
            if (strtolower($class) === 'helper') {
                return ReferenceHelperPage::findByName($chain[0]);
            }
        }

        // Get page for base class/object
        if ($page = ReferenceClassPage::findByName($class)) {

            // If type is only the class, return the page
            if (count($chain) === 0) {
                return $page;
            }

            // Clean up method names
            $methods = array_map(function ($method) {
                return preg_replace('/\(.*\)$/', '', $method);
            }, $chain);

            // If method page can be found by chain, return that page
            if ($page = ReferenceClassMethodPage::findByNames($page, $methods)) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Takes a type tring and wraps it in a <code> tag with the
     * matching CSS classes. Also links to Reference for classes or
     * class methods. Supports multiple types separated by | as well.
     *
     * @param string|null $type
     * @param bool $withLink should the tag be linked (if possible) or not
     * @param string $text label text to use instead type
     * @return string
     */
    public static function format(?string $type = null, bool $withLink = true, ?string $text = null): string
    {
        if(empty($type) === true) {
            return '';
        }

        // Any type containing at least one whitespace or any character
        // that cannot be part of a datatype, just return a plain
        // code element.
        if (preg_match('/^[^\\a-z0-9_\->:]+$/iu', $type) === 1) {
            return '<code>' . ($text ?? $type) . '</code>';
        }

        // Multiple datatypes
        if (Str::contains($type, '|')) {
            $types = explode('|', $type);

            // Don’t process code blocks, that contain empty elements
            if (in_array('', $types) === true) {
                return "<code>{$type}</code>";
            }

            $types = array_map(function ($t) use ($withLink) {
                return static::format($t, $withLink);
            }, $types);

            return implode('<span class="px-1">|</span>', $types);
        }

        // Native PHP/JS datatypes
        $native = [
            'string',
            'int',
            'float',
            'number',
            'bool',
            'boolean',
            'array',
            'object',
            'mixed',
            'null',
        ];

        if (in_array($type, $native) === true) {
            return static::tag($type, $type);
        }

        // Implied native datatypes
        $implied = [
            'false' => 'bool',
            'true'  => 'bool'
        ];

        if (in_array($type, array_keys($implied)) === true) {
            return static::tag($type, $implied[$type]);
        }

        $text = $text ?? $type;

        // Assume, it’s a class or class method name
        // (starting with a letter, \ or $)
        if (preg_match('/^[A-Z\\\$]/', $type) === 1) {

            // Check if reference page for Kirby class
            // or class method exists
            if ($page = static::findReferencePage($type)) {
                $class = is_a($page, ReferenceClassPage::class) === true ? 'object' : 'method';
                $tag = static::tag($text, $class);

                if ($withLink === true) {
                    $tag = Html::a($page->url(), [$tag], [
                        'class' => 'type-link'
                    ]);
                }

                return $tag;
            }

            // Some class that exists in PHP in the global namespace.
            // The second check is done to ensure correct case,
            // as `class_exists()` is not case-sensitive.
            if (class_exists($type) === true &&
                (new ReflectionClass($type))->getName() === $type
            ) {
                return static::tag($text, 'class');
            }
        }

        // Probably a code example (not a datatype),
        // just return a plain code tag
        return static::tag($text);
    }

    /**
     * Returns required asteriks markup if required flag is true
     *
     * @param boolean $required
     * @return string|null
     */
    public static function required(bool $required): ?string
    {
        if ($required === true) {
            return '<span class="required-mark">*</span>';
        }

        return null;
    }

    /**
     * Wraps the type string in code tag with matching CSS classes
     *
     * @param string $type
     * @param string|null $class
     * @return string
     */
    public static function tag(string $type, ?string $class = null): string
    {
        return Html::tag('code', $type, [
            'class' => $class ? static::class($class) : null,
        ]);
    }
}
