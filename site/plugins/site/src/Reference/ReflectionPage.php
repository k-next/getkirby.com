<?php

namespace Kirby\Reference;

use Kirby\Cms\App;
use Kirby\Cms\Field;
use Kirby\Cms\Page;
use Kirby\Cms\Template;
use Kirby\Reference\Types;

abstract class ReflectionPage extends Page
{

    protected $docBlock;
    protected $parameters;
    protected $reflection;
    protected $returns = false;
    protected $throws;

    /**
     * Returns how this entry would be called in code
     *
     * @return string
     */
    public function call(): string
    {
        if ($reflection = $this->reflection()) {
            $parameters = array_column($this->parameters(), 'export');
            $parameters = empty($parameters) ? '' : implode(', ', $parameters);

            $call = $this->name() . '(' . $parameters . ')';

            if ($return = $this->returnType()) {
                $call .= ': ' . $return;
            }

            return $call;
        }

        return $this->slug();
    }

    /**
     * Returns deprecation information
     *
     * @return \Kirby\Cms\Field
     */
    public function deprecated(): Field
    {
        if ($docBlock = $this->docBlock()) {
            if ($tag = $docBlock->getTag('deprecated')) {
                $value = $tag->getVersion() . '|' . $tag->getDescription();
                return new Field($this, 'deprecated', $value);
            }
        }

        return parent::deprecated();
    }

    /**
     * Gets the DocBlock information
     *
     * @return \Kirby\Reference\DocBlock|bool
     */
    public function docBlock()
    {
        if ($this->docBlock !== null) {
            return $this->docBlock;
        }

        if ($reflection = $this->reflection()) {
            try {
                return $this->docBlock = new DocBlock($reflection->getDocComment());
            } catch (Throwable $e) {
                return $this->docBlock = false;
            }
        }

        return $this->docBlock = false;
    }

    /**
     * Returns an intro description
     *
     * @return \Kirby\Cms\Field
     */
    public function intro(): Field
    {
        $intro = null;

        // prefer intro defined in content file
        if ($this->content()->has('intro')) {
            return $this->content()->get('intro');
        }

        // otherwise try to get summary from DocBlock in code
        if ($docBlock = $this->docBlock()) {
            $intro = trim($docBlock->getSummary());
            $intro = str_replace(PHP_EOL, ' ', $intro);

            if ($intro === '/') {
                $intro = null;
            }
        }

        return new Field($this, 'intro', $intro);
    }

    /**
     * Check if this has been deprecated
     *
     * @return boolean
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated()->isNotEmpty() === true;
    }

    /**
     * Check if this is marked as internal
     *
     * @return boolean
     */
    public function isInternal(): bool
    {
        if ($docBlock = $this->docBlock()) {
            return is_null($docBlock->getTag('internal')) === false;
        }

        return false;
    }

    public function isMutable(): bool
    {
        return strpos($this->returns(), '$this') !== false;
    }

    public function isImmutable(): bool
    {
        return strpos($this->returns(), 'static') !== false || 
               strpos($this->returns(), 'self') !== false;
    }

    /**
     * Gets the line number where this starts in the code
     *
     * @return int|null
     */
    public function line(): ?int
    {
        if ($reflection = $this->reflection()) {
            return $reflection->getStartLine();
        }

        return null;
    }

    public function metadata(): array
    {
        return [
            'description' => strip_tags($this->intro()->kirbytags()),
            'thumbnail' => [
                'lead'  => $this->metaLead(page('docs/reference'), 'Reference')
            ]
        ];
    }
    
    /**
     * Get the name of this entry
     *
     * @return string
     */
    public function name(): string
    {
        return preg_replace_callback('!-([a-z])!', function ($matches) {
            return strtoupper($matches[1]);
        }, $this->slug());
    }

    /**
     * Returns URL to the code on GitHub
     *
     * @return \Kirby\Cms\Field
     */
    public function onGitHub(string $path = ''): Field
    {
        
        if (empty($path) === false) {
            $url  = option('github.url') . '/kirby/tree/' . App::version();
            $url .= '/' . $path;

            if ($line = $this->line()) {
                $url .= '#L' . $line;
            }
        }

        return field($url ?? null);
    }

    /**
     * Returns an array with all parameter info
     *
     * @return array
     */
    public function parameters(): array
    {
        if ($this->parameters !== null) {
            return $this->parameters;
        }

        $reflection = $this->reflection();
        $parameters = [];

        if (!$reflection) {
            return $this->parameters = $parameters;
        }

        foreach ($reflection->getParameters() as $parameter) {
            $name = $parameter->getName();

            if ($docBlock = $this->docBlock()) {
                $doc = $docBlock->getParameter($name);
            } else {
                $doc = null;
            }
        
            if ($type = $parameter->getType()) {
                $type = $type->getName();
            } elseif ($doc) {
                $type = (string)$doc->getType();
            }

            $param    = trim($type . ' $' . $name);
            $default  = null;
            $optional = false;

            if ($parameter->isOptional() === true) {
                
                if ($parameter->isDefaultValueAvailable()) {
                    $default = $parameter->getDefaultValue();
                    $default = var_export($default, true);
                    $default = str_replace('NULL', 'null', $default);
                    $default = str_replace('array (' . PHP_EOL . ')', '[ ]', $default);
                } else {
                    $default = 'null';
                }
                
                $optional  = true;
                $param    .= ' = ' . $default;
            }

            $parameters[] = [
                'name'        => '$' . $name,
                'required'    => $optional === false,
                'type'        => Types::factory($type ?? 'mixed', $this),
                'default'     => $default,
                'description' => $doc ? (string)$doc->getDescription() : null,
                'export'      => $param
            ];
        }

        return $this->parameters = $parameters;
    }

    /**
     * Creates the reflection object
     */
    protected function reflection()
    {
        return $this->reflection = $this->reflection ?? $this->_reflection();
    }

    protected function _reflection()
    {
        return false;
    }

    public function returns(): ?string
    {
        if ($this->returns !== false) {
            return $this->returns;
        }

        if ($reflection = $this->reflection()) {
            // First, try to get return type from reflection
            if ($reflection->hasReturnType() === true) {
                $type = $reflection->getReturnType();
                $name = $type->getName();

                if ($type->allowsNull() === true) {
                    $name =  $name . '|null';
                }

                return $this->returns = $name;
            }

            // Otherwise, check DocBlock for return type
            if ($docBlock = $this->docBlock()) {
                if ($type = $docBlock->getReturnType()) {
                    $type = trim((string)$type->getType());
                    return $this->returns = $type;
                }
            }
        }

        return $this->returns = null;
    }
    

    /**
     * Returns a string of all return types
     *
     * @return string|null
     */
    public function returnType(): ?string
    {
        if ($return = $this->returns()) {
            return Types::factory($return, $this); 
        }

        return null;
    }

    /**
     * Returns in which version this entry was introduced
     *
     * @return \Kirby\Cms\Field
     */
    public function since(): Field
    {
        if ($docBlock = $this->docBlock()) {
            if ($tag = $docBlock->getTag('since')) {
                $since = $tag->getVersion();
                return new Field($this, 'since', $since ?? null);
            }
        }

        return parent::since();
    }
    
    /**
     * If a dedicated template exist, use it.
     * Otherwise fall back to `reference-article` template.
     * 
     * @return \Kirby\Cms\Template
     */
    public function template(): Template
    {
        // If template exists, use it
        if ($this->intendedTemplate() === parent::template()) {
            return parent::template();
        }
        
        return $this->kirby()->template('reference-article');
    }

     /**
     * Returns what exceptions can be thrown by this
     *
     * @return array
     */
    public function throws(): array
    {
        if ($this->throws !== null) {
            return $this->throws;
        }

        $throws     = [];
        $reflection = $this->reflection();
        $docBlock   = $this->docBlock();

        if (!$reflection || !$docBlock) {
            return $this->throws = $throws;
        }

        foreach ($docBlock->getTagsByName('throws') as $doc) {
            $throws[] = [
                'description' => $doc->getDescription(),
                'type'        => ltrim($doc->getType(), '\\'),
            ];
        }

        return $this->throws = $throws;
    }
}
