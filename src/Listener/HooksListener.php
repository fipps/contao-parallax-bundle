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
     * Add all necessary JS and CSS
     *
     * @param \PageModel   $objPage
     * @param \LayoutModel $objLayout
     * @param \PageRegular $objPageRegular
     */
    public function onGetPageLayout(\PageModel $objPage, \LayoutModel $objLayout, \PageRegular $objPageRegular)
    {
        if (\Config::get('debugMode')) {
            if (!$objLayout->addJQuery) {
                $GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/js/jquery.js|static';
            }
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/fippsparallax/js/parallax.min.js|async';
            $GLOBALS['TL_CSS'][]        = 'bundles/fippsparallax/css/parallax.min.css';
        } else {
            if (!$objLayout->addJQuery) {
                $GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/js/jquery.min.js|static';
            }
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/fippsparallax/js/parallax.js|async';
            $GLOBALS['TL_CSS'][]        = 'bundles/fippsparallax/css/parallax.css';
        }
    }

    /**
     * @param \FrontendTemplate $objTemplate
     * @param array             $arrData
     */
    public function onCompileArticle(\FrontendTemplate &$objTemplate, array $arrData)
    {
        if (TL_MODE == 'FE' && $arrData['hasBackgroundImage'] == 1) {
            $file = \FilesModel::findByUuid($arrData['singleSRC']);
            if ($file === null) {
                return;
            }
            $arrData['singleSRC']    = $file->path;
            $templateBackgroundImage = new \FrontendTemplate('ce_backgroundimage');
            \Controller::addImageToTemplate($templateBackgroundImage, $arrData);
            $templateBackgroundImage->hAlign = ($arrData['hAlign'] != '') ?: 'center';

            $elements = $objTemplate->elements;
            array_unshift($elements, $templateBackgroundImage->parse());
            $objTemplate->elements = $elements;
        }
    }

    /**
     * @param \Template $objTemplate
     */
    public function onParseTemplate(\Template $objTemplate)
    {
        if (TL_MODE == 'FE' && $objTemplate->type == 'article' && $objTemplate->hasBackgroundImage == 1) {
            $arrClasses = array('has-responsive-background-image');
            if ($objTemplate->isParallax == 1) {
                $arrClasses[] = 'parallax';
            }
            $objTemplate->class = implode(' ', $arrClasses);
        }
    }
}