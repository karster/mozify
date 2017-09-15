<?php

namespace karster\image;


class MosaicCell
{
    private $color;

    private $window;

    private $colSpan;

    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    public function setSearchWindow($search_window)
    {
        $this->window = $search_window;

        return $this;
    }

    public function setColSpan($col_span)
    {
        $this->colspan = $col_span;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getWidth()
    {
        return $this->window * $this->colSpan;
    }

    public function getHeight()
    {
        return $this->window;
    }
}