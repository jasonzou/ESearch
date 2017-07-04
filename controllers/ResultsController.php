<?php

/**
 * @package     omeka
 * @subpackage  esearch
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


class ESearch_ResultsController
    extends Omeka_Controller_AbstractActionController
{


    /**
     * Cache the facets table.
     */
    public function init()
    {
        $this->_fields = $this->_helper->db->getTable('ESearchField');
    }


    /**
     * Intercept queries from the simple search form.
     */
    public function interceptorAction()
    {
        $this->_redirect('esearch?'.http_build_query(array(
            'q' => $this->_request->getParam('query')
        )));
    }


    /**
     * Display E results.
     */
    public function indexAction()
    {

        // Get pagination settings.
        $limit = get_option('per_page_public');
        $page  = $this->_request->page ? $this->_request->page : 1;
        $start = ($page-1) * $limit;


        // determine whether to display private items or not
        // items will only be displayed if:
        // esearch_display_private_items has been enabled in the E Search admin panel
        // user is logged in
        // user_role has sufficient permissions

        $user = current_user();
        if(get_option('esearch_display_private_items')
            && $user
            && is_allowed('Items','showNotPublic')) {
            // limit to public items
            $limitToPublicItems = false;
        } else {
            $limitToPublicItems = true;
        }

        // Execute the query.
        $results = $this->_search($start, $limit, $limitToPublicItems);

        // Set the pagination.
        Zend_Registry::set('pagination', array(
            'page'          => $page,
            'total_results' => $results->response->numFound,
            'per_page'      => $limit
        ));

        // Push results to the view.
        $this->view->results = $results;

    }


    /**
     * Pass setting to E search
     *
     * @param int $offset Results offset
     * @param int $limit  Limit per page
     * @return EResultDoc E results
     */
    protected function _search($offset, $limit, $limitToPublicItems = true)
    {

        // Connect to E.
        $solr = ESearch_Helpers_Index::connect();

        // Get the parameters.
        $params = $this->_getParameters();

        // Construct the query.
        $query = $this->_getQuery($limitToPublicItems);

        // Execute the query.
        return $e>search($query, $offset, $limit, $params);

    }


    /**
     * Form the complete E query.
     *
     * @return string The E query.
     */
    protected function _getQuery($limitToPublicItems = true)
    {

        // Get the `q` GET parameter.
        $query = $this->_request->q;

        // If defined, replace `:`; otherwise, revert to `*:*`.
        // Also, clean it up some.
        if (!empty($query)) {
            $query = str_replace(':', ' ', $query);
            $to_remove = array('[', ']');
            foreach ($to_remove as $c) {
                $query = str_replace($c, '', $query);
            }
        } else {
            $query = '*:*';
        }

        // Get the `facet` GET parameter
        $facet = $this->_request->facet;

        // Form the composite E query.
        if (!empty($facet)) $query .= " AND {$facet}";

        // Limit the query to public items if required
        if($limitToPublicItems) {
           $query .= ' AND public:"true"';
        }

        return $query;

    }


    /**
     * Construct the E search parameters.
     *
     * @return array Array of fields to pass to E
     */
    protected function _getParameters()
    {

        // Get a list of active facets.
        $facets = $this->_fields->getActiveFacetKeys();

        return array(

            'facet'               => 'true',
            'facet.field'         => $facets,
            'facet.mincount'      => 1,
            'facet.limit'         => get_option('esearch_facet_limit'),
            'facet.sort'          => get_option('esearch_facet_sort'),
            'hl'                  => get_option('esearch_hl')?'true':'false',
            'hl.snippets'         => get_option('esearch_hl_snippets'),
            'hl.fragsize'         => get_option('esearch_hl_fragsize'),
            'hl.maxAnalyzedChars' => get_option('esearch_hl_max_analyzed_chars'),
            'hl.fl'               => '*_t'

        );

    }


}
