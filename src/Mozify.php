<?php

namespace karster\image;


class Mozify
{
    /**
     * @var int
     */
    private $searchWindow = 5;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $imageSrc;

    /**
     * @var int
     */
    private $colorDepth = 5;

    /**
     * @var int
     */
    private $userWidth;

    /**
     * @var int
     */
    private $userHeight;

    /**
     * @var string
     */
    private $altText;

    /**
     * @var bool
     */
    private $test = false;

    /**
     * @param string $image_src
     * @return $this
     */
    public function setImageSrc($image_src)
    {
        $this->imageSrc = $image_src;
        list($this->width, $this->height, $this->type) = getimagesize($image_src);

        return $this;
    }

    /**
     * @param int $sharpness
     * @return $this;
     */
    public function setSearchWindow(int $search_window)
    {
        $this->searchWindow = $search_window;

        return $this;
    }

    /**
     * @param $alt_text
     * @return $this
     */
    public function setAltText($alt_text)
    {
        $this->altText = $alt_text;

        return $this;
    }

    /**
     * @param int $bit
     * @return $this
     */
    public function setColorDepth(int $bit)
    {
        $this->colorDepth = $bit;

        return $this;
    }

    /**
     * @param $test
     * @return $this
     */
    public function setTest(bool $test)
    {
        $this->test = $test;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth(int $width)
    {
        $this->userWidth = $width;

        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight(int $height)
    {
        $this->userHeight = $height;

        return $this;
    }

    /**
     * Generovanie mozify
     * @return string
     */
    public function generate()
    {
        $configuration = $this->createConfiguration();
        $image = (new Image($configuration))->process();

        $color_array = $image->getColorArray();
        $rows = $this->createTableCells($color_array);

        $configuration = $image->updateConfiguration();
        $mosaic_table = new MosaicTable($configuration);
        $mso_hack = new MsoHack($configuration);
        $wrapper = new Wrapper($configuration);
        $return = '';

        if(!$this->test) {
            $return .= $this->getCSS().PHP_EOL;
            $return .= $mso_hack->begin().PHP_EOL;
            $return .= $wrapper->begin().PHP_EOL;
            $return .= $this->getImageReplacement($configuration).PHP_EOL;
        }

        $return .= $mosaic_table->setRows($rows)->generate().PHP_EOL;

        if(!$this->test) {
            $return .= $wrapper->end().PHP_EOL;
            $return .= $mso_hack->end().PHP_EOL;
        }

        return $return;
    }

    /**
     * @return Configuration
     */
    private function createConfiguration()
    {
        return new Configuration([
            'width' => $this->userWidth,
            'height' => $this->userHeight,
            'colorDepth' => $this->colorDepth,
            'searchWindow' => $this->searchWindow,
            'imageSrc' => $this->imageSrc,
            'altText' => $this->altText
        ]);
    }

    private function createTableCells($color_array)
    {
        $result = [];
        foreach($color_array as $row => &$cols)
        {
            $cols = $this->groupSameColor($cols);

            foreach($cols as $i => $col)
            {
                $rows = 1;
                $j    = $row;

                $result[$row][$i] = $col;

                if(count($color_array[$row]) == 1)
                {
                    while(isset($color_array[$j][$i]) && count($color_array[$j]) == 1 && $color_array[$j][$i]["bgcolor"] == $col["bgcolor"] && $color_array[$j][$i]["colspan"] == $col["colspan"])
                    {
                        $result[$row][$i]["height"] = $this->searchWindow*$rows;

                        $j++;
                        $rows++;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Zoskupí rovnaké farby
     * @param  array $row riadok
     * @return array
     */
    private function groupSameColor($row)
    {
        $color_group = [];
        $count = 1;
        foreach ($row as $index => $color) {
            if (isset($row[$index + 1])) {
                if ($row[$index + 1] == $color) {
                    $count++;
                } else {
                    $color_group[] = $this->setColorArray($color, $count);
                    $count = 1;
                }
            } else {
                $color_group[] = $this->setColorArray($color, $count);
            }
        }

        return $color_group;
    }

    /**
     * Vloží hodnoty do poľa
     * @param string  $color farba
     * @param integer $count počet
     */
    private function setColorArray($color, $count)
    {

        return [
            'bgcolor' => $color,
            'colspan' => $count,
            'width' => $this->searchWindow * $count,
            'height' => $this->searchWindow
        ];
    }

    /**
     * @param Configuration $configuration
     * @return string
     */
    private function getImageReplacement(Configuration $configuration)
    {
        return <<<HTML
        <div class="c0" style="width:0;height:0;overflow:visible;float:left;position:absolute">
            <table cellspacing="0" cellpadding="0" class="c0">
                <tbody>
                    <tr>
                        <td style="background: url({$this->imageSrc}) no-repeat top / {$configuration->width}px {$configuration->height}px;">
                            <div class="c0" style="width:{$configuration->width}px;height:{$configuration->height}px"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
HTML;
    }

    /**
     * Vytvorenie CSS štýlu
     * @return string
     */
    private function getCSS()
    {
        $css = '<style type="text/css">';
        $css .= '.ExternalClass .ecxhm1_3{width:'.$this->width.'px !important;height:'.$this->height.'px !important;float:none !important}.ExternalClass .ecxhm2_3{display:none !important}';
        $css .= '.c0 td b{width:1px;height:1px;font-size:1px}.c0{-webkit-text-size-adjust: none}';
        //$css .= '.mHt td{width:'.$this->sharpness.'px;height:'.$this->sharpness.'px;}';
        $css .= '</style>';

        return $css;
    }
}