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
include_once("header.php");
include_once(XOOPS_ROOT_PATH."/modules/survey/language/".$GLOBALS['xoopsConfig']['language']."/main.php");
xoops_cp_header();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "index";

if (isset($_REQUEST['addopt']) && $_REQUEST['addopt'] > 0) {
    $op = isset($_REQUEST['id']) ? "edit" : "new";
}

switch($op) {
    case "new":
        $formid = isset($_REQUEST['formid']) ? intval($_REQUEST['formid']) : redirect_header('index.php', 2, _AM_SV_NOFORM);
        $type = isset($_REQUEST['ele_type']) ? $_REQUEST['ele_type'] : redirect_header('index.php', 2, _AM_SV_NOELEMENT);
        survey_adminMenu(2, '', $formid);
        $question_handler =& xoops_getmodulehandler('question', 'survey');
        $form =& $question_handler->getCEForm($formid, $type);
        $form->display();
    break;
    
    case "save":
        $question_handler =& xoops_getmodulehandler('question', 'survey');
        if (isset($_POST['qid'])) {
            $question =& $question_handler->get(intval($_POST['qid']));
        }
        else {
            $question =& $question_handler->create();
            $question->setVar('ele_type', $_POST['ele_type']);
            $question->setVar('formid', $_POST['formid']);
        }
        if(!$question->store()) {
            echo "something is wrong";
        }
        header('location:elements.php?op=edit&id='.$question->getVar('qid'));
        break;
        
    case "edit":
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : redirect_header('index.php', 3, _AM_SV_NOELEMENTFOUND);
        $question_handler =& xoops_getmodulehandler('question', 'survey');
        $form =& $question_handler->getCEForm($id);
        $form->display();
    break;
    
    case "del":
        $question_handler =& xoops_getmodulehandler('question', 'survey');
        if (!isset($_POST['ok'])) {
            xoops_confirm(array('id' => $_REQUEST['id'], 'op' => 'del', 'ok' => 1), 'elements.php', _AM_SV_RUSURE_DELQUESTION);
            break;
        }
        $question =& $question_handler->get($_REQUEST['id']);
        if ($question_handler->delete($question)) {
            redirect_header('elements.php?formid='.$question->getVar('formid'), 3, _AM_SV_QUESTION_DELETED);
        }
    break;
    
    case "reorder":
        if (!isset($_REQUEST['elementsorder']) || count($_REQUEST['elementsorder']) == 0) {
            header("location: index.php");
            exit();
        }
        $errors = 0;
        $question_handler =& xoops_getmodulehandler('question', 'survey');
        foreach ($_REQUEST['elementsorder'] as $qid => $order) {
            $question =& $question_handler->get($qid);
            $question->setVar('q_order', intval($order));
            if (!isset($formid)) {
                $formid = $question->getVar('formid');
            }
            if (!$question_handler->insert($question)) {
                $errors++;
            }
        }
        if ($errors === 0) {
            redirect_header('elements.php?formid='.$formid, 3, _AM_SV_FORM_REORDERED);
        }
    break;
       
    default:
    case "index":
        $formid = isset($_REQUEST['formid']) ? intval($_REQUEST['formid']) : redirect_header('index.php', 2, _AM_SV_NOFORM);
        survey_adminMenu(2, '', $formid);
        $form_handler =& xoops_getmodulehandler('form', 'survey');
        $form =& $form_handler->get($formid);
        $form->loadQuestions();
        echo $form->displayForEdit();
    break;
}

xoops_cp_footer();

?>