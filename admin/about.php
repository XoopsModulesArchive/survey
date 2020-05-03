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


$module_handler = &xoops_gethandler('module');
$versioninfo = &$module_handler->get($xoopsModule->getVar('mid'));

survey_adminMenu(-1, _AM_SV_ABOUT . " " . $versioninfo->getInfo('name'));


echo "<img src='" . XOOPS_URL . "/modules/survey/" . $versioninfo->getInfo('image') . "' alt='' hspace='0' vspace='0' align='left' style='margin-right: 10px;'/></a>";
echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo->getInfo('name') . " version " . $versioninfo->getInfo('version') . " (" . $versioninfo->getInfo('status_version') . ")</div>";
if ($versioninfo->getInfo('author_realname') != '') {
    $author_name = $versioninfo->getInfo('author') . " (" . $versioninfo->getInfo('author_realname') . ")";
} else {
    $author_name = $versioninfo->getInfo('author');
} 

echo "<div style = 'line-height: 16px; font-weight: bold; display: block;'>" . _AM_SV_BY . " " . $author_name;
echo "</div>";
echo "<div style = 'line-height: 16px; display: block;'>" . $versioninfo->getInfo('license') . "</div>\n";

// Author Information
echo "<br /><table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
echo "<tr>";
echo "<td colspan='2' class='bg3' align='left'><b>" . _MI_SV_AUTHOR_INFO . "</b></td>";
echo "</tr>";

If ($author_name != '') {
    echo "<tr>";
    echo "<td class='head' width = '150px' align='left'>" . _MI_SV_AUTHOR_NAME . "</td>";
    echo "<td class='even' align='left'>" . $author_name . "</td>";
    echo "</tr>";
} 
If ($versioninfo->getInfo('author_website_url') != '') {
    echo "<tr>";
    echo "<td class='head' width = '150px' align='left'>" . _MI_SV_AUTHOR_WEBSITE . "</td>";
    echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('author_website_url') . "' target='blank'>" . $versioninfo->getInfo('author_website_name') . "</a></td>";
    echo "</tr>";
} 
If ($versioninfo->getInfo('author_email') != '') {
    echo "<tr>";
    echo "<td class='head' width = '150px' align='left'>" . _MI_SV_AUTHOR_EMAIL . "</td>";
    echo "<td class='even' align='left'><a href='mailto:" . $versioninfo->getInfo('author_email') . "'>" . $versioninfo->getInfo('author_email') . "</a></td>";
    echo "</tr>";
} 
If ($versioninfo->getInfo('credits') != '') {
    echo "<tr>";
    echo "<td class='head' width = '150px' align='left'>" . _MI_SV_AUTHOR_CREDITS . "</td>";
    echo "<td class='even' align='left'>" . $versioninfo->getInfo('credits') . "</td>";
    echo "</tr>";
} 

echo "</table>";
echo "<br />\n";
// Module Developpment information
echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
echo "<tr>";
echo "<td colspan='2' class='bg3' align='left'><b>" . _MI_SV_MODULE_INFO . "</b></td>";
echo "</tr>";

If ($versioninfo->getInfo('status') != '') {
    echo "<tr>";
    echo "<td class='head' width = '200' align='left'>" . _MI_SV_MODULE_STATUS . "</td>";
    echo "<td class='even' align='left'>" . $versioninfo->getInfo('status') . "</td>";
    echo "</tr>";
} 

If ($versioninfo->getInfo('demo_site_url') != '') {
    echo "<tr>";
    echo "<td class='head' align='left'>" . _MI_SV_MODULE_DEMO . "</td>";
    echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('demo_site_url') . "' target='blank'>" . $versioninfo->getInfo('demo_site_name') . "</a></td>";
    echo "</tr>";
} 

If ($versioninfo->getInfo('support_site_url') != '') {
    echo "<tr>";
    echo "<td class='head' align='left'>" . _MI_SV_MODULE_SUPPORT . "</td>";
    echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('support_site_url') . "' target='blank'>" . $versioninfo->getInfo('support_site_name') . "</a></td>";
    echo "</tr>";
} 

echo "</table>";
// Warning
If ($versioninfo->getInfo('warning') != '') {
    echo "<br />\n";
    echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
    echo "<tr>";
    echo "<td class='bg3' align='left'><b>" . _MI_SV_MODULE_DISCLAIMER . "</b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='even' align='left'>" . $versioninfo->getInfo('warning') . "</td>";
    echo "</tr>";

    echo "</table>";
} 
// Author's note
If ($versioninfo->getInfo('author_word') != '') {
    echo "<br />\n";
    echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
    echo "<tr>";
    echo "<td class='bg3' align='left'><b>" . _MI_SV_AUTHOR_WORD . "</b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='even' align='left'>" . $versioninfo->getInfo('author_word') . "</td>";
    echo "</tr>";

    echo "</table>";
} 

// Version History
If ($versioninfo->getInfo('version_history') != '') {
    echo "<br />\n";
    echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
    echo "<tr>";
    echo "<td class='bg3' align='left'><b>" . _MI_SV_VERSION_HISTORY . "</b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='even' align='left'>" . $versioninfo->getInfo('version_history') . "</td>";
    echo "</tr>";

    echo "</table>";
} 


xoops_cp_footer();


?>