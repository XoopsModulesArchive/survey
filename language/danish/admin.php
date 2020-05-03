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

define("_AM_SV_ABOUT", "Om");
define("_AM_SV_PREFERENCES", "Indstillinger");
define("_AM_SV_GOMOD", "Gå Til Modulet");
define("_AM_SV_INDEX", "Forside");
define("_AM_SV_FORMS", "Tilføj Nyt Skema");
define("_AM_SV_ELEMENTS", "Spørgsmål");
define("_AM_SV_MODADMIN", "Moduladministration");
define("_AM_SV_FORM_EDIT", "Rediger Skema Indstillinger");
define("_AM_SV_EXPORT", "Eksporter");
define("_AM_SV_CLONE", "Klon");
define("_AM_SV_DELETE", "Slet");
define("_AM_SV_SEARCH", "Søg");

define("_AM_SV_SUBMIT", "Send");
define("_AM_SV_EDIT_ELEMENTS", "Rediger Spørgsmål");
define("_AM_SV_EDIT_ELEMENT", "Rediger Spørgsmål");
define("_AM_SV_DELETE_ELEMENT", "Set Spørgsmål");
define("_AM_SV_RUSURE_DELQUESTION", "Er du sikker på, at du vil slette dette spørgsmål?");
define("_AM_SV_QUESTION_DELETED", "Spørgsmål Slettet");

define('_AM_SV_FORM_NAME', "Spørgeskema Navn");
define('_AM_SV_FORM_DESC', "Spørgeskema Beskrivelse");
define('_AM_SV_FORM_START', "Skema start dato");
define("_AM_SV_FORM_EXPIRE", "Skema udløbsdato");
define("_AM_SV_FORM_ACTIVE", "Spørgeskema Aktivt?");
define("_AM_SV_FORM_COUNT", "Svar");
define("_AM_SV_FORMSAVED", "Spørgeskema Gemt");
define("_AM_SV_ACTIVE", "Aktiv");
define("_AM_SV_INACTIVE", "Inaktiv");
define("_AM_SV_FORM_STARTDATE", "Start");
define("_AM_SV_FORM_EXPIREDATE", "Udløber");
define("_AM_SV_FORM_SUBMIT_MESSAGE", "Meddelelse, som vises til udfylderen efter spørgeskemaets udfyldelse");
define("_AM_SV_FORM_RESTRICTMODE", "Begræns Dobbelt-udfyldelse");
define("_AM_SV_FORM_RES_EMAIL", "Valideret Email Adresse");
define("_AM_SV_FORM_RES_COOKIE", "Cookie");
define("_AM_SV_FORM_RES_IP", "Gem IP");

define("_AM_SV_QUESTION", "Spørgsmål");
define("_AM_SV_CAPTION", "Hovedspørgsmål");
define("_AM_SV_DESCRIPTION", "Beskrivelse");
define("_AM_SV_REQUIRED", "Svar Krævet?");
define("_AM_SV_DISPLAY", "Vis?");
define("_AM_SV_ELE_ADD_OPT", "Tilføj %s Svarmuligheder");
define("_AM_SV_ELE_OPT", "Svarmuligheder");
define("_AM_SV_ELE_OPT_DESC", "Disse muligheder kan vælges som besvarelse af spørgsmålet");
define("_AM_SV_ELE_ADD_OPT_SUBMIT", "Gem Svarmuligheder");
define("_AM_SV_ELE_QUES", "Spørgsmål");
define("_AM_SV_ELE_QUES_DESC", "Disse spørgsmål vil blive vist med en radio-knap for hver svarmulighed");

define("_AM_SV_ELE_SIZE", "Størrelse");
define("_AM_SV_ELE_MULTIPLE", "Multi-Svar?");
define("_AM_SV_ELE_MAX_LENGTH", "Maksimum Længde");

define("_AM_SV_ELE_DEFAULT", "Standard Svar");

define("_AM_SV_ELE_ROWS", "Rækker");
define("_AM_SV_ELE_COLS", "Kolonner");

define("_AM_SV_OPTION_COUNT", "Antal Svarmuligheder");
define("_AM_SV_QUESTION_COUNT", "Antal Spørgsmål");
define("_AM_SV_ADDOTHEROPTION", "Tilføj 'Anden' mulighed til spørgsmålet");

define("_AM_SV_FORM_ADDELEMENT", "Tilføj Spørgsmål til Spørgeskema");
define("_AM_SV_FORM_REORDERED", "Spørgeskema Omrokeret");
define("_AM_SV_GO", "Vælg");
define("_AM_SV_ELE_TEXTDHTML", "Dhtml Tekstfelt");
define("_AM_SV_ELE_TEXTAREA", "Tekstfelt");
define("_AM_SV_ELE_TEXTBOX", "Tekstlinje");
define("_AM_SV_ELE_SELECT", "Rullemenu");
define("_AM_SV_ELE_RADIO", "Radioknapper");
define("_AM_SV_ELE_RADIOYN", "Ja/Nej knapper");
define("_AM_SV_ELE_LABEL", "Skrivebeskyttet Tekstfelt");
define("_AM_SV_ELE_CHECKBOX", "Checkbokse");
define("_AM_SV_ELE_DATE", "Datovælger");
define("_AM_SV_ELE_PERSONDATA", "Persondata felter");
define("_AM_SV_ELE_RADIOGROUP", "Radio Gruppe");

define('_AM_SV_SHOW_NAME', "Vis Navn?");
define('_AM_SV_SHOW_POSITION', "Vis Stilling?");
define('_AM_SV_SHOW_COMPANY', "Vis Firma?");
define('_AM_SV_SHOW_ADDRESS', "Vis Adresse?");
define('_AM_SV_SHOW_POSTAL', "Vis Postnr.?");
define('_AM_SV_SHOW_CITY', "Vis By?");

define("_AM_SV_EXP_CANNOTCREATEEXPORTFILE", "Kan ikke oprette %s filen ");
define("_AM_SV_EXP_CANNOTWRITEEXPORTFILE", "Kan ikke skrive til %s filen");
define("_AM_SV_EXP_CANNOTCLOSEEXPORTFILE", "Kan ikke lukke forbindelsen til %s filen");
define("_AM_SV_EXP_FORMATFILENOEXIST", "Format fil for %s eksisterer ikke");
define("_AM_SV_EXP_PURGEREPLIES", "Marker Svar som fjernede efter eksport?");
define("_AM_SV_EXP_REPLYID", "Svarid");
define("_AM_SV_EXP_REPLYEMAIL", "Email");
define("_AM_SV_EXP_REPLYDATE", "Svar Dato");
define("_AM_SV_EXP_FORM", "Spørgeskema");
define("_AM_SV_EXP_FORMAT", "Format");
define("_AM_SV_EXP_ONLYNEW", "Eksporter kun nye svar (ikke markeret som fjernet)");
define("_AM_SV_EXP_NOANSWERS", "Der er ingen besvarelser af dette spørgeskema");

define("_AM_SV_CLONE_COULDNOT", 'Kunne ikke klone %s');
define("_AM_SV_CLONE_SUCCESS", "%s Klonet");

define("_AM_SV_SEARCH_KEYWORD", "Søgeord");
define("_AM_SV_SEARCH_ACTIVE", "Kun Aktive");

define("_AM_SV_RUSURE_DELFORM", "Er du sikker på, at du vil slette dette spørgeskema OG ALLE BESVARELSER AF SKEMAET! ?");
define("_AM_SV_DELETE_SUCCESS", "%s Slettet");
define("_AM_SV_UPDATE_REPLY_FAILED", "Opdatering af svar %u fejlede!");
?>