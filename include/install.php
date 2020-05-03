<?php 
function xoops_module_install_survey($module) {
    @umask(0000);
    mkdir(XOOPS_UPLOAD_PATH."/survey", 0777);
    @chmod(XOOPS_UPLOAD_PATH."/survey", 0777);
}
function xoops_module_uninstall_survey($module) {
    rmdirr(XOOPS_UPLOAD_PATH."/survey");
}


/**
 * Delete a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.2
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }
 
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
 
    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Recurse
        rmdirr("$dirname/$entry");
    }
 
    // Clean up
    $dir->close();
    return rmdir($dirname);
}
 
?>