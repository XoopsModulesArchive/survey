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
* Class for Checkbox Elements
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class CheckboxElement extends XoopsFormCheckBox {
    var $_question;
    var $canBeRequired = false;
    var $canBeSaved = true;
    var $hasMultipleAnswers = true;
    var $_addOther = 0;
    function CheckboxElement(&$question) {
        $this->_question = $question;
        $myts =& MyTextSanitizer::getInstance();
        if ($question->isNew() != true) {
            $ele_value = $this->_question->getVar('q_value');
        }
        else {
            $ele_value = array('options' => array(), 'other' => 0);
        }
        $this->_addOther = $ele_value['other'];
        $selected = array();
        $options = array();
        $opt_count = 1;
        if (is_array($ele_value['options']) && count($ele_value['options']) > 0) {
            while( $i = each($ele_value['options']) ){
                $options[$opt_count] = $myts->stripSlashesGPC($i['key']);
                if( $i['value'] > 0 ){
                    $selected[] = $opt_count;
                }
                $opt_count++;
            }
        }
        $this->XoopsFormCheckBox(
        $this->_question->getVar('q_caption'),
        "ele_".$this->_question->getVar('qid'),
        $selected);
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
        $value = array();
        while( $v = each($ele_value['opt']) ){
            if( !empty($v['value']) ){
                $value['options'][$v['value']] = isset($_REQUEST['checked'][$v['key']]) ? 1 : 0;
            }
        }
        $value['other'] = isset($ele_value['other']) && $ele_value['other'] == 1 ? 1 : 0;
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
        $options = $this->_question->getVar('q_value');
        $options = $options['options'];
        $replies = array();
        if (is_array($reply) && count($reply) > 0) {
            $opt_array = array_keys($options);
            foreach ($reply as $optionid) {
                $replies['options'][$optionid] = $opt_array[($optionid - 1)];
            }
        }
        if (isset($_REQUEST["ele_".$this->_question->getVar('qid')."_other"]) && $_REQUEST["ele_".$this->_question->getVar('qid')."_other"] != "") {
            $count = count($options);
            $replies['other'] = $_REQUEST["ele_".$this->_question->getVar('qid')."_other"];
        }
        return serialize($replies);
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
    * Formats answer in the database to suit exportation.
    *
    * @return array;
    */
    function formatAnswerForExport($answer) {
        $ret = array("-");
        $answer = unserialize($answer);
        $caps = $this->_question->getVar('q_value');
        $caps = array_keys($caps['options']);
        foreach ($caps as $i) {
            $ret[] = isset($answer['options']) && in_array($i, $answer['options']) ? 1 : 0;
        }
        if ($this->_addOther) {
            $answer['other'] = isset($answer['other']) ? $answer['other'] : " ";
            $ret[] = $answer['other'];
        }
        return $ret;
    }

    /**
    * Returns the captions for the element - used for exporting
    *
    * @return array
    */
    function getCaptions() {
        $val = $this->_question->getVar('q_value');
        $ret[] = $this->_question->getVar('q_caption');
        $ret = array_merge($ret, array_keys($val['options']));
        if ($this->_addOther) {
            include_once(XOOPS_ROOT_PATH."/modules/survey/language/".$GLOBALS['xoopsConfig']['language']."/main.php");
            array_push($ret, _MA_SV_OTHER);
        }
        return $ret;
    }

    /**
    * Get the element(s) for use when adding this element to a form
    *
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $ele_value = isset($settings['value']['options']) ? $settings['value']['options'] : array();
        $other = isset($settings['value']['other']) ? $settings['value']['other'] : 0;
        $myts =& MyTextSanitizer::getInstance();
        $options = array();
        $opt_count = 0;
        if (!$this->_question->isNew()) {
            // Keys of array is the option - the value is 0 or 1, depending on checked status
            $keys = array_keys($ele_value);
            for( $i=0; $i<count($keys); $i++ ){
                $v = $myts->makeTboxData4PreviewInForm($keys[$i]);
                $options[] = $this->addElementOption('ele_value[opt]['.$opt_count.']', 'checked['.$opt_count.']', $v, $ele_value[$keys[$i]]);
                $opt_count++;
            }
        }

        $addopt = isset($settings['addopt']) ? $settings['addopt'] : 0;
        if (!$addopt && $this->_question->isNew()) {
            $addopt = 2;
        }
        for( $i=0; $i<$addopt; $i++ ){
            $options[] = $this->addElementOption('ele_value[opt]['.$opt_count.']', 'checked['.$opt_count.']');
            $opt_count++;
        }

        $options[] = $this->addOptionsTray();
        $opt_tray = new XoopsFormElementTray(_AM_SV_ELE_OPT, '<br />');
        $opt_tray->setDescription(_AM_SV_ELE_OPT_DESC);
        for( $i=0; $i<count($options); $i++ ){
            $opt_tray->addElement($options[$i]);
        }
        $return[] = $opt_tray;
        $return[] = new XoopsFormRadioYN(_AM_SV_ADDOTHEROPTION, 'ele_value[other]', $other);
        $return[] = new XoopsFormHidden('ele_type', 'Checkbox');
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

    /**
    * prepare HTML for output
    *
    * @return	string
    */
    function render(){
        global $xoopsModuleConfig;
        $delimiter = $xoopsModuleConfig['delimiter'] == 0 ? "<br />" : "&nbsp;";
        $ret = "";
        $oldname = $this->getName();
        if ( count($this->getOptions()) > 1 && substr($this->getName(), -2, 2) != "[]" ) {
            $newname = $this->getName()."[]";
            $this->setName($newname);
        }
        foreach ( $this->getOptions() as $value => $name ) {
            $ret .= "<input type='checkbox' name='".$this->getName()."' value='".$value."'";
            if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
                $ret .= " checked='checked'";
            }
            $ret .= $this->getExtra()." />".$name."\n".$delimiter;
        }

        if ($this->_addOther) {
            $e = new XoopsFormText(_MA_SV_OTHER, $oldname."_other", $xoopsModuleConfig['t_width'], $xoopsModuleConfig['t_max'], "");
            $ret .= $e->getCaption()." ".$e->render();
        }

        return $ret;
    }
}
?>