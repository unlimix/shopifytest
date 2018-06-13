<?php

namespace App\Data\Product;

/**
 * Class Product
 * @package App\Data
 */
class Product
{

    /** @var float */
    private $id;

    /** @var string */
    private $title;

    /** @var array */
    private $tags;

    /** @var string */
    private $image;

    /**
     * Product constructor.
     * @param float $id
     * @param string $title
     * @param string $tags
     * @param \stdClass|null $image
     */
    public function __construct($id, $title, $tags, $image)
    {
        $this->id = $id;
        $this->title = $title;
        $this->tags = explode(', ', $tags);
        $this->image = !is_null($image)? $image->src : '';
    }

    /**
     * @return float
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getImageSrc()
    {
        return $this->image;
    }

}