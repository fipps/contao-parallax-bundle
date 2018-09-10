<?php
/**
 *  Copyright Information
 *
 * @copyright: 2018 agentur fipps e.K.
 * @author   : Arne Borchert
 * @license  : LGPL 3.0+
 */

namespace Fipps\ParallaxBundle\Listener;


class HooksListener
{

    /**
     * @param \FrontendTemplate $objTemplate
     * @param array             $arrData
     * @param \Module           $objModule
     */
    public function onCompileArticle(\FrontendTemplate &$objTemplate, array $arrData, \Module $objModule)
    {
        if (TL_MODE == 'FE' && $arrData['hasBackgroundImage'] == 1) {

            $file = \FilesModel::findByUuid($arrData['singleSRC']);
            if ($file === null) {
                return;
            }
            $arrData['singleSRC']    = $file->path;
            $templateBackgroundImage = new \FrontendTemplate('ce_backgroundimage');
            \Controller::addImageToTemplate($templateBackgroundImage, $arrData);
            $templateBackgroundImage->isParallax = $arrData['isParallax'];
            $templateBackgroundImage->hAlign = $arrData['hAlign'];

            $arrElements = array();
            $arrElements[] = '<div class="responsive-background-wrapper">';
            $arrElements[] = $templateBackgroundImage->parse();
            $arrElements[] = '<div class="responsive-background-content">';
            $arrElements    = array_merge($arrElements, $objTemplate->elements);
            $arrElements[]  = '</div>';
            $arrElements[]  = '</div>';

            $objTemplate->elements = $arrElements;

            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/fippsparallax/js/parallax.js|async';
            $GLOBALS['TL_CSS'][]        = 'bundles/fippsparallax/css/parallax.css';
        }
    }

    /**
     * @param \Template $objTemplate
     */
    public function onParseTemplate(\Template $objTemplate)
    {
        if (TL_MODE == 'FE' && $objTemplate->getName() == 'ce_backgroundimage') {
            $arrClasses = array('responsive-background-image');
            if ($objTemplate->isParallax == 1) {
                $arrClasses[] = 'parallax-image';

            }
            $objTemplate->class = implode(' ', $arrClasses);
            if (isset($objTemplate->hAlign)) {
                $objTemplate->style = 'background-position:'.$objTemplate->hAlign.' center';
            }
        }
    }
}