<?php
/**
 *  Copyright Information
 *
 * @copyright: 2018 agentur fipps e.K.
 * @author   : Arne Borchert
 * @license  : LGPL 3.0+
 */

namespace Fipps\ParallaxBundle\Helper;


class CheckContentElementHelper
{
    /**
     * checks if there already exists a content element of type 'ce_backgroungimage' and
     * removes 'ce_backgroundimage' from $GLOBALS['TL_CTE']['media'] if it is
     *
     * @param \DataContainer $dataContainer
     */
    public function checkForCTEBackgroundImage(\DataContainer $dataContainer)
    {
        $get            = \Input::get('act');
        $contentElement = \ContentModel::findById($dataContainer->id);
        // if show content element in article or open content element, that is already a background image
        if ($get === null || $contentElement->type == 'backgroundimage') {
            return;
        }

        $numberOfBackgroundImages = $this->getNumberOfBackgroundImagesforArticelId($contentElement->pid);
        if ($numberOfBackgroundImages > 0) {
            unset($GLOBALS['TL_CTE']['media']['backgroundimage']);
        }
    }

    public function moveBackgroundImageToTop(\DataContainer $dataContainer)
    {
        $get            = \Input::get('act');
        $contentElement = \ContentModel::findById($dataContainer->id);
        if ($contentElement->type == 'backgroundimage') {
            $contentElement->sorting = 0;
            $contentElement->save();
        }
    }


    /**
     * @param int $id
     * @return int
     */
    private function getNumberOfBackgroundImagesforArticelId(int $id)
    {
        //todo: move to services
        $arrOptions = array(
            'column' => 'type',
            'value'  => 'backgroundimage',
        );
        $collection = \ContentModel::findByPid($id, $arrOptions);

        if ($collection === null) {
            return 0;
        }

        return $collection->count();
    }
}