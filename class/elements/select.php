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
* Class for Select Elements
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class SelectElement extends XoopsFormSelect {
    var $_question;
    var $canBeRequired = true;
    var $canBeSaved = true;
    var $hasMultipleAnswers = false;
    function SelectElement(&$question) {
        $this->_question = $question;
        $myts =& MyTextSanitizer::getInstance();
        $selected = array();
        $options = array();
        $size = 1;
        $multiple = false;
        $opt_count = 0;
        if ($this->_question->isNew() != true) {
            $ele_value = $this->_question->getVar('q_value');
            $multiple = $ele_value[1];
            $size = $multiple == 1 ? $ele_value[0] : 1;
            while( $i = each($ele_value[2]) ){
                $options[$opt_count] = $myts->stripSlashesGPC($i['key']);
                if( $i['value'] > 0 ){
                    $selected[] = $opt_count;
                }
                $opt_count++;
            }
        }
        $this->hasMultipleAnswers = $multiple;
        $this->XoopsFormSelect(
        $this->_question->getVar('q_caption'),
        "ele_".$this->_question->getVar('qid'),
        $selected,
        $size,
        $multiple);
        $this->addOptionArray($options);
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
        $value = array();
        $value[0] = $ele_value[0]>1 ? intval($ele_value[0]) : 1;
        $value[1] = !empty($ele_value[1]) ? 1 : 0;
        $v2 = array();
        $multi_flag = 1;
        while( $v = each($ele_value[2]) ){
            if( !empty($v['value']) ){
                if( $value[1] == 1 || $multi_flag ){
                    if( isset($_REQUEST['checked'][$v['key']]) && $_REQUEST['checked'][$v['key']] == 1 ){
                        $check = 1;
                        $multi_flag = 0;
                    }else{
                        $check = 0;
                    }
                }else{
                    $check = 0;
                }
                $v2[$v['value']] = $check;
            }
        }
        $value[2] = $v2;
        return $value;
    }

    /*
    * Returns a text string for insertion in storage - this will mostly be, when a form is submitted
    * And the {@link SurveyReply} object is saved
    * @param array $reply
    *
    * @return string
    */
    function getTextValue($reply) {
        $replies = array();
        $options = $this->_question->getVar('q_value');
        foreach (array_keys($options[2]) as $option) {
            $opt_array[] = $option;
        }
        if (is_array($reply) && count($reply) > 0) {
            //$opt_array = array_keys($options[2]);
            foreach ($reply as $optionid) {
                $replies[] = $opt_array[$optionid];
            }
        }
        else {
            $replies[] = $opt_array[$reply];
        }
        return serialize($replies);
    }

    /**
    * Formats answer in the database to suit exportation.
    *
    * @return array;
    */
    function formatAnswerForExport($answer) {
        $answer = unserialize($answer);
        if ($this->hasMultipleAnswers) {
            $ret = array();
            $ret[] = "-";
            $options = $this->_question->getVar('q_value');
            $options = array_keys($options[2]);
            foreach ($options as $option) {
                $ret[] = in_array($option, $answer) ? 1 : 0;
            }
            return $ret;
        }
        else {
            return $answer;
        }
    }

    /**
    * Returns a default value to be saved as the reply, if this field is not filled out in the form submissal
    *
    * @return string
    */
    function getDefaultValue() {
        return array();
    }

    /**
    * Returns the captions for the element - used for exporting
    *
    * @return array
    */
    function getCaptions() {
        if ($this->hasMultipleAnswers) {
            $val = $this->_question->getVar('q_value');
            $ret[] = $this->_question->getVar('q_caption');
            return array_merge($ret, array_keys($val[2]));
        }
        else {
            return $this->_question->getVar('q_caption');
        }
    }

    /**
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $ele_value = $settings['value'];
        $addopt = isset($settings['addopt']) ? $settings['addopt'] : null;
        //$checked = $settings['checked'];
        $ele_size = !empty($ele_value[0]) ? $ele_value[0] : 1;
        $size = new XoopsFormText(_AM_SV_ELE_SIZE, 'ele_value[0]', 3, 2, $ele_size);
        $allow_multi = empty($ele_value[1]) ? 0 : 1;
        $multiple = new XoopsFormRadioYN(_AM_SV_ELE_MULTIPLE, 'ele_value[1]', $allow_multi);

        $options = array();
        $opt_count = 0;
        if( !$this->_question->isNew() ) {
            $myts =& MyTextSanitizer::getInstance();
            $keys = array_keys($ele_value[2]);
            for( $i=0; $i<count($keys); $i++ ){
                $v = $myts->makeTboxData4PreviewInForm($keys[$i]);
                $options[] = $this->addElementOption('ele_value[2]['.$opt_count.']', 'checked['.$opt_count.']', $v, $ele_value[2][$keys[$i]]);
                $opt_count++;
            }
        }
        $addopt = isset($settings['addopt']) ? $settings['addopt'] : 0;
        if (!$addopt && $this->_question->isNew()) {
            $addopt = 2;
        }
        for( $i=0; $i<$addopt; $i++ ){
            $options[] = $this->addElementOption('ele_value[2]['.$opt_count.']', 'checked['.$opt_count.']');
            $opt_count++;
        }
        $add_opt = $this->addOptionsTray();
        $options[] = $add_opt;

        $opt_tray = new XoopsFormElementTray(_AM_SV_ELE_OPT, '<br />');
        $opt_tray->setDescription(_AM_SV_ELE_OPT_DESC);
        for( $i=0; $i<count($options); $i++ ){
            $opt_tray->addElement($options[$i]);
        }
        $return = array($size, $multiple, $opt_tray);
        $return[] = new XoopsFormHidden('ele_type', 'Select');
        return $return;
    }

    /**
    * @param string $id1 name of text element POST variable
    * @param string $id2 name of checkbox/radio POST variable
    * @param string $text text to go in text element
    * @param array $checked
    *
    * @return {@link XoopsFormElementTray}
    */
    function addElementOption($id1, $id2, $text = null, $checked=null){
        $d = new XoopsFormText('', $id1, 40, 255, $text);
        $c = new XoopsFormCheckBox('', $id2, $checked);
        $c->addOption(1, ' ');
        $t = new XoopsFormElementTray('');
        $t->addElement($c);
        $t->addElement($d);
        return $t;
    }

    /**
    * Adds a textfield element to the checkbox administration rendering to input a number of options
    *
    * @return {@link XoopsFormElementTray}
    */
    function addOptionsTray(){
        $t = new XoopsFormText('', 'addopt', 3, 2);
        $l = new XoopsFormLabel('', sprintf(_AM_SV_ELE_ADD_OPT, $t->render()));
        $b = new XoopsFormButton('', 'submit', _AM_SV_ELE_ADD_OPT_SUBMIT, 'submit');
        $r = new XoopsFormElementTray('');
        $r->addElement($l);
        $r->addElement($b);
        return $r;
    }
}
?>