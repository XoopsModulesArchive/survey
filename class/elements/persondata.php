<?php
###############################################################################
##             Survey - Information submitting module for XOOPS              ##
##                    Developed 2004 Jan Pedersen (aka Mithrandir)           ##
##                       <http://www.web-udvikling.dk>                       ##
##                 Inspired by Formulaire, developed by NS Tai (aka tuff)    ##
###############################################################################
##                    XOOPS - PHP Content Management System                  ##
##                       Copyright (c) 2000 XOOPS.org                        ##
##                          <http://www.xoops.org/>                          ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
/**
* Class for Form Elements to specify the name, email, address etc. of the submitter
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class PersondataElement extends XoopsFormElementTray {
    var $_question;
    var $canBeRequired = false;
    var $canBeSaved = true;
    var $hasMultipleAnswers = true;

    function PersondataElement(&$question) {
        include_once(XOOPS_ROOT_PATH."/modules/survey/language/".$GLOBALS['xoopsConfig']['language']."/main.php");
        $this->_question = $question;
        if ($question->isNew() != true) {
            $ele_value = $this->_question->getVar('q_value');
        }
        else {
            $ele_value = array('show_name' => 0,
            'show_address' => 0,
            'show_position' => 0,
            'show_company' => 0,
            'show_postal' => 0,
            'show_city' => 0);
        }
        $this->XoopsFormElementTray($this->_question->getVar('q_caption'), "\n<br />", "ele_".$question->getVar('qid'));

        if ($ele_value['show_name']) {
            $this->addElement(new XoopsFormText(_MA_SV_NAME, 'submitter_name', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max']), true);
        }
        if ($ele_value['show_position']) {
            $this->addElement(new XoopsFormText(_MA_SV_POSITION, 'submitter_position', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max']));
        }
        if ($ele_value['show_company']) {
            $this->addElement(new XoopsFormText(_MA_SV_COMPANY, 'submitter_company', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max']));
        }
        if ($ele_value['show_address']) {
            $this->addElement(new XoopsFormTextArea(_MA_SV_ADDRESS, 'submitter_address', "", $GLOBALS['xoopsModuleConfig']['ta_rows'], $GLOBALS['xoopsModuleConfig']['ta_cols']));
        }
        if ($ele_value['show_postal']) {
            $this->addElement(new XoopsFormText(_MA_SV_POSTAL, 'submitter_postal', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max']));
        }
        if ($ele_value['show_city']) {
            $this->addElement(new XoopsFormText(_MA_SV_CITY, 'submitter_city', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max']));
        }
        $this->addElement(new XoopsFormText(_MA_SV_EMAIL, 'submitter_email', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max']), true);
    }

    /*
    * Manipulates a value following submissal of create element form
    * So it is ready for being set as the question's value variable
    * @param array $value
    *
    * @return array
    */
    function getValueForSave($ele_value) {
        return $ele_value;
    }

    /*
    * Returns a text string for insertion a reply into the database
    * Note that the $reply parameter is NOT used in this element
    * @param string $reply
    *
    * @return string
    */
    function getTextValue($reply) {
        $ret['name'] = isset($_REQUEST['submitter_name']) ? $_REQUEST['submitter_name'] : "";
        $ret['posi'] = isset($_REQUEST['submitter_position']) ? $_REQUEST['submitter_position'] : "";
        $ret['comp'] = isset($_REQUEST['submitter_company']) ? $_REQUEST['submitter_company'] : "";
        $ret['addr'] = isset($_REQUEST['submitter_address']) ? $_REQUEST['submitter_address'] : "";
        $ret['post'] = isset($_REQUEST['submitter_postal']) ? $_REQUEST['submitter_postal'] : "";
        $ret['city'] = isset($_REQUEST['submitter_city']) ? $_REQUEST['submitter_city'] : "";
        return serialize($ret);
    }

    /**
    * Returns a default value to be saved as the reply, if this field is not filled out in the form submissal
    *
    * @return string
    */
    function getDefaultValue() {
        return "";
    }

    /**
    * Returns the captions for the element - used for exporting
    *
    * @return array
    */
    function getCaptions() {
        include_once(XOOPS_ROOT_PATH."/modules/survey/language/".$GLOBALS['xoopsConfig']['language']."/main.php");
        $ret = array();
        $ret[] = _MA_SV_NAME;
        $ret[] = _MA_SV_POSITION;
        $ret[] = _MA_SV_COMPANY;
        $ret[] = _MA_SV_ADDRESS;
        $ret[] = _MA_SV_POSTAL;
        $ret[] = _MA_SV_CITY;
        return $ret;
    }

    /**
    * Formats answer in the database to suit exportation.
    *
    * @return array;
    */
    function formatAnswerForExport($answer) {
        $ret = unserialize($answer);
        return $ret;
    }

    /**
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $settings = $settings['value'];
        $show_name = isset($settings['show_name']) ? $settings['show_name'] : 0;
        $show_position = isset($settings['show_position']) ? $settings['show_position'] : 0;
        $show_company = isset($settings['show_company']) ? $settings['show_company'] : 0;
        $show_address = isset($settings['show_address']) ? $settings['show_address'] : 0;
        $show_postal = isset($settings['show_postal']) ? $settings['show_postal'] : 0;
        $show_city = isset($settings['show_city']) ? $settings['show_city'] : 0;

        $return = array();
        $return[] = new XoopsFormRadioYN(_AM_SV_SHOW_NAME, 'ele_value[show_name]', $show_name);
        $return[] = new XoopsFormRadioYN(_AM_SV_SHOW_POSITION, 'ele_value[show_position]', $show_position);
        $return[] = new XoopsFormRadioYN(_AM_SV_SHOW_COMPANY, 'ele_value[show_company]', $show_company);
        $return[] = new XoopsFormRadioYN(_AM_SV_SHOW_ADDRESS, 'ele_value[show_address]', $show_address);
        $return[] = new XoopsFormRadioYN(_AM_SV_SHOW_POSTAL, 'ele_value[show_postal]', $show_postal);
        $return[] = new XoopsFormRadioYN(_AM_SV_SHOW_CITY, 'ele_value[show_city]', $show_city);
        $return[] = new XoopsFormHidden('ele_type', 'Persondata');
        return $return;
    }
}
?>