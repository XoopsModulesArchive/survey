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
include 'header.php';

$formid = isset($_REQUEST['formid']) ? intval($_REQUEST['formid']) : 0;
$form_handler =& xoops_getmodulehandler('form', 'survey');
$show_results = false;
if (isset($_REQUEST['submit']) && isset($_REQUEST['format']) && $formid > 0) {
    $form =& $form_handler->get($formid);
    if (!$outcome = $form->export($_REQUEST['format'], $_REQUEST['onlynew'])) {
        $errors = $form->getErrors();
    }
    else {
        if (isset($_REQUEST['purge']) && $_REQUEST['purge'] == 1) {
            $form->purgeReplies();
        }
        if (strtolower($_REQUEST['format']) != "screen") {
            $xoopsTpl->xoops_setDebugging(false);
            header('Content-type: text/xml');
            header('Content-Disposition: attachment; filename="'.$outcome.'"');
            $xoopsTpl->display("db:survey_exportTo".ucfirst($_REQUEST['format']).".html");
            exit();
        }
        else {
            $show_results = true;
        }
    }
}
    xoops_cp_header();
    survey_adminMenu (3, '', $formid);
    
    if (isset($errors) && count($errors) > 0) {
        xoops_error(implode('<br />', $errors));
    }
    
    if ($show_results) {
        $xoopsTpl->display("db:survey_exportToScreen.html");
    }
    
    $forms = $form_handler->getObjects(null, true, false);
    include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
    $export_form = new XoopsThemeForm(_AM_SV_EXPORT, 'exportform', 'export.php');
    $form_select = new XoopsFormSelect(_AM_SV_EXP_FORM, 'formid', $formid);
    $form_select->addOptionArray($forms);
    $export_form->addElement($form_select);

    $format_select = new XoopsFormSelect(_AM_SV_EXP_FORMAT, 'format');
    $format_select->addOption("Excel", "Microsoft Excel");
    $format_select->addOption("XML");
    $format_select->addOption("Screen", "Screen");
    $export_form->addElement($format_select);

    $export_form->addElement(new XoopsFormRadioYN(_AM_SV_EXP_PURGEREPLIES, 'purge', 0));
    $export_form->addElement(new XoopsFormRadioYN(_AM_SV_EXP_ONLYNEW, 'onlynew', 1));
    
    $export_form->addElement(new XoopsFormButton('', 'submit', _AM_SV_SUBMIT, 'submit'));

    $export_form->display();

xoops_cp_footer();
?>