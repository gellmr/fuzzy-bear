<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
*/


$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = getenv('FZB_DATABASE_HOSTNAME');
$db['default']['username'] = getenv('FZB_DATABASE_USERNAME');
$db['default']['password'] = getenv('FZB_DATABASE_PASSWORD');
$db['default']['database'] = getenv('FZB_DATABASE_NAME');

$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = TRUE;


/* End of file database.php */
/* Location: ./application/config/database.php */