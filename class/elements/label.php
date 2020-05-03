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
* Class for Label Elements
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class LabelElement extends XoopsFormLabel {
    var $_question;
    var $canBeRequired = false;
    var $canBeSaved = false;
    var $hasMultipleAnswers = false;
    function LabelElement(&$question) {
        $this->_question = $question;
        $myts =& MyTextSanitizer::getInstance();
        $value = $this->_question->isNew() ? array("") : $this->_question->getVar('q_value');
        $this->XoopsFormLabel($this->_question->getVar('q_caption'), $myts->displayTarea($myts->stripSlashesGPC($value[0]), 1));
    }
    /*
    * Manipulates a value following submissal of create element form
    * So it is ready for being set as the {@link SurveyQuestion} question's value variable
    * @param array $value
    *
    * @return array
    */
    function getValueForSave($ele_value) {
        $myts =& MyTextSanitizer::getInstance();
        $ele_value[0] = $myts->addSlashes($ele_value[0]);
        return $ele_value;
    }

    /*
    * Returns a text string for insertion in storage - this will mostly be, when a form is submitted
    * And the {@link SurveyReply} object is saved
    * @param string $reply
    *
    * @return string
    */
    function getTextValue($reply) {
        return $reply;
    }

    /**
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $myts =& MyTextSanitizer::getInstance();
        $value = !empty($settings['value'][0]) ? $myts->stripSlashesGPC($settings['value'][0]) : "";
        $default = new XoopsFormDhtmlTextArea(_AM_SV_ELE_DEFAULT, 'ele_value[0]', $value, 15, 35);
        $ele_type = new XoopsFormHidden('ele_type', 'Label');
        return array($default, $ele_type);
    }
}
?>