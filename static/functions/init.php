<?php
    require_once 'connect.php';

    abstract class ErrorMessage {
        const SUCCESS = "สำเร็จ";
        const ERROR = "พบข้อผิดพลาด";
        const WARNING = "คำเตือน";

        const AUTH_WRONG = "ERROR 01 : Wrong username or password";
        const AUTH_INVALID_EMAIL_TOKEN = "ERROR 06 : That email confirmation token is invalid, is that expired?";
        const AUTH_INVALID_RESET_PASSWORD_TOKEN = "ERROR 07 : That reset password token is invalid, is that expired?";
        
        const USER_NOT_FOUND = "ERROR 10 : User not found";
        const USER_ERROR = "ERROR 19 : Found conflict user";

        const FILE_IO = "ERROR 20 : Cannot performing File IO";
        const FILE_UPLOAD_NOT_FOUND = "ERROR 21 : Cannot locate the uploaded file";
        const FILE_DUPLICATE = "ERROR 22 : Duplicate file/folder name";

        const DATABASE_ESTABLISH = "ERROR 40 : Cannot established with the database";
        const DATABASE_QUERY = "ERROR 41 : Cannot query with the database";
        const DATABASE_ERROR = "ERROR 49 : Unexpected internal database error";

        const SESSION_INVALID = "ERROR 60 : Session is invalid";
        
        const PERMISSION_REQUIRE = "ERROR 90 : You do not have enough permission";
        const PERMISSION_ERROR = "ERROR 99 : Found conflict permission";
    }

    abstract class Role {
        const ADMIN = "admin";
        const GUEST = "guest";
        const ROLES = array(Role::ADMIN, Role::GUEST);
    }
    
    abstract class HardcodedPostCategory {
        const CATEGORIES = array("เกี่ยวกับ", "บุคลากร", "การศึกษา", "งานวิจัย", "บริการ", "ประชาสัมพันธ์");
    }

    class Config {
        protected $config;

        public function getCategory() {

        }

        public function view() {
            return $this->config;
        }

        public function __construct() {
            global $conn;
            if ($stmt = $conn->prepare('SELECT * FROM `config`')) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $conf = array();
                    while ($row = $result->fetch_assoc()) {
                        array_push($conf, array($row['key']=>json_decode($row['val'])));
                    }
                    $this->config = $conf;
                }
            }
            return null;
        }
    }

    class User {
        protected $id, $user, $email, $role, $group, $job, $firstname, $lastname;
        public $perm, $profile;

        public function getID() {
            return $this->id;
        }
        public function setID(int $id) {
            $this->id = $id;
        }

        public function getUsername() {
            return $this->user;
        }
        public function setUsername(String $username) {
            $this->user = $username;
        }

        public function getName() {
            return $this->firstname . " " . $this->lastname;
        }
        public function getFirstname() {
            return $this->firstname;
        }
        public function getLastname() {
            return $this->lastname;
        }
        public function setName(String $firstname, String $lastname) {
            $this->firstname = $firstname;
            $this->lastname = $lastname;
        }

        public function getEmail() {
            return $this->email;
        }
        public function setEmail(String $email) {
            $this->email = $email;
        }

        public function isAdmin() {
            return $this->admin;
        }
        public function setAdmin(bool $admin) {
            $this->admin = $admin;
        }

        
        public function getProfile() {
            if (empty($this->profile) || !file_exists($this->profile)) return "../static/elements/user.svg";
            return $this->profile;
        }
        public function setProfile(string $url) {
            $this->profile = $url;
        }

        public function getInfo() {
            return array(
                "id" => $this->id,
                "username" => $this->user,
                "name" => $this->getName(),
                "email" => $this->email,
                "profile" => $this->profile,
                "admin" => $this->admin
            );
        }

        public function __construct(int $id) {
            $this->id = $id;
            $data = getUserData($id);
            if (!empty($data)) {
                $this->firstname = $data['firstname'];
                $this->lastname = $data['lastname'];
                $this->profile = $data["profile"];
                $this->user = $data['username'];
                $this->email = $data['email'];
                $this->admin = $data['admin'];
            } else {
                $this->id = -1;
            }
        }
    }

    class Post {
        protected $id, $title, $article, $properties;

        public function getID() {
            return $this->id;
        }

        public function validate() {
            return ($this->id != -1);
        }

        public function getTitle() {
            return $this->title;
        }
        public function setTitle(String $title) {
            $this->title = $title;
        }
        
        public function getArticle() {
            return $this->article;
        }
        public function setArticle(String $article) {
            $this->article = $article;
        }

        public function properties() {
            return $this->properties;
        }
        public function getProperty(String $key) {
            if (empty($this->properties) || $this->properties==null) return null;
            return array_key_exists($key, $this->properties) ? $this->properties[$key] : null;
        }
        public function setProperty($key, $val) {
            $this->properties[$key] = $val;
        }

        public function __construct(int $id) {
            $this->id = $id;
            if ($id > 0) {
                $post = getPostData($id);
                if ($post != null) {
                    $this->title = $post['title'];
                    $this->article = $post['article'];
                    $this->properties = json_decode($post['properties'], true);
                } else {
                    $this->id = -1;
                }
            } else if (!isset($_SESSION['user'])) {
                $this->id = -1;
            } else {
                $this->title = null;
                $this->article = null;
                $this->properties = array(
                    "author" => $_SESSION['user']->getID(),
                    "category" => null,
                    "updated" => time(),
                    "hide" => false,
                    "pin" => false,
                    "cover" => null,
                    "tag" => null,
                    "allowDelete" => true
                );
            }
        }
    }
?>