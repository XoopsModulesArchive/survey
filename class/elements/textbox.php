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
* Class for Text field Elements
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class TextboxElement extends XoopsFormText {
    var $_question;
    var $canBeRequired = true;
    var $canBeSaved = true;
    var $hasMultipleAnswers = false;
    function TextboxElement(&$question) {
        $this->_question = $question;
        global $xoopsUser, $xoopsModuleConfig;
        $myts =& MyTextSanitizer::getInstance();
        if (!$this->_question->isNew()) {
            $ele_value = $this->_question->getVar('q_value');
        }
        else {
            $ele_value = array("", $xoopsModuleConfig['t_max'], $xoopsModuleConfig['t_width']);
        }
        $ele_value[0] = stripslashes($ele_value[0]);
        $ele_value[0] = $myts->displayTarea($ele_value[0]);
        if( !is_object($xoopsUser) ){
            $ele_value[0] = preg_replace('/\{NAME\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{name\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{UNAME\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{uname\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{EMAIL\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{email\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{MAIL\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{mail\}/', '', $ele_value[0]);
            $ele_value[0] = preg_replace('/\{DATE\}/', '', $ele_value[0]);
        }
        else {
            $ele_value[0] = preg_replace('/\{NAME\}/', $xoopsUser->getVar('uname', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{name\}/', $xoopsUser->getVar('uname', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{UNAME\}/', $xoopsUser->getVar('uname', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{uname\}/', $xoopsUser->getVar('uname', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{MAIL\}/', $xoopsUser->getVar('email', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{mail\}/', $xoopsUser->getVar('email', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{EMAIL\}/', $xoopsUser->getVar('email', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{email\}/', $xoopsUser->getVar('email', 'e'), $ele_value[0]);
            $ele_value[0] = preg_replace('/\{DATE\}/', date("d-m-Y"), $ele_value[0]);
        }
        $this->XoopsFormText($this->_question->getVar('q_caption'), "ele_".$this->_question->getVar('qid'), $ele_value[2], $ele_value[1], $ele_value[0]);
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
        $value[0] = $myts->addSlashes($ele_value[0]);
        $value[1] = !empty($ele_value[1]) ? intval($ele_value[1]) : $xoopsModuleConfig['t_max'];
        $value[2] = !empty($ele_value[2]) ? intval($ele_value[2]) : $xoopsModuleConfig['t_width'];        
        return $value;
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
    * @param array $options Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($options = array()) {
        global $xoopsModuleConfig;
        $myts =& MyTextSanitizer::getInstance();
        $default_value = isset($options['value'][0]) ? $myts->stripSlashesGPC($options['value'][0]) : "";
        $size = !empty($options['value'][2]) ? intval($options['value'][2]) : $xoopsModuleConfig['t_width'];
        $max = !empty($options['value'][1]) ? intval($options['value'][1]) : $xoopsModuleConfig['t_max'];
        $size = new XoopsFormText(_AM_SV_ELE_SIZE, 'ele_value[2]', 3, 3, $size);
        $max = new XoopsFormText(_AM_SV_ELE_MAX_LENGTH, 'ele_value[1]', 3, 3, $max);
        $default = new XoopsFormText(_AM_SV_ELE_DEFAULT, 'ele_value[0]', 50, 255, $default_value);
        $default->setDescription(_AM_SV_DESCRIPTION);
        
        $return = array($max, $size, $default);
        $return[] = new XoopsFormHidden('ele_type', 'Textbox');
        return $return;
    }
}
?>