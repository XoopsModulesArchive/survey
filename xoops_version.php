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
$modversion['name'] = _MI_SV_NAME;
$modversion['version'] = 0.6;
$modversion['status_version'] = "Beta";
$modversion['description'] = _MI_SV_DESC;
$modversion['author'] = "Mithrandir";
$modversion['author_realname'] = "Jan Pedersen";
$modversion['author_website_url'] = "http://www.idg.dk";
$modversion['author_website_name'] = "International Data Group";
$modversion['author_email'] = "Mithrandir@xoops.org";
$modversion['credits'] = "<a href='http://www.idg.dk/'>IDG</a> - brandycoke.com <br />Hsalazar, Marcan";
$modversion['author_word'] = "An enormous thank you to Horacio Salazar and Marcan for developing the excellent module admin navigation
                              and this about-page. Also thank you to Marcan for his visually pleasing code, from which I have stolen a lot<br /><br />
                          Thank you to brandycoke.com for making Liaise and Formulaire modules on which this module is based";
$modversion['help'] = "";
$modversion['license'] = "GNU GPL";
$modversion['official'] = 0;
$modversion['status'] = "Free";

$modversion['image'] = "images/survey.png";
$modversion['dirname'] = "survey";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "survey_form";
$modversion['tables'][1] = "survey_question";
$modversion['tables'][2] = "survey_reply";
$modversion['tables'][3] = "survey_answer";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu -- content in main menu block
$modversion['hasMain'] = 1;

// Install Script
$modversion['onInstall'] = "include/install.php";
$modversion['onUninstall'] = "include/install.php";

// Templates
$modversion['templates'][1]['file'] = 'survey.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'survey_admin_index.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'survey_list.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = "survey_exportToExcel.html";
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = "survey_exportToXML.html";
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = "survey_exportToScreen.html";
$modversion['templates'][6]['description'] = '';

// $xoopsModuleConfig['t_width']
$modversion['config'][1]['name'] = 't_width';
$modversion['config'][1]['title'] = '_MI_SV_TEXT_WIDTH';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = '30';

// $xoopsModuleConfig['t_max']
$modversion['config'][2]['name'] = 't_max';
$modversion['config'][2]['title'] = '_MI_SV_TEXT_MAX';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = '255';

// $xoopsModuleConfig['ta_rows']
$modversion['config'][3]['name'] = 'ta_rows';
$modversion['config'][3]['title'] = '_MI_SV_TAREA_ROWS';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = '5';

// $xoopsModuleConfig['ta_cols']
$modversion['config'][4]['name'] = 'ta_cols';
$modversion['config'][4]['title'] = '_MI_SV_TAREA_COLS';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = '35';

// $xoopsModuleConfig['max_forms_list_admin']
$modversion['config'][5]['name'] = 'max_forms_list_admin';
$modversion['config'][5]['title'] = '_MI_SV_FORM_COUNT_IN_ADMIN';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'select';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['options'] = array(5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 35 => 35, 50 => 50);
$modversion['config'][5]['default'] = '20';

// $xoopsModuleConfig['delimiter']
$modversion['config'][6]['name'] = 'delimiter';
$modversion['config'][6]['title'] = '_MI_SV_FORM_DELIMITER';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'select';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['options'] = array(_MI_SV_LINEBREAK => 0, _MI_SV_SPACE => 1);
$modversion['config'][6]['default'] = 0;
?>