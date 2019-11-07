<?php
//error_reporting(E_ALL | E_DEPRECATED | E_STRICT);
/*
 *
 *
 */
require_once(__DIR__ . "/../site_config.php");

class user {
    protected $login_iduser = null;
    protected $username = null;
    protected $password = null;
    protected $usergroup = null;
    protected $base_url = './';

    public function __construct($data = array()) {
        if (isset($data['username'])) $this->username = stripslashes(strip_tags($data['username']));
        if (isset($data['password'])) $this->password = stripslashes(strip_tags($data['password']));
        //In real not usefull if not creating with Users($_SESSION)
//        if (isset($data['usergroup'])) $this->usergroup = stripslashes(strip_tags($data['usergroup'])); // todo remove if we want to make login same for all users
    }
    // protected and not private so that every inheriting class can access this method
    protected function connectMySQL() {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        /* check connection */
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_errno);
            exit();
        }
        return $mysqli;
    }

    public function storeFormValues($params) {
        //Memorizzo i parametri
        $this->__construct($params);
    }

    /** Get data for a given user to log in (administrative officer, teacher, parents, admin)
     *
     * Service Id is set only for clerk
     *
     * @param $post_data
     * @return userinfo []
     */
    public function getUserData($id) {
        $userinfo = [];
        return $userinfo;
    }

    /** Verify username and password for a user to consent log in (can be different "types" of users)
     *
     * @param $post_data : array containing username and password
     * @return bool
     */
    function user_login($post_data) {
        $username = $post_data["username"];
        $password = $post_data["password"];
        $success = false;

        $mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            return $success;
        }

//        todo: first version: LOGIN ONLY FOR PARENTS
        // Here using prepared statement to avoid SQLi
        $query = $mysqli->prepare("SELECT Password FROM parent WHERE Email = ?");
        $query->bind_param('s', $username);
        $res = $query->execute();
        if (!$res) {
            printf("Error message: %s\n", $mysqli->error);
            return $success;
        }

        $query->store_result();
        $query->bind_result($pass);
        // In case of success there should be just 1 user for a given (username is also a primary key for its table)
        if ($query->num_rows != 1) {
            return $success;
        }
        $query->fetch();
        if (password_verify($password, $pass)) {
            // If here login was successful (hash was verified)
            $success = true;
            $this->set_logged($username);
            $this->set_usergroup("Parent"); // todo modify when extend login
            $this->set_username($username); // todo check if we really need to save username, may be enough to store it in session[id]
        }
        $query->close();
        $mysqli->close();

        // Extends login when needed
        //$userinfo = get_user_data($front_office);
        //set_usergroup($userinfo['usergroup']);
        //set_username($userinfo['username']);             // todo controllare questa riga, era: set_name($userinfo['name']); ----- ma name non esiste

        return $success;
    }

    // verifica sintassi username
    public function is_username($username) {
        $regex = '/^[a-z0-9\.\-_]{3,30}$/i';
        return preg_match($regex, $username);
    }

    // verifica sintassi email (TRUE se ok)
    public function is_email($email) {
        $regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$/';
        return preg_match($regex, $email);
    }

    // Retur TRUE if constraints are satisfied
    public function is_secure_password($password) {
        if (strlen($password) >= 5) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /***********************************
     * VERIFICA DELLO STATO DI LOGIN UTENTE
     ***********************************/

    // verifica login
    public function is_logged() {
        return isset($_SESSION['id']);
    }

    // set login
    protected function set_logged($id_user) {
        $_SESSION['id'] = $id_user;
        return;
    }


    //Memorizza nelle sessioni lo username
    protected function set_username($username) {
        $_SESSION['username'] = $username;
        return;
    }

    protected function set_usergroup($usergroup) {
        $_SESSION['usergroup'] = $usergroup;
        return;
    }

    // Save name for gui?
    protected function set_name($name) {
        $_SESSION['name'] = ucfirst($name);
        return;
    }

    //Restituisce la mail memorizzata nelle sessioni
    public function get_username() {
        return isset($_SESSION['username']) ? $_SESSION['username'] : '';
    }

    public function get_name() {
        return isset($_SESSION['name']) ? $_SESSION['name'] : '';
    }

    public function get_usergroup() {
        return isset($_SESSION['usergroup']) ? $_SESSION['usergroup'] : '';
    }

    public function get_id() {
        return isset($_SESSION['id']) ? $_SESSION['id'] : '';
    }

    /* Error handling */
    // Redirect to error.php (Error handler)
    public function get_error($id, $noref = null) {
        $html = "<meta http-equiv='refresh' content='0; url=" . $this->base_url . "/error.php?message=$id' />";
        if (!empty($noref)) {
            $html = "<meta http-equiv='refresh' content='0; url=" . $this->base_url . "/error.php?message=$id&noref=1' />";
        }
        print $html;
        //echo "url=".$this->base_url."/error.php?message=$id";
        exit();
    }

    /*  Return the logout link */
    public function get_link_logout() {
        if ($this->is_logged()) {
            return '<a href="' . $this->Urls['logout_page'] . '" class="logout">Logout</a>';
        }
        return '';
    }


    /*
    public function sendEmail($to, $subject, $content, $name){
        //Modifico php.ini per far si da utilizzare il protocollo smtp senza autenticazione.
        ini_set('SMTP', 'smtp.mydomain.it'); 
        ini_set('smtp_port', 25); 
        ini_set('sendmail_from', "info@mydomain.it");
        //Controllo che l'email sia sintassicamente corretta.
        if(!$this->is_email($to)) {
            return false;
        }
        $subject = htmlspecialchars($subject);
        // message
        $message = '
        <html>
        <head>
            <title>Grazie '.$name.'</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        </head>
        <body>'.
            $content
        .'</body><br><br>
        <small>
            Questa email è stata generata dal sito <a href=http://mydomain.it>colosi.it</a><br>
            Per ogni altra informazione o per l\'eliminazione del proprio account andare sul sito o scrivere a<br>
            info@mydomain.it</small>
        </html>
        ';
        // Modifico il Content-type poichè è una mail in formato html.
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        // Aggiungo header per non mandarla in SPAM.
        $headers .= 'To: '.htmlspecialchars($name).' <'.$to.'>' . "\r\n";
        $headers .= 'From: Digital Student Recorder<info@mydomain.it>' . "\r\n";
        // Uso la funzione mail per inviare.
        if(mail($to, $subject, $message, $headers)){
            return true;
        }
        else{
            return false;
        }
    }*/
}

?>