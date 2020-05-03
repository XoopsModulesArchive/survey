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
require_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
/*
formid int(12) NOT NULL auto_increment,
f_name varchar(20) NOT NULL,
f_desc varchar(60) NOT NULL default '',
f_startdate int(12),
f_expiredate int(12),
f_isactive tinyint(1),
*/
/**
* Class for Survey Forms
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
*/

define("XOOPS_SURVEY_EMAIL", 1);
define("XOOPS_SURVEY_COOKIE", 2);
define("XOOPS_SURVEY_IP", 3);

class SurveyForm extends XoopsThemeForm {
    var $_errors = array();
    var $questions = array();
    var $questionsLoaded = false;
    var $formid;
    var $f_name;
    var $f_desc;
    var $f_startdate;
    var $f_expiredate;
    var $f_isactive;
    var $f_submit_message;
    var $f_restrictMode = array();
    var $f_req_icon = "*";

    /**
    * add an error
    *
    * @param string $value error to add
    * @access public
    */
    function setErrors($err_str)
    {
        $this->_errors[] = trim($err_str);
    }

    /**
    * return the errors for this object as an array
    *
    * @return array an array of errors
    * @access public
    */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
    * return the errors for this object as html
    *
    * @return string html listing the errors
    * @access public
    */
    function getHtmlErrors()
    {
        $ret = '<h4>Errors</h4>';
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $ret .= $error.'<br />';
            }
        } else {
            $ret .= 'None<br />';
        }
        return $ret;
    }

    /**
    * write replies to the form with answers to an export file
    *
    * @param string $format format to export to - currently supporting XML and CSV
    *
    * @return boolean
    */
    function export($format = "XML", $onlynew = 1) {
        global $xoopsTpl;
        if (!$this->questionsLoaded) {
            $this->loadQuestions('qid');
        }
        if (count($this->questions) == 0) {
            $this->setErrors(_AM_SV_EXP_NOQUESTIONSINFORM);
            return false;
        }
        $replies = $this->getReplies($onlynew);
        if (count($replies) == 0) {
            $this->setErrors(_AM_SV_EXP_NOANSWERS);
            return false;
        }
        $criteria = new Criteria('replyid', "(".implode(',', array_keys($replies)).")", "IN");
        $answer_handler =& xoops_getmodulehandler('answer', 'survey');
        $answer_arr = $answer_handler->getObjects($criteria);

        $questions[-2] = array('caption' => _AM_SV_EXP_REPLYID, 'qid' => -2 );
        $questions[-1] = array('caption' => _AM_SV_EXP_REPLYEMAIL, 'qid' => -1 );
        $questions[0]  = array('caption' => _AM_SV_EXP_REPLYDATE, 'qid' => 0);
        foreach ($this->questions as $questionid => $question) {
            $elements[$questionid] = $question->getElement();
            if ($elements[$questionid]->canBeSaved) {
                $questions[$questionid] = array('caption' => $elements[$questionid]->getCaptions(), 'qid' => $questionid, 'type' => get_class($elements[$questionid]));
            }
        }
        foreach ($replies as $replyid => $reply) {
            $reply_arr[$replyid]['id'] = $replyid;
            $reply_arr[$replyid]['email'] = $reply->getVar('replyemail');
            $reply_arr[$replyid]['date'] = formattimestamp($reply->getVar('replydate'));
        }
        //$all_answers = array();
        foreach ($answer_arr as $answer) {
            $thisanswer = $elements[$answer->getVar('qid')]->formatAnswerForExport($answer->getVar('answer', 'n'));
            $questions[$answer->getVar('qid')]['answers'][$answer->getVar('replyid')] = $thisanswer;
        }
        $templatename = "survey_exportTo".$format.".html";

        $ext = (strtolower($format) == "excel") ? "xml" : $format;

        $xoopsTpl->append('forms', array('questions' => $questions, 'replies' => $reply_arr, 'name' => $this->f_name));

        $filename = "form_".$this->formid."_".$format.".".$ext;
        /*
        if (!$fp = fopen (XOOPS_UPLOAD_PATH."/survey/".$filename, "w")) {
            $this->setErrors(sprintf(_AM_SV_EXP_CANNOTCREATEEXPORTFILE, $format));
            return false;
        }
        if ($xoopsTpl->template_exists('db:'.$templatename)) {
            $msg = $xoopsTpl->fetch('db:'.$templatename);
        }
        else {
            $this->setErrors(sprintf(_AM_SV_EXP_FORMATFILENOEXIST, $format));
            return false;
        }
        if (!fwrite ($fp, $msg)) {
            $this->setErrors(sprintf(_AM_SV_EXP_CANNOTWRITEEXPORTFILE, $format));
            fclose($fp);
            return false;
        }
        if (!fclose ($fp)) {
            $this->setErrors(sprintf(_AM_SV_EXP_CANNOTCLOSEEXPORTFILE, $format));
            return false;
        }
        */
        return $filename;
    }

    /**
    * get the {@link SurveyReply} objects, answering this form
    *
    * @return array
    */
    function getReplies($onlynew = true) {
        $reply_handler =& xoops_getmodulehandler('reply', 'survey');
        $criteria = new CriteriaCompo(new Criteria('formid', $this->formid));
        $criteria->add(new Criteria('replyvalidated', 1));
        if ($onlynew) {
            $criteria->add(new Criteria('purged', 0));
        }
        return $reply_handler->getObjects($criteria);
    }

    /**
    * retrieve the {@link SurveyQuestion} objects in the form and load them into a form property
    */
    function loadQuestions($sort = "q_order") {
        $question_handler =& xoops_getmodulehandler('question', 'survey');
        $criteria = new Criteria('formid', $this->formid);
        $criteria->setOrder('ASC');
        $criteria->setSort($sort);
        $this->questions = $question_handler->getObjects($criteria);
        $this->questionsLoaded = true;
    }

    /**
    * convert questions to form elements and add them
    */
    function loadElements() {
        if (!$this->questionsLoaded) {
            $this->loadQuestions();
        }
        foreach ($this->questions as $question) {
            $this->addElement($question->getElement(), $question->getVar('q_req'));
        }
        $this->addElement(new XoopsFormHidden('formid', $this->formid));
        $this->addElement(new XoopsFormButton('', 'submit', _MA_SV_SUBMIT, 'submit'));
    }

    /**
    * Return HTML for showing a form's elements for editing
    *
    * @return string
    */
    function displayForEdit() {
        $ret = "";
        if (!$this->questionsLoaded) {
            $this->loadQuestions();
        }
        global $xoopsModule;
        $ret .= $this->getElementSelect();

        $ret .= "<form action='elements.php' method='POST'>";
        $ret .= "<table width='100%' class='outer' cellspacing='1'><tr><th colspan='4'>".$this->getTitle()."</th></tr>";
        foreach ($this->questions as $question) {
            $ele =& $question->getElement();
            $ret .= "<tr valign='top' align='left'><td class='head' width='30%'>".$ele->getCaption();
            if ($ele->getDescription() != '') {
                $ret .= '<br /><br /><span style="font-weight: normal;">'.$ele->getDescription().'</span>';
            }
            $ret .= "</td><td class='even'>".$ele->render()."</td>";
            $ret .= "<td class='odd'><input type='text' name='elementsorder[".$question->getVar('qid')."]' size='10' value='".$question->getVar('q_order')."' /></td>";
            $ret .= "<td class='odd'><a href='elements.php?op=edit&amp;id=".$question->getVar('qid')."'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/edit.gif' title='"._AM_SV_EDIT_ELEMENT."' alt='"._AM_SV_EDIT_ELEMENT."' /></a>
			         <a href='elements.php?op=del&amp;id=".$question->getVar('qid')."'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/delete.gif' title='"._AM_SV_DELETE_ELEMENT."' alt='"._AM_SV_DELETE_ELEMENT."' /></a></td></tr>";
        }
        if (count($this->questions) > 0) {
            $ret .= "<tr><td colspan='2'></td><td colspan='2'><input type='hidden' name='op' value='reorder' /><input type='submit' name='submit' value='"._AM_SV_SUBMIT."'/></td>";
        }
        $ret .= "</table></form>";
        return $ret;
    }

    /***
    * Returns a form for selecting available element types, in HTML format
    *
    * return string
    */
    function getElementSelect() {
        require_once (XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        $form = new XoopsThemeForm("", 'addelementform', 'elements.php');
        $element_tray = new XoopsFormElementTray(_AM_SV_FORM_ADDELEMENT);
        $select = new XoopsFormSelect("", 'ele_type');
        $select->addOption("checkbox", _AM_SV_ELE_CHECKBOX);
        $select->addOption("surveydate", _AM_SV_ELE_DATE);
        $select->addOption("radio", _AM_SV_ELE_RADIO);
        $select->addOption("radioyn", _AM_SV_ELE_RADIOYN);
        $select->addOption("radiogroup", _AM_SV_ELE_RADIOGROUP);
        $select->addOption("select", _AM_SV_ELE_SELECT);
        $select->addOption("textbox", _AM_SV_ELE_TEXTBOX);
        $select->addOption("dhtmlTextarea", _AM_SV_ELE_TEXTDHTML);
        $select->addOption("textarea", _AM_SV_ELE_TEXTAREA);
        $select->addOption("label", _AM_SV_ELE_LABEL);
        $select->addOption("persondata", _AM_SV_ELE_PERSONDATA);
        $submit = new XoopsFormButton("", 'submit', _AM_SV_GO, "submit");
        $element_tray->addElement($select, true);
        $element_tray->addElement(new XoopsFormHidden('formid', $this->formid));
        $element_tray->addElement(new XoopsFormHidden('op', 'new'));
        $element_tray->addElement($submit);
        $form->addElement($element_tray);
        return $form->render();
    }

    /**
    * Returns whether the form is active
    * @return boolean
    */
    function isActive() {
        $now = time();
        return (($this->f_isactive > 0) && ($this->f_startdate < $now && $this->f_expiredate > $now));
    }

    /**
    * Returns whether the form is accessible by the current user
    *
    * @return boolean
    */
    function isAccessibleByUser() {
        if (!$this->isActive()) {
            $this->setErrors("Form inactive");
            return false;
        }
        if (in_array(XOOPS_SURVEY_EMAIL, $this->f_restrictMode)) {
            //check for validation key
            if ( isset($_REQUEST['submitter_email'])) {
                if (!checkEmail($_REQUEST['submitter_email'])) {
                    $this->setErrors(_MA_SV_ERR_INVALID_EMAIL);
                    return false;
                }
                $reply_handler =& xoops_getmodulehandler('reply', 'survey');
                //Look for - validated - replies for this form, from this email
                $criteria = new CriteriaCompo(new Criteria('formid', $this->formid));
                $criteria->add(new Criteria('replyemail', $_REQUEST['submitter_email']));
                $criteria->add(new Criteria('replyvalidated', 1));

                if ($reply_handler->getCount($criteria) > 0) {
                    $this->setErrors(_MA_SV_ERR_VALIDATED_EMAIL_FAIL);
                    return false;
                }
            }
        }

        if (in_array(XOOPS_SURVEY_COOKIE, $this->f_restrictMode)) {
            //check for COOKIE
            $key = 'survey_'.$this->formid;
            if (isset($_COOKIE[$key])) {
                $this->setErrors(_MA_SV_ERR_COOKIE_EXISTS);
                return false;
            }
        }

        if (in_array(XOOPS_SURVEY_IP, $this->f_restrictMode)) {
            //check for IP having voted
            $reply_handler =& xoops_getmodulehandler('reply', 'survey');
            $criteria = new CriteriaCompo(new Criteria('replyip', $_SERVER['REMOTE_ADDR']));
            $criteria->add(new Criteria('formid', $this->formid));
            $reply_count = $reply_handler->getCount($criteria);
            if ( $reply_count > 0) {
                $this->setErrors(_MA_SV_ERR_IP_ALREADY_REGISTERED);
                return false;
            }
        }
        return true;
    }

    /**
    * Submit a form and save reply and answers
    *
    * @return bool
    */
    function submit() {

        if (!$this->isAccessibleByUser()) {
            return false;
        }
        //Load questions
        if (!$this->questionsLoaded) {
            $this->loadQuestions();
        }
        //Only process forms that actually have questions
        if (count($this->questions) == 0) {
            return false;
        }
        //Create reply
        $email = isset($_REQUEST['submitter_email']) ? $_REQUEST['submitter_email'] : "";
        $reply_handler =& xoops_getmodulehandler('reply', 'survey');
        $reply =& $reply_handler->create($email, $this->formid);
        $reply->setVar('formid', $_REQUEST['formid']);
        $reply->setVar('replydate', time());
        $reply->setVar('replyip', $_SERVER['REMOTE_ADDR']);
        if (!$reply_handler->insert($reply)) {
            return false;
        }
        //Save answers
        $errors = 0;
        $answer_handler =& xoops_getmodulehandler('answer', 'survey');
        
        $questions = $this->questions;
        foreach (array_keys($questions) as $i) {
            $questions[$i]->loadElement();
            if ($questions[$i]->_element->canBeSaved) {
                $varname = "ele_".$questions[$i]->getVar('qid');
                $submitted_reply = isset($_REQUEST[$varname]) ? $_REQUEST[$varname] : $questions[$i]->getDefaultValue();
                $answer =& $answer_handler->create();
                //$answer->question = $questions[$i];
                $answer->setVar('replyid', $reply->getVar('replyid'));
                $answer->setVar('answer', $questions[$i]->_element->getTextValue($submitted_reply));
                $answer->setVar('qid', $questions[$i]->getVar('qid'));
                if (!$answer_handler->insert($answer)) {
                    $errors++;
                }
                unset($answer);
            }
        }
        if ($errors > 0) {
            return $errors;
        }
        $reply->register($this->f_restrictMode);
        return true;
    }

    /**
    * Converts an object to an array
    *
    * @return array
    */
    function toArray() {
        $myts =& MyTextSanitizer::getInstance();
        $ret = array();

        $ret['formid'] = $this->formid;
        $ret['name'] = $myts->htmlSpecialChars($this->f_name);
        $ret['description'] = $myts->displayTarea($this->f_desc);
        $ret['start'] = formatTimestamp($this->f_startdate, 's');
        $ret['expire'] = formatTimestamp($this->f_expiredate, 's');
        $ret['isactive'] = $this->f_isactive;
        $ret['submit_message'] = $myts->displayTarea($this->f_submit_message);

        return $ret;
    }

    /**
    * Deletes all replies to this form
    *
    * @return bool
    */
    function deleteReplies() {
        $reply_handler =& xoops_getmodulehandler('reply', 'survey');
        $replies = $reply_handler->getObjects(new Criteria('formid', $this->formid));
        foreach ($replies as $reply) {
            if (!$reply_handler->delete($reply) ) {
                $this->setErrors(sprintf(_AM_SV_DELETE_REPLY_FAILED, $reply->getVar('replyid')));
            }
        }
        if (count($this->_errors) > 0) {
            return false;
        }
        return true;
    }

    /**
    * Marks replies as purged
    *
    * @return bool
    */
    function purgeReplies() {
        $reply_handler =& xoops_getmodulehandler('reply', 'survey');
        $replies = $reply_handler->getObjects(new Criteria('formid', $this->formid));
        if (count($replies) > 0) {
            return $reply_handler->purge(new Criteria('replyid', "(".implode(',', array_keys($replies)).")", "IN"));
        }
        return true;
    }

    /**
    * Renders the Javascript function needed for client-side for validation
    *
    * @param		boolean  $withtags	Include the < javascript > tags in the returned string
    */
    function renderValidationJS( $withtags = true ) {
        $js = "";
        if ( $withtags ) {
            $js .= "\n<!-- Start Form Validation JavaScript //-->\n<script type='text/javascript'>\n<!--//\n";
        }
        $myts =& MyTextSanitizer::getInstance();
        $formname = $this->getName();
        $required =& $this->getRequired();
        $reqcount = count($required);
        $js .= "function xoopsFormValidate_{$formname}() {
	myform = window.document.$formname;\n";
        for ($i = 0; $i < $reqcount; $i++) {
            $eltname    = $required[$i]->getName();
            $eltcaption = trim( $required[$i]->getCaption() );
            $eltmsg = empty($eltcaption) ? sprintf( _FORM_ENTER, $eltname ) : sprintf( _FORM_ENTER, $eltcaption );
            $eltmsg = $myts->displayTarea($eltmsg);

            $js .= "if ( myform.{$eltname}.value == \"\" ) "
            . "{ window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }\n";
        }
        //$js .= "alert(myform.elements[\"ele_36[]\"].length);";
        $js .= "return true;\n}\n";

        if ( $withtags ) {
            $js .= "//--></script>\n<!-- End Form Validation JavaScript //-->\n";
        }
        return $js;
    }

    /**
    * assign to smarty form template instead of displaying directly
    *
    * @param	object  &$tpl    reference to a {@link Smarty} object
    * @see     Smarty
    */
    function assign(&$tpl){
        $i = 0;
        $myts =& MyTextSanitizer::getInstance();
        $elements = array();
        $required = $this->getRequired();
        $req_count = count($required);
        foreach ($required as $req_ele) {
            $req_names[] = $req_ele->getCaption();
        }
        foreach ( $this->getElements() as $ele ) {
            $n = ($ele->getName() != "") ? $ele->getName() : $i;
            $elements[$n]['name']	  = $ele->getName();
            $elements[$n]['caption']  = $ele->getCaption();
            $elements[$n]['body']	  = $ele->render();
            $elements[$n]['hidden']	  = $ele->isHidden();
            $elements[$n]['type']     = get_class($ele);

            $elements[$n]['description'] = ($ele->getDescription() != '') ? $ele->getDescription() : "";

            $elements[$n]['required'] = ($req_count > 0) && in_array($ele->getCaption(), $req_names) ? 1 : 0;

            $i++;
        }
        $js = $this->renderValidationJS();
        $tpl->assign($this->getName(), array('title' => $this->getTitle(), 'name' => $this->getName(), 'action' => $this->getAction(),  'method' => $this->getMethod(), 'extra' => 'onsubmit="return xoopsFormValidate_'.$this->getName().'();"'.$this->getExtra(), 'javascript' => $js, 'elements' => $elements, 'req_icon' => $this->f_req_icon, 'description' => $myts->displayTarea($this->f_desc)));
    }
}
/**
* Class for Survey Form Handling
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
*/
class SurveyFormHandler extends XoopsObjectHandler {
    var $table = "survey_form";
    /**
    * retrieve a given form by its ID
    * @param int $id ID of the form
    *
    * @return {@link SurveyForm} object
    */
    function &get($id) {
        $criteria = new Criteria('formid', intval($id));
        $form_arr = $this->getObjects($criteria, false);
        return $form_arr[0];
    }

    /**
    * Delete a form in the database
    * @param object $form {@link SurveyForm} object to delete
    *
    * @return bool
    */
    function delete(&$form) {
        if ($form->deleteReplies()) {
            $form->loadQuestions();
            $question_handler =& xoops_getmodulehandler('question', 'survey');
            foreach ($form->questions as $question) {
                $question_handler->delete($question);
            }
            $sql = "DELETE FROM ".$this->db->prefix($this->table)." WHERE formid=".intval($form->formid);
            return $this->db->query($sql);
        }
        return false;
    }


    /**
    * retrieve forms from the database
    *
    * @param object $criteria {@link CriteriaElement} conditions to be met
    * @param bool $id_as_key use the form ID as key for the array?
    * @param bool $as_object return objects or an array of id => name
    *
    * @return array array of {@link SurveyForm} objects
    */
    function getObjects($criteria = null, $id_as_key = true, $as_object = true) {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix($this->table);
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            if ($as_object == true) {
                $form = new SurveyForm($myrow['f_name'], 'survey', 'submit.php');
                $form->formid = $myrow['formid'];
                $form->f_name = $myrow['f_name'];
                $form->f_desc = $myrow['f_desc'];
                $form->f_startdate = $myrow['f_startdate'];
                $form->f_expiredate = $myrow['f_expiredate'];
                $form->f_isactive = $myrow['f_isactive'];
                $form->f_submit_message = $myrow['f_submit_message'];
                $form->f_restrictMode = explode(',', $myrow['f_restrictmode']);
                if (!$id_as_key) {
                    $ret[] =& $form;
                } else {
                    $ret[$myrow['formid']] =& $form;
                }
                unset($form);
            }
            else {
                if (!$id_as_key) {
                    $ret[] = $myrow['f_name'];
                } else {
                    $ret[$myrow['formid']] = $myrow['f_name'];
                }
            }
        }
        return $ret;
    }

    /**
    * retrieve forms count based on criteria
    *
    * @param object $criteria {@link CriteriaElement} conditions to be met
    *
    * @return array array of {@link SurveyForm} objects
    */
    function getObjectsCount($criteria = null) {
        $ret = 0;
        $limit = $start = 0;
        $sql = 'SELECT COUNT(*) FROM '.$this->db->prefix($this->table);
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        list($ret) = $this->db->fetchRow($result);
        return $ret;
    }

    /**
    * retrieve form for creating and editing a form
    *
    * @param int $id form ID if editing an existing form
    *
    * @return {@link SurveyForm} object
    */
    function getCEForm($id = false) {
        require_once (XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        if (false != $id) {
            $form =& $this->get($id);
            $isactive = $form->f_isactive;
        }
        else {
            $form =& $this->create();
            $isactive = 0;
        }

        $ts =& MyTextSanitizer::getInstance();

        $name = new XoopsFormText(_AM_SV_FORM_NAME, 'form_name', 30, 50, $ts->htmlSpecialChars($form->f_name));
        $description = new XoopsFormDhtmlTextArea(_AM_SV_FORM_DESC, 'form_desc', htmlspecialchars($form->f_desc, ENT_QUOTES), 15, 50);
        $startdate = new XoopsFormDateTime(_AM_SV_FORM_START, 'form_start', 15, $form->f_startdate);
        $expiredate = new XoopsFormDateTime(_AM_SV_FORM_EXPIRE, 'form_expire', 15, $form->f_expiredate);
        $active = new XoopsFormRadioYN(_AM_SV_FORM_ACTIVE, 'form_active', $isactive);
        $submit_message = new XoopsFormDhtmlTextArea(_AM_SV_FORM_SUBMIT_MESSAGE, 'form_submit', $ts->htmlSpecialChars($form->f_submit_message), 15, 50);

        $restrictmode = new XoopsFormCheckBox(_AM_SV_FORM_RESTRICTMODE, 'form_restrictmode', $form->f_restrictMode);
        $restrictmode->addOption(XOOPS_SURVEY_EMAIL, _AM_SV_FORM_RES_EMAIL);
        $restrictmode->addOption(XOOPS_SURVEY_COOKIE, _AM_SV_FORM_RES_COOKIE);
        $restrictmode->addOption(XOOPS_SURVEY_IP, _AM_SV_FORM_RES_IP);


        $inputform = new XoopsThemeForm('', 'form_form', $_SERVER['REQUEST_URI']);
        if (false != $id) {
            $inputform->addElement(new XoopsFormHidden('formid', $form->formid));
        }
        $inputform->addElement(new XoopsFormHidden('op', 'saveform'));
        $inputform->addElement($name, true);
        $inputform->addElement($description, true);
        $inputform->addElement($startdate, true);
        $inputform->addElement($expiredate);
        $inputform->addElement($active);
        $inputform->addElement($submit_message);
        $inputform->addElement($restrictmode);
        $inputform->addElement(new XoopsFormButton('', 'submit', _AM_SV_SUBMIT, 'submit'));
        return $inputform;
    }

    /**
    * Insert form into database
    *
    * @param int $id ID of form
    * @param string $name form name
    * @param string $description form description
    * @param int $startdate UNIX Timestamp for form activation
    * @param int $expiredate UNIX Timestamp for form de-activation
    * @param bool $active true for active, false for inactive
    * @param string $submit_msg the form's post-submit message
    * @param array $restrictMode array of modes to restrict double-submissals - if any
    *
    * @return array array of {@link SurveyForm} objects
    */
    function insert($id = false, $name, $description, $startdate, $expiredate, $active, $submit_msg, $restrictmode = array()) {
        $myts =& MyTextSanitizer::getInstance();
        if (false != $id) {
            $sql = "UPDATE ".$this->db->prefix($this->table)." SET
                    f_name = '".$myts->addSlashes($name)."', 
                    f_desc = '".$myts->addSlashes($description)."', 
                    f_startdate = ".intval($startdate).", 
                    f_expiredate = ".intval($expiredate).",
                    f_isactive = ".intval($active).",
                    f_submit_message = '".$myts->addSlashes($submit_msg)."',
                    f_restrictmode = '".implode(',', $restrictmode)."'
                WHERE formid = ".intval($id);
        }
        else {
            $sql = "INSERT INTO ".$this->db->prefix($this->table)." SET
                    f_name = '".$myts->addSlashes($name)."', 
                    f_desc = '".$myts->addSlashes($description)."', 
                    f_startdate = ".intval($startdate).", 
                    f_expiredate = ".intval($expiredate).",
                    f_isactive = ".intval($active).",
                    f_submit_message = '".$myts->addSlashes($submit_msg)."',
                    f_restrictmode = '".implode(',', $restrictmode)."'";
        }
        if (!$this->db->query($sql)) {
            return false;
        }
        elseif (false == $id) {
            return $this->db->getInsertId();
        }
        else {
            return true;
        }
    }

    /**
    * Save an identical copy of a form in the database including questions - but not replies/answers
    *
    * @param object $form
    *
    * @return bool
    */
    function form_clone(&$form) {
        $form->f_name .= "{CLONE}";
        if ($newid = $this->insert(false,
        $form->f_name,
        $form->f_desc,
        $form->f_startdate,
        $form->f_expiredate,
        0,
        $form->f_submit_message,
        $form->f_restrictMode)) {
            if (!$form->questionsLoaded) {
                $form->loadQuestions();
            }
            $form->formid = $newid;
            $question_handler =& xoops_getmodulehandler('question', 'survey');
            foreach ($form->questions as $question) {
                $question->setNew();
                $question->setVar('formid', $newid);
                if (!$question_handler->insert($question)) {
                    $form->setErrors(sprintf(_AM_SV_CLONE_COULDNOT, $question->getVar('q_caption')));
                }
            }
            return;
        }
        $form->setErrors(sprintf(_AM_SV_CLONE_COULDNOT, $form->f_name));
        return;
    }

    /**
    * Search for forms containing keywords
    *
    * @param string $keywords one or more keywords to search for
    * @param string $andor - can be 'AND', 'OR' or 'Exact'
    *
    * @return array array of form IDs
    */
    function search($keywords, $andor = "AND") {
        unset($_SESSION['sur_search']);

        $criteria = new CriteriaCompo();
        $criteria2 = new CriteriaCompo();
        $criteria3 = new CriteriaCompo();
        if ($andor != "exact") {
            $keywords = $this->extractKeywords($keywords, $andor);
            foreach ($keywords as $q) {
                $criteria->add(new Criteria('f_name', "%".$q."%", 'LIKE'), $andor);
                $criteria2->add(new Criteria('f_desc', "%".$q."%", 'LIKE'), $andor);
                $criteria3->add(new Criteria('f_submit_message', "%".$q."%", 'LIKE'), $andor);
            }
        }
        else {
            $criteria->add(new Criteria('f_name', "%".$keywords."%", 'LIKE'));
            $criteria2->add(new Criteria('f_desc', "%".$keywords."%", 'LIKE'));
            $criteria3->add(new Criteria('f_submit_message', "%".$keywords."%", 'LIKE'));
        }
        $criteria_join = new CriteriaCompo($criteria);
        $criteria_join->add($criteria2, 'OR');
        $criteria_join->add($criteria3, 'OR');
        $surveys = array_keys($this->getObjects($criteria_join, true, false));

        $question_handler =& xoops_getmodulehandler('question', 'survey');
        $questions = $question_handler->search($keywords, $andor);
        foreach ($questions as $q) {
            array_push($surveys, $q['formid']);
        }
        //remove doublets and save in session variable

        $_SESSION['sur_search'] = array_keys(array_flip($surveys));
        return $_SESSION['sur_search'];
    }

    /**
    * Extract individual keywords from a string of several keywords
    *
    * @param string $query
    * @param string $andor - can be 'AND', 'OR' or 'Exact'
    *
    * @return array
    */
    function extractKeywords($query, $andor) {
        $myts =& MyTextSanitizer::getInstance();
        if ( strtolower($andor) != "exact" ) {
            $temp_queries = preg_split('/[\s,]+/', $query);
            foreach ($temp_queries as $q) {
                $q = trim($q);
                $queries[] = $myts->addSlashes($q);
            }
            if (count($queries) == 0) {
                return array();
            }
        } else {
            $query = trim($query);
            $queries = array($myts->addSlashes($query));
        }
        return $queries;
    }
}
?>