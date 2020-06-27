<?php

    class User extends Model {

        //VARIABLES

        public static $types = [
            'A' => 'Author',
            'E' => 'Editor',
            'R' => 'Reviewer'
        ];

        /////////////////////////////////////////////////////////////////////////////////////
        //ATTRIBUTES

        protected $id;
        protected $name;
        protected $email;
        protected $password;
        protected $job;
        protected $type;
        protected $email_verified;
        protected $created_at;

        /////////////////////////////////////////////////////////////////////////////////////
        //CONSTRUCTORS

        public function __construct()
        {
            $this->id = 0;
            $this->name = null;
            $this->email = null;
            $this->password = null;
            $this->job = null;
            $this->type = null;
            $this->email_verified = false;
            $this->email_verified = boolval(null);
            $this->created_at = date('d-m-Y H:i:s');
        }

        /////////////////////////////////////////////////////////////////////////////////////
        //SETTERS

        public function setId($id) : void { $this->id = $id; }
        public function setName($name) : void { $this->name = $name; }
        public function setEmail($email) : void { $this->email = $email; }
        public function setPassword($password) : void { $this->password = $password; }
        public function setJob($job) : void { $this->job = $job; }
        public function setType($type) : void { $this->type = $type; }
        public function setEmailVerified($email_verified) : void { $this->email_verified = $email_verified; }
        public function setCreatedAt($created_at) : void { $this->created_at = $created_at; }

        /////////////////////////////////////////////////////////////////////////////////////
        //GETTERS 

        public function getId() { return $this->id; }
        public function getName() : string { return $this->name; }
        public function getEmail() : string { return $this->email; }
        public function getPassword() : string { return $this->password; }
        public function getJob() : string { return $this->job; }
        public function getType() : string { return $this->type; }
        public function getRealType() : string { return User::$types[$this->type]; }
        public function getEmailVerified() : bool { return $this->email_verified; }
        public function getCreatedAt() { return $this->created_at; }

        /////////////////////////////////////////////////////////////////////////////////////
        //METHODS

        //Get User By id
        public static function findBy($value, $column = 'user_id', $table_name = 'user') {
            $data = parent::findBy($value, $column, $table_name);

            if($data != null) {

                switch(strtoupper($data['type'])) {
                    case 'A':
                        $user = new Author();
                        break;
                    case 'R':
                        $user = new Reviewer();
                        break;
                    case 'E':
                        $user = new Editor();
                        break;
                }

                $user->setId($data['user_id']);
                $user->setName($data['name']);
                $user->setEmail($data['email']);
                $user->setPassword($data['password']);
                $user->setJob($data['job']);
                $user->setEmailVerified($data['email_verified']);
                $user->setCreatedAt($data['created_at']);

                return $user;

            } else {
                return null;
            }
        }

        //Get all users
        public static function findAll($table_name = 'user') {
            $data = parent::findAll($table_name);

            if($data != null) {

                $users = array(count($data));

                for($i = 0; $i < count($data); $i++) {

                    $info = $data[$i];
                    switch(strtoupper($info['type'])) {
                        case 'A': $user = new Author(); break;
                        case 'R': $user = new Reviewer(); break;
                        case 'E': $user = new Editor(); break;
                    }

                    $user->setId($info['user_id']);
                    $user->setName($info['name']);
                    $user->setEmail($info['email']);
                    $user->setPassword($info['password']);
                    $user->setJob($info['job']);
                    $user->setEmailVerified($info['email_verified']);
                    $user->setCreatedAt($info['created_at']);

                    $users[$i] = $user;
                }

                return $users;

            } else {
                return null;
            }
        }

        //Add new user
        public static function addUser($user) : bool {
            $query = 'insert into user (name, email, password, job, type, email_verified) values (?, ?, ?, ?, ?, ?)';
            $params = [
                $user->getName(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getJob(),
                $user->getType(),
                $user->getEmailVerified()
            ];

            $addToUser = parent::submitData($query, $params);
            
            if(strtoupper($user->getType()) == 'E') {

                //Insert user in editor Table
                $addToObject = parent::submitData(
                    'insert into editor (editor_id) values (?)',
                    [
                        User::findBy($user->getEmail(), 'email')->getId()
                    ]
                );

            } else {

                //Insert user in Author & Reviewer Table
                $addToObject = parent::submitData(
                    'insert into author (author_id) values (?)',
                    [
                        User::findBy($user->getEmail(), 'email')->getId()
                    ]
                )
                && 
                parent::submitData(
                    'insert into reviewer (reviewer_id) values (?)',
                    [
                        User::findBy($user->getEmail(), 'email')->getId()
                    ]
                );

            }

            return $addToUser && $addToObject;
        }

        //Update User
        public static function updateUser($user) : bool {
            $query = "update user set name = ?, email = ?, password = ?, job = ?, email_verified = ? where user_id = ?";
            $params = [
                $user->getName(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getJob(),
                $user->getEmailVerified(),
                $user->getId()
            ];

            return parent::submitData($query, $params);
        }

        //Delete User
        public static function deleteUser($user) : bool {

            if(strtoupper($user->getType()) == 'E') {

                //Delete user from editor Table
                $deleteFromObject = parent::submitData(
                    'delete from editor where editor_id = ?',
                    [
                        $user->getId()
                    ]
                );

            } else {

                //Delete user from Author & Reviewer Table
                $deleteFromObject = parent::submitData(
                    'delete from author where author_id = ?',
                    [
                        $user->getId()
                    ]
                )
                && 
                parent::submitData(
                    'delete from reviewer where reviewer_id = ?',
                    [
                        $user->getId()
                    ]
                );

            }

            $query = 'delete from user where user_id = ?';
            $deleteFromUser = parent::submitData($query, [ $user->getId() ]);

            return $deleteFromObject && $deleteFromUser;
        }

        /////////////////////////////////////////////////////////////////////////////////////
        //EMAIL METHODS

        //Send Verification email
        public static function sendVerificationEmail($user) : bool {

            $user = self::findBy($user->getEmail(), 'email');
            $user_id = $user->getId();

            $token = sha1($user->getCreatedAt().''.$user->getEmail());

            $to = $user->getEmail();
            
            $subject = 'SCIENCE NEWSLETTER : Verify your registration';
            $message = "
                <h2>Email Validation :</h2>
                <p><b>We did have your request to join us science newsletter team<b></p>
                <hr>
                <p>to complete your process visit this link below</p>
                <p><a href='https://be-scientist.000webhostapp.com/actions/verify.php?key=$user_id&token=$token'>Click here</a></p>
                <br><hr><br>
                <p>This message was sended automatically please don't reply!</p>";

            //from w3schools
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <science@newsletter.com>' . "\r\n";

            if(mail($to, $subject, $message, $headers))
                //Email Sended
                return true;
            else
                return false;

        }

        //Verify Email
        public static function verifyEmail($key, $token) : bool {

            $user = self::findBy($key);

            if($user == null) return false;

            $hash = sha1($user->getCreatedAt().''.$user->getEmail());

            if($token != $hash) return false;

            $user->setEmailVerified(true);

            if(self::updateUser($user)) return true;
            else return false;

        }


    }

?>