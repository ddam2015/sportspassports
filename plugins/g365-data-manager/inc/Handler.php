<?php

if (count(get_included_files()) === 1) {
    exit('Direct access not permitted.');
}

/**
 * Class Handler
 */
class Handler
{

    /**
     * receives a posted variable and checks it against the same one in the session
     *
     * @param  $sessionParameter
     * @param  $sessionValue
     * @return bool
     */
    public function verifySessionValue($sessionParameter, $sessionValue)
    {
        $whiteList = ['token', 'anti_bot'];

        if (in_array($sessionParameter, $whiteList) && isset($_SESSION[$sessionParameter]) &&
            $_SESSION[$sessionParameter] === $sessionValue
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * checks required fields, max length for search input and numbers for pagination
     *
     * @param  $inputArray
     * @return array
     */
    public function validateInput($inputArray)
    {
        $error = [];

        $maxInputLength = Config::getConfig('maxInputLength');

        if (!empty($inputArray)) {
            foreach ($inputArray as $k => &$v) {
                if ($k === 'ls_query' && strlen($v) > $maxInputLength) {
                  //just substr it instead of throwing an error
                  $v = substr( $v, 0, $maxInputLength );
//                     array_push($error, $k);
                } elseif ($k === 'ls_current_page' || $k === 'ls_items_per_page') {
                    if ((int) $v < 0) {
                        array_push($error, $k);
                    }
                }
            }
        }

        return $error;
    }

    /**
     * forms the response object including
     * status (success or failed)
     * message
     * result (html result)
     *
     * @param $status
     * @param $message
     * @param string     $result
     */
    public function formResponse($status, $message, $result = '')
    {
        $cssClass = ($status === 'failed') ? 'error' : 'success';

        $message = "<tr class='{$cssClass}'><td>{$message}</td></tr>";

        echo json_encode([
            'status' => $status,
            'message' => $message,
            'result' => $result
        ]);
        exit;
    }

    /**
     * @param     $searchFieldId: This is html id
     * @param     $query
     * @param int $currentPage
     * @param int $perPage
     *
     * @return array
     * @throws \Exception
     */
    public function getData($searchFieldId, $query, $currentPage = 1, $perPage = 0, $extra_lock = null)
    {
        // get data sources list
        $dataSources = Config::getConfig('dataSources');

        if (!isset($dataSources[$searchFieldId])) {
            throw new \Exception("There is no data info for {$searchFieldId}");
        }

        // get info for the selected data source
        $dbInfo = $dataSources[$searchFieldId];

        switch ($dbInfo['type']) {
            case 'mysql':
                return $this->getDataFromMySQL($dbInfo, $query, $currentPage, $perPage, $extra_lock);
                break;
            case 'mongo':
                return $this->getDataFromMongo($dbInfo, $query, $currentPage, $perPage);
                break;
            default:
                return $this->getDataFromMySQL($dbInfo, $query, $currentPage, $perPage, $extra_lock);
        }
    }

  
    /**
     * @param  $dbInfo
     * @param  $query
     * @param  $currentPage
     * @param  $perPage
     * @throws \Exception
     * @return array
     */
    private function getDataFromMySQL($dbInfo, $query, $currentPage, $perPage, $extra_lock)
    {
        // get connection
        $db = new Database($dbInfo['host'], $dbInfo['database'], $dbInfo['username'], $dbInfo['pass']);

        $sql = "SELECT COUNT(*) FROM {$dbInfo['table']}";
        $add_lock = (object) array();
        // append where clause if search columns is set in the config
        $whereClause = '';
        if (!empty($dbInfo['searchColumns'])) {
            $whereClause .= ' WHERE';
            $counter = 1;

            $binary = $dbInfo['caseSensitive'] == true ? ' BINARY' : '';

            switch ($dbInfo['comparisonOperator']) {
                case '=':
                    $comparisonOperator = '=';
                    break;
                case 'LIKE':
                    $comparisonOperator = 'LIKE';
                    break;
                default:
                    throw new \Exception('Comparison Operator is not valid');
            }

            foreach ($dbInfo['searchColumns'] as $searchColumn) {
                if ($counter == count($dbInfo['searchColumns'])) {
                    // last item
                    $whereClause .= "{$binary} {$searchColumn} {$comparisonOperator} :query{$counter}";
                } else {
                    $whereClause .= "{$binary} {$searchColumn} {$comparisonOperator} :query{$counter} OR";
                }
                ++$counter;
            }
            $col_lock = $dbInfo['col_lock'];
            if( is_array($extra_lock) ) $col_lock = array_merge($col_lock, $extra_lock);

            if( is_array($col_lock) ) {
              $or_switch = false;
              foreach( $col_lock as $col_title => $col_val ) {
                if( strpos(trim($col_val), '-OR') === (strlen($col_val)-3) ) {
                  $col_val = substr($col_val, 0, -3 );
                  $or_switch = true;
                }
                $add_lock->{$col_title} = $col_val;
                if( is_integer($col_val) ) {
                  $comparison_value = '= ' . $col_val;
                } else {
                  if( strpos($col_val, '>') === 0 ) {
//                     $comparison_value = $col_val;
                    continue;
                  } else if( strpos($col_val, '(') === 0 ) {
                    $comparison_value = 'IN ' . $col_val;
                  } else {
                    $comparison_value = "LIKE '" . $col_val . "'";
                  }
                }
                if( $or_switch ) {
                  $or_switch = false;
                  if( strpos($whereClause, ' OR ') === (strlen($whereClause)-4)  ) {
                    $whereClause .= "{$col_title} {$comparison_value})";
                  } else {
                    $whereClause .= " AND ({$col_title} {$comparison_value} OR ";
                  }
                } else {
                  $whereClause .= " AND {$col_title} {$comparison_value}";
                }
              }
            }
            $sql .= $whereClause;
        }

        // get the number of total result
        $db->query($sql);

        if (!empty($whereClause)) {
            switch ($dbInfo['searchPattern']) {
                case 'q':
                    $searchQuery = $query;
                    break;
                case '*q':
                    $searchQuery = "%{$query}";
                    break;
                case 'q*':
                    $searchQuery = "{$query}%";
                    break;
                case '*q*':
                    $searchQuery = "%{$query}%";
                    break;
                default:
                    throw new \Exception('Search Pattern is not valid');
            }
            for ($i = 1; $i <= count($dbInfo['searchColumns']); ++$i) {
              $toBindQuery = ':query' . $i;
              $db->bind($toBindQuery, $searchQuery);
            }
        }
        $resultNumber = $db->resultNum();


        if (isset($dbInfo['maxResult']) && $resultNumber > $dbInfo['maxResult']) {
            $resultNumber = $dbInfo['maxResult'];
        }

        // initialize variables
        $HTML = '';
        $pagesNumber = 1;

        if (!empty($resultNumber) && $resultNumber !== 0) {
            if (!empty($dbInfo['filterResult'])) {
                $fromColumn = implode(',', $dbInfo['filterResult']);
            } else {
                $fromColumn = '*';
            }

            $baseSQL = "SELECT {$fromColumn} FROM {$dbInfo['table']}";

            if (!empty($whereClause)) {
                // set order by
                $orderBy = !empty($dbInfo['orderBy']) ? $dbInfo['orderBy'] : $dbInfo['searchColumns'][0];

                // set order direction
                $allowedOrderDirection = ['ASC', 'DESC'];
                if (!empty($dbInfo['orderDirection']) && in_array($dbInfo['orderDirection'], $allowedOrderDirection)) {
                    $orderDirection = $dbInfo['orderDirection'];
                } else {
                    $orderDirection = 'ASC';
                }

                $baseSQL .= "{$whereClause} ORDER BY {$orderBy} {$orderDirection}";
            }

            if ($perPage === 0) {
                if (isset($dbInfo['maxResult'])) {
                    $baseSQL .= " LIMIT {$dbInfo['maxResult']}";
                }

                // show all
                $db->query($baseSQL);

                if (!empty($whereClause)) {
                    for ($i = 1; $i <= count($dbInfo['searchColumns']); ++$i) {
                        $toBindQuery = ':query' . $i;
                        $db->bind($toBindQuery, $searchQuery);
                    }
                }
            } else {
                /*
                 * pagination
                 *
                 * calculate total pages
                 */
                if ($resultNumber < $perPage) {
                    $pagesNumber = 1;
                } elseif ($resultNumber > $perPage) {
                    if ($resultNumber % $perPage === 0) {
                        $pagesNumber = floor($resultNumber / $perPage);
                    } else {
                        $pagesNumber = floor($resultNumber / $perPage) + 1;
                    }
                } else {
                    $pagesNumber = $resultNumber / $perPage;
                }

                if (isset($dbInfo['maxResult'])) {
                    // calculate the limit
                    if ($currentPage == 1) {
                        if ($perPage > $dbInfo['maxResult']) {
                            $limit = $dbInfo['maxResult'];
                        } else {
                            $limit = $perPage;
                        }
                    } elseif ($currentPage == $pagesNumber) {
                        // last page
                        $limit = $dbInfo['maxResult'] - (($currentPage - 1) * $perPage);
                    } else {
                        $limit = $perPage;
                    }
                } else {
                    $limit = $perPage;
                }

                /*
                 * pagination
                 *
                 * calculate start
                 */
                $start = ($currentPage > 0) ? ($currentPage - 1) * $perPage : 0;

                $db->query(
                    "{$baseSQL} LIMIT {$start}, {$limit}"
                );

                if (!empty($whereClause)) {
                    for ($i = 1; $i <= count($dbInfo['searchColumns']); ++$i) {
                        $toBindQuery = ':query' . $i;
                        $db->bind($toBindQuery, $searchQuery);
                    }
                }
            }

            // run the query and get the result
            $rows = $db->resultset();

            // if requested, generate column headers
            $headers = !empty($rows[0]) ? array_keys($rows[0]) : [];

            if (isset($dbInfo['displayHeader']['active']) && $dbInfo['displayHeader']['active'] == true) {
                $mapper = !empty($dbInfo['displayHeader']['mapper']) ? $dbInfo['displayHeader']['mapper'] : [];

                if (!empty($headers)) {
                    foreach ($headers as $aHeaderKey => $aHeader) {
                        $aHeaderText = array_key_exists($aHeader, $mapper) ? $mapper[$aHeader] : $aHeader;

                        $headers[$aHeaderKey] = $aHeaderText;
                    }
                }
            }
        } else {
            $headers = [];
            $rows = [];
        }
        // form the return
        return [
            'headers'           => $headers,
            'rows'              => $rows,
            'number_of_results' => (int) $resultNumber,
            'total_pages'       => $pagesNumber,
            'add_lock'          => $add_lock
        ];
    }
    
    /**
     * @param $dbInfo
     * @param $query
     * @param $currentPage
     * @param $perPage
     * @return array
     * @throws \Exception
     */
    private function getDataFromMongo($dbInfo, $query, $currentPage, $perPage)
    {
        $mongoClient = new \MongoClient($dbInfo['server']);
        $database = $mongoClient->selectDB($dbInfo['database']);
        $collection = $database->selectCollection($dbInfo['collection']);

        $searchField = $dbInfo['searchField'];
        $regex = new \MongoRegex("/^{$query}/i");
        $criteria = [$searchField => $regex];
        $results = $collection->find($criteria, $dbInfo['filterResult']);

        if (!$results instanceof \MongoCursor) {
            throw new \Exception('There is an issue getting data from Mongodb');
        }

        $resultNumber = $results->count();
        $start = ($currentPage > 0) ? ($currentPage - 1) * $perPage : 0;
        $rows = $results->limit($perPage)->skip($start);

        /*
         * pagination
         *
         * calculate total pages
         */
        if ($resultNumber < $perPage) {
            $pagesNumber = 1;
        } elseif ($resultNumber > $perPage) {
            if ($resultNumber % $perPage === 0) {
                $pagesNumber = floor($resultNumber / $perPage);
            } else {
                $pagesNumber = floor($resultNumber / $perPage) + 1;
            }
        } else {
            $pagesNumber = $resultNumber / $perPage;
        }

        // form the return
        return [
            'rows' => $rows,
            'number_of_results' => (int) $resultNumber,
            'total_pages' => $pagesNumber,
        ];
    }
    
    /**
     * Calculate the timestamp difference between the time page is loaded
     * and the time searching is started for the first time in seconds
     *
     * @param  $pageLoadedAt
     * @return bool
     */
    public function verifyBotSearched($pageLoadedAt)
    {
        // if searching starts less than start time offset it seems it's a Bot
        return (time() - $pageLoadedAt < Config::getConfig('searchStartTimeOffset')) ? false : true;
    }

    /**
     * @param $dbInfo
     * @param $query
     * @param $currentPage
     * @param $perPage
     *
     * @return array
     * @throws \Exception
     */
    public function renderView($dbInfo, $query, $currentPage, $perPage, $extra_lock, $user_access)
    {
        $result = $this->getData($dbInfo, $query, $currentPage, $perPage, $extra_lock);
        $headers = $result['headers'];
        $rows = $result['rows'];
        $add_lock = $result['add_lock'];

        $DS = DIRECTORY_SEPARATOR;

        $dataSources = Config::getConfig('dataSources');
        $templateName = $dataSources[$dbInfo]['template'];
        if (!isset($dataSources[$dbInfo]['template'])) $templateName = Config::getConfig('template');
      
        $templatePath = realpath(__DIR__ . $DS . 'ls_templates' . $DS . $templateName);

        if (file_exists($templatePath) !== true) {
            throw new \Exception('Template file not found');
        }

        $html = include_once $templatePath;

        return [
            'html' => $html,
            'number_of_results' => $result['number_of_results'],
            'total_pages'       => $result['total_pages'],
        ];
    }

    /**
     * @return bool
     */
    public function isAJAX()
    {
        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'];
        if (!empty($requestedWith) && strtolower($requestedWith) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
}
