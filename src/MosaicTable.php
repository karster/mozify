<?php

namespace karster\image;


class MosaicTable
{
    private $rows;

    private $width;

    private $height;

    private $sharpness;

    private $mostCommonColor;

    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function setSharpness($sharpness)
    {
        $this->sharpness = $sharpness;

        return $this;
    }

    public function setMostCommonColor($color)
    {
        $this->mostCommonColor = $color;

        return $this;
    }

    public function generate()
    {
        $options = [
            'width' => $this->width,
            'height' => $this->height,
            'cellspacing' => 0,
            'cellpadding' => 0,
            'border' => 0,
            'bgcolor' => $this->mostCommonColor,
            'class ' => 'c0'
        ];

        $result = $this->beginTag('table', $options);
        $result .= $this->beginTag('tbody');

        foreach ($this->rows as $row) {
            $result .= $this->beginTag('tr');

            foreach ($row as $column) {
                $column_options = [];
                $column_options['width'] = $column['width'];

                if ($column['height'] > $this->sharpness) {
                    $column_options['height'] = $column["height"];
                }

                if ($column["colspan"] != 1) {
                    $column_options['colspan'] = $column["colspan"];
                }

                if ($this->mostCommonColor != $column["bgcolor"]) {
                    $column_options['bgcolor'] = $column['bgcolor'];
                }

                $result .= $this->beginTag('td', $column_options);
                $result .= $this->endTag('td');
            }

            $result .= $this->endTag('tr');
        }

        $result .= $this->endTag('tbody');
        $result .= $this->endTag('table');

        return $result;
    }

    private function beginTag($tag_name, array $options = [])
    {
        array_walk($options, function (&$value, $key) {
            $value = $key . '="' . $value . '"';
        });

        return "<$tag_name " . implode(' ', $options) . ">";
    }

    private function endTag($tag_name)
    {
        return "</$tag_name>";
    }
}