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
replyid int(12) unsigned NOT NULL auto_increment,
formid int(12) NOT NULL,
replydate int(12) NOT NULL,
replyemail varchar(100) default '0',
replyip varchar(20),
replyvalidated tinyint(1) default 1,
vkey varchar(100)
*/

/**
* Class for Survey Replies
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
*/
class SurveyReply extends XoopsObject {
    function SurveyReply() {
        $this->initVar('replyid', XOBJ_DTYPE_INT);
        $this->initVar('formid', XOBJ_DTYPE_INT);
        $this->initVar('replyemail', XOBJ_DTYPE_TXTBOX);
        $this->initVar('replydate', XOBJ_DTYPE_INT);
        $this->initVar('replyip', XOBJ_DTYPE_TXTBOX);
        $this->initVar('replyvalidated', XOBJ_DTYPE_INT, 1);
        $this->initVar('vkey', XOBJ_DTYPE_TXTBOX);
        $this->initVar('purged', XOBJ_DTYPE_INT, 0);
    }

    /**
    * Registers a submissal of a form, if the form is restricting double-submissals
    *
    * @param array $restrictMode, array of enabled double-submit restricting measures
    *
    */
    function register($restrictMode) {
        if (count($restrictMode) == 0) {
            return;
        }
        if (in_array(XOOPS_SURVEY_EMAIL, $restrictMode) && isset($_REQUEST['submitter_email'])) {
            //set reply to be unvalidated and send validation email
            $reply_handler =& xoops_getmodulehandler('reply', 'survey');
            $reply_handler->setValidated($this, false);
            $this->sendValidationEmail($_REQUEST['submitter_email']);
        }
        else {
            //reply is validated - double-submitting is avoided by cookie and/or IP or nothing
            $reply_handler =& xoops_getmodulehandler('reply', 'survey');
            $reply_handler->setValidated($this, true);
        }
        if (in_array(XOOPS_SURVEY_COOKIE, $restrictMode)) {
            setcookie("survey_".$this->getVar('formid'), md5($_SERVER['REMOTE_ADDR']));
        }
        if (in_array(XOOPS_SURVEY_IP, $restrictMode)) {
            //Do nothing - IP's are automatically registered when replying
        }
        return;
    }

    /**
    * Sends an email to the submitter, asking him to validate the reply
    * @param string $email
    */
    function sendValidationEmail($email) {
        $mailer =& getMailer();
        $mailer->useMail();
        $mailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/survey/language/".$GLOBALS['xoopsConfig']['language']."/mail_template");
        $mailer->setTemplate("validation.tpl");
        $mailer->setToEmails($email);
        $mailer->setFromEmail($GLOBALS['xoopsConfig']['adminmail']);
        $mailer->setFromName($GLOBALS['xoopsConfig']['sitename']);
        $mailer->setSubject(_MA_SV_VALIDATION_SUBJECT);

        //Tags used in the mail message
        $mailer->assign('X_ACT_LINK', XOOPS_URL."/modules/survey/validate.php?rid=".$this->getVar('replyid')."&valkey=".$this->getVar('vkey'));

        if ( !$mailer->send($GLOBALS['xoopsConfig']['debug_mode'] > 0) ) {
            trigger_error( $mailer->getErrors());
        }
        return;
    }
}
/**
* Class for Survey Reply Handling
* @author Jan Pedersen
* @copyright copyright (c) 2004 IDG.dk
* @package Survey
*/
class SurveyReplyHandler extends XoopsObjectHandler {
    var $table = "survey_reply";

    /**
    * Retrieve a new instance of a SurveyReply object
    *
    * @param string $email - optional email to check if exists
    * @param int $formid - ID of the form for this reply
    *
    * @return {@link SurveyReply}
    */
    function &create($email = "", $formid = 0) {
        $obj = new SurveyReply();
        if ($email != "" && $formid > 0) {
            $criteria = new Criteria('vkey', md5($email.$formid));
            $reply_arr = $this->getObjects($criteria, false);
            if (count($reply_arr) == 1) {
                return $reply_arr[0];
            }
            else {
                $obj->setVar('replyemail', $email);
            }
        }
        $obj->setNew();
        return $obj;
    }

    /**
    * retrieve a given reply by its ID
    * @param int $id ID of the reply
    *
    * @return {@link SurveyReply} object
    */
    function &get($id) {
        $criteria = new Criteria('replyid', intval($id));
        $form_arr = $this->getObjects($criteria, false);
        return $form_arr[0];
    }

    /**
    * Delete a reply in the database
    * @param {@link SurveyReply} object to delete
    *
    * @return bool
    */
    function delete(&$obj) {
        $id = intval($obj->getVar('replyid'));
        $sql = "DELETE FROM ".$this->db->prefix($this->table)."
                WHERE replyid = ".$id;
        if (! $this->db->query($sql)) {
            return false;
        }
        $answer_handler =& xoops_getmodulehandler('answer', 'survey');
        return $answer_handler->deleteByReplyID($id);
    }
    /**
    * retrieve replies from the database
    *
    * @param object $criteria {@link CriteriaElement} conditions to be met
    * @param bool $id_as_key use the reply ID as key for the array?
    *
    * @return array array of {@link SurveyReply} objects
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
            $reply = new SurveyReply();
            $reply->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $reply;
            } else {
                $ret[$myrow['replyid']] =& $reply;
            }
            unset($reply);
        }
        return $ret;
    }

    /**
    * count replies matching a condition
    *
    * @param object $criteria {@link CriteriaElement} to match
    * @return int count of replies
    */
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->db->prefix($this->table);
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);
        return $count;
    }
    
    /**
    * count replies matching a condition - group by form ID
    *
    * @param object $criteria {@link CriteriaElement} to match
    * @return int count of replies
    */
    function getCountByFormId($criteria = null)
    {
        $sql = 'SELECT formid, COUNT(*) FROM '.$this->db->prefix($this->table);
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $criteria->setGroupby('formid');
            $sql .= ' '.$criteria->renderWhere()." ".$criteria->getGroupby();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return array();
        }
        $counts = array();
        while(list($id, $count) = $this->db->fetchRow($result)) {
            $counts[$id] = $count;
        }
        return $counts;
    }
    /**
    * Insert a reply into the database - if reply is not NEW, 
    * it will REMOVE all answers in the database for this reply
    * - it is meant to be used only for unvalidated replies that are re-submitted through the form
    *
    * @param $reply {@link SurveyReply} object to save
    *
    * @return bool
    */
    function insert(&$reply) {

        $vkey = md5($reply->getVar('replyemail', 'n').$reply->getVar('formid'));
        $reply->setVar('vkey', $vkey);
        if (!$reply->cleanVars()) {
            return false;
        }
        $myts =& MyTextSanitizer::getInstance();
        if (!$reply->isNew()) {
            //Reply exists already
            $this->delete($reply);
        }
        $sql = "INSERT INTO ".$this->db->prefix($this->table)." SET
                    formid = ".intval($reply->getVar('formid')).", 
                    replyemail = '".$myts->addSlashes($reply->getVar('replyemail', 'n'))."', 
                    replydate = ".intval($reply->getVar('replydate')).",
                    replyip = '".$reply->getVar('replyip', 'n')."',
                    vkey = '".$vkey."',
                    purged = ".intval($reply->getVar('purged'));

        if (!$this->db->query($sql)) {
            return false;
        }
        if ($reply->isNew()) {
            $reply->assignVar('replyid', $this->db->getInsertId());
        }
        return true;
    }

    /**
    * Set validated status to 1 (validated) or 0 (unvalidated)
    *
    * @param $status bool
    *
    * @return bool
    */
    function setValidated($reply, $status = false) {
        $valid = $status ? 1 : 0;
        $sql = "UPDATE ".$this->db->prefix($this->table)." SET
                    replyvalidated = ".$valid."
                WHERE replyid = ".$reply->getVar('replyid');
        return $this->db->queryF($sql);
    }

    /**
    * Validates a reply, used when the submitter follows the link in the validation email
    *
    * @param int $replyid ID of the reply
    * @param string $vkey md5 hash key of the reply
    *
    * @return bool
    */
    function validate($replyid, $vkey) {
        $reply =& $this->get($replyid);
        if ($reply->getVar('vkey') === $vkey) {
            return $this->setValidated($reply, true);
        }
        return false;
    }
    
    /**
    * Purges one or more replies based on criteria
    *
    * @param object $criteria {@link Criteria}
    *
    * @return bool
    */
    function purge($criteria) {
        $sql = "UPDATE ".$this->db->prefix($this->table)." SET purged=1";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
        }
        else {
            //avoid purging all replies with invalid criteria object
            return false;
        }
        return $this->db->query($sql);
    }
}
?>