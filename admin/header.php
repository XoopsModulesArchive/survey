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

include_once("../../../include/cp_header.php");
if (!isset($xoopsTpl)) {
    include_once(XOOPS_ROOT_PATH."/class/template.php");
    $xoopsTpl = new XoopsTpl();
    //$xoopsTpl->xoops_setDebugging(true);
}
function survey_adminMenu ($currentoption = 0, $breadcrumb = '', $formid = 0)
{
    global $xoopsModule;

    /* Nice buttons styles */
    echo "
    	<style type='text/css'>
    	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		#buttonbar li { display:inline; margin:0; padding:0; }
		#buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
		#buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
		/* Commented Backslash Hack hides rule from IE5-Mac \*/
		#buttonbar a span {float:none;}
		/* End IE5-Mac hack */
		#buttonbar a:hover span { color:#333; }
		#buttonbar #current a { background-position:0 -150px; border-width:0; }
		#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		#buttonbar a:hover { background-position:0% -150px; }
		#buttonbar a:hover span { background-position:100% -150px; }
		</style>
    ";

    // global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    global $xoopsModule, $xoopsConfig;
    $myts = &MyTextSanitizer::getInstance();

    $tblColors = Array();
    $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
    $tblColors[$currentoption] = 'current';
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/english/modinfo.php';
    }

    echo "<div id='buttontop'>";
    echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
    //echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _AM_SF_OPTS . "</a> | <a href=\"import.php\">" . _AM_SF_IMPORT . "</a> | <a href=\"../index.php\">" . _AM_SF_GOMOD . "</a> | <a href=\"../help/index.html\" target=\"_blank\">" . _AM_SF_HELP . "</a> | <a href=\"about.php\">" . _AM_SF_ABOUT . "</a></td>";
    echo "<td style='width: 60%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'><a class='nobutton' href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "'>" . _AM_SV_PREFERENCES . "</a> | <a href='" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php'>" . _AM_SV_GOMOD . "</a> | <a href='" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/about.php'>" . _AM_SV_ABOUT . "</a></td>";
    echo "<td style='width: 40%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($xoopsModule->name()) . " " . _AM_SV_MODADMIN . "</b> " . $breadcrumb . "</td>";
    echo "</tr></table>";
    echo "</div>";

    echo "<div id='buttonbar'>";
    echo "<ul>";
    echo "<li id='" . $tblColors[0] . "'><a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/index.php\"><span>" . _AM_SV_INDEX . "</span></a></li>";
    if ($formid > 0) {
        echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/form.php?op=editform&amp;formid=".$formid."\"><span>" . _AM_SV_FORM_EDIT . "</span></a></li>";
        echo "<li id='" . $tblColors[2] . "'><a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/elements.php?formid=".$formid."\"><span>" . _AM_SV_EDIT_ELEMENTS . "</span></a></li>";
        echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/export.php?formid=".$formid."\"><span>" . _AM_SV_EXPORT . "</span></a></li>";
    }
    else {
        echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/form.php?op=newform\"><span>" . _AM_SV_FORMS . "</span></a></li>";
        echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/export.php\"><span>" . _AM_SV_EXPORT . "</span></a></li>";
    }

    echo "</ul></div><div style=\"clear:both;\"></div>";
}
?>