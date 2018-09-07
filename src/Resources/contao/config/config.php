<?php
/**
 *  Copyright Information
 *
 * @copyright: 2018 agentur fipps e.K.
 * @author   : Arne Borchert
 * @license  : LGPL 3.0+
 */


// Hooks
$GLOBALS['TL_HOOKS']['parseArticles'][]         = array('Fipps\ParallaxBundle\Listener\HooksListener', 'parseArticles');
$GLOBALS['TL_HOOKS']['compileArticle'][]        = array('Fipps\ParallaxBundle\Listener\HooksListener', 'compileArticle');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('Fipps\ParallaxBundle\Listener\HooksListener', 'parseFrontendTemplate');
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = array('Fipps\ParallaxBundle\Listener\HooksListener', 'parseTemplate');
