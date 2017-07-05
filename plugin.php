<?php
/**
 * @package     omeka
 * @subpackage  esearch
 * @copyright   2017 Jason Zou
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

if (!defined('ES_DIR')) define('ES_DIR', dirname(__FILE__));

// Plugin manager class:
require_once ES_DIR.'/ESearchPlugin.php';

// Elasticsearch PHP Client library:
require_once ES_DIR. 'vendor/autoload.php';

// ESearch utility classes:
require_once ES_DIR.'/lib/ESearch/Addon/Addon.php';
require_once ES_DIR.'/lib/ESearch/Addon/Config.php';
require_once ES_DIR.'/lib/ESearch/Addon/Field.php';
require_once ES_DIR.'/lib/ESearch/Addon/Indexer.php';
require_once ES_DIR.'/lib/ESearch/Addon/Manager.php';
require_once ES_DIR.'/lib/ESearch/Utils.php';
require_once ES_DIR.'/lib/ESearch/DbPager.php';

// Helpers:
require_once ES_DIR.'/helpers/ESearch_Helpers_View.php';
require_once ES_DIR.'/helpers/ESearch_Helpers_Index.php';
require_once ES_DIR.'/helpers/ESearch_Helpers_Facet.php';

// Forms:
require_once ES_DIR.'/forms/ESearch_Form_Server.php';
require_once ES_DIR.'/forms/ESearch_Form_Results.php';
require_once ES_DIR.'/forms/ESearch_Form_Reindex.php';

// Jobs:
require_once ES_DIR.'/jobs/ESearch_Job_Reindex.php';

$es = new ESearchPlugin();
$es->setUp();
