<?php

// if(empty($_SERVER['HTTP_ORIGIN'])){
//   $http_origin = "https://".$_SERVER['HTTP_HOST'];
// }else{
  $http_origin = $_SERVER['HTTP_ORIGIN'];
// }
if (count(get_included_files()) === 1) {
  exit('Direct access not permitted.');
}
/**
 * Session
 */
class g365_Session {
 
  /**
   * Db Object
   */
  private $db;

  public function __construct(){

    // get data sources list
    $dataSources = Config::getConfig('dataSources');
    if (!isset($dataSources['player_profiles'])) throw new \Exception("There is no data info for database connection.");

    // Instantiate new Database object
    $this->db = new Database($dataSources['player_profiles']['host'], $dataSources['player_profiles']['database'], $dataSources['player_profiles']['username'], $dataSources['player_profiles']['pass']);

//     // get info for the selected data source
//     $dbInfo = $dataSources['player_profiles'];
//     // Instantiate new Database object
//     $this->db = DB::getConnection($dbInfo);

    // Set handler to overide SESSION
    session_set_save_handler(
      array($this, "_open"),
      array($this, "_close"),
      array($this, "_read"),
      array($this, "_write"),
      array($this, "_destroy"),
      array($this, "_gc")
    );
    
//     session_name( 'g365_SID' );
//     session_set_cookie_params ( 172800, '/', 'grassroots365.com', true );
    session_name( 'g365_SID' );
    session_set_cookie_params ( 172800, '/', 'sportspassports.com', true );
//     figure out if we have an id or need a session id, then start the session
    if ( !empty($_POST['g365_session']) ) session_id($_POST['g365_session']);
    session_start();
  }
  
  public function _open(){
    // If successful
    if($this->db){
      // Return True
      return true;
    }
    // Return False
    return false;
  }
  public function _close(){
    // Close the database connection
    // If successful
    if($this->db->close()){
      // Return True
      return true;
    }
    // Return False
    return false;
  }
  public function _read($id){

    // Set query
    $this->db->query('SELECT data FROM wp_54ab678738_g365_sessions WHERE id = :id');
    
    // Bind the Id
    $this->db->bind(':id', $id);

    // Attempt execution
    // If successful
    if($this->db->execute()){
      // Save returned row
      $row = $this->db->single();

      //if the data result is null return blank;
      if(isset($row['data'])){
        if( ($row['data']) === 'false' ){
          return ''; 
        }else{
          return $row['data'];
        }
      }else{
        return '';
      }
//       if( is_null($row['data']) ) return '';

      // Return the data
//       return $row['data'];
    }else{
      // Return an empty string
      return '';
    }
  }
  public function _write($id, $data){
    // Create time stamp
    $access = time();

    // Set query  
    $this->db->query('REPLACE INTO wp_54ab678738_g365_sessions VALUES (:id, :access, :data)');

    // Bind data
    $this->db->bind(':id', $id);
    $this->db->bind(':access', $access);  
    $this->db->bind(':data', $data);

    // Attempt Execution
    // If successful
    if($this->db->execute()){
      // Return True
      return true;
    }

    // Return False
    return false;
  }
  public function _destroy($id){
    // Set query
    $this->db->query('DELETE FROM wp_54ab678738_g365_sessions WHERE id = :id');

    // Bind data
    $this->db->bind(':id', $id);

    // Attempt execution
    // If successful
    if($this->db->execute()){
      // Return True
      return true;
    }

    // Return False
    return false;
  }
  public function _gc($max){
    // Calculate what is to be deemed old
    $old = time() - $max;

    // Set query
    $this->db->query('DELETE * FROM wp_54ab678738_g365_sessions WHERE access < :old');

    // Bind data
    $this->db->bind(':old', $old);

    // Attempt execution
    if($this->db->execute()){
      // Return True  
      return true;
    }

    // Return False
    return false;
  }
}

//open a db connection
$use_config = (( in_array($http_origin, ["https://sportspassports.com", "https://grassroots365.com", "https://opengympremier.com", "https://elitebasketballcircuit.com", "https://thestagecircuit.com"]) ) ? '' : '_Dev');

$DS = DIRECTORY_SEPARATOR;
file_exists(__DIR__ . $DS . 'Config' . $use_config . '.php') ? require_once __DIR__ . $DS . 'Config' . $use_config . '.php' : die('Config.php not found');
file_exists(__DIR__ . $DS . 'DB.php') ? require_once __DIR__ . $DS . 'DB.php' : die('DB.php not found');
$g365_session = new g365_Session();

?>