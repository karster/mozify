<?php

namespace karster\image;


class MsoHack
{
    /**
     * @var string
     */
    private $outLookConditionalComment = 'mso';

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
        <!--[if {$this->outLookConditionalComment} ]>
        <style>.c0{display:none !important}</style>
        <table cellpadding="0" cellspacing="0" style="display:block;float:none;" align="">
            <tr>
                <td>
                    <img src="{$this->configuration->imageSrc}" alt="{$this->configuration->altText}" style="display:block;" moz="3" valid="true" height="{$this->configuration->height}" width="{$this->configuration->width}">
                </td>
            </tr>
        </table>
        <style type="text/css">/*<![endif]-->

HTML;

    }

    public function end()
    {
        return <<<HTML
        <!--[if {$this->outLookConditionalComment}]>*/</style><![endif]-->
HTML;

    }
}