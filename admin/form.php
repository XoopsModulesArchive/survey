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

xoops_cp_header();

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "newform";
$form_handler =& xoops_getmodulehandler('form', 'survey');
switch($op) {

    default:
    case "newform":
        survey_adminMenu(1);
        // get Create/Edit form for adding or editing forms
        $newform = $form_handler->getCEForm();
        $newform->display();
    break;
    
    case "editform":
        survey_adminMenu(1, '', $_REQUEST['formid']);
        $editform = $form_handler->getCEForm($_REQUEST['formid']);
        $editform->display();
    break;
    
    case "saveform":
        survey_adminMenu(1);
        $formid = isset($_REQUEST['formid']) ? $_REQUEST['formid'] : null;
        $startdate = isset($_REQUEST['form_start']) ? strtotime($_REQUEST['form_start']['date']) + $_REQUEST['form_start']['time'] : 0;
        $expiredate = isset($_REQUEST['form_expire']) ? strtotime($_REQUEST['form_expire']['date']) + $_REQUEST['form_expire']['time'] : 0;
        $restrictmode = isset($_REQUEST['form_restrictmode']) ? $_REQUEST['form_restrictmode'] : array();
        if ($form_handler->insert($formid, $_REQUEST['form_name'], $_REQUEST['form_desc'], $startdate, $expiredate, $_REQUEST['form_active'], $_REQUEST['form_submit'], $restrictmode)) {
            redirect_header('index.php', 3, _AM_SV_FORMSAVED);
        }
        echo "NO GO!!!!";        
    break;
    
    case "delform":
        survey_adminMenu(1, '', $_REQUEST['formid']);
        if (isset($_REQUEST['formid']) && isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            $form = $form_handler->get($_REQUEST['formid']);
            if (!$form_handler->delete($form)) {
                echo $form->getHtmlErrors();
            }
            else {
                redirect_header('index.php', 3, sprintf(_AM_SV_DELETE_SUCCESS, $form->f_name));
            }
        }
        else {
            xoops_confirm(array('formid' => $_REQUEST['formid'], 'ok' => 1, 'op' => 'delform'), 'form.php', _AM_SV_RUSURE_DELFORM);
        }
    break;
    
    case "cloneform":
        survey_adminMenu();
        $cloneform = $form_handler->get($_REQUEST['formid']);
        $form_handler->form_clone($cloneform);
        if (count($cloneform->_errors) > 0) {
            echo $cloneform->getHtmlErrors();
        }
        else {
            redirect_header('index.php', 3, sprintf(_AM_SV_CLONE_SUCCESS, $cloneform->f_name));
        }
        break;
}
xoops_cp_footer();
?>