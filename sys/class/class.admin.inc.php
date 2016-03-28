<?php
/**
 * User Login
 *
 * PHP version 5
 *
 * @author Attique Rehman
 * @copyright
 * @license
 */

class Admin extends DB_Connect
{
    /**
     * Determines the length of the salt to use for password hash
     *
     * @var int the length of the password salt to use
     */
    private $_saltLength = 7;

    /**
     * stores or creates DB object and sets saltLength;
     *
     * @param object $db stores the db object
     * @param $saltLength length for the password hash
     */

    public function __construct($db = NULL, $saltLength = NULL)
    {
        parent::__construct($db);

        /**
         * if int was passed set saltlength
         */
        if(is_int($saltLength)){
            $this->$saltLength = $saltLength;
        }
    }

    /**
     * checks login credentials for valid user
     */
    public function processLoginForm()
    {
        /**
         * fails if proper action was not used
         */
        if($_POST['action'] !="user_login"){
            return "Invalid Action supplied for processLoginForm";
        }
        /**
         * escape the user input for security
         */
        $uname = htmlentities($_POST['uname'], ENT_QUOTES);
        $pword = htmlentities($_POST['pword'], ENT_QUOTES);

        /**
         * retreive the matching info from DB if exists
         */
        $sql = "SELECT `user_id`, `user_name`, `user_email`, `user_pass` FROM `users` WHERE `user_name` = :uname LIMIT 1";


        try
        {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':uname', $uname, PDO::PARAM_STR);
            $stmt->execute();
            $user = array_shift($stmt->fetchAll());
            $stmt->closeCursor();
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
        /**
         * fails if username doesn't exist
         */
        if (!isset($user)){
            return "Your username or password is invalid";
        }

        /**
         * Get the hash of the user supplied pasword
         */
        $hash = $this->_getSaltedHash($pword, $user['user_pass']);

        /**
         * check if hashed password matches the stored hash
         */
        if($user['user_pass'] == $hash)
        {
            /**
             * store the user info in session as an array
             */
            $_SESSION['user'] = array(
                'id' => $user['user_id'],
                'name' => $user['user_name'],
                'email' => $user['user_email']
            );
            return TRUE;
        }
        else{
            /**
             * if password hash doesn't match
             */
            return "Your username or password is invalid";
        }
    }

    /**
     * Logs out the user
     *
     * @return mixed TRUE on success or message on failure
     */

 public function processLogout()
{
/**
 * fails if proper action was not submitted
 */
    if($_POST['action'] !='user_logout')
{
    return "Invalid action was used";
}
else
{
    /**
     * removes the user array from current session
     */
    session_destroy();
    return TRUE;
}
}

    /**
     * Generates a salted hash of a supplied string
     *
     * @param string $string string to be hashed
     * @param $salt extract the salt from here
     * @return string the salted hash
     */
    /**
     * Generates a salted hash of a supplied string
     *
     * @param string $string to be hashed
     * @param string $salt extract the hash from here
     * @return string the salted hash
     */
    private function _getSaltedHash($string, $salt=NULL)
    {
        /*
        * Generate a salt if no salt is passed
        */
        if ( $salt==NULL )
        {
            $salt = substr(md5(time()), 0, $this->_saltLength);
        }
        /*
        * Extract the salt from the string if one is passed
        */
        else
        {
            $salt = substr($salt, 0, $this->_saltLength);
        }
        /**
         * Add the salt to the hash and return it
         */
        return $salt . sha1($salt . $string);
    }


    public function testSaltedHash($string, $salt=NULL)
    {
        return $this->_getSaltedHash($string, $salt);
    }

}
?>