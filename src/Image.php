<?php

namespace karster\image;


class Image
{
    /**
     * @var resource
     */
    private $image;

    private $colorDepth = 5;

    private $width;

    private $height;

    private $type;

    /**
     * @var array
     */
    private $colorArray = [];

    /**
     * @var Configuration
     */
    private $configuration;

    
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        $this->image = imagecreatefromstring(file_get_contents($this->configuration->imageSrc));
        imagealphablending($this->image, false);
        imagesavealpha($this->image, false);
        list($this->width, $this->height, $this->type) = getimagesize($this->configuration->imageSrc);
    }

    /**
     * @return resource
     */
    private function resize()
    {
        $image = imagecreatetruecolor($this->configuration->width, $this->configuration->height);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        imagecolortransparent($image, $white);
        imagecopyresampled($image, $this->image, 0, 0, 0, 0, $this->configuration->width, $this->configuration->height, $this->width, $this->height);

        $this->width = $this->configuration->width;
        $this->height = $this->configuration->height;
        $this->image = $image;
    }

    public function breakIntoColorArray()
    {
        imagetruecolortopalette($this->image, false, $this->configuration->colorDepth);
        $row_index = 0;
        for ($y = 0; $y < $this->height; $y += $this->configuration->searchWindow) {
            for ($x = 0; $x < $this->width; $x += $this->configuration->searchWindow) {
                $this->colorArray[$row_index][] = $this->extractColor($x, $y);
            }
            $row_index++;
        }
    }

    /**
     * @param $x
     * @param $y
     * @return string
     */
    private function extractColor($x, $y)
    {
        $color = imagecolorat($this->image, $x, $y);
        $rgba = imagecolorsforindex($this->image, $color);

        if (isset($rgba['alpha'])) {
            unset($rgba['alpha']);
        }

        $hex = "#";

        foreach ($rgba as $c) {
            $hex .= str_pad(dechex($c), 2, "0", STR_PAD_LEFT);
        }

        return $hex;
    }

    public function process()
    {
        if (!empty($this->configuration->width) && !empty($this->configuration->height)) {
            $this->resize();
        } else {
            $white = imagecolorallocate($this->image, 255, 255, 255);
            imagefill($this->image, 0, 0, $white);
            imagecolortransparent($this->image, $white);
        }

        $this->breakIntoColorArray();

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getColorArray()
    {
        return $this->colorArray;
    }
}