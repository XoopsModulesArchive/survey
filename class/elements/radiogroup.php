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
* Class for Radio Elements
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class RadioGroupElement extends XoopsFormElementTray  {
    var $_question;
    var $canBeRequired = false;
    var $canBeSaved = true;
    var $hasMultipleAnswers = true;
    var $options = array();
    var $questions = array();
    function RadioGroupElement(&$question) {
        $this->_question = $question;
        if (!$question->isNew()) {
            $val = $question->getVar('q_value');
            $this->options = $val['options'];
            $this->questions = $val['questions'];
        }
        $this->XoopsFormElementTray($this->_question->getVar('q_caption'), "\n<br />", "ele_".$question->getVar('qid'));
    }

    /*
    * Manipulates a value following submissal of create element form
    * So it is ready for being set as the question's value variable
    * @param array $value
    *
    * @return array
    */
    function getValueForSave($ele_value) {
        $ret['checked'] = isset($_REQUEST['checked']) ? intval($_REQUEST['checked']) : null;
        foreach ($ele_value['options'] as $key => $option) {
            if ($option != "") {
                $ret['options'][$key]['value'] = $option;
            }
        }
        foreach ($ele_value['questions'] as $key => $question) {
            if ($question != "") {
                $ret['questions'] = $ele_value['questions'];
            }
        }
        $ret['optcount'] = $ele_value['optcount'];
        $ret['questcount'] = $ele_value['questcount'];
        return $ret;
    }

    /*
    * Returns a text string for insertion in database or export facility such as CSV file
    * @param array $reply
    *
    * @return string
    */
    function getTextValue($replies) {
        if ($replies == null) {
            return "";
        }
        $ret = array();
        $val = $this->_question->getVar('q_value');
        foreach ( $val['questions'] as $key => $name) {
            $ret[$key] = $val['options'][$replies[$key]];
        }
        return serialize($ret);
    }

    /**
    * Formats answer in the database to suit exportation.
    *
    * @return array;
    */
    function formatAnswerForExport($answer) {
        $answer = unserialize($answer);
        $val = $this->_question->getVar('q_value');
        foreach ($answer as $thisanswer) {
            $ret[] = "-";
            foreach ($val['options'] as $key => $option) {
                $ret[] = $option['value'] == $thisanswer['value'] ? 1 : 0;
            }
        }
        return $ret;
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
        $ret = array();
        $val = $this->_question->getVar('q_value');
        foreach ($val['questions'] as $k) {
            $ret[] = $k;
            foreach ($val['options'] as $i) {
                $ret[] = $i['value'];
            }
        }
        return $ret;
    }

    /**
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $return = array();

        $ele_options = isset($settings['value']['options']) ? $settings['value']['options'] : array();
        $ele_questions = isset($settings['value']['questions']) ? $settings['value']['questions'] : array();
        $optioncount = isset($settings['value']['optcount']) ? $settings['value']['optcount'] : 5;
        $questioncount = isset($settings['value']['questcount']) ? $settings['value']['questcount'] : 2;

        $return[] = new XoopsFormText(_AM_SV_OPTION_COUNT, 'ele_value[optcount]', 30, 20, $optioncount);
        $return[] = new XoopsFormText(_AM_SV_QUESTION_COUNT, 'ele_value[questcount]', 30, 20, $questioncount);

        $r = isset($settings['value']['checked']) ? $settings['value']['checked'] : null;
        //$myts =& MyTextSanitizer::getInstance();
        //set options
        $options = array();
        $opt_count = 0;
        for( $i=0; $i < $optioncount; $i++ ){
            $v = isset($ele_options[$i]) ? $ele_options[$i]['value'] : "";
            $options[] = $this->addElementOption('ele_value[options]['.$opt_count.']', $opt_count, $v, $r);
            $opt_count++;
        }
        $opt_tray = new XoopsFormElementTray(_AM_SV_ELE_OPT, '<br />');
        $opt_tray->setDescription(_AM_SV_ELE_OPT_DESC);
        for( $i=0; $i<count($options); $i++ ){
            $opt_tray->addElement($options[$i]);
        }
        $return[] = $opt_tray;

        //set questions
        $questions = array();
        $ques_count = 0;
        for( $i=0; $i < $questioncount; $i++ ){
            $v = isset($ele_questions[$i]) ? $ele_questions[$i] : "";
            $questions[] = new XoopsFormText('', 'ele_value[questions]['.$ques_count.']', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max'], $v);
            $ques_count++;
        }
        $ques_tray = new XoopsFormElementTray(_AM_SV_ELE_QUES, '<br />');
        $ques_tray->setDescription(_AM_SV_ELE_QUES_DESC);
        for( $i=0; $i<count($questions); $i++ ){
            $ques_tray->addElement($questions[$i]);
        }
        $return[] = $ques_tray;
        $return[] = new XoopsFormHidden('ele_type', 'RadioGroup');
        return $return;
    }

    /**
    * @param string $id1 name of text element POST variable
    * @param string $id2 name of checkbox/radio POST variable
    * @param string $text text to go in text element
    * @param int $checked
    *
    * @return {@link XoopsFormElementTray}
    */
    function addElementOption($id1, $id2, $text, $checked=null){
        $d = new XoopsFormText('', $id1, $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max'], $text);

        $c = new XoopsFormRadio('', 'checked', $checked);
        $c->addOption($id2, ' ');

        $t = new XoopsFormElementTray('');
        $t->addElement($c);
        $t->addElement($d);
        return $t;
    }

    /**
    * Prepare HTML for output
    *
    * @return	string	HTML
    */
    function render(){
        $settings = $this->_question->getVar('q_value');
        $options = $this->options;
        $questions = $this->questions;
        $ret = "<table><tr><th></th>";
        foreach ( $options as $option ) {
            $ret .= "<th>".$option['value']."</th>";
        }
        $ret .= "</tr>";
        foreach ( $questions as $key => $name) {
            $ret .= "<tr><td>".$name."</td>";
            foreach ($options as $value => $option) {
                $ret .= "<td><input type='radio' name='ele_".$this->_question->getVar('qid')."[".$key."]' value='".$value."'";
                //$selected = $this->getValue();
                if ( $settings['checked'] == $value ) {
                    $ret .= " checked='checked'";
                }
                $ret .= " /></td>";
            }
            $ret .= "</tr>";
        }
        $ret .= "</table>";
        return $ret;
    }
}
?>