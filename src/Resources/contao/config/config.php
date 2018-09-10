<?php
/**
 *  Copyright Information
 *
 * @copyright: 2018 agentur fipps e.K.
 * @author   : Arne Borchert
 * @license  : LGPL 3.0+
 */


// Hooks
$GLOBALS['TL_HOOKS']['compileArticle'][]        = array('Fipps\ParallaxBundle\Listener\HooksListener', 'onCompileArticle');
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = array('Fipps\ParallaxBundle\Listener\HooksListener', 'onParseTemplate');
