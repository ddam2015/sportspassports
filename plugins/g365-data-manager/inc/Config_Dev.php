<?php

if (count(get_included_files()) === 1) {
    exit('Direct access not permitted.');
}
/**
 * Class Config
 */
class Config
{
/**
 * @var array
 */
  private static $configs = [
        // ***** Database ***** //
        'dataSources'           => [
//             'player_profiles' => [
//                 'host'               => 'localhost',
//                 'database'           => 'sportspassports-dev-wp-Q1ZlLivS',
//                 'username'           => 'OkQTcxehx7sk',
//                 'pass'               => 'ueUN8bXkkvWt6vqQ',
//                 'table'              => 'wp_54ab678738_g365_players',
//                 // specify the name of search columns
//                 'searchColumns'      => ['name'],
//                 // specify order by column. This is optional
//                 'orderBy'            => 'name',
//                 // specify order direction e.g. ASC or DESC. This is optional
//                 'orderDirection'     => '',
//                 /**
//                  * filter the result by entering table column names
//                  * to get all the columns, remove filterResult or make it an empty array
//                  */
//                 'filterResult'       => ['name','city','state','nickname'],
//                 /**
//                  * specify search query comparison operator.
//                  * possible values for comparison operators are: 'LIKE' and '='. this is required
//                  */
//                 'comparisonOperator' => 'LIKE',
//                 /**
//                  * searchPattern is used to specify how the query is searched.
//                  * possible values are: 'q', '*q', 'q*', '*q*'. this is required
//                  */
//                 'searchPattern'      => '*q*',
//                 // specify search query case sensitivity
//                 'caseSensitive'      => false,
//                 // to limit the maximum number of result uncomment this:
//                 'maxResult' => 40,
//                 // to display column header, change 'active' value to true
//                 'displayHeader' => [
//                     'active' => true,
//                     'mapper' => [
//                       'name'      => 'Player Name',
//                       'city'      => 'City',
//                       'state'     => 'State',
//                       'nickname'  => "URL"
//                     ]
//                 ],
//                 'type'               => 'mysql',
//                 'col_lock'           => [
//                   'enabled'   => 1
//                 ],
//                 'template'           => 'player_search.php'
//             ],
            'player_profiles' => [
                  'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                  'database'           => 'sportspassports-dev',
                  'username'           => 'doadmin',
                  'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                  'table'              => 'wp_54ab678738_g365_players',
                  // specify the name of search columns
                  'searchColumns'      => ['name'],
                  // specify order by column. This is optional
                  'orderBy'            => 'name',
                  // specify order direction e.g. ASC or DESC. This is optional
                  'orderDirection'     => '',
                  /**
                   * filter the result by entering table column names
                   * to get all the columns, remove filterResult or make it an empty array
                   */
                  'filterResult'       => ['name','city','state','nickname'],
                  /**
                   * specify search query comparison operator.
                   * possible values for comparison operators are: 'LIKE' and '='. this is required
                   */
                  'comparisonOperator' => 'LIKE',
                  /**
                   * searchPattern is used to specify how the query is searched.
                   * possible values are: 'q', '*q', 'q*', '*q*'. this is required
                   */
                  'searchPattern'      => '*q*',
                  // specify search query case sensitivity
                  'caseSensitive'      => false,
                  // to limit the maximum number of result uncomment this:
                  'maxResult' => 40,
                  // to display column header, change 'active' value to true
                  'displayHeader' => [
                      'active' => true,
                      'mapper' => [
                        'name'      => 'Player Name',
                        'city'      => 'City',
                        'state'     => 'State',
                        'nickname'  => "URL"
                      ]
                  ],
                  'type'               => 'mysql',
                  'col_lock'           => [
                    'enabled'   => 1
                  ],
                  'template'           => 'player_search.php'
              ],
            'dcp_players' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                // specify the name of search columns
                'searchColumns'      => ['name'],
                // specify order by column. This is optional
                'orderBy'            => 'name',
                // specify order direction e.g. ASC or DESC. This is optional
                'orderDirection'     => '',
                /**
                 * filter the result by entering table column names
                 * to get all the columns, remove filterResult or make it an empty array
                 */
                'filterResult'       => ['id','profile_img','name','city','state','nickname'],
                /**
                 * specify search query comparison operator.
                 * possible values for comparison operators are: 'LIKE' and '='. this is required
                 */
                'comparisonOperator' => 'LIKE',
                /**
                 * searchPattern is used to specify how the query is searched.
                 * possible values are: 'q', '*q', 'q*', '*q*'. this is required
                 */
                'searchPattern'      => '*q*',
                // specify search query case sensitivity
                'caseSensitive'      => false,
                // to limit the maximum number of result uncomment this:
                'maxResult' => 20,
                // to display column header, change 'active' value to true
                'displayHeader' => [
                    'active' => true,
                    'mapper' => [
                      'name'      => 'Player Name',
                      'city'      => 'City',
                      'state'     => 'State',
                      'nickname'  => "URL"
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'dcp_player.php'
            ],
            'hhh_profiles' => [
                  'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                  'database'           => 'sportspassports-dev',
                  'username'           => 'doadmin',
                  'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                  'table'              => 'wp_54ab678738_g365_players',
                  // specify the name of search columns
                  'searchColumns'      => ['name'],
                  // specify order by column. This is optional
                  'orderBy'            => 'name',
                  // specify order direction e.g. ASC or DESC. This is optional
                  'orderDirection'     => '',
                  /**
                   * filter the result by entering table column names
                   * to get all the columns, remove filterResult or make it an empty array
                   */
                  'filterResult'       => ['name','city','state','nickname'],
                  /**
                   * specify search query comparison operator.
                   * possible values for comparison operators are: 'LIKE' and '='. this is required
                   */
                  'comparisonOperator' => 'LIKE',
                  /**
                   * searchPattern is used to specify how the query is searched.
                   * possible values are: 'q', '*q', 'q*', '*q*'. this is required
                   */
                  'searchPattern'      => '*q*',
                  // specify search query case sensitivity
                  'caseSensitive'      => false,
                  // to limit the maximum number of result uncomment this:
                  'maxResult' => 40,
                  // to display column header, change 'active' value to true
                  'displayHeader' => [
                      'active' => true,
                      'mapper' => [
                        'name'      => 'Player Name',
                        'city'      => 'City',
                        'state'     => 'State',
                        'nickname'  => "URL"
                      ]
                  ],
                  'type'               => 'mysql',
                  'col_lock'           => [
                    'enabled'   => 1
                  ],
                  'template'           => 'player_search.php'
             ],
            'player_photo' => [
              'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
              'database'           => 'sportspassports-dev',
              'username'           => 'doadmin',
              'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
              'table'              => 'wp_54ab678738_g365_players',
              // specify the name of search columns
              'searchColumns'      => ['name'],
              // specify order by column. This is optional
              'orderBy'            => 'name',
              // specify order direction e.g. ASC or DESC. This is optional
              'orderDirection'     => '',
              /**
               * filter the result by entering table column names
               * to get all the columns, remove filterResult or make it an empty array
               */
              'filterResult'       => ['id','profile_img','name','city','state','nickname'],
              /**
               * specify search query comparison operator.
               * possible values for comparison operators are: 'LIKE' and '='. this is required
               */
              'comparisonOperator' => 'LIKE',
              /**
               * searchPattern is used to specify how the query is searched.
               * possible values are: 'q', '*q', 'q*', '*q*'. this is required
               */
              'searchPattern'      => '*q*',
              // specify search query case sensitivity
              'caseSensitive'      => false,
              // to limit the maximum number of result uncomment this:
              'maxResult' => 20,
              // to display column header, change 'active' value to true
              'displayHeader' => [
                  'active' => true,
                  'mapper' => [
                    'name'      => 'Player Name',
                    'city'      => 'City',
                    'state'     => 'State',
                    'nickname'  => "URL"
                  ]
              ],
              'type'               => 'mysql',
              'col_lock'           => [
                'enabled'   => 1
              ],
              'template'           => 'player_photo.php'
            ],
            'player_photo_search' => [
              'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
              'database'           => 'sportspassports-dev',
              'username'           => 'doadmin',
              'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
              'table'              => 'wp_54ab678738_g365_players',
              // specify the name of search columns
              'searchColumns'      => ['name'],
              // specify order by column. This is optional
              'orderBy'            => 'name',
              // specify order direction e.g. ASC or DESC. This is optional
              'orderDirection'     => '',
              /**
               * filter the result by entering table column names
               * to get all the columns, remove filterResult or make it an empty array
               */
              'filterResult'       => ['id','profile_img','name','city','state','nickname'],
              /**
               * specify search query comparison operator.
               * possible values for comparison operators are: 'LIKE' and '='. this is required
               */
              'comparisonOperator' => 'LIKE',
              /**
               * searchPattern is used to specify how the query is searched.
               * possible values are: 'q', '*q', 'q*', '*q*'. this is required
               */
              'searchPattern'      => '*q*',
              // specify search query case sensitivity
              'caseSensitive'      => false,
              // to limit the maximum number of result uncomment this:
              'maxResult' => 20,
              // to display column header, change 'active' value to true
              'displayHeader' => [
                  'active' => true,
                  'mapper' => [
                    'name'      => 'Player Name',
                    'city'      => 'City',
                    'state'     => 'State',
                    'nickname'  => "URL"
                  ]
              ],
              'type'               => 'mysql',
              'col_lock'           => [
                'enabled'   => 1
              ],
              'template'           => 'player_photo_search.php'
            ],
            'player_pending_photo_search' => [
              'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
              'database'           => 'sportspassports-dev',
              'username'           => 'doadmin',
              'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
              'table'              => 'wp_54ab678738_g365_players',
              // specify the name of search columns
              'searchColumns'      => ['name'],
              // specify order by column. This is optional
              'orderBy'            => 'name',
              // specify order direction e.g. ASC or DESC. This is optional
              'orderDirection'     => '',
              /**
               * filter the result by entering table column names
               * to get all the columns, remove filterResult or make it an empty array
               */
              'filterResult'       => ['id','profile_img','name','city','state','nickname'],
              /**
               * specify search query comparison operator.
               * possible values for comparison operators are: 'LIKE' and '='. this is required
               */
              'comparisonOperator' => 'LIKE',
              /**
               * searchPattern is used to specify how the query is searched.
               * possible values are: 'q', '*q', 'q*', '*q*'. this is required
               */
              'searchPattern'      => '*q*',
              // specify search query case sensitivity
              'caseSensitive'      => false,
              // to limit the maximum number of result uncomment this:
              'maxResult' => 20,
              // to display column header, change 'active' value to true
              'displayHeader' => [
                  'active' => true,
                  'mapper' => [
                    'name'      => 'Player Name',
                    'city'      => 'City',
                    'state'     => 'State',
                    'nickname'  => "URL"
                  ]
              ],
              'type'               => 'mysql',
              'col_lock'           => [
                'enabled'   => 1
              ],
              'template'           => 'player_pending_photo_search.php'
              ],
              'player_video_search' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                // specify the name of search columns
                'searchColumns'      => ['name'],
                // specify order by column. This is optional
                'orderBy'            => 'name',
                // specify order direction e.g. ASC or DESC. This is optional
                'orderDirection'     => '',
                /**
                 * filter the result by entering table column names
                 * to get all the columns, remove filterResult or make it an empty array
                 */
                'filterResult'       => ['id','profile_img','name','city','state','nickname'],
                /**
                 * specify search query comparison operator.
                 * possible values for comparison operators are: 'LIKE' and '='. this is required
                 */
                'comparisonOperator' => 'LIKE',
                /**
                 * searchPattern is used to specify how the query is searched.
                 * possible values are: 'q', '*q', 'q*', '*q*'. this is required
                 */
                'searchPattern'      => '*q*',
                // specify search query case sensitivity
                'caseSensitive'      => false,
                // to limit the maximum number of result uncomment this:
                'maxResult' => 20,
                // to display column header, change 'active' value to true
                'displayHeader' => [
                    'active' => true,
                    'mapper' => [
                      'name'      => 'Player Name',
                      'city'      => 'City',
                      'state'     => 'State',
                      'nickname'  => "URL"
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'player_video_search.php'
            ],
            'player_pending_video_search' => [
              'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
              'database'           => 'sportspassports-dev',
              'username'           => 'doadmin',
              'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
              'table'              => 'wp_54ab678738_g365_players',
              // specify the name of search columns
              'searchColumns'      => ['name'],
              // specify order by column. This is optional
              'orderBy'            => 'name',
              // specify order direction e.g. ASC or DESC. This is optional
              'orderDirection'     => '',
              /**
               * filter the result by entering table column names
               * to get all the columns, remove filterResult or make it an empty array
               */
              'filterResult'       => ['id','profile_img','name','city','state','nickname'],
              /**
               * specify search query comparison operator.
               * possible values for comparison operators are: 'LIKE' and '='. this is required
               */
              'comparisonOperator' => 'LIKE',
              /**
               * searchPattern is used to specify how the query is searched.
               * possible values are: 'q', '*q', 'q*', '*q*'. this is required
               */
              'searchPattern'      => '*q*',
              // specify search query case sensitivity
              'caseSensitive'      => false,
              // to limit the maximum number of result uncomment this:
              'maxResult' => 20,
              // to display column header, change 'active' value to true
              'displayHeader' => [
                  'active' => true,
                  'mapper' => [
                    'name'      => 'Player Name',
                    'city'      => 'City',
                    'state'     => 'State',
                    'nickname'  => "URL"
                  ]
              ],
              'type'               => 'mysql',
              'col_lock'           => [
                'enabled'   => 1
              ],
              'template'           => 'player_pending_video_search.php'
            ],
            'player_badge' => [
              'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
              'database'           => 'sportspassports-dev',
              'username'           => 'doadmin',
              'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
              'table'              => 'wp_54ab678738_g365_players',
              // specify the name of search columns
              'searchColumns'      => ['name'],
              // specify order by column. This is optional
              'orderBy'            => 'name',
              // specify order direction e.g. ASC or DESC. This is optional
              'orderDirection'     => '',
              /**
               * filter the result by entering table column names
               * to get all the columns, remove filterResult or make it an empty array
               */
              'filterResult'       => ['id','profile_img','name','city','state','nickname'],
              /**
               * specify search query comparison operator.
               * possible values for comparison operators are: 'LIKE' and '='. this is required
               */
              'comparisonOperator' => 'LIKE',
              /**
               * searchPattern is used to specify how the query is searched.
               * possible values are: 'q', '*q', 'q*', '*q*'. this is required
               */
              'searchPattern'      => '*q*',
              // specify search query case sensitivity
              'caseSensitive'      => false,
              // to limit the maximum number of result uncomment this:
              'maxResult' => 20,
              // to display column header, change 'active' value to true
              'displayHeader' => [
                  'active' => true,
                  'mapper' => [
                    'name'      => 'Player Name',
                    'city'      => 'City',
                    'state'     => 'State',
                    'nickname'  => "URL"
                  ]
              ],
              'type'               => 'mysql',
              'col_lock'           => [
                'enabled'   => 1
              ],
              'template'           => 'player_badge.php'
            ],
            'club_profiles' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['name','abbreviation','city','state','nickname'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'type'      => 1,
                  'enabled'   => 1
                ],
                'template'           => 'club_search.php'
            ],
            'club_profiles_admin' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','abbreviation','city','state'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'type'      => 1
                ],
                'template'           => 'club_search_admin.php'
            ],
            'player_names' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','first_name','last_name','city','state','verified','birthday','grad_year','access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1,
//                   'verified'  => '(0,1,2)'
                ],
                'template'           => 'player_search_claim.php'
            ],
            'player_names_admin' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name','id'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state','verified', 'access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 80,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Player Name',
                      'city'          => 'City',
                      'state'         => 'State',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'player_search_form_admin.php'
            ],
          //for dcp registering to act
            'dcp_pl_ev' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','city','state','access','first_name','last_name','access', 'email', 'school'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'dcp_player_search_claim.php'
            ],
            'player_award' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 80,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Player Name',
                      'city'          => 'City',
                      'state'         => 'State',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'player_award_search.php'
            ],
            'player_rosters' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state','verified','birthday','grad_year','access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1,
//                   'verified'  => '(0,1,2)'
                ],
                'template'           => 'player_search_form.php'
            ],
            'player_rosters_admin' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state','verified','birthday','grad_year','access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1,
//                   'verified'  => '(0,1,2)'
                ],
                'template'           => 'player_search_admin.php'
            ],
            'pl_ev' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','city','state','access','first_name','last_name','access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'player_search_claim.php'
            ],
            'orgs' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','abbreviation','profile_img','city','state','country'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'club_search_form.php'
            ],
            'club_names' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','abbreviation','profile_img','city','state','country','access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'type'      => 1,
                  'enabled'   => 1
                ],
                'template'           => 'club_search_claim.php'
            ],
            'club_players' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','abbreviation','profile_img','city','state','country'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'type'      => 1
                ],
                'template'           => 'club_search_form.php'
            ],
            'club_names_admin' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list' , 'id'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','abbreviation','profile_img','city','state','country'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Club Name',
                      'abbreviation'  => 'Abbr.',
                      'profile_img'   => 'Profile Image',
                      'city'          => 'City',
                      'state'         => 'State',
                      'country'       => 'Country'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'type'      => 1
                ],
                'template'           => 'club_search_form.php'
            ],
            'school_names' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_organizations',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','abbreviation','profile_img','city','state','country'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'School Name',
                      'abbreviation'  => 'Abbr.',
                      'profile_img'   => 'Profile Image',
                      'city'          => 'City',
                      'state'         => 'State',
                      'country'       => 'Country'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'type'      => 2,
                  'enabled'   => 1
                ],
                'template'           => 'school_search_form.php'
            ],
            'event_profiles' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','eventtime','enabled','name','logo_img','dates','nickname'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                      'eventtime'     => 'Event Start',
                      'logo_img'      => 'Event Logo Image',
                      'dates'         => 'Dates',
                      'nickname'      => 'Url'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_search.php'
            ],
            'event_profiles_admin' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','eventtime','name','nickname'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'event_search_admin.php'
            ],
            'event_stats' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','eventtime','enabled','name','logo_img','dates','nickname'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                      'eventtime'     => 'Event Start',
                      'logo_img'      => 'Event Logo Image',
                      'dates'         => 'Dates',
                      'nickname'      => 'Url'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_stats.php'
            ],
            'event_expo_csv' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','eventtime','enabled','name','logo_img','dates','nickname'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                      'eventtime'     => 'Event Start',
                      'logo_img'      => 'Event Logo Image',
                      'dates'         => 'Dates',
                      'nickname'      => 'Url'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_search_expo_csv.php'
            ],
            'event_admin_tournament' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_admin_tournament.php'
            ],
            'player_stat_table' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'player_stat_table.php'
            ],
            'indi_pl_stat' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                // specify the name of search columns
                'searchColumns'      => ['name'],
                // specify order by column. This is optional
                'orderBy'            => 'name',
                // specify order direction e.g. ASC or DESC. This is optional
                'orderDirection'     => '',
                /**
                 * filter the result by entering table column names
                 * to get all the columns, remove filterResult or make it an empty array
                 */
                'filterResult'       => ['id', 'name','city','state','nickname'],
                /**
                 * specify search query comparison operator.
                 * possible values for comparison operators are: 'LIKE' and '='. this is required
                 */
                'comparisonOperator' => 'LIKE',
                /**
                 * searchPattern is used to specify how the query is searched.
                 * possible values are: 'q', '*q', 'q*', '*q*'. this is required
                 */
                'searchPattern'      => '*q*',
                // specify search query case sensitivity
                'caseSensitive'      => false,
                // to limit the maximum number of result uncomment this:
                'maxResult' => 20,
                // to display column header, change 'active' value to true
                'displayHeader' => [
                    'active' => true,
                    'mapper' => [
                      'name'      => 'Player Name',
                      'city'      => 'City',
                      'state'     => 'State',
                      'nickname'  => "URL"
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'indi_pl_stat.php'
            ],
            'stat_leaderboard' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'stat_leaderboard.php'
            ],
            'event_admin_stat' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_admin_stat.php'
            ],
            'event_admin_team_stat' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_admin_team_stat.php'
            ],
            'event_names' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name','short_name','eventtime'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_search_form.php'
            ],
            'event_names_div' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name','short_name','eventtime','divisions', 'dates'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_search_form_divisions.php'
            ],
            'team_names' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_teams',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'level',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name','level','team_type'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 50,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'team_search_form.php'
            ],
            'team_names_admin' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_teams',
                'searchColumns'      => ['search_list'],
                'orderBy'            => 'level',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name','level','team_type'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 50,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'team_search_admin.php'
            ],
           'positions' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_positions',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Position Name',
                      'id'            => 'Position ID'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'position_search.php'
            ],
            'coach_names' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_coaches',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name','city','state'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Coach Name'
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'coaches_search_form.php'
            ],
            'event_all_tournament' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_all_tournament.php'
            ],
            'event_all_tournament_player' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name','id'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state','verified', 'access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 80,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Player Name',
                      'city'          => 'City',
                      'state'         => 'State',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'event_all_tournament_player.php'
              ],
              'event_admin_team' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_admin_team.php'
            ],
            'event_pass_rep' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_events',
                'searchColumns'      => ['name','short_name'],
                'orderBy'            => 'eventtime',
                'orderDirection'     => 'DESC',
                'filterResult'       => ['id','name'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Event Name',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1
                ],
                'template'           => 'event_pass_rep.php'
            ],
            'player_merge' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name','id'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state','verified', 'access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 80,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Player Name',
                      'city'          => 'City',
                      'state'         => 'State',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'player_merge.php'
              ],
            'player_keep' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name','id'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','name','city','state','verified', 'access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 80,
                'displayHeader'      => [
                    'active'      => true,
                    'mapper'      => [
                      'name'          => 'Player Name',
                      'city'          => 'City',
                      'state'         => 'State',
                    ]
                ],
                'type'               => 'mysql',
                'col_lock'           => [],
                'template'           => 'player_keep.php'
              ],
            'user_photo_claim' => [
                'host'               => 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060',
                'database'           => 'sportspassports-dev',
                'username'           => 'doadmin',
                'pass'               => 'AVNS_zcYevEXyOsnS4WlkMOE',
                'table'              => 'wp_54ab678738_g365_players',
                'searchColumns'      => ['name'],
                'orderBy'            => 'name',
                'orderDirection'     => '',
                'filterResult'       => ['id','first_name','last_name','city','state','verified','birthday','grad_year','access'],
                'comparisonOperator' => 'LIKE',
                'searchPattern'      => '*q*',
                'caseSensitive'      => false,
                'maxResult'          => 20,
                'displayHeader'      => [
                    'active'      => false,
                    'mapper'      => []
                ],
                'type'               => 'mysql',
                'col_lock'           => [
                  'enabled'   => 1,
//                   'verified'  => '(0,1,2)'
                ],
                'template'           => 'player_photo_claim.php'
            ],
            'mainMongo' => [
                'server'       => 'your_server',
                'database'     => 'local',
                'collection'   => 'your_collection',
                'filterResult' => [],
                'searchField'  => 'your_collection_search_field',
                'type'         => 'mongo',
            ]
        ],
        // ***** Form ***** //
        'antiBot'               => "ajaxlivesearch_guard",
        // Assigning more than 3 seconds is not recommended
        'searchStartTimeOffset' => 2,
        // ***** Search Input ***** //
        'maxInputLength'        => 20,
        // ***** Template ***** //
        'template'              => 'default.php'
    ];

    /**
     *
     * @param  $key
     * @throws \Exception
     * @return mixed
     */
    public static function getConfig($key)
    {
        if (!array_key_exists($key, static::$configs)) {
            throw new \Exception("Key: {$key} does not exist in the configs");
        }

        return static::$configs[$key];
    }
}
