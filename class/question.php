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

/*
qid int(12) unsigned NOT NULL auto_increment,
formid int(12) NOT NULL default '0',
ele_type varchar(10) NOT NULL default '',
q_caption varchar(255) NOT NULL default '',
q_description varchar(255) default '',
q_order smallint(2) NOT NULL default '0',
q_req tinyint(1) NOT NULL default '1',
q_value text NOT NULL,
q_display tinyint(1) NOT NULL default '1'
*/
/**
* Class for Survey Forms Questions
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
*/
class SurveyQuestion extends XoopsObject {
    var $_element;
    function SurveyQuestion() {
        $this->initVar('qid', XOBJ_DTYPE_INT);
        $this->initVar('formid', XOBJ_DTYPE_INT);
        $this->initVar('ele_type', XOBJ_DTYPE_TXTBOX);
        $this->initVar('q_caption', XOBJ_DTYPE_TXTBOX);
        $this->initVar('q_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('q_order', XOBJ_DTYPE_INT);
        $this->initVar('q_req', XOBJ_DTYPE_INT, 0);
        $this->initVar('q_value', XOBJ_DTYPE_ARRAY);
        $this->initVar('q_display', XOBJ_DTYPE_INT, 1);
    }

    /**
    * Get instance of the form element related to this question
    *
    * @return {@link XoopsFormElement} child object
    */
    function &getElement() {
        if (!is_object($this->_element)) {
            $this->loadElement();
        }
        return $this->_element;
    }

    /**
    * Load instance of the question's element
    */
    function loadElement() {
        $myts =& MyTextSanitizer::getInstance();
        $element_handler =& xoops_getmodulehandler('element', 'survey');
        $thiselement = $element_handler->getElement($this->getVar('ele_type'), $this);
        $thiselement->setDescription($myts->displayTarea($this->getVar('q_description', 'n'), 1));
        $this->_element = $thiselement;
    }

    /**
    * Returns a default value to be saved as the reply, if this field is not filled out in the form submissal
    *
    * @return string
    */
    function getDefaultValue() {
        $element =& $this->getElement();
        return $element->getDefaultValue();
    }

    /**
    * Store the question in the database following add/edit form submissal
    * $_POST vars are directly accessed and must be available to this method
    * If this is not the case, call insert() on the QuestionHandler instead
    *
    * @return bool
    */
    function store() {
        $required = isset($_POST['required']) ? $_POST['required'] : 0;
        $this->setVar('q_caption', $_POST['caption']);
        $this->setVar('q_description', $_POST['description']);
        $this->setVar('q_req', $required);
        $this->setVar('q_display', $_POST['display']);

        $element =& $this->getElement();
        $this->setVar('q_value', $element->getValueForSave($_POST['ele_value']));
        $q_handler =& xoops_getmodulehandler('question', 'survey');
        return $q_handler->insert(&$this);
    }

}
/**
* Class for Survey Forms Question Handling
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
*/
class SurveyQuestionHandler extends XoopsObjectHandler {
    var $table = "survey_question";

    /**
    * Retrieve a new instance of a SurveyQuestion object
    *
    * @return {@link SurveyQuestion}
    */
    function &create($new = true) {
        $obj = new SurveyQuestion();
        if ($new) {
            $obj->setNew();
        }
        return $obj;
    }

    /**
    * retrieve a given question by its ID
    * @param int $id ID of the question
    *
    * @return {@link SurveyQuestion} object
    */
    function &get($id) {
        $criteria = new Criteria('qid', intval($id));
        $form_arr = $this->getObjects($criteria, false);
        return $form_arr[0];
    }

    /**
    * Delete a question in the database
    * @param {@link SurveyQuestion} object to delete
    *
    * @return bool
    */
    function delete(&$question) {
        $id = intval($question->getVar('qid'));
        $sql = "DELETE FROM ".$this->db->prefix($this->table)."
                WHERE qid = ".$id;
        return $this->db->query($sql);
    }

    /**
    * Delete a given question by its ID
    * @param int $id ID of the question
    *
    * @return bool
    */
    function deleteById($id) {
        $criteria = new Criteria('qid', intval($id));
        $form_arr = $this->getObjects($criteria, false);
        return $this->delete($form_arr[0]);
    }

    /*
    * Save {@link SurveyQuestion} object in the database
    *
    * @param object $question {@link SurveyQuestion} to save
    * @param
    *
    * @return bool
    */
    function insert($question) {
        if (strtolower(get_class($question)) != 'surveyquestion') {
            return false;
        }
        if (!$question->isDirty()) {
            return true;
        }
        if (!$question->cleanVars()) {
            return false;
        }
        foreach ($question->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($question->isNew()) {
            $question_id = $this->db->genId('survey_question_qid_seq');
            $sql = sprintf("INSERT INTO %s (qid, formid, ele_type, q_caption, q_description, q_order, q_req, q_value, q_display) VALUES (%u, %u, %s, %s, %s, %u, %u, %s, %u)", $this->db->prefix($this->table), $question_id, $formid, $this->db->quoteString($ele_type), $this->db->quoteString($q_caption), $this->db->quoteString($q_description), $q_order, $q_req, $this->db->quoteString($q_value), $q_display);
        } else {
            $sql = "UPDATE ".$this->db->prefix($this->table)."
                    SET q_caption = ".$this->db->quoteString($q_caption).", 
                        q_description = ".$this->db->quoteString($q_description).",
                        q_order = ".$q_order.",
                        q_req = ".$q_req.",
                        q_value = ".$this->db->quoteString($q_value).",
                        q_display = ".$q_display."
                    WHERE qid = ".$qid;
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if ($question->isNew()) {
            $question->assignVar('qid', $this->db->getInsertId());
            $question->unsetNew();
        }
        return true;
    }
    /**
    * retrieve questions from the database
    *
    * @param object $criteria {@link CriteriaElement} conditions to be met
    * @param bool $id_as_key use the question ID as key for the array?
    * @param bool $as_object return objects or an array of id => name
    *
    * @return array array of {@link SurveyQuestion} objects
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
                $question =& $this->create(false);
                $question->assignVars($myrow);
                if (!$id_as_key) {
                    $ret[] =& $question;
                } else {
                    $ret[$myrow['qid']] =& $question;
                }
                unset($question);
            }
            else {
                if (!$id_as_key) {
                    $ret[] = $myrow;
                } else {
                    $ret[$myrow['qid']] = $myrow;
                }
            }
        }
        return $ret;
    }

    /**
    * Returns {@link XoopsThemeForm} instance for creating or editing the question
    *
    * @return array
    */
    function getCEForm($id = null, $type = null) {
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        if (null != $type) {
            $question =& $this->create();
            $question->setVar('ele_type', $type);
            $question->setVar('formid', $id);
        }
        else {
            $question =& $this->get($id);
            $id_hidden = new XoopsFormHidden('qid', $id);
            survey_adminMenu(2, '', $question->getVar('formid'));
        }

        if (!is_object($question->_element)) {
            $question->loadElement();
        }

        $settings['value'] = $question->vars['q_value']['value'] != "" ? $question->getVar('q_value') : "";
        $settings['addopt'] = isset($_REQUEST['addopt']) ? $_REQUEST['addopt'] : null;
        $elements = $question->_element->getAdminElement($settings);

        $form = new XoopsThemeForm(_AM_SV_QUESTION, 'questionform', $_SERVER['REQUEST_URI']);
        $form->addElement(new XoopsFormText(_AM_SV_CAPTION, 'caption', 40, 255, $question->getVar('q_caption')));
        $form->addElement(new XoopsFormText(_AM_SV_DESCRIPTION, 'description', 35, 255, $question->getVar('q_description')));
        foreach (array_keys($elements) as $i) {
            $form->addElement($elements[$i]);
        }
        if ($question->_element->canBeRequired) {
            $form->addElement(new XoopsFormRadioYN(_AM_SV_REQUIRED, 'required', $question->getVar('q_req')));
        }
        $form->addElement(new XoopsFormRadioYN(_AM_SV_DISPLAY, 'display', $question->getVar('q_display')));
        $form->addElement(new XoopsFormHidden('op', 'save'));
        if (isset($id_hidden)) {
            $form->addElement($id_hidden);
        }
        $form->addElement(new XoopsFormHidden('formid', $question->getVar('formid')));
        $form->addElement(new XoopsFormButton('', 'submit', _AM_SV_SUBMIT, 'submit'));
        return $form;
    }

    /**
    * Search for questions containing keywords
    *
    * @param array $keywords one or more keywords to search for
    * @param string $andor - can be 'AND', 'OR' or 'Exact'
    *
    * @return array array of questions IDs
    */
    function search($keywords, $andor = "AND") {

        $criteria = new CriteriaCompo();
        $criteria2 = new CriteriaCompo();
        if ($andor != "exact") {
            foreach ($keywords as $q) {
                $criteria->add(new Criteria('q_caption', "%".$q."%", 'LIKE'), $andor);
                $criteria2->add(new Criteria('q_description', "%".$q."%", 'LIKE'), $andor);
            }
        }
        else {
            $criteria->add(new Criteria('q_caption', "%".$keywords."%", 'LIKE'));
            $criteria2->add(new Criteria('q_description', "%".$keywords."%", 'LIKE'));
        }
        $criteria_join = new CriteriaCompo($criteria);
        $criteria_join->add($criteria2, 'OR');
        return $this->getObjects($criteria_join, true, false);
    }
}
?>