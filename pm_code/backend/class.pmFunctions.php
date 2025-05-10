<?php ?><?php
/* You may add your copyright here and it will be added to every encoded file.
   Format the copyright string as PHP comments like below */

/* Copyright (c) by MyCompany */

/* Also you may add your custom error handler which is added UNENCODED to every encoded file. */
if (!function_exists('my_error_handler')) {
   function my_error_handler($code, $message) {
       /* This is a sample of the error handler you may use to catch SourceGuardian errors
          such as IP or domain lock violation, expiring the license etc.
          Add this my_error_handler function name to the 'Custom error handlers' list on the
          'Lock' window in your SourceGuardian project. Please refer to 'Custom errors handling'
          section in 'Locking options' in the user manual for further information. */
       echo sprintf("Something goes wrong. Error code %s. ", $code);
       echo "Please contact <a href='info@pmlearning.ir'>info@pmlearning.ir</a>";
   }
}

/* Or you may add custom PHP code and it will be added UNENCODED to every encoded file. */
if (!function_exists('function_added_to_every_file')) {
   function function_added_to_every_file() {
       /* This is a sample of the function which is added as-is to every encoded file */
   }
}
?><?php
if(!function_exists('sg_load')){$__v=phpversion();$__x=explode('.',$__v);$__v2=$__x[0].'.'.(int)$__x[1];$__u=strtolower(substr(php_uname(),0,3));$__ts=(@constant('PHP_ZTS') || @constant('ZEND_THREAD_SAFE')?'ts':'');$__f=$__f0='ixed.'.$__v2.$__ts.'.'.$__u;$__ff=$__ff0='ixed.'.$__v2.'.'.(int)$__x[2].$__ts.'.'.$__u;$__ed=@ini_get('extension_dir');$__e=$__e0=@realpath($__ed);$__dl=function_exists('dl') && function_exists('file_exists') && @ini_get('enable_dl') && !@ini_get('safe_mode');if($__dl && $__e && version_compare($__v,'5.2.5','<') && function_exists('getcwd') && function_exists('dirname')){$__d=$__d0=getcwd();if(@$__d[1]==':') {$__d=str_replace('\\','/',substr($__d,2));$__e=str_replace('\\','/',substr($__e,2));}$__e.=($__h=str_repeat('/..',substr_count($__e,'/')));$__f='/ixed/'.$__f0;$__ff='/ixed/'.$__ff0;while(!file_exists($__e.$__d.$__ff) && !file_exists($__e.$__d.$__f) && strlen($__d)>1){$__d=dirname($__d);}if(file_exists($__e.$__d.$__ff)) dl($__h.$__d.$__ff); else if(file_exists($__e.$__d.$__f)) dl($__h.$__d.$__f);}if(!function_exists('sg_load') && $__dl && $__e0){if(file_exists($__e0.'/'.$__ff0)) dl($__ff0); else if(file_exists($__e0.'/'.$__f0)) dl($__f0);}if(!function_exists('sg_load')){$__ixedurl='https://www.sourceguardian.com/loaders/download.php?php_v='.urlencode($__v).'&php_ts='.($__ts?'1':'0').'&php_is='.@constant('PHP_INT_SIZE').'&os_s='.urlencode(php_uname('s')).'&os_r='.urlencode(php_uname('r')).'&os_m='.urlencode(php_uname('m')); ?><?php
   echo "It looks like you did not install the loader. ";
   echo "Please contact <a href='info@pmlearning.ir'>info@pmlearning.ir</a>";
?><?php
exit();}}return sg_load('4A569C520016575EAAQAAAAhAAAABwToAAAA/1XbZDwVWDEqQkl9VDGFnMNUDg/c4Nuas+UcDdl6Oc2x9iQmKpxNVOAhtpersnFm8O2/uwlbYJVhvtUdBhBPiIFscZVJ1eYsFo0TB0JPoh9T0ELzsw3h7Jo3cdJ9IPggI5CR6sP5+uhpQQmuwny19RQ0tSy/cun/z7pEGoPsR619GSF5MFufjXtwkSrMM7VzvB6BCzczSKffIKMYczwSNF9OwMQ+LUjBS2A1jwAm3IFU1uodxQQjRcO4RDCJ/KO1EcNAkFM9fgN6kIwQ3BMVv3gqMhUtHUfzQGsZw0Gr77oHAHqqYQmAdVlRAAAAwAMAAObczRAEQzdUeaVRh0NnxXPnzSD0f9lBIgURWC3Y4rQOe7S/F98CHNLsjzGKFHKqMRpgeX1EBbt+YLdK1EnCz7ppoZg1mKAxp9XE2OQ0bXv49UpZMSDw7XMJxNVDaKQHpYUfEypPDPaQeZidupOkLyt5fS2E6nItv5ZosacJ346asAkv7uogqQiNpmRkgUYFmShZ20FKIbTFkKIhqcWnukU23iHTXzPSDUKc0bXGcZgaSF5svonUzSs7XRKF7gp4HTaBCQSouvmM31iCaMHBcHKpX9puiC6dk6op4LyhzVIxHtjSFsXdSomc+ow+S8s7GK6KRtBHzS5i0Zx1dB1XZPNb1Tdqk/yrMXrAOa1/q05a/lQARU5QW2ye9oRYDkJyeI9uHR7iHFkiFPmDKCcIsQq9vfeVESAxBBORlUmI3tBxryQ1UDDBVfi+XmdGg1YcF4L06+WlYg2AbWotf9oMVm6rBj5K2jofEff0YkLZ8G+h36l7sOQ8DS5pvvGhXKGkyq4Q8qnwDPgHptRKLxhPrhW2vtk8PhFMFZvJt667W4Cg/0WOAarRZGCECCm4CPgDFBxkC7ab2ZZAJx8F7/WjrCqypLJecdtSd5IJxtIMUeoa+mCs/5XBG1CI9bFytUX0wGBtxghIO1d+j2UGJ+bfYghPYCmkILyhfPU4n2tHsPRhcSt0m5FFOYTk1e863X3wZdtn0ngvYcf1T8pcfRibQ3NFdqqTLzoyGhaAG4wO3mqks+6RvArgH9DpJGK/tZmpKDF/9xbpdnhtaxQf7CzEEBzrKG8KY5nUxSdeBW0kkp6KktwfiLcvFYcJZwmUKLXuDE4FCkK3SOqSl5q+6tqfNT9Bzbm6v0dXRvO9qzNSdVF7SuXRLAZ68gb6p4t4DzMqDUWADVz7wP8tWImIZyBTqw5vjja+cnSzImimsfT3gske1mvH2fz4lNaDIYWDNIWehodL2v2CzHEZ4yi+TQb9P9p39POdORo7bVoXrxvdIAo/UlFVYStydS0WIXE0nvSV+VEWCawN83LuFz7fi3R6G9+uKbMjARGmgAhH3yayNqJWvbcn83SnpUq2juRKROV/dsPL/IM6Rh1etzpDxoyJdZOJJfEJUwsRkBQTz10XW0Oppr4f4Qvx9VWtVHb7a2O34Gh+eF5tLVwN0XZPM72Tpc9fse/AI99Eu4QgLfdB8ihITi+YXiZQvc6ZtW55vnGVSBC4TXnHDx6Sqdb6MDy4kwNcEt23TEQh/h/TG6nmc+M4Nx/TcrN4JlWkRaO0pjxhAgAAAAA=');
