<?php
/**
 *  Copyright Information
 *  @copyright: 2018 agentur fipps e.K.
 *  @author   : Arne Borchert
 *  @license  : LGPL 3.0+
 */

namespace Fipps\ParallaxBundle\Listener;


use Contao\CoreBundle\Framework\ContaoFramework;

class InsertTagsListener
{

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var array
     */
    private $supportedTags = array();

    /**
     * Constructor.
     *
     * @param ContaoFramework $framework
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }
}