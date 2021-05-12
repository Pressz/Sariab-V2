<?php
/**
 * 
 * Based on https://github.com/stil/gd-text/issues/53
 * 
 */

namespace GDText;

class BoxPatched extends Box
{
    public function __construct(&$image)
    {
        parent::__construct($image);
    }

    /**
     * @var bool
     */
    protected $reverse_text_lines_order = false;

    /**
     * @param bool $reverse_text_lines_order To Reverse Lines Order
     */
    public function setReverseTextLinesOrder($reverse_text_lines_order)
    {
        $this->reverse_text_lines_order = $reverse_text_lines_order;
    }

    /**
     * Splits overflowing text into array of strings.
     * @param string $text
     * @return string[]
     */
    protected function wrapTextWithOverflow($text)
    {
        $lines = parent::wrapTextWithOverflow($text);

        if ($this->reverse_text_lines_order == true) {
            $lines = array_reverse($lines);
        }

        return $lines;
    }
}
?>