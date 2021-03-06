<?php

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

class ESearch_Form_Reindex extends Omeka_Form
{


    /**
     * Build the "Clear and Reindex" button.
     */
    public function init()
    {

        parent::init();

        $this->addElement('submit', 'submit', array(
            'label' => __('Clear and Reindex')
        ));

    }


}
