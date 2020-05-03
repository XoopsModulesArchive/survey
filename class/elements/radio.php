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
* Class for Radio Elements with only yes/no options
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class RadioYNElement extends XoopsFormRadioYN {
    var $_question;
    var $canBeRequired = true;
    var $canBeSaved = true;
    var $hasMultipleAnswers = false;
    function RadioYNElement(&$question) {
        $this->_question = $question;
        if ($question->isNew() != true) {
            $ele_value = $this->_question->getVar('q_value');
            $ele_value = $ele_value['_YES'];
        }
        else {
            $ele_value = "";
        }
        $this->XoopsFormRadioYN(
        $this->_question->getVar('q_caption'),
        "ele_".$this->_question->getVar('qid'),
        $ele_value
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
        $value = array();
        if( $ele_value == '_NO' ){
            $value = array('_YES'=>0,'_NO'=>1);
        }else{
            $value = array('_YES'=>1,'_NO'=>0);
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
        return $reply != 0 ? _YES : _NO;
    }

    /**
    * Returns a default value to be saved as the reply, if this field is not filled out in the form submissal
    *
    * @return string
    */
    function getDefaultValue() {
        return 0;
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
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        if( $this->_question->isNew() != true) {
            if( $settings['value']['_YES'] == 1 ){
                $selected = '_YES';
            }
            else{
                $selected = '_NO';
            }
        }
        else{
            $selected = '_YES';
        }
        $options = new XoopsFormRadio(_AM_SV_ELE_DEFAULT, 'ele_value', $selected);
        $options->addOption('_YES', _YES);
        $options->addOption('_NO', _NO);

        $return[] = $options;
        $return[] = new XoopsFormHidden('ele_type', 'RadioYN');
        return $return;
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
	 * Prepare HTML for output
	 * 
	 * @return	string	HTML
	 */
	function render(){
	    global $xoopsModuleConfig;
	    $delimiter = $xoopsModuleConfig['delimiter'] == 0 ? "<br />" : "&nbsp;";
		$ret = "";
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type='radio' name='".$this->getName()."' value='".$value."'";
			$selected = $this->getValue();
			if ( isset($selected) && ($value == $selected) ) {
				$ret .= " checked='checked'";
			}
			$ret .= $this->getExtra()." />".$name."\n".$delimiter;
		}
		return $ret;
	}
}
/**
* Class for Radio Elements
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
* @subpackage Elements
*/
class RadioElement extends XoopsFormRadio {
    var $_question;
    var $canBeRequired = true;
    var $canBeSaved = true;
    var $hasMultipleAnswers = false;
    var $_addOther = 0;
    function RadioElement($question) {
        $this->_question = $question;
        $myts =& MyTextSanitizer::getInstance();
        if ($this->_question->isNew() != true) {
            $ele_value = $this->_question->getVar('q_value');
        }
        else {
            $ele_value = array('options' => array(), 'other' => 0);
        }
        $this->_addOther = $ele_value['other'];
        $selected = '';
        $options = array();
        $opt_count = 0;
        while( $i = each($ele_value['options']) ){
            $val = $myts->stripSlashesGPC($i['key']);
            $options[$opt_count] = $myts->displayTarea($val);
            if( $i['value'] > 0 ){
                $selected = $opt_count;
            }
            $opt_count++;
        }

        $this->XoopsFormRadio(
        $this->_question->getVar('q_caption'),
        "ele_".$this->_question->getVar('qid'),
        $selected
        );
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
        foreach( $ele_value['opt'] as $id => $v){
            if( !empty($v['value']) ){
                $value['options'][$v] = isset($_REQUEST['checked']) && $_REQUEST['checked'] == $id ? 1 : 0;
            }
        }
        $value['other'] = isset($ele_value['other']) && $ele_value['other'] == 1 ? 1 : 0;
        return $value;
    }

    /*
    * Returns a text string for insertion in export facility such as CSV file
    * @param string $reply
    *
    * @return string
    */
    function getTextValue($reply) {
        if (isset($_REQUEST["ele_".$this->_question->getVar('qid')."_other"]) && $_REQUEST["ele_".$this->_question->getVar('qid')."_other"] != "") {
            return $_REQUEST["ele_".$this->_question->getVar('qid')."_other"];
        }
        if ($reply == null) {
            return "";
        }
        $options = $this->_question->getVar('q_value');
        $options = $options['options'];
        $opt_array = array_keys($options);
        return $opt_array[$reply];
    }
    
    /**
    * Returns a default value to be saved as the reply, if this field is not filled out in the form submissal
    *
    * @return string
    */
    function getDefaultValue() {
        return null;
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
    * @param array $settings Options for the element
    *
    * @return array array of {@link XoopsFormElement} for the administration area
    */
    function getAdminElement($settings = array()) {
        $ele_value = $settings['value']['options'];
        $myts =& MyTextSanitizer::getInstance();
        $options = array();
        $opt_count = 0;
        if (!$this->_question->isNew()) {
            $keys = array_keys($ele_value);
            for( $i=0; $i<count($keys); $i++ ){
                $r = $ele_value[$keys[$i]] ? $opt_count : null;
                $v = $myts->makeTboxData4PreviewInForm($keys[$i]);
                $options[] = $this->addElementOption('ele_value[opt]['.$opt_count.']', $opt_count, $v, $r);
                $opt_count++;
            }
        }
        $addopt = isset($settings['addopt']) ? $settings['addopt'] : 0;
        if (!$addopt && $this->_question->isNew()) {
            $addopt = 2;
        }
        for( $i=0; $i<$addopt; $i++ ){
            $options[] = $this->addElementOption('ele_value[opt]['.$opt_count.']', $opt_count, '');
            $opt_count++;
        }
        $options[] = $this->addOptionsTray();
        $opt_tray = new XoopsFormElementTray(_AM_SV_ELE_OPT, '<br />');
        $opt_tray->setDescription(_AM_SV_ELE_OPT_DESC);
        for( $i=0; $i<count($options); $i++ ){
            $opt_tray->addElement($options[$i]);
        }
        $return[] = $opt_tray;
        $return[] = new XoopsFormRadioYN(_AM_SV_ADDOTHEROPTION, 'ele_value[other]', $settings['value']['other']);
        $return[] = new XoopsFormHidden('ele_type', 'Radio');
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
        $d = new XoopsFormText('', $id1, 40, 255, $text);

        $c = new XoopsFormRadio('', 'checked', $checked);
        $c->addOption($id2, ' ');

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
	 * Prepare HTML for output
	 * 
	 * @return	string	HTML
	 */
	function render(){
	    global $xoopsModuleConfig;
	    $delimiter = $xoopsModuleConfig['delimiter'] == 0 ? "<br />" : "&nbsp;";
		$ret = "";
		$oldname = $this->getName();
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type='radio' name='".$this->getName()."' value='".$value."'";
			$selected = $this->getValue();
			if ( isset($selected) && ($value == $selected) ) {
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