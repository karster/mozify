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
        imagetruecolortopalette($this->image, false, $this->configuration->colorDepth);
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
        for ($y = 0; $y < $this->height; $y += $this->configuration->searchWindow) {
            for ($x = 0; $x < $this->width; $x += $this->configuration->searchWindow) {
                $this->colorArray[$y][$x] = $this->extractColor($x, $y);
            }
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
        $out = '';

        if (isset($rgba['alpha'])) {
            unset($rgba['alpha']);
        }

        foreach ($rgba as $c) {
            $hex = base_convert($c, 10, 16);
            $out .= ($c < 16) ? ("0" . $hex) : $hex;
        }

        return '#' . strtoupper($out);
    }

    public function process()
    {
        if (!empty($this->configuration->width) && !empty($this->configuration->height)) {
            $this->resize();
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

    public function getMostCommonColor()
    {
        $result = [];
        foreach ($this->colorArray as $row) {
            $result = array_merge($result, $row);
        }

        return array_keys($result, max($result))[0];
    }

    public function getColorArray()
    {
        return $this->colorArray;
    }
}