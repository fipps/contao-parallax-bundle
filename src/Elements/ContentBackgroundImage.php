<?php
/**
 *  Copyright Information
 *  @copyright: 2018 agentur fipps e.K.
 *  @author   : Arne Borchert
 *  @license  : LGPL 3.0+
 */

namespace Fipps\ParallaxBundle\Elements;


class ContentBackgroundImage extends \ContentImage
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_backgroundimage';


    public function compile()
    {
        parent::compile();
    }


}