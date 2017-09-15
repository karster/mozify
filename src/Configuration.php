<?php

namespace karster\image;


class Configuration
{
    public $width;

    public $height;

    public $searchWindow;

    public $colorDepth;

    public $imageSrc;

    public $altText;

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