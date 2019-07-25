<?php
/**
 *  Copyright Information
 *
 * @copyright: 2018 agentur fipps e.K.
 * @author   : Arne Borchert
 * @license  : LGPL 3.0+
 */

$this->loadLanguageFile('tl_content');
$DCA = &$GLOBALS['TL_DCA']['tl_article'];

// Change pallette
$newLegend = '{backgroundimage_legend:hide},hasBackgroundImage;';

$DCA['palettes']['default']        = str_replace('{syndication_legend', $newLegend.'{syndication_legend', $DCA['palettes']['default']);
$DCA['palettes']['__selector__'][] = 'hasBackgroundImage';
$DCA['palettes']['__selector__'][] = 'isParallax';

$DCA['subpalettes']['hasBackgroundImage'] = 'singleSRC,size,hAlign,vAlign,isParallax, deactivateForMobile';

// New fields
$newFields = array(
    'hasBackgroundImage'  => array(
        'label'     => &$GLOBALS['TL_LANG']['tl_article']['hasBackgroundImage'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => array(
            'submitOnChange' => true,
        ),
        'sql'       => "char(1) NOT NULL default ''",
    ),
    'singleSRC'           => array(
        'label'         => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
        'exclude'       => true,
        'inputType'     => 'fileTree',
        'eval'          => array(
            'filesOnly' => true,
            'fieldType' => 'radio',
            'tl_class'  => 'clr',
        ),
        'load_callback' => array(
            array('Fipps\ParallaxBundle\DataContainer\FileHelper', 'setSingleSrcFlags'),
        ),
        'sql'           => "binary(16) NULL",
    ),
    'size'                => array(
        'label'            => &$GLOBALS['TL_LANG']['tl_content']['size'],
        'exclude'          => true,
        'inputType'        => 'imageSize',
        'reference'        => &$GLOBALS['TL_LANG']['MSC'],
        'eval'             => array(
            'rgxp'               => 'natural',
            'includeBlankOption' => true,
            'nospace'            => true,
            'helpwizard'         => true,
            'tl_class'           => 'clr w50',
        ),
        'options_callback' => function () {
            return System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(BackendUser::getInstance());
        },
        'sql'              => "varchar(64) NOT NULL default ''",
    ),
    'hAlign'              => array(
        'label'     => &$GLOBALS['TL_LANG']['tl_article']['hAlign'],
        'exclude'   => true,
        'inputType' => 'text',
        'default'   => 50,
        'eval'      => array(
            'tl_class' => 'clr w50',
            'maxval'   => 200,
            'minval'   => -100,
            'rgxp'     => 'digit',
        ),
        'sql'       => "int(4) NOT NULL default '50'",

    ),
    'vAlign'              => array(
        'label'     => &$GLOBALS['TL_LANG']['tl_article']['vAlign'],
        'exclude'   => true,
        'inputType' => 'text',
        'default'   => 50,
        'eval'      => array(
            'tl_class' => 'clr w50',
            'maxval'   => 200,
            'minval'   => -100,
            'rgxp'     => 'digit',
        ),
        'sql'       => "int(4) NOT NULL default '50'",

    ),
    'isParallax'          => array(
        'label'     => &$GLOBALS['TL_LANG']['tl_article']['isParallax'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => array(
            'tl_class'       => 'clr w50 m12',
        ),
        'sql'       => "char(1) NOT NULL default ''",
    ),
    'deactivateForMobile' => array(
        'label'     => &$GLOBALS['TL_LANG']['tl_article']['deactivateForMobile'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => array(
            'tl_class' => 'w50 m12',
        ),
        'sql'       => "char(1) NOT NULL default ''",
    ),

);

$DCA['fields'] = array_merge($DCA['fields'], $newFields);