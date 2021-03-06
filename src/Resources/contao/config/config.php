<?php
/**
 *  Copyright Information
 *
 * @copyright: 2018 agentur fipps e.K.
 * @author   : Arne Borchert
 * @license  : LGPL 3.0+
 */


// Hooks
$GLOBALS['TL_HOOKS']['compileArticle'][] = array(\Fipps\ParallaxBundle\Listener\HooksListener::class, 'onCompileArticle');
$GLOBALS['TL_HOOKS']['parseTemplate'][]  = array(\Fipps\ParallaxBundle\Listener\HooksListener::class, 'onParseTemplate');
$GLOBALS['TL_HOOKS']['getPageLayout'][]  = array(\Fipps\ParallaxBundle\Listener\HooksListener::class, 'onGetPageLayout');
