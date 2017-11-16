<?php
require 'index.php.user.php';
$site_name = "Leefine's Doc";
$root_path = $_SERVER['DOCUMENT_ROOT'];
$root_url = '';
$http_host = $_SERVER['HTTP_HOST'];
$iconv_input_encoding = 'CP1251';
@set_time_limit(600);
$datetime_format = 'Y.m.d H:i';
date_default_timezone_set('Asia/Shanghai');
ini_set('default_charset', 'UTF-8');
if (version_compare(PHP_VERSION, '5.6.0', '<') && function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}
if (function_exists('mb_regex_encoding')) {
    mb_regex_encoding('UTF-8');
}
session_cache_limiter('');
session_name('filemanager');
session_start();
$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>Root path \"{$root_path}\" not found!</h1>";
    exit;
}
// clean $root_url
$root_url = fm_clean_path($root_url);
defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);
// logout
if (isset($_GET['logout'])) {
    unset($_SESSION['logged']);
    fm_redirect(FM_SELF_URL);
}
if (isset($_GET['getico'])) {
 $image = '';

if($_GET['getico']=='ico')
    $image = 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkZGNjVGQUM4QUVFRTExRTdCMkFBRDYzMjI1RkI0OENBIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkZGNjVGQUM5QUVFRTExRTdCMkFBRDYzMjI1RkI0OENBIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RkY2NUZBQzZBRUVFMTFFN0IyQUFENjMyMjVGQjQ4Q0EiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RkY2NUZBQzdBRUVFMTFFN0IyQUFENjMyMjVGQjQ4Q0EiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6ZoU2KAAANRklEQVR42uxae3BU1Rn/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';


else if($_GET['getico']=='plusbottom')
$image = 'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkZFODY1NUIwQjk3MTExRTdCNzc4QTFCMEUzMTFCNjBEIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkZFODY1NUIxQjk3MTExRTdCNzc4QTFCMEUzMTFCNjBEIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RkU4NjU1QUVCOTcxMTFFN0I3NzhBMUIwRTMxMUI2MEQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RkU4NjU1QUZCOTcxMTFFN0I3NzhBMUIwRTMxMUI2MEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6gp6krAAAAbUlEQVR42mL4//8/Ay7c0NDwH588MmYEEdQATPgkGxsbibaFai5iIdUF9fX1jAQNwqeQkEV4w4iRkfE/VQKb4lgDuQTmGmQ2uhdRaHwJEAj+E5tAaes1GADaxEhWOiI1NdMkZdMnjIa2QQABBgDAaY3JRzlr6AAAAABJRU5ErkJggg==';

else if($_GET['getico']=='plus')
$image = 'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjAzOTBDRDEwQjk3MjExRTdCQjc5RjNGMEIxMkNGNTcyIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjAzOTBDRDExQjk3MjExRTdCQjc5RjNGMEIxMkNGNTcyIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MDM5MENEMEVCOTcyMTFFN0JCNzlGM0YwQjEyQ0Y1NzIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MDM5MENEMEZCOTcyMTFFN0JCNzlGM0YwQjEyQ0Y1NzIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6N7H+nAAAAbElEQVR42sxT0QrAIAjsxv5b/fJbPQRtMKvNoAMrMLzTrkQyvYWq0su3gbJE4PCSZjbMEqbonFUgIugW8i72iNwZAWDIsH+/WlFS1bTnZ4u33TNgBkcNura1isyETz6adfMSZ+/31/ZTdAkwAMxGmMWbybZ5AAAAAElFTkSuQmCC';


else if($_GET['getico']=='empty')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY5MUY2RTQwQjk3MTExRTc5QUFCRTU1RThDRDZDODI2IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY5MUY2RTQxQjk3MTExRTc5QUFCRTU1RThDRDZDODI2Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjkxRjZFM0VCOTcxMTFFNzlBQUJFNTVFOENENkM4MjYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjkxRjZFM0ZCOTcxMTFFNzlBQUJFNTVFOENENkM4MjYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4nsEruAAAAIUlEQVR42mL8//8/AzUAEwOVwKhBowaNGjRq0GAxCCDAALMHAyG9Rmo9AAAAAElFTkSuQmCC';

else if($_GET['getico']=='line')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjFBQUY3OEMwQjk3MjExRTc4QzVCODQzQ0E1OTcxMEQ5IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjFBQUY3OEMxQjk3MjExRTc4QzVCODQzQ0E1OTcxMEQ5Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MUFBRjc4QkVCOTcyMTFFNzhDNUI4NDNDQTU5NzEwRDkiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MUFBRjc4QkZCOTcyMTFFNzhDNUI4NDNDQTU5NzEwRDkiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7nrFooAAAAMklEQVR42mL4//8/Ay7c0NDwH588MmYEEdQATPgkGxsbibZl1EWjLhp10aiLKAQAAQYAS2xkW9cWAGEAAAAASUVORK5CYII=';

else if($_GET['getico']=='join')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkYzRDI5RTgwQjk3MTExRTc4RDI1RjQzNzZCODgxRkRFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkYzRDI5RTgxQjk3MTExRTc4RDI1RjQzNzZCODgxRkRFIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjNEMjlFN0VCOTcxMTFFNzhEMjVGNDM3NkI4ODFGREUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjNEMjlFN0ZCOTcxMTFFNzhEMjVGNDM3NkI4ODFGREUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4vO4jvAAAANklEQVR42mL4//8/Ay7c0NDwH588MmYEEdQATPgkGxsbibZl1EWDzUXE0KOxNuqiQeUigAADAF/NcOWMFtBgAAAAAElFTkSuQmCC';


else if($_GET['getico']=='joinbottom')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkVFMjg4M0YwQjk3MTExRTc5NEM3OEVCNjZGMUFEN0U1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkVFMjg4M0YxQjk3MTExRTc5NEM3OEVCNjZGMUFEN0U1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RUUyODgzRUVCOTcxMTFFNzk0Qzc4RUI2NkYxQUQ3RTUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RUUyODgzRUZCOTcxMTFFNzk0Qzc4RUI2NkYxQUQ3RTUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4U5s0pAAAAN0lEQVR42mL4//8/Ay7c0NDwH588MmYEEdQATPgkGxsbibZl1EWDzUXE0PRx0ahBowYRCQACDAC/4FrtGnihLgAAAABJRU5ErkJggg==';


else if($_GET['getico']=='minus')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjE2NzZDODgwQjk3MjExRTdBMDVCQzYzNzU4Q0JDOUE5IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjE2NzZDODgxQjk3MjExRTdBMDVCQzYzNzU4Q0JDOUE5Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MTY3NkM4N0VCOTcyMTFFN0EwNUJDNjM3NThDQkM5QTkiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MTY3NkM4N0ZCOTcyMTFFN0EwNUJDNjM3NThDQkM5QTkiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7QBuY5AAAAY0lEQVR42sxT0QrAIAjU6L9V/PCLvW1js0XGEi4fLvSUkwDQG1QVEX8GH09GlIg0s89d0hTVUQUiwt1C0cdeo0JJsbYQM+OOpxEvecSAEb/fjuqMm5c4OxzN3bGXol+uvwkwAIMTl8scwF3MAAAAAElFTkSuQmCC';

else if($_GET['getico']=='minusbottom')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjExRkIyM0EwQjk3MjExRTc5MkMyOTRDMUNDMEREMTY3IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjExRkIyM0ExQjk3MjExRTc5MkMyOTRDMUNDMEREMTY3Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MTFGQjIzOUVCOTcyMTFFNzkyQzI5NEMxQ0MwREQxNjciIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MTFGQjIzOUZCOTcyMTFFNzkyQzI5NEMxQ0MwREQxNjciLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4nsalLAAAAXUlEQVR42mL4//8/Ay7c0NDwH588MmYEEdQATPgkGxsbibaFai5iIdUF9fX1jAQNwqeQkEVMDFQCtDWIkZHxPzrG5kUUmpQEiE9+8IURCyWpmSYpe4ikowE1CCDAAHESjMkofm4sAAAAAElFTkSuQmCC';

else if($_GET['getico']=='nolines_minus')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjBEOEI2NUEwQjk3MjExRTdBMEQwRDdFQjUxQkJDRTU5IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjBEOEI2NUExQjk3MjExRTdBMEQwRDdFQjUxQkJDRTU5Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MEQ4QjY1OUVCOTcyMTFFN0EwRDBEN0VCNTFCQkNFNTkiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MEQ4QjY1OUZCOTcyMTFFN0EwRDBEN0VCNTFCQkNFNTkiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6yKRxEAAAAQElEQVR42mJgGAWEACMyp6Gh4T8hDUA1jAxEKPpPrjwTtbxGNYNYcIj/JxSexBrEOGBeo10YEZOWRsEAA4AAAwCIHQyU8gphlAAAAABJRU5ErkJggg==';

else if($_GET['getico']=='nolines_plus')
$image = 
'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjA4QUI5RkYwQjk3MjExRTdCRkM5RTIzRUU5RDk1MzIxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjA4QUI5RkYxQjk3MjExRTdCRkM5RTIzRUU5RDk1MzIxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MDhBQjlGRUVCOTcyMTFFN0JGQzlFMjNFRTlEOTUzMjEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MDhBQjlGRUZCOTcyMTFFN0JGQzlFMjNFRTlEOTUzMjEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7MrBA/AAAATElEQVR42mJgGAWEACMyp6Gh4T8hDUA1jAxEKPpPrjwTAbP/E+s1JmqFEQsRLvmPLTyJNYgRyRBGunqNiZTkQZLXiElLo2CAAUCAAQD3bg+TIR/StwAAAABJRU5ErkJggg==';

    header('Content-Type: image/png');
    $image = base64_decode($image);
    header("Cache-Control: public");
    header("Pragma: cache");
    $offset = 300 * 60 * 60 * 24;
    $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
    header($ExpStr);   
    echo $image;
    exit;
}
if (isset($_SESSION['logged'], $auth_users[$_SESSION['logged']])) {
    $root_path = $root_path . $auth_users[$_SESSION['logged']]['ro'][0];
} elseif (isset($_POST['fm_usr'], $_POST['fm_pwd'])) {
    if (isset($auth_users[$_POST['fm_usr']]) && md5($_POST['fm_pwd']) === $auth_users[$_POST['fm_usr']]['pwd']) {
        $root_path = $root_path . $auth_users[$_POST['fm_usr']]['ro'][0];
        $_SESSION['logged'] = $_POST['fm_usr'];
        fm_set_msg('You are logged in');
        $login_redirect = '?p=';
        if (isset($_GET['p'])) $login_redirect.= $_GET['p'];
        if (isset($_GET['view'])) $login_redirect.= '&view=' . $_GET['view'];
        fm_redirect(FM_SELF_URL . $login_redirect);
    } else {
        unset($_SESSION['logged']);
        fm_set_msg('Wrong password', 'error');
        fm_redirect(FM_SELF_URL);
    }
} else {
    unset($_SESSION['logged']);
    fm_show_header();
?>
        <div class="login-form"><h1><img src="?getico=ico"/> <?php
    echo $site_name ?></h1>
            <form action="" method="post">
                <label for="fm_usr">Username</label><input type="text" id="fm_usr" name="fm_usr" required/><br>
                <label for="fm_pwd">Password</label><input type="password" id="fm_pwd" name="fm_pwd" required/><br>
<input type="submit" value=" - - - Login - - -"/> <?php
    fm_show_message(); ?>
            </form>
        </div>
        <?php
    fm_show_footer();
    exit;
}
define('FM_READONLY', isset($_SESSION['logged']) && ($auth_users[$_SESSION['logged']]['rw'] == 'r'));
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');
// always use ?p=
if (!isset($_GET['p'])) {
    fm_redirect(FM_SELF_URL . '?p=');
}
// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');
// clean path
$p = fm_clean_path($p);
// instead globals vars
define('FM_PATH', $p);
defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);
/*************************** ACTIONS ***************************/
//AJAX Request
if (isset($_POST['ajax'])) {
    if (isset($_POST['type']) && $_POST['type'] == "search") {
        $response = scanAll($_POST['path']);
        echo json_encode($response);
    }
    exit;
}
//Create User
if (isset($_POST['crtuser']) && isset($_POST['crtuser_pwd']) && !FM_READONLY) {
    if (!empty($_POST['crtuser']) && !empty($_POST['crtuser_pwd'])) {
        if ($_POST['crtuser'] == 'admin') {
            fm_set_msg('User name is forbidden', 'error');
            fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
        }
        /*
        if(stripos($_POST['root_path'],$auth_users[$_SESSION['logged']]['ro'])=== false){
        fm_set_msg('Root is forbidden', 'error');
        fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
        }*/
        $auth_users[$_POST['crtuser']]['pwd'] = md5($_POST['crtuser_pwd']);
        $auth_users[$_POST['crtuser']]['rw'] = $_POST['crtuser_readonly'];
        $auth_users[$_POST['crtuser']]['ro'] = $_POST['root_path'];
        $auth_users[$_POST['crtuser']]['cb'] = $_SESSION['logged'];
        //Save user to file
        $userFiles = fopen("index.php.user.php", "w") or die("Unable to open file!");
        $users = '<?php $auth_users = ' . var_export($auth_users, true) . ';';
        fwrite($userFiles, $users);
        fclose($userFiles);
        sleep(3);
        fm_set_msg('User ' . $_POST['crtuser'] . ' is created', 'alert');
    } else {
        fm_set_msg('User name or password is empty', 'error');
    }
    fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
}
//Modify password
if (isset($_POST['user_pwd_current']) && isset($_POST['user_pwd_new']) && !FM_READONLY) {
    if (md5($_POST['user_pwd_current']) != $auth_users[$_SESSION['logged']]['pwd']) {
        fm_set_msg('Password is wrong', 'error');
        fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
    }
    $auth_users[$_SESSION['logged']]['pwd'] = md5($_POST['user_pwd_new']);
    //Save user to file
    $userFiles = fopen("index.php.user.php", "w") or die("Unable to open file!");
    $users = '<?php $auth_users = ' . var_export($auth_users, true) . ';';
    fwrite($userFiles, $users);
    fclose($userFiles);
    sleep(3);
    fm_set_msg('Password is modified', 'alert');
    fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
}
//Delete User
if (isset($_GET['deluser']) && !FM_READONLY) {
    if (!empty($_GET['deluser'])) {
        if ($_GET['deluser'] == 'admin') {
            fm_set_msg('Delete is forbidden', 'error');
            fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
        }
        unset($auth_users[$_GET['deluser']]);
        //Save user to file
        $userFiles = fopen("index.php.user.php", "w") or die("Unable to open file!");
        $users = '<?php $auth_users = ' . var_export($auth_users, true) . ';';
        fwrite($userFiles, $users);
        fclose($userFiles);
        sleep(3);
        fm_set_msg('User ' . $_GET['deluser'] . ' is deleted', 'alert');
    } else {
        fm_set_msg('User name is empty', 'error');
    }
    fm_redirect(FM_SELF_URL . '?usermgt&p=' . urlencode(FM_PATH));
}
// Delete file / folder
if (isset($_GET['del']) && !FM_READONLY) {
    $del = $_GET['del'];
    $del = fm_clean_path($del);
    $del = str_replace('/', '', $del);
    if ($del != '' && $del != '..' && $del != '.') {
        $path = $root_path;
        if (FM_PATH != '') {
            $path.= '/' . FM_PATH;
        }
        $is_dir = is_dir($path . '/' . $del);
        if (fm_rdelete($path . '/' . $del)) {
            $msg = $is_dir ? 'Folder <b>%s</b> deleted' : 'File <b>%s</b> deleted';
            fm_set_msg(sprintf($msg, $del));
        } else {
            $msg = $is_dir ? 'Folder <b>%s</b> not deleted' : 'File <b>%s</b> not deleted';
            fm_set_msg(sprintf($msg, $del) , 'error');
        }
    } else {
        fm_set_msg('Wrong file or folder name', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}
// Create folder
if (isset($_GET['new']) && !FM_READONLY) {
    $new = $_GET['new'];
    $new = fm_clean_path($new);
    $new = str_replace('/', '', $new);
    if ($new != '' && $new != '..' && $new != '.') {
        $path = $root_path;
        if (FM_PATH != '') {
            $path.= '/' . FM_PATH;
        }
        if (fm_mkdir($path . '/' . $new, false) === true) {
            fm_set_msg(sprintf('Folder <b>%s</b> created', $new));
        } elseif (fm_mkdir($path . '/' . $new, false) === $path . '/' . $new) {
            fm_set_msg(sprintf('Folder <b>%s</b> already exists', $new) , 'alert');
        } else {
            fm_set_msg(sprintf('Folder <b>%s</b> not created', $new) , 'error');
        }
    } else {
        fm_set_msg('Wrong folder name', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}
// Mass copy files/ folders
if (isset($_POST['file'], $_POST['copy_to'], $_POST['finish']) && !FM_READONLY) {
    // from
    $path = $root_path;
    if (FM_PATH != '') {
        $path.= '/' . FM_PATH;
    }
    // to
    $copy_to_path = $root_path;
    $copy_to = fm_clean_path($_POST['copy_to']);
    if ($copy_to != '') {
        $copy_to_path.= '/' . $copy_to;
    }
    if ($path == $copy_to_path) {
        fm_set_msg('Paths must be not equal', 'alert');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
    if (!is_dir($copy_to_path)) {
        if (!fm_mkdir($copy_to_path, true)) {
            fm_set_msg('Unable to create destination folder', 'error');
            fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
        }
    }
    // move?
    $move = isset($_POST['move']);
    // copy/move
    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                // abs path from
                $from = $path . '/' . $f;
                // abs path to
                $dest = $copy_to_path . '/' . $f;
                // do
                if ($move) {
                    $rename = fm_rename($from, $dest);
                    if ($rename === false) {
                        $errors++;
                    }
                } else {
                    if (!fm_rcopy($from, $dest)) {
                        $errors++;
                    }
                }
            }
        }
        if ($errors == 0) {
            $msg = $move ? 'Selected files and folders moved' : 'Selected files and folders copied';
            fm_set_msg($msg);
        } else {
            $msg = $move ? 'Error while moving items' : 'Error while copying items';
            fm_set_msg($msg, 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}
// Rename
if (isset($_GET['ren'], $_GET['to']) && !FM_READONLY) {
    // old name
    $old = $_GET['ren'];
    $old = fm_clean_path($old);
    $old = str_replace('/', '', $old);
    // new name
    $new = $_GET['to'];
    $new = fm_clean_path($new);
    $new = str_replace('/', '', $new);
    // path
    $path = $root_path;
    if (FM_PATH != '') {
        $path.= '/' . FM_PATH;
    }
    // rename
    if ($old != '' && $new != '') {
        //禁止修改为.php后缀
        if (fm_rename($path . '/' . $old, strtr($path . '/' . $new, ".php", ""))) {
            fm_set_msg(sprintf('Renamed from <b>%s</b> to <b>%s</b>', $old, $new));
        } else {
            fm_set_msg(sprintf('Error while renaming from <b>%s</b> to <b>%s</b>', $old, $new) , 'error');
        }
    } else {
        fm_set_msg('Names not set', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}
// Download
if (isset($_GET['dl'])) {
    $dl = $_GET['dl'];
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
    $path = $root_path;
    if (FM_PATH != '') {
        $path.= '/' . FM_PATH;
    }
    if ($dl != '' && is_file($path . '/' . $dl)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path . '/' . $dl) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path . '/' . $dl));
        readfile($path . '/' . $dl);
        exit;
    } else {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
}
// Upload
if (isset($_POST['upl']) && !FM_READONLY) {
    $path = $root_path;
    if (FM_PATH != '') {
        $path.= '/' . FM_PATH;
    }
    $errors = 0;
    $uploads = 0;
    $total = count($_FILES['upload']['name']);
    for ($i = 0; $i < $total; $i++) {
        $tmp_name = $_FILES['upload']['tmp_name'][$i];
        if (empty($_FILES['upload']['error'][$i]) && !empty($tmp_name) && $tmp_name != 'none') {
            //移除上传的php后缀
            if (move_uploaded_file($tmp_name, $path . '/' . strtr($_FILES['upload']['name'][$i], ".php", ""))) {
                $uploads++;
            } else {
                $errors++;
            }
        }
    }
    if ($errors == 0 && $uploads > 0) {
        fm_set_msg(sprintf('All files uploaded to <b>%s</b>', $path));
    } elseif ($errors == 0 && $uploads == 0) {
        fm_set_msg('Nothing uploaded', 'alert');
    } else {
        fm_set_msg(sprintf('Error while uploading files. Uploaded files: %s', $uploads) , 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}
// Mass deleting
if (isset($_POST['delete']) && !FM_READONLY) {
    $path = $root_path;
    if (FM_PATH != '') {
        $path.= '/' . FM_PATH;
    }
    $errors = 0;

if (isset($_POST['file'])){
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                $new_path = $path . '/' . $f;
                if (!fm_rdelete($new_path)) {
                    $errors++;
                }
            }
        }
        if ($errors == 0) {
            fm_set_msg('Selected files and folder deleted');
        } else {
            fm_set_msg('Error while deleting items', 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }
}
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}
// get current path
$path = $root_path;
if (FM_PATH != '') {
    $path.= '/' . FM_PATH;
}
// check path
if (!is_dir($path)) {
    fm_redirect(FM_SELF_URL . '?p=');
}
// get parent folder
$parent = fm_get_parent_path(FM_PATH);
$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
if (is_array($objects)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (is_file($new_path)) {
            $files[] = $file;
        } elseif (is_dir($new_path) && $file != '.' && $file != '..') {
            $folders[] = $file;
        }
    }
}
if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}
// upload form
if (isset($_GET['upload']) && !FM_READONLY) {
    fm_show_header();
    fm_show_nav_path(FM_PATH);
?>
    <div class="path">
        <h3>Uploading files</h3>
        <p>Destination folder: <?php
    echo fm_convert_win($root_path . FM_PATH) ?></p>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="p" value="<?php
    echo fm_enc(FM_PATH) ?>">
            <input type="hidden" name="upl" value="1">
            <input type="file" name="upload[]"><br>
            <input type="file" name="upload[]"><br>
            <input type="file" name="upload[]"><br>
            <input type="file" name="upload[]"><br>
            <input type="file" name="upload[]"><br>
            <br>
            <p><button type="submit" class="group-btn"><i class="fa fa-check-circle"></i> Upload</button></p>
        </form>
    </div>
    <?php
    fm_show_footer();
    exit;
}
// create user form
if (isset($_GET['usermgt']) && !FM_READONLY) {
    fm_show_header();
    fm_show_nav_path(FM_PATH);
    fm_show_message(); ?>
    <div class="path">
<form action="" method="post" enctype="multipart/form-data">
<h3>Modify my password</h3>       
<table>
<tr><td class="gray" width="40%">Current Password</td><td><input type="text" name="user_pwd_current" required></td></tr>
<tr><td class="gray">New Password</td><td><input type="text" name="user_pwd_new" required></td></tr>
</table>
<p><button type="submit" class="group-btn"><i class="fa fa-check-circle"></i> Modify</button></p>
</form>
<hr/>
<form action="" method="post" enctype="multipart/form-data">
<h3>Create User</h3>       
<table>
<tr><td class="gray" width="40%">Permission</td><td> <select name="crtuser_readonly"><option value="r">Read</option><option value="rw">Read&Write</option></select></td></tr><tr><td class="gray">Username</td><td> <input type="email" name="crtuser" required></td></tr><tr><td class="gray">Password</td><td><input type="text" name="crtuser_pwd" required></td></tr>
<tr><td class="gray">Root</td><td id="folderTree">
</td></tr></table>
<p><button type="submit" class="group-btn"><i class="fa fa-check-circle"></i> Create</button></p>
</form>
<hr/>
<h3>User List</h3>
<table><thead><th>User</th><th>RW</th><th>Creator</th><th>Root</th><th><i class="fa fa-trash-o"></i></th></thead>
<tbody><?php
    foreach ($auth_users as $user_key => $user_detail) {
        if ($user_key != 'admin' && ($user_detail['cb'] == $_SESSION['logged'] || $_SESSION['logged'] == 'admin')) { ?>
<tr><td><?php
            echo $user_key ?></td>
<td><?php
            echo $user_detail['rw'] ?></td>
<td><?php
            echo $user_detail['cb'] ?></td>
<td><?php
            foreach ($user_detail['ro'] as $rn) {
                echo $rn, '<br/>';
            }
?>
</td>
<td><a  href="?p=<?php
            echo urlencode(FM_PATH) ?>&amp;deluser=<?php
            echo $user_key ?>" onclick="return confirm('Delete user?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
</td></tr><?php
        }
    } ?>
</tbody></table>
    </div>
    <?php
    fm_show_footer();
    exit;
}
// copy form POST
if (isset($_POST['copy']) && isset($_POST['file']) && !FM_READONLY) {
    $copy_files = $_POST['file'];
    if (!is_array($copy_files) || empty($copy_files)) {
        fm_set_msg('Nothing selected', 'alert');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
    fm_show_header();
    fm_show_nav_path(FM_PATH);
?>
    <div class="path">
        <p><b>Copying</b></p>
        <form action="" method="post">
            <input type="hidden" name="p" value="<?php
    echo fm_enc(FM_PATH) ?>">
            <input type="hidden" name="finish" value="1">
            <?php
    foreach ($copy_files as $cf) {
        echo '<input type="hidden" name="file[]" value="' . fm_enc($cf) . '">' . PHP_EOL;
    }
?>
            <p class="break-word">Files:<b><?php
    echo implode('</b>, <b>', $copy_files) ?></b></p>
            <p class="break-word">Source folder: <?php
    echo fm_convert_win($root_path . FM_PATH) ?><br>
                <label for="inp_copy_to">Destination folder:</label>
                <?php
    echo $root_path ?><input type="text" name="copy_to" id="inp_copy_to" value="<?php
    echo fm_enc(FM_PATH) ?>">
            </p>
            <p><label><input type="checkbox" name="move" value="1"> Move</label></p>
            <p>
                <button type="submit" class="btn"><i class="fa fa-check-circle"></i> Copy</button> &nbsp;
                <b><a href="?p=<?php
    echo urlencode(FM_PATH) ?>"><i class="fa fa-times-circle"></i> Cancel</a></b>
            </p>
        </form>
    </div>
    <?php
    fm_show_footer();
    exit;
}
// file viewer
if (isset($_GET['view'])) {
    $file = $_GET['view'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file)) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
    fm_show_header();
    fm_show_nav_path(FM_PATH);
    $temp_url = $auth_users[$_SESSION['logged']]['ro'][0] . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $temp_url = str_replace('//', '/', $temp_url);
    $file_url = FM_ROOT_URL . $temp_url;
    $file_path = $path . '/' . $file;
    $file_path = str_replace('//', '/', $file_path);
    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize = filesize($file_path);
    $is_zip = false;
    $is_image = false;
    $is_audio = false;
    $is_video = false;
    $is_text = false;
    $view_title = 'File';
    $filenames = false; // for zip
    $content = ''; // for text
    if ($ext == 'zip') {
        $is_zip = true;
        $view_title = 'Archive';
        $filenames = fm_get_zif_info($file_path);
    } elseif (in_array($ext, fm_get_image_exts())) {
        $is_image = true;
        $view_title = 'Image';
    } elseif (in_array($ext, fm_get_audio_exts())) {
        $is_audio = true;
        $view_title = 'Audio';
    } elseif (in_array($ext, fm_get_video_exts())) {
        $is_video = true;
        $view_title = 'Video';
    } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }
?>
    <div class="path">
        <h3><?php
    echo $view_title ?> "<?php
    echo fm_convert_win($file) ?>"</h3>
        <p>File on internet: <a href="<?php
    echo $file_url ?>" target="_blank"><?php
    echo $file_url ?></a><br>
            Full path: <?php
    echo fm_convert_win($file_path) ?><br>
            File size: <?php
    echo fm_get_filesize($filesize) ?><?php
    if ($filesize >= 1000): ?> (<?php
        echo sprintf('%s bytes', $filesize) ?>)<?php
    endif; ?><br>
            MIME-type: <?php
    echo $mime_type ?><br>
            <?php
    // ZIP info
    if ($is_zip && $filenames !== false) {
        $total_files = 0;
        $total_comp = 0;
        $total_uncomp = 0;
        foreach ($filenames as $fn) {
            if (!$fn['folder']) {
                $total_files++;
            }
            $total_comp+= $fn['compressed_size'];
            $total_uncomp+= $fn['filesize'];
        }
?>
                Files in archive: <?php
        echo $total_files ?><br>                
                Total size: <?php
        echo fm_get_filesize($total_uncomp) ?><br>
                Size in archive: <?php
        echo fm_get_filesize($total_comp) ?><br>
                Compression: <?php
        echo round(($total_comp / $total_uncomp) * 100) ?>%<br>
                <?php
    }
    // Image info
    if ($is_image) {
        $image_size = getimagesize($file_path);
        echo 'Image sizes: ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
    }
    // Text info
    if ($is_text) {
        $is_utf8 = fm_is_utf8($content);
        echo 'Charset: ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
    }
?>
        </p>
        <p>
            <a href="?p=<?php
    echo urlencode(FM_PATH) ?>&amp;dl=<?php
    echo urlencode($file) ?>" class="group-btn"><i class="fa fa-cloud-download"></i> Download</a>
            <a href="<?php
    echo $file_url ?>" target="_blank" class="group-btn"><i class="fa fa-external-link-square"></i> Open</a>
            <a href="?p=<?php
    echo urlencode(FM_PATH) ?>" class="group-btn"><i class="fa fa-chevron-circle-left"></i> Back</a>
        </p>
    </div>
    <?php
    fm_show_footer();
    exit;
}
fm_show_header();
fm_show_nav_path(FM_PATH);
fm_show_message();
$num_files = 0;
$num_folders = 0;
$all_files_size = 0;
?>
<form action="" method="post">
<input type="hidden" name="p" value="<?php
echo fm_enc(FM_PATH) ?>">
<table class="table"><thead><tr>
<?php
if (!FM_READONLY): ?><th style="width:20px"><label><input type="checkbox" onclick="checkbox_toggle()"></label></th><?php
endif; ?>
<th>Name <i class="fa fa-search" onclick="showSearch('<?php
echo urlencode(FM_PATH) ?>')" style="cursor:pointer;"></i></th><th style="width:80px"  class="hideSmallDevice">Size</th>
<th style="width:120px" class="hideSmallDevice">Modified</th>
<th style="width:80px"><i class="fa fa-cogs"></i>
</th></tr></thead>
<tbody>
<?php
if ($parent !== false) { ?>
<tr><?php
    if (!FM_READONLY): ?><td></td><?php
    endif; ?><td colspan="<?php
    echo !FM_IS_WIN ? '6' : '4' ?>"><a href="?p=<?php
    echo urlencode($parent) ?>"><i class="fa fa-chevron-circle-left"></i> ..</a></td></tr>
<?php
}
foreach ($folders as $f) {
    if (strstr($f, 'index.php') !== false) continue;
    if ($_SESSION['logged'] != 'admin') {
        $current_folder_full_path = trim('/' . FM_PATH . '/' . $f);
        $current_folder_full_path = str_replace('//', '/', $current_folder_full_path);
        if (!in_array($current_folder_full_path, $auth_users[$_SESSION['logged']]['ro'])) continue;
    }
    $num_folders++;
    $is_link = is_link($path . '/' . $f);
    $img = 'fa fa-folder-o';
    $modif = date(FM_DATETIME_FORMAT, filemtime($path . '/' . $f));
?>
<tr>
<?php
    if (!FM_READONLY): ?><td><label><input type="checkbox" name="file[]" value="<?php
        echo fm_enc($f) ?>"></label></td><?php
    endif; ?>
<td><div class="filename"><a href="?p=<?php
    echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="<?php
    echo $img ?>"></i> <?php
    echo fm_convert_win($f) ?></a><?php
    echo ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div></td>
<td  class="hideSmallDevice">Folder</td><td  class="hideSmallDevice"><?php
    echo $modif ?></td>
<td class="inline-actions"><?php
    if (!FM_READONLY): ?>
<a  href="?p=<?php
        echo urlencode(FM_PATH) ?>&amp;del=<?php
        echo urlencode($f) ?>" onclick="return confirm('Delete folder?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
<a href="#" onclick="rename('<?php
        echo fm_enc(FM_PATH) ?>', '<?php
        echo fm_enc($f) ?>');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
<?php
    endif; ?>
</td></tr>
<?php
    flush();
}
foreach ($files as $f) {
    if (strstr($f, 'index.php') !== false) continue;
    $num_files++;
    $is_link = is_link($path . '/' . $f);
    $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
    $modif = date(FM_DATETIME_FORMAT, filemtime($path . '/' . $f));
    $filesize_raw = filesize($path . '/' . $f);
    $filesize = fm_get_filesize($filesize_raw);
    $filelink = '?p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
    $all_files_size+= $filesize_raw;
?>
<tr>
<?php
    if (!FM_READONLY): ?><td><label><input type="checkbox" name="file[]" value="<?php
        echo fm_enc($f) ?>"></label></td><?php
    endif; ?>
<td><div class="filename"><a href="<?php
    echo $filelink ?>"><i class="<?php
    echo $img ?>"></i> <?php
    echo fm_convert_win($f) ?></a><?php
    echo ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div></td>
<td class="hideSmallDevice"><?php
    echo $filesize ?></td>
<td class="hideSmallDevice"><?php
    echo $modif ?></td>
<td class="inline-actions">
<?php
    if (!FM_READONLY): ?>
<a  href="?p=<?php
        echo urlencode(FM_PATH) ?>&amp;del=<?php
        echo urlencode($f) ?>" onclick="return confirm('Delete file?');"><i class="fa fa-trash-o"></i></a>
<a  href="#" onclick="rename('<?php
        echo fm_enc(FM_PATH) ?>', '<?php
        echo fm_enc($f) ?>');return false;"><i class="fa fa-pencil-square-o"></i></a>
<?php
    endif; ?>
<a  href="?p=<?php
    echo urlencode(FM_PATH) ?>&amp;dl=<?php
    echo urlencode($f) ?>"><i class="fa fa-download"></i></a>
</td></tr>
<?php
    flush();
}
if (empty($folders) && empty($files)) {
?>
<tr><?php
    if (!FM_READONLY): ?><td></td><?php
    endif; ?><td colspan="<?php
    echo !FM_IS_WIN ? '6' : '4' ?>"><em>Folder is empty</em></td></tr>
<?php
} else {
?>
<tr><?php
    if (!FM_READONLY): ?><td class="gray"></td><?php
    endif; ?><td class="gray" colspan="<?php
    echo !FM_IS_WIN ? '6' : '4' ?>">
Full size: <span><?php
    echo fm_get_filesize($all_files_size) ?></span>,
files: <?php
    echo $num_files ?>,
folders: <?php
    echo $num_folders ?>
</td></tr>
<?php
}
?></tbody>
<tfoot>
<tr><td colspan="5">
<?php
if (!FM_READONLY): ?>
<input type="submit" class="hidden" name="delete" id="a-delete" value="Delete" onclick="return confirm('Delete files and folders?')">
<a href="javascript:document.getElementById('a-delete').click();" class="group-btn"><i class="fa fa-trash"></i>Delete</a>
<input type="submit" class="hidden" name="copy" id="a-copy" value="Copy">
<a href="javascript:document.getElementById('a-copy').click();" class="group-btn"><i class="fa fa-files-o"></i>Copy</a>
<?php
endif; ?>
</td></tr>
</tfoot>
</table>
</form>
<?php
fm_show_footer();
function fm_rdelete($path) {
    if (is_link($path)) {
        return unlink($path);
    } elseif (is_dir($path)) {
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rdelete($path . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return ($ok) ? rmdir($path) : false;
    } elseif (is_file($path)) {
        return unlink($path);
    }
    return false;
}
function fm_rename($old, $new) {
    return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
}
function fm_rcopy($path, $dest, $upd = true, $force = true) {
    if (is_dir($path)) {
        if (!fm_mkdir($dest, $force)) {
            return false;
        }
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rcopy($path . '/' . $file, $dest . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return $ok;
    } elseif (is_file($path)) {
        return fm_copy($path, $dest, $upd);
    }
    return false;
}
function fm_mkdir($dir, $force) {
    if (file_exists($dir)) {
        if (is_dir($dir)) {
            return $dir;
        } elseif (!$force) {
            return false;
        }
        unlink($dir);
    }
    return mkdir($dir, 0777, true);
}
function fm_copy($f1, $f2, $upd) {
    $time1 = filemtime($f1);
    if (file_exists($f2)) {
        $time2 = filemtime($f2);
        if ($time2 >= $time1 && $upd) {
            return false;
        }
    }
    $ok = copy($f1, $f2);
    if ($ok) {
        touch($f2, $time1);
    }
    return $ok;
}
function fm_get_mime_type($file_path) {
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime;
    } elseif (function_exists('mime_content_type')) {
        return mime_content_type($file_path);
    } elseif (!stristr(ini_get('disable_functions') , 'shell_exec')) {
        $file = escapeshellarg($file_path);
        $mime = shell_exec('file -bi ' . $file);
        return $mime;
    } else {
        return '--';
    }
}
function fm_redirect($url, $code = 302) {
    header('Location: ' . $url, true, $code);
    exit;
}
function fm_clean_path($path) {
    $path = trim($path);
    $path = trim($path, '\\/');
    $path = str_replace(array(
        '../',
        '..\\'
    ) , '', $path);
    if ($path == '..') {
        $path = '';
    }
    return str_replace('\\', '/', $path);
}
function fm_get_parent_path($path) {
    $path = fm_clean_path($path);
    if ($path != '') {
        $array = explode('/', $path);
        if (count($array) > 1) {
            $array = array_slice($array, 0, -1);
            return implode('/', $array);
        }
        return '';
    }
    return false;
}
function fm_get_filesize($size) {
    if ($size < 1000) {
        return sprintf('%s B', $size);
    } elseif (($size / 1024) < 1000) {
        return sprintf('%s K', round(($size / 1024) , 2));
    } elseif (($size / 1024 / 1024) < 1000) {
        return sprintf('%s M', round(($size / 1024 / 1024) , 2));
    } elseif (($size / 1024 / 1024 / 1024) < 1000) {
        return sprintf('%s G', round(($size / 1024 / 1024 / 1024) , 2));
    } else {
        return sprintf('%s T', round(($size / 1024 / 1024 / 1024 / 1024) , 2));
    }
}
function fm_get_zif_info($path) {
    if (function_exists('zip_open')) {
        $arch = zip_open($path);
        if ($arch) {
            $filenames = array();
            while ($zip_entry = zip_read($arch)) {
                $zip_name = zip_entry_name($zip_entry);
                $zip_folder = substr($zip_name, -1) == '/';
                $filenames[] = array(
                    'name' => $zip_name,
                    'filesize' => zip_entry_filesize($zip_entry) ,
                    'compressed_size' => zip_entry_compressedsize($zip_entry) ,
                    'folder' => $zip_folder
                    //'compression_method' => zip_entry_compressionmethod($zip_entry),
                    
                );
            }
            zip_close($arch);
            return $filenames;
        }
    }
    return false;
}
function fm_enc($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function scanAll($dir) {
    global $root_path, $auth_users;
    $files = array();
    if (file_exists($root_path . '/' . $dir)) {
        foreach (scandir($root_path . '/' . $dir) as $f) {
            if (!$f || $f[0] == '.') {
                continue; // Ignore hidden files
                
            }
            if (strstr($f, 'index.php') !== false) continue;
            if (is_dir($root_path . '/' . $dir . '/' . $f)) {
                if ($_SESSION['logged'] != 'admin') {
                    $current_folder_full_path = trim('/' . $dir . '/' . $f);
                    $current_folder_full_path = str_replace('//', '/', $current_folder_full_path);
                    if (!in_array($current_folder_full_path, $auth_users[$_SESSION['logged']]['ro'])) continue;
                }
                $files[] = array(
                    "name" => $f,
                    "type" => "folder",
                    "path" => $dir . '/' . $f,
                    "items" => scanAll($dir . '/' . $f) ,
                );
            } else {
                $files[] = array(
                    "name" => $f,
                    "type" => "file",
                    "path" => $dir,
                );
            }
        }
    }
    return $files;
}
function scanFolderTree($dir, &$files) {
    global $root_path, $auth_users;
    if (file_exists($root_path . '/' . $dir)) {
        foreach (scandir($root_path . '/' . $dir) as $f) {
            if (!$f || $f[0] == '.') {
                continue; // Ignore hidden files
                
            }
            if (strstr($f, 'index.php') !== false) continue;
            if (is_dir($root_path . '/' . $dir . '/' . $f)) {
                if ($_SESSION['logged'] != 'admin') {
                    $current_folder_full_path = trim('/' . $dir . '/' . $f);
                    $current_folder_full_path = str_replace('//', '/', $current_folder_full_path);
                    if (!in_array($current_folder_full_path, $auth_users[$_SESSION['logged']]['ro'])) continue;
                }
                $t_path0 = $auth_users[$_SESSION['logged']]['ro'][0] . $dir;
                $t_path0 = str_replace('//', '/', $t_path0);
                $t_path1 = str_replace('//', '/', $t_path0 . '/' . $f);
                $files.= "d.add(" . crc32($t_path1) . "," . crc32($t_path0) . ",'root_path','$t_path1','$t_path1');";
                scanFolderTree($dir . '/' . $f, $files);
            }
        }
    }
}
function fm_set_msg($msg, $status = 'ok') {
    $_SESSION['message'] = $msg;
    $_SESSION['status'] = $status;
}
function fm_is_utf8($string) {
    return preg_match('//u', $string);
}
function fm_convert_win($filename) {
    if (FM_IS_WIN && function_exists('iconv')) {
        $filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
    }
    return $filename;
}
function fm_get_file_icon_class($path) {
    // get extension
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'ico':
        case 'gif':
        case 'jpg':
        case 'jpeg':
        case 'jpc':
        case 'jp2':
        case 'jpx':
        case 'xbm':
        case 'wbmp':
        case 'png':
        case 'bmp':
        case 'tif':
        case 'tiff':
        case 'svg':
            $img = 'fa fa-picture-o';
            break;

        case 'passwd':
        case 'ftpquota':
        case 'sql':
        case 'js':
        case 'json':
        case 'sh':
        case 'config':
        case 'twig':
        case 'tpl':
        case 'md':
        case 'gitignore':
        case 'c':
        case 'cpp':
        case 'cs':
        case 'py':
        case 'map':
        case 'lock':
        case 'dtd':
            $img = 'fa fa-file-code-o';
            break;

        case 'txt':
        case 'ini':
        case 'conf':
        case 'log':
        case 'htaccess':
            $img = 'fa fa-file-text-o';
            break;

        case 'css':
        case 'less':
        case 'sass':
        case 'scss':
            $img = 'fa fa-css3';
            break;

        case 'zip':
        case 'rar':
        case 'gz':
        case 'tar':
        case '7z':
            $img = 'fa fa-file-archive-o';
            break;

        case 'php':
        case 'php4':
        case 'php5':
        case 'phps':
        case 'phtml':
            $img = 'fa fa-code';
            break;

        case 'htm':
        case 'html':
        case 'shtml':
        case 'xhtml':
            $img = 'fa fa-html5';
            break;

        case 'xml':
        case 'xsl':
            $img = 'fa fa-file-excel-o';
            break;

        case 'wav':
        case 'mp3':
        case 'mp2':
        case 'm4a':
        case 'aac':
        case 'ogg':
        case 'oga':
        case 'wma':
        case 'mka':
        case 'flac':
        case 'ac3':
        case 'tds':
            $img = 'fa fa-music';
            break;

        case 'm3u':
        case 'm3u8':
        case 'pls':
        case 'cue':
            $img = 'fa fa-headphones';
            break;

        case 'avi':
        case 'mpg':
        case 'mpeg':
        case 'mp4':
        case 'm4v':
        case 'flv':
        case 'f4v':
        case 'ogm':
        case 'ogv':
        case 'mov':
        case 'mkv':
        case '3gp':
        case 'asf':
        case 'wmv':
            $img = 'fa fa-file-video-o';
            break;

        case 'eml':
        case 'msg':
            $img = 'fa fa-envelope-o';
            break;

        case 'xls':
        case 'xlsx':
            $img = 'fa fa-file-excel-o';
            break;

        case 'csv':
            $img = 'fa fa-file-text-o';
            break;

        case 'bak':
            $img = 'fa fa-clipboard';
            break;

        case 'doc':
        case 'docx':
            $img = 'fa fa-file-word-o';
            break;

        case 'ppt':
        case 'pptx':
            $img = 'fa fa-file-powerpoint-o';
            break;

        case 'ttf':
        case 'ttc':
        case 'otf':
        case 'woff':
        case 'woff2':
        case 'eot':
        case 'fon':
            $img = 'fa fa-font';
            break;

        case 'pdf':
            $img = 'fa fa-file-pdf-o';
            break;

        case 'psd':
        case 'ai':
        case 'eps':
        case 'fla':
        case 'swf':
            $img = 'fa fa-file-image-o';
            break;

        case 'exe':
        case 'msi':
            $img = 'fa fa-file-o';
            break;

        case 'bat':
            $img = 'fa fa-terminal';
            break;

        default:
            $img = 'fa fa-info-circle';
    }
    return $img;
}
function fm_get_image_exts() {
    return array(
        'ico',
        'gif',
        'jpg',
        'jpeg',
        'jpc',
        'jp2',
        'jpx',
        'xbm',
        'wbmp',
        'png',
        'bmp',
        'tif',
        'tiff',
        'psd'
    );
}
function fm_get_video_exts() {
    return array(
        'webm',
        'mp4',
        'm4v',
        'ogm',
        'ogv',
        'mov'
    );
}
function fm_get_audio_exts() {
    return array(
        'wav',
        'mp3',
        'ogg',
        'm4a'
    );
}
function fm_get_text_exts() {
    return array(
        'txt',
        'css',
        'ini',
        'conf',
        'log',
        'htaccess',
        'passwd',
        'ftpquota',
        'sql',
        'js',
        'json',
        'sh',
        'config',
        'php',
        'php4',
        'php5',
        'phps',
        'phtml',
        'htm',
        'html',
        'shtml',
        'xhtml',
        'xml',
        'xsl',
        'm3u',
        'm3u8',
        'pls',
        'cue',
        'eml',
        'msg',
        'csv',
        'bat',
        'twig',
        'tpl',
        'md',
        'gitignore',
        'less',
        'sass',
        'scss',
        'c',
        'cpp',
        'cs',
        'py',
        'map',
        'lock',
        'dtd',
        'svg',
    );
}
function fm_get_text_mimes() {
    return array(
        'application/xml',
        'application/javascript',
        'application/x-javascript',
        'image/svg+xml',
        'message/rfc822',
    );
}
function fm_get_text_names() {
    return array(
        'license',
        'readme',
        'authors',
        'contributors',
        'changelog',
    );
}
function fm_show_nav_path($path) { ?>
<div class="main-nav">
<?php
    $path = fm_clean_path($path);
    $root_url = "<a href='?p='><i class='fa fa-home' aria-hidden='true'></i> Root</a>";
    $sep = '<i class="fa fa-caret-right"></i>';
    if ($path != '') {
        $exploded = explode('/', $path);
        $count = count($exploded);
        $array = array();
        $parent = '';
        for ($i = 0; $i < $count; $i++) {
            $parent = trim($parent . '/' . $exploded[$i], '/');
            $parent_enc = urlencode($parent);
            $array[] = "<a href='?p={$parent_enc}'>" . $sep . fm_convert_win($exploded[$i]) . "</a>";
        }
        $root_url.= implode('', $array);
    } ?>
<div class="break-word float-left"><?php
    echo $root_url ?></div> 
<div class="float-right">
<?php
    if (!FM_READONLY): ?>
        <a  href="?p=<?php
        echo urlencode(FM_PATH) ?>&amp;usermgt"><i class="fa fa-user" aria-hidden="true"></i></a>
        <a  href="?p=<?php
        echo urlencode(FM_PATH) ?>&amp;upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
        <a  href="#createNewItem" ><i class="fa fa-plus-square"></i></a>
        <?php
    endif;
    if (isset($_SESSION['logged'])): ?><a  href="?logout=1"><i class="fa fa-sign-out" aria-hidden="true"></i></a><?php
    endif; ?>
</div></div>
<?php
}
function fm_show_message() {
    if (isset($_SESSION['message'])) {
        $class = isset($_SESSION['status']) ? $_SESSION['status'] : 'ok';
        echo '<p class="message ' . $class . '">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    }
}
function fm_show_header() {
    global $site_name;
    header("Content-Type: text/html; charset=utf-8");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php
    echo $site_name ?></title>
<link rel="icon" href="?getico=ico" type="image/png">
<link rel="shortcut icon" href="?getico=ico" type="image/png">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<?php
    if (isset($_GET['view'])): ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/vs.min.css">
<?php
    endif; ?>
<link rel="stylesheet" href="index.php.css">
</head>
<body>

<div id="wrapper">
<?php
    if (isset($_SESSION['logged'])) { ?>
<div id="searchResult" class="modalDialog"><div class="model-wrapper"><a href="#close" class="close">X</a><input type="search" name="search" style="width:90%" class="group-btn" placeholder="Search..."><div id="searchresultWrapper"></div></div></div>
<?php
    }
    if (isset($_SESSION['logged']) && !FM_READONLY) { ?>
<div id="createNewItem" class="modalDialog"><div class="model-wrapper"><a href="#close" class="close">X</a><input type="text" name="newfilename" id="newfilename" style="width:75%" class="group-btn" placeholder="Folder name..."> <input type="submit" name="submit" class="group-btn" value="Create" onclick="newfolder('<?php
        echo fm_enc(FM_PATH) ?>');return false;"></div></div>    
<?php
    }
}
function fm_show_footer() {
    global $site_name;
?></div><div id="copyright">&copy;2017 <?php
    echo $site_name ?></div>
<script src="index.php.js"></script>
<?php
    if (isset($_SESSION['logged']) && isset($_GET['usermgt']) && !FM_READONLY) { ?>
<script>
var d = new dTree('d');
<?php   $folders = "";
        scanFolderTree('', $folders);
        echo "d.add(0,-1,'');d.add(" . crc32('/') . ",0,'root_path','/','ROOT');", $folders; ?>
document.getElementById("folderTree").innerHTML=d.toString();
</script>
<?php
    } ?>
<?php
    if (isset($_GET['view'])): ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<?php
    endif; ?>
</body></html>
<?php
}

