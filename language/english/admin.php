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

define("_AM_SV_ABOUT", "About");
define("_AM_SV_PREFERENCES", "Preferences");
define("_AM_SV_GOMOD", "Go To Module");
define("_AM_SV_INDEX", "Index");
define("_AM_SV_FORMS", "Add New Survey");
define("_AM_SV_ELEMENTS", "Questions");
define("_AM_SV_MODADMIN", "Module Admin");
define("_AM_SV_FORM_EDIT", "Edit Survey Settings");
define("_AM_SV_EXPORT", "Export");
define("_AM_SV_CLONE", "Clone");
define("_AM_SV_DELETE", "Delete");
define("_AM_SV_SEARCH", "Search");

define("_AM_SV_SUBMIT", "Submit");
define("_AM_SV_EDIT_ELEMENTS", "Edit Questions");
define("_AM_SV_EDIT_ELEMENT", "Edit Question");
define("_AM_SV_DELETE_ELEMENT", "Delete Questions");
define("_AM_SV_RUSURE_DELQUESTION", "Are you sure, you want to delete this question?");
define("_AM_SV_QUESTION_DELETED", "Question Deleted");

define('_AM_SV_FORM_NAME', "Survey Name");
define('_AM_SV_FORM_DESC', "Survey Description");
define('_AM_SV_FORM_START', "Survey start date");
define("_AM_SV_FORM_EXPIRE", "Survey expiration date");
define("_AM_SV_FORM_ACTIVE", "Survey active?");
define("_AM_SV_FORM_COUNT", "Replies");
define("_AM_SV_FORMSAVED", "Survey Saved Successfully");
define("_AM_SV_ACTIVE", "Active");
define("_AM_SV_INACTIVE", "Inactive");
define("_AM_SV_FORM_STARTDATE", "Start");
define("_AM_SV_FORM_EXPIREDATE", "Expires on");
define("_AM_SV_FORM_SUBMIT_MESSAGE", "Message to be presented to the submitter after submissal");
define("_AM_SV_FORM_RESTRICTMODE", "Restrict Double-submissal");
define("_AM_SV_FORM_RES_EMAIL", "Validated Email Address");
define("_AM_SV_FORM_RES_COOKIE", "Cookie");
define("_AM_SV_FORM_RES_IP", "Store IP");

define("_AM_SV_QUESTION", "Question");
define("_AM_SV_CAPTION", "Caption");
define("_AM_SV_DESCRIPTION", "Description");
define("_AM_SV_REQUIRED", "Required?");
define("_AM_SV_DISPLAY", "Display?");
define("_AM_SV_ELE_ADD_OPT", "Add %s Options");
define("_AM_SV_ELE_OPT", "Element Options");
define("_AM_SV_ELE_OPT_DESC", "These options can be selected when answering the question");
define("_AM_SV_ELE_ADD_OPT_SUBMIT", "Submit options");
define("_AM_SV_ELE_QUES", "Questions");
define("_AM_SV_ELE_QUES_DESC", "These questions will be listed with a radio button for each option");

define("_AM_SV_ELE_SIZE", "Size");
define("_AM_SV_ELE_MULTIPLE", "Multiple Answers?");
define("_AM_SV_ELE_MAX_LENGTH", "Maximum Length");

define("_AM_SV_ELE_DEFAULT", "Default Answer");

define("_AM_SV_ELE_ROWS", "Rows");
define("_AM_SV_ELE_COLS", "Columns");

define("_AM_SV_OPTION_COUNT", "Number of Options");
define("_AM_SV_QUESTION_COUNT", "Number of Questions");
define("_AM_SV_ADDOTHEROPTION", "Add 'other' option to question");

define("_AM_SV_FORM_ADDELEMENT", "Add Question To Survey");
define("_AM_SV_FORM_REORDERED", "Survey Reordered");
define("_AM_SV_GO", "Go");
define("_AM_SV_ELE_TEXTDHTML", "Dhtml Text Area");
define("_AM_SV_ELE_TEXTAREA", "Plain Text Area");
define("_AM_SV_ELE_TEXTBOX", "Text Line");
define("_AM_SV_ELE_SELECT", "Select Box");
define("_AM_SV_ELE_RADIO", "Radio Buttons");
define("_AM_SV_ELE_RADIOYN", "Yes/No Buttons");
define("_AM_SV_ELE_LABEL", "Read-only Textarea");
define("_AM_SV_ELE_CHECKBOX", "Checkboxes");
define("_AM_SV_ELE_DATE", "Date Selector");
define("_AM_SV_ELE_PERSONDATA", "Person Data Input Fields");
define("_AM_SV_ELE_RADIOGROUP", "Radio Group");

define('_AM_SV_SHOW_NAME', "Show Name?");
define('_AM_SV_SHOW_POSITION', "Show Position?");
define('_AM_SV_SHOW_COMPANY', "Show Company?");
define('_AM_SV_SHOW_ADDRESS', "Show Address?");
define('_AM_SV_SHOW_POSTAL', "Show Postal Code?");
define('_AM_SV_SHOW_CITY', "Show City?");

define("_AM_SV_EXP_CANNOTCREATEEXPORTFILE", "Cannot create %s file");
define("_AM_SV_EXP_CANNOTWRITEEXPORTFILE", "Cannot write to %s file");
define("_AM_SV_EXP_CANNOTCLOSEEXPORTFILE", "Cannot close %s file");
define("_AM_SV_EXP_FORMATFILENOEXIST", "Format file for %s does not exist");
define("_AM_SV_EXP_PURGEREPLIES", "Purge Replies After Exportation?");
define("_AM_SV_EXP_REPLYID", "Replyid");
define("_AM_SV_EXP_REPLYEMAIL", "Email");
define("_AM_SV_EXP_REPLYDATE", "Reply Date");
define("_AM_SV_EXP_FORM", "Survey");
define("_AM_SV_EXP_FORMAT", "Format");
define("_AM_SV_EXP_ONLYNEW", "Only export replies since last purge");
define("_AM_SV_EXP_NOANSWERS", "There are no answers to this survey");

define("_AM_SV_CLONE_COULDNOT", 'Could not clone %s');
define("_AM_SV_CLONE_SUCCESS", "%s Cloned Successfully");

define("_AM_SV_SEARCH_KEYWORD", "Keyword");
define("_AM_SV_SEARCH_ACTIVE", "Only Active");

define("_AM_SV_RUSURE_DELFORM", "Are you sure you want to delete this survey AND ALL ITS REPLIES! ?");
define("_AM_SV_DELETE_SUCCESS", "%s Deleted Successfully");
define("_AM_SV_UPDATE_REPLY_FAILED", "Update of Reply %u Failed!");
?>