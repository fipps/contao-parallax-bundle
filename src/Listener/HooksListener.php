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
     * @param string $strBuffer
     * @param string $strTemplate
     * @return string
     */
    public function parseFrontendTemplate(string $strBuffer, string $strTemplate)
    {
        return $strBuffer;
    }

    /**
     * @param \FrontendTemplate $objTemplate
     * @param array             $arrData
     * @param \Module           $objModule
     */
    public function compileArticle(\FrontendTemplate &$objTemplate, array $arrData, \Module $objModule)
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
     * @param \FrontendTemplate $objTemplate
     * @param array             $arrRow
     * @param \Module           $objModule
     */
    public function parseArticles(\FrontendTemplate $objTemplate, array $arrRow, \Module $objModule)
    {
        return;
    }

    /**
     * @param \Template $objTemplate
     */
    public function parseTemplate(\Template $objTemplate)
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