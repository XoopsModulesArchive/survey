<?php
// $Id:
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
  answerid int(12) unsigned NOT NULL auto_increment,
  qid int(12) NOT NULL,  
  replyid int(12) NOT NULL,
  answer text NOT NULL,
*/

/**
 * Class for Survey Answers
 * @author Jan Pedersen
 * @copyright copyright (c) 2004 IDG.dk
 * @package Survey
 */
class SurveyAnswer extends XoopsObject {
    var $question;
    function SurveyAnswer() {
        $this->initVar('answerid', XOBJ_DTYPE_INT);
        $this->initVar('replyid', XOBJ_DTYPE_INT);
        $this->initVar('qid', XOBJ_DTYPE_INT);
        $this->initVar('answer', XOBJ_DTYPE_TXTAREA);
    }
    /**
    * return the answer text, suitable for saving
    */
    function getTextValue($text) {
        $element =& $this->question->getElement();
        return $element->getTextValue($text);
    }
    
}
/**
 * Class for Survey Answer Handling
 * @author Jan Pedersen
 * @copyright copyright (c) 2004 IDG.dk
 * @package Survey
 */
class SurveyAnswerHandler extends XoopsObjectHandler {
    var $table = "survey_answer";
    
    /**
    * Retrieve a new instance of a SurveyAnswer object
    *
    * @return {@link SurveyAnswer}
    */
    function &create($new = true) {
        $obj = new SurveyAnswer();
        if ($new) {
            $obj->setNew();
        }
        return $obj;
    }
    
    /**
    * retrieve a given answer by its ID
    * @param int $id ID of the answer
    *
    * @return {@link SurveyAnswer} object
    */
    function &get($id) {
        $criteria = new Criteria('answerid', intval($id));
        $form_arr = $this->getObjects($criteria, false);
        return $form_arr[0];
    }
    
    /**
    * Delete an answer in the database
    * @param {@link SurveyAnswer} object to delete
    *
    * @return bool
    */
    function delete(&$obj) {
        $id = intval($obj->getVar('answerid'));
        $sql = "DELETE FROM ".$this->db->prefix($this->table)." 
                WHERE answerid = ".$id;
        return $this->db->query($sql);
    }
    
    /**
     * retrieve answers from the database
     * 
     * @param object $criteria {@link CriteriaElement} conditions to be met
     * @param bool $id_as_key use the answer ID as key for the array?
     *
     * @return array array of {@link SurveyAnswer} objects
     */
    function getObjects($criteria = null, $id_as_key = true) {
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
            $answer = new SurveyAnswer();
            $answer->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $answer;
            } else {
                $ret[$myrow['answerid']] =& $answer;
            }
            unset($answer);
        }
        return $ret;
    }
    
    /**
    * Insert a reply into the database
    *
    * @param $answer {@link SurveyAnswer} object to save
    *
    * @return bool
    */
    function insert(&$answer) {
        if (!$answer->cleanVars()) {
            return false;
        }
        $myts =& MyTextSanitizer::getInstance();
        $sql = "INSERT INTO ".$this->db->prefix($this->table)." SET
                    replyid = ".intval($answer->getVar('replyid')).",
                    qid = ".intval($answer->getVar('qid')).",
                    answer = '".$myts->addSlashes($answer->getVar('answer', 'n'))."'";
        if (!$this->db->query($sql)) {
            return false;
        }
        $answer->assignVar('answerid', $this->db->getInsertId());
        return true;
    }
    
    /**
    * Delete all answers for a given reply in the database
    * @param int $id ID of the reply object
    *
    * @return bool
    */
    function deleteByReplyID($id) {
        $id = intval($id);
        $sql = "DELETE FROM ".$this->db->prefix($this->table)." 
                WHERE replyid = ".$id;
        return $this->db->query($sql);
    }
}