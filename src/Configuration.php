<?php

namespace karster\image;

/**
 * Class Configuration
 * @package karster\image
 */
class Configuration
{
    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $searchWindow;

    /**
     * @var int
     */
    public $colorDepth;

    /**
     * @var string
     */
    public $imageSrc;

    /**
     * @var string
     */
    public $altText;

    /**
     * @var bool
     */
    public $test = false;

    public function __construct(array $configuration_array)
    {
        foreach ($configuration_array as $attribute => $value) {
            if ($attribute == 'colorDepth') {
                $this->$attribute = (int) pow(2, $value);
            } else {
                $this->$attribute = $value;
            }
        }
    }
}