<?php
namespace src\traits;

trait Slug
{
    /**
     * @var string
     */
    private $slug;

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}