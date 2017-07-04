<?php

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


class ESearch_Job_Reindex extends Omeka_Job_AbstractJob
{


    /**
     * Reindex all records.
     */
    public function perform()
    {
        ESearch_Helpers_Index::deleteAll();
        ESearch_Helpers_Index::indexAll();
    }


}
