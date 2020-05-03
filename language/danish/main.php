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
define("_MA_SV_SUBMIT", "Send");
define("_MA_SV_STANDARD_REPLY", "Tak for Deres besvarelse");

define("_MA_SV_ERRORS_ENCOUNTERED", "Fejl :");
define("_MA_SV_ERR_FORMNOEXIST", "Spørgeskema eksisterer ikke");
define("_MA_SV_ERR_NOFORMS_EXIST", "Ingen aktive spørgeskema-undersøgelser");
define("_MA_SV_ERR_VALIDATED_EMAIL_FAIL", "Email Adresse Er Allerede Registreret For Dette Spørgeskema");
define("_MA_SV_ERR_COOKIE_EXISTS", "De har allerede besvaret dette spørgeskema");
define("_MA_SV_ERR_IP_ALREADY_REGISTERED", "De har allerede besvaret dette spørgeskema");
define("_MA_SV_ERR_INVALID_EMAIL", "Ugyldig Email Adresse");

define("_MA_SV_NAME", "Navn <br />");
define("_MA_SV_POSITION", "Stilling<br />");
define("_MA_SV_COMPANY", "Firma<br />");
define("_MA_SV_ADDRESS", "Adresse<br />");
define("_MA_SV_POSTAL", "Postnr<br />");
define("_MA_SV_EMAIL", "Email Adresse<br />");
define("_MA_SV_CITY", "By<br />");

define("_MA_SV_VALIDATION_SUBJECT", "Validering af spørgeskema besvarelse ");

define("_MA_SV_OTHER", "Andet");
?>