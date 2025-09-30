<?php

require_once 'BaseModel.php';

<<<<<<< HEAD
class UserModel extends BaseModel
{

    public function findUserById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = ' . $id;
=======
class UserModel extends BaseModel {

    public function findUserById($id) {
        $sql = 'SELECT * FROM users WHERE id = '.$id;
>>>>>>> main
        $user = $this->select($sql);

        return $user;
    }

<<<<<<< HEAD
    public function findUser($keyword)
    {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %' . $keyword . '%' . ' OR user_email LIKE %' . $keyword . '%';
=======
    public function findUser($keyword) {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %'.$keyword.'%'. ' OR user_email LIKE %'.$keyword.'%';
>>>>>>> main
        $user = $this->select($sql);

        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
<<<<<<< HEAD
    public function auth($userName, $password)
    {
        // Giữ nguyên hashing MD5 như logic hiện tại
        $md5Password = md5($password);

        // Giả sử BaseModel khởi tạo kết nối mysqli ở self::$_connection
        $conn = self::$_connection;
        if (empty($conn) || !($conn instanceof mysqli)) {
            error_log('auth(): mysqli connection not available');
            return [];
        }

        $sql = "SELECT * FROM users WHERE name = ? AND password = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("auth(): prepare failed: " . $conn->error);
            return [];
        }

        // Bind parameters (both strings)
        $stmt->bind_param('ss', $userName, $md5Password);

        if (!$stmt->execute()) {
            error_log("auth(): execute failed: " . $stmt->error);
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        return $rows;
    }


=======
    public function auth($userName, $password) {
        $md5Password = md5($password);
        $sql = 'SELECT * FROM users WHERE name = "' . $userName . '" AND password = "'.$md5Password.'"';

        $user = $this->select($sql);
        return $user;
    }

>>>>>>> main
    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
<<<<<<< HEAD
    public function deleteUserById($id)
    {
        $sql = 'DELETE FROM users WHERE id = ' . $id;
        return $this->delete($sql);
=======
    public function deleteUserById($id) {
        $sql = 'DELETE FROM users WHERE id = '.$id;
        return $this->delete($sql);

>>>>>>> main
    }

    /**
     * Update user
     * @param $input
     * @return mixed
     */
<<<<<<< HEAD
    public function updateUser($input)
    {
        $sql = 'UPDATE users SET 
                 name = "' . mysqli_real_escape_string(self::$_connection, $input['name']) . '", 
                 password="' . md5($input['password']) . '"
=======
    public function updateUser($input) {
        $sql = 'UPDATE users SET 
                 name = "' . mysqli_real_escape_string(self::$_connection, $input['name']) .'", 
                 password="'. md5($input['password']) .'"
>>>>>>> main
                WHERE id = ' . $input['id'];

        $user = $this->update($sql);

        return $user;
    }

    /**
     * Insert user
     * @param $input
     * @return mixed
     */
<<<<<<< HEAD
    public function insertUser($input)
    {
        $sql = "INSERT INTO `app_web1`.`users` (`name`, `password`) VALUES (" .
            "'" . $input['name'] . "', '" . md5($input['password']) . "')";
=======
    public function insertUser($input) {
        $sql = "INSERT INTO `app_web1`.`users` (`name`, `password`) VALUES (" .
                "'" . $input['name'] . "', '".md5($input['password'])."')";
>>>>>>> main

        $user = $this->insert($sql);

        return $user;
    }

    /**
     * Search users
     * @param array $params
     * @return array
     */
<<<<<<< HEAD
    public function getUsers($params = [])
    {
        //Keyword
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE "%' . $params['keyword'] . '%"';
=======
    public function getUsers($params = []) {
        //Keyword
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE "%' . $params['keyword'] .'%"';
>>>>>>> main

            //Keep this line to use Sql Injection
            //Don't change
            //Example keyword: abcef%";TRUNCATE banks;##
            $users = self::$_connection->multi_query($sql);

            //Get data
            $users = $this->query($sql);
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
        }

        return $users;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> main
