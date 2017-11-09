<?php

class DBHandler
{
    const TABLE_ALBUMS = 'albums';
    var $connection;

    /**
     * @param $host String host to connect to.
     * @param $user String username to use with the connection. Make sure to grant all necessary privileges.
     * @param $password String password belonging to the username.
     * @param $db String name of the database.
     */
    function __construct($host,$user,$password,$db){
        $this->connection = new mysqli($host,$user,$password, $db);
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        } 
        $this->ensureAlbumsTable();
        $this->ensureArtistsTable();
    }

    /*********************
     * DESTRUCTOR
     */

    /**
     * creates a database record for the given artist and title.
     * @param $artistName String name of te album's artist
     * @param $albumTitle String title of the album
     * @return bool true for success, false for error
     */
    function insertAlbum($artistName, $albumTitle){
        if($this->connection){
            
            $query_selectArtist = "SELECT id FROM artist WHERE name = ?";
            
            if($selectStatement = $this->connection->prepare($query_selectArtist)) {           
                $selectStatement->bind_param("s", $artistName);
                $selectStatement->execute();
                echo ("in");
                $selectStatement->bind_result($name);
                if (count($selectStatement->fetch()) > 0) {
                    echo $name;
                }
            }
        }
        return false;
    }

    /**
     * makes sure that the albums table is present in the database
     * before any interaction occurs with it.
     */
    function ensureAlbumsTable(){
        if($this->connection && !$this->tableExists('albums')) {

            $query = "CREATE TABLE `albums` (`album_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `album_name` VARCHAR(30), `artist_fid` INT(11))";
            $this->connection->query($query);
            
        }
    }

    /**
     * makes sure that the artists table is present in the database
     * before any interaction occurs with it.
     */
    function ensureArtistsTable(){
        if($this->connection && !$this->tableExists('artist')) {
            $query = "CREATE TABLE artist(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30))";
            $this->connection->query($query);
            
        }
    }
    function tableExists($table) {
        $res = $this->connection->query("SHOW TABLES LIKE '$table'");  
        
        if (!$res) return false;
        return true;        
      }

    /**
     * @return array of rows (id, artist, title)
     */
    function fetchAlbums(){
        $albums = array();
        // TODO fetch all albums and put them into the $albums array.
        return $albums;
    }

    /**
     * useful to sanitize data before trying to insert it into the database.
     * @param $string String to be escaped from malicious SQL statements
     */
    function sanitizeInput(&$string){
        $string = $this->connection->real_escape_string($string);
    }
}