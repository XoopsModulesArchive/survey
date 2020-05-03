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
 * Class for TextareaElements
 * @author Jan Pedersen
 * @copyright copyright (c) 2004 IDG.dk
 * @package Survey
 * @subpackage Elements
 */
class TextareaElement extends XoopsFormTextArea {
    var $_question;
    var $canBeRequired = true;
    var $canBeSaved = true;
    var $hasMultipleAnswers = false;
    
    function TextareaElement(&$question) {
        global $xoopsModuleConfig;
        $this->_question = $question;
        $myts =& MyTextSanitizer::getInstance();
        $ele_value = !$this->_question->isNew() ? $this->_question->getVar('q_value') : array("", $xoopsModuleConfig['ta_rows'], $xoopsModuleConfig['ta_cols']) ;
        $ele_value[0] = stripslashes($ele_value[0]);
        $ele_value[0] = $myts->htmlSpecialChars($ele_value[0]);

        $this->XoopsFormTextArea(
        $this->_question->getVar('q_caption'),
        "ele_".$this->_question->getVar('qid'),
        $ele_value[0],	//	default value
        $ele_value[1],	//	rows
        $ele_value[2]	//	cols
        );
    }

    /*
    * Manipulates a value following submissal of create element form
    * So it is ready for being set as the question's value variable
    * @param array $value
    *
    * @return array
    */
    function getValueForSave($ele_value) {
        global $xoopsModuleConfig;
        $myts =& MyTextSanitizer::getInstance();
        $value = array();
        $value[] = $myts->addSlashes($ele_value[0]);
        if( intval($ele_value[1]) != 0 ){
            $value[] = intval($ele_value[1]);
        }else{
            $value[] = $xoopsModuleConfig['ta_rows'];
        }
        if( intval($ele_value[2]) != 0 ){
            $value[] = intval($ele_value[2]);
        }else{
            $value[] = $xoopsModuleConfig['ta_cols'];
        }
        return $value;
    }

    /*
    * Returns a text string for insertion in export facility such as CSV file
    * @param string $reply
    *
    * @return string
    */
    function getTextValue($reply) {
        return $reply;
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
    * Formats answer in the database to suit exportation.
    *
    * @return array;
    */
    function formatAnswerForExport($answer) {
        return array($answer);
    }
    
    /**
    * Returns the captions for the element - used for exporting
    *
    * @return string
    */
    function getCaptions() {
        return $this->_question->getVar('q_caption');
    }
    
    /**
    * @param array $options Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($options = array()) {
        global $xoopsModuleConfig;
        $myts =& MyTextSanitizer::getInstance();
        $default_value =  isset($options['value'][0]) ? $myts->stripSlashesGPC($options['value'][0]) : "";
        $rows = !empty($options['value'][0]) ? $options['value'][1] : $xoopsModuleConfig['ta_rows'];
        $cols = !empty($options['value'][0]) ? $options['value'][2] : $xoopsModuleConfig['ta_cols'];
        $rows = new XoopsFormText(_AM_SV_ELE_ROWS, 'ele_value[1]', 3, 3, $rows);
        $cols = new XoopsFormText(_AM_SV_ELE_COLS, 'ele_value[2]', 3, 3, $cols);
        $default = new XoopsFormTextArea(_AM_SV_ELE_DEFAULT, 'ele_value[0]', $default_value, 5, 35);

        $return = array($rows, $cols, $default);
        $return[] = new XoopsFormHidden('ele_type', 'Textarea');
        return $return;
    }
}
?>