<?php

namespace karster\image;


class Wrapper
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function begin()
    {
        return <<<HTML
        <table width="{$this->configuration->width}" cellspacing="0" cellpadding="0" border="0" style="display:block;float:none" class="c0">
            <tbody>
                <tr>
                    <td style="padding:0;" class="c0">

HTML;

    }

    public function end()
    {
        return <<<HTML
                    </td>
                </tr>
            </tbody>
         </table>
HTML;

    }
}