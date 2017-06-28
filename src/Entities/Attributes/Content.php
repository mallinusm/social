<?php

namespace Social\Entities\Attributes;

/**
 * Trait Content
 * @package Social\Entities\Attributes
 */
trait Content
{
    /**
     * @var string
     */
    private $content;

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        return $this->content !== null;
    }
}
