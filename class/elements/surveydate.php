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
 * Class for Date Elements
 * @author Jan Pedersen
 * @copyright copyright (c) 2004 IDG.dk
 * @package Survey
 * @subpackage Elements
 */
class SurveyDateElement extends XoopsFormTextDateSelect {
    var $_question;
    var $canBeRequired = true;
    var $canBeSaved = true;
    var $hasMultipleAnswers = false;
    function SurveyDateElement(&$question) {
        $this->_question = $question;
        if ($this->_question->isNew() != true) {
            $value = $this->_question->getVar('q_value');
            //$value = $value[0];
        }
        else {
            $value = 0;
        }
        
        $this->XoopsFormTextDateSelect(
        $this->_question->getVar('q_caption'),
        "ele_".$this->_question->getVar('qid'),
        15,
        $value
        );
    }

    /**
    * Manipulates a value following submissal of create element form
    * So it is ready for being set as the question's value variable
    * @param array $value
    *
    * @return array
    */
    function getValueForSave($ele_value) {
        return strtotime($ele_value[0]);
    }

    /**
    * Returns a text string for insertion in export facility such as CSV file
    * @param string $reply
    *
    * @return string
    */
    function getTextValue($reply) {
        return strtotime($reply);
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
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $value = isset($settings['value']) ? $settings['value'] : time();
        $return[] = new XoopsFormTextDateSelect(_AM_SV_ELE_DEFAULT, 'ele_value[0]', 15, $value);
        $return[] = new XoopsFormHidden('ele_type', 'SurveyDate');
        return $return;
    }
    
    /**
    * Formats answer in the database to suit exportation.
    *
    * @return array;
    */
    function formatAnswerForExport($answer) {
        return array(formatTimestamp($answer));
    }
    
    /**
    * Returns the captions for the element - used for exporting
    *
    * @return string
    */
    function getCaptions() {
        return $this->_question->getVar('q_caption');
    }

}
?>