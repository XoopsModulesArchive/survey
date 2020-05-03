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

$form_handler =& xoops_getmodulehandler('form', 'survey');

if (isset($_REQUEST['submit']) ) {
    $andor = isset($_REQUEST['andor']) ? $_REQUEST['andor'] : "AND";
    $keywords = $_REQUEST['keyword'];
    $active = intval($_REQUEST['active']) == 1 ? 1 : null;
    if ($keywords != "" ) {
        $formids = $form_handler->search($keyword, $andor);
    }
}
elseif (isset($_SESSION['sur_search'])) {
    $formids = $_SESSION['sur_search'];
}

survey_adminMenu();
//Make semi-empty criteria for setting start, limit and ordering
$criteria = new CriteriaCompo(new Criteria('formid', 0, "!="));

//If search results, add them to the criteria
if(isset($formids)) {
    //if formids is empty
    if ($formids == array()) {
        //set formids to an invalid form ID to avoid the query failing but at the same
        //time avoiding returning every form in the database
        $formids = array(0);
    }
    $criteria->add(new Criteria('formid', "(".implode(',', $formids).")", 'IN'));
}
//If active-only is selected
if (isset($active) ) {
    $criteria->add(new Criteria('f_isactive', $active));
}

$criteria->setLimit($GLOBALS['xoopsModuleConfig']['max_forms_list_admin']);
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$criteria->setStart($start);
$criteria->setSort('f_startdate');
$criteria->setOrder('DESC');

$forms = $form_handler->getObjects($criteria);
$reply_handler =& xoops_getmodulehandler('reply', 'survey');
$replyCriteria = new CriteriaCompo(new Criteria('formid', "(".implode(',', array_keys($forms)).")", 'IN'));
$replyCriteria->add(new Criteria('replyvalidated', 1));
$replycount = $reply_handler->getCountByFormId($replyCriteria);

foreach ($forms as $form) {
    $form_arr = $form->toArray();
    $form_arr['reply_count'] = isset($replycount[$form->formid]) ? $replycount[$form->formid] : 0;
    $xoopsTpl->append('forms', $form_arr);
}
//page navigation
include_once(XOOPS_ROOT_PATH."/class/pagenav.php");
$nav = new XoopsPageNav($form_handler->getObjectsCount(), $GLOBALS['xoopsModuleConfig']['max_forms_list_admin'], $start);
$xoopsTpl->assign('formnav', $nav->renderNav());

// Search/Filter form
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
$search_form = new XoopsThemeForm("", "search_form", $_SERVER['REQUEST_URI']);
if (!isset($keywords)) {
    $keywords = "";
}
if (!isset($active) ) {
    $active = 0;
}
if (!isset($andor) ) {
    $andor = "AND";
}
include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/search.php";
$search_form->addElement(new XoopsFormText(_AM_SV_SEARCH_KEYWORD, 'keyword', $GLOBALS['xoopsModuleConfig']['t_width'], $GLOBALS['xoopsModuleConfig']['t_max'], $keywords));
$type_select = new XoopsFormSelect(_SR_TYPE, "andor", $andor);
$type_select->addOptionArray(array("AND"=>_SR_ALL, "OR"=>_SR_ANY, "exact"=>_SR_EXACT));
$search_form->addElement($type_select);
$search_form->addElement(new XoopsFormRadioYN(_AM_SV_SEARCH_ACTIVE, 'active', $active));
$search_form->addElement(new XoopsFormButton('', 'submit', _AM_SV_SEARCH, 'submit'));
$search_form->assign($xoopsTpl);

$xoopsTpl->display('db:survey_admin_index.html');

xoops_cp_footer();

?>