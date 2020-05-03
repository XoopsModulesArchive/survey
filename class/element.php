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
require_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once('elements/checkbox.php');
include_once('elements/surveydate.php');
include_once('elements/label.php');
include_once('elements/persondata.php');
include_once('elements/radio.php');
include_once('elements/select.php');
include_once('elements/textarea.php');
include_once('elements/textbox.php');
include_once('elements/textdhtml.php');
include_once('elements/radiogroup.php');
/**
 * Class for Survey Form Elements
 * @author Jan Pedersen
 * @copyright copyright (c) 2004 IDG.dk
 * @package Survey
 */
class SurveyElementHandler extends XoopsObjectHandler {
    /**
    * @param string $elementName Name of the element to instantiate
    *
    * @return {@link XoopsFormElement} derived object
    */
    function getElement($elementName, $question) {
        $classname = ucfirst($elementName."Element");
        return new $classname($question);
    }
}
?>