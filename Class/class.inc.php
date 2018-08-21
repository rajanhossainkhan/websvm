<?php
session_start();

class Config {

    //Closing connection
    public function close($con) {
        mysqli_close($con);
    }

    //Opening connection
    public function open() {
        $con = mysqli_connect("localhost", "root", "root", "db_jrs");
        return $con;
    }

    //Debug output
    public function debug($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     * Insert query for Object
     * @param type $object
     * @param type $object_array
     * @return string/Exception
     */
    function insert($object, $object_array) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        foreach ($object_array as $col => $val) {
            if ($count++ != 0)
                $fields .= ', ';
            $col = mysqli_real_escape_string($con, $col);
            $val = mysqli_real_escape_string($con, $val);
            $fields .= "`$col` = '$val'";
        }
        $query = "INSERT INTO `$object` SET $fields";

        if (mysqli_query($con, $query)) {
            $this->close($con);
            return 1;
        } else {
            return 0;
        }
    }

    function insert_with_last_id($object, $object_array) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        foreach ($object_array as $col => $val) {
            if ($count++ != 0)
                $fields .= ', ';
            $col = mysqli_real_escape_string($con, $col);
            $val = mysqli_real_escape_string($con, $val);
            $fields .= "`$col` = '$val'";
        }
        $query = "INSERT INTO `$object` SET $fields";

        if (mysqli_query($con, $query)) {
            $last_id = mysqli_insert_id($con);
            return $last_id;
        } else {
            return 0;
        }
    }

    function SelectAllByAssoc($object, $condition) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        $query = "SELECT * FROM `$object` WHERE $condition";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {
                //$object[]=array();
                while ($rows = mysqli_fetch_assoc($result)) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        }
    }

    /**
     * limit word
     * @param type $string
     * @param type $word_limit
     * @return type
     */
    public function limit_words($string, $word_limit) {
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }

    /**
     * return the survey Array
     * @param type $yes
     * @param type $no
     * @param type $no_comments
     * @return associated array
     */

    public function online_topic_survey($yes, $no, $no_comments) {
        $sum = $yes + $no + $no_comments;
        if ($sum == 0) {
            $surveryArray = array("yes" => 0, "no" => 0, "no_comment" => 0);
            return $surveryArray;
        } else {
            $yesPercent = number_format((($yes * 100) / $sum), 2);
            $noPercent = number_format((($no * 100) / $sum), 2);
            $no_comments = number_format((($no_comments * 100) / $sum), 2);
            $surveryArray = array("yes" => $yesPercent, "no" => $noPercent, "no_comment" => $no_comments);
            return $surveryArray;
        }
    }

    function QueryResultForNormalEntry($queryString, $con) {
        $result = mysqli_query($con, $queryString);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function QueryResult($queryString) {
        $con = $this->open();
        $result = mysqli_query($con, $queryString);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        }
    }

    function redirect($link) {
        echo "<script>location.href='$link'</script>";
    }

    function adminlogin($username, $password) {
        $count = 0;
        $fields = '';
        $con = $this->open();

        $fields = " user_name='" . mysqli_real_escape_string($con, $username) . "' and password='" . mysqli_real_escape_string($con, $password) . "'";

        $query = "SELECT * FROM `admin` WHERE $fields";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            $this->close($con);
            if ($count == 1) {
                return 1;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    function login($username, $password) {
        $count = 0;
        $fields = '';
        $con = $this->open();

        $fields = "admin_email='" . mysqli_real_escape_string($con, $username) . "' and admin_password ='" . mysqli_real_escape_string($con, $password) . "'";

        $query = "SELECT * FROM `admin` WHERE $fields";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            $this->close($con);
            if ($count == 1) {
                return 1;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * Another login
     */
    function logintwo($username, $password, $user_id) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        $fields = " admin_email='" . mysqli_real_escape_string($con, $username) . "' and admin_password='" . mysqli_real_escape_string($con, $password) . "' and user_id='" . mysqli_real_escape_string($con, $user_id) . "'";
        $query = "SELECT * FROM `admin` WHERE $fields";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            $this->close($con);
            if ($count == 1) {
                return 1;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * Multiple LOGIN
     */
    function loginmulti($username, $password, $user_type) {
        $count = 0;
        $con = $this->open();
        $condition = "";
        $query = '';
        if ($user_type == 1) {

            $condition = " admin_email='" . mysqli_real_escape_string($con, $username) . "' and password='" . mysqli_real_escape_string($con, $password) . "'";
            $query = "SELECT * FROM `admin` WHERE $condition";
            $result = mysqli_query($con, $query);
            if ($result) {
                $count = mysqli_num_rows($result);
                $this->close($con);
                if ($count == 1) {
                    return 1;
                }
            } else {
                $this->close($con);
                return 0;
            }
        } else if ($user_type == 2) {
            $condition = " c_email='" . mysqli_real_escape_string($con, $username) . "' and c_password='" . mysqli_real_escape_string($con, $password) . "'";
            $query = "SELECT * FROM `tbl_consultant` WHERE $condition";
            $result = mysqli_query($con, $query);
            if ($result) {
                $count = mysqli_num_rows($result);
                $this->close($con);
                if ($count == 1) {
                    return 1;
                }
            } else {
                $this->close($con);
                return 0;
            }
        } else if ($user_type == 3) {
            $condition = " std_email='" . mysqli_real_escape_string($con, $username) . "' and std_password='" . mysqli_real_escape_string($con, $password) . "'";
            $query = "SELECT * FROM `tbl_student` WHERE $condition";
            $result = mysqli_query($con, $query);
            if ($result) {
                $count = mysqli_num_rows($result);
                $this->close($con);
                if ($count == 1) {
                    return 1;
                }
            } else {
                $this->close($con);
                return 0;
            }
        } else if ($user_type == 4) {
            $condition = " email='" . mysqli_real_escape_string($con, $username) . "' and uni_pass='" . mysqli_real_escape_string($con, $password) . "'";
            $query = "SELECT * FROM `tbl_university` WHERE $condition";
            $result = mysqli_query($con, $query);
            if ($result) {
                $count = mysqli_num_rows($result);
                $this->close($con);
                if ($count == 1) {
                    return 1;
                }
            } else {
                $this->close($con);
                return 0;
            }
        }
    }

    /**
     * if the object is exists
     * @param type $object
     * @param type $object_array
     * @return int
     */
    function exists($object, $object_array) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        if (count($object_array) <= 1) {
            foreach ($object_array as $col => $val) {
                if ($count++ != 0)
                    $fields .= ',';
                $col = mysqli_real_escape_string($con, $col);
                $val = mysqli_real_escape_string($con, $val);
                $fields .= "`$col` = '$val'";
            }
        }
        $query = "SELECT * FROM `$object` WHERE $fields";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            $this->close($con);
            if ($count >= 1) {
                return 1;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * if the object is exists
     * @param type $object
     * @param type $object_array
     * @return int
     */
    function existsByCondition($object, $condition) {
        $con = $this->open();
        $query = "SELECT * FROM `$object` WHERE  $condition";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            $this->close($con);
            if ($count >= 1) {
                return 1;
            } else {       //// Below added by Asma 24 FEb 2015
                return 0;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * Select all by Condtion with Limit
     * @param type $object
     * @param type $condition
     * @return type
     */

    function SelectAllByConditionByLimit($object, $condition, $limit) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        $query = "SELECT * FROM `$object` WHERE $condition limit 0, $limit";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {
                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        }
    }

    /**
     * Query for selecting the data with condition
     * @param type $object
     * @param type $condition
     * @return type
     */

    function SelectAllByCondition($object, $condition) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        $query = "SELECT * FROM `$object` WHERE $condition";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {
                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        }
    }

    /**
     * Select all the objects
     * @param type $object
     * @return array
     */
    function SelectAll($object) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        $query = "SELECT * FROM `$object`";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {

                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        }
    }

    /**
     * using for limiting the mysqli result
     * @param type $object
     * @param type $limit
     * @return type
     */
    function SelectAllByLimit($object, $limit) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        $query = "SELECT * FROM `$object` limit 0, $limit";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {

                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        }
    }

    /**
     * Using for Limiting the Mysql Result
     * @param type $object
     * @param type $object_array
     * @return int
     */
    function SelectAllByIDByLimit($object, $object_array, $limit) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        if (count($object_array) <= 1) {
            foreach ($object_array as $col => $val) {
                if ($count++ != 0)
                    $fields .= ', ';
                $col = mysqli_real_escape_string($con, $col);
                $val = mysqli_real_escape_string($con, $val);
                $fields .= "`$col` = '$val'";
            }
        }
        $query = "SELECT * FROM `$object` WHERE $fields limit 0, $limit";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {
                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * Select object by ID
     * @param type $object
     * @param type $object_array
     * @return int
     */
    function SelectAllByField($object, $object_array) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        if (count($object_array) <= 1) {
            foreach ($object_array as $col => $val) {
                if ($count++ != 0)
                    $fields .= ', ';
                $col = mysqli_real_escape_string($con, $col);
                $val = mysqli_real_escape_string($con, $val);
                $fields .= "`$col` = '$val'";
            }
        }
        $query = "SELECT * FROM `$object` WHERE $fields";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {
                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * Select object by ID
     * @param type $object
     * @param type $object_array
     * @return int
     */
    function SelectAllByID($object, $object_array) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        if (count($object_array) <= 1) {
            foreach ($object_array as $col => $val) {
                if ($count++ != 0)
                    $fields .= ', ';
                $col = mysqli_real_escape_string($con, $col);
                $val = mysqli_real_escape_string($con, $val);
                $fields .= "`$col` = '$val'";
            }
        }
        $query = "SELECT * FROM `$object` WHERE $fields";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);

            if ($count >= 1) {
                //$object[]=array();
                while ($rows = $result->fetch_object()) {
                    $objects[] = $rows;
                }
                $this->close($con);
                return $objects;
            }
        } else {
            $this->close($con);
            return 0;
        }
    }

    /**
     * Delete the object from database
     * @param type $object
     * @param type $object_array
     * @return string|\Exception
     */
    function delete($object, $object_array) {
        $count = 0;
        $fields = '';
        $con = $this->open();
        if (count($object_array) <= 1) {
            foreach ($object_array as $col => $val) {
                if ($count++ != 0)
                    $fields .= ', ';
                $col = mysqli_real_escape_string($con, $col);
                $val = mysqli_real_escape_string($con, $val);
                $fields .= "`$col` = '$val'";
            }
        }
        $query = "Delete FROM `$object` WHERE $fields";
        if (mysqli_query($con, $query)) {

            $this->close($con);
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Delete the object
     * @param type $object
     * @param type $object_array
     */
    function update($object, $object_array) {
        $con_key_from_arr = array_keys($object_array);
        $key = $con_key_from_arr[0];
        $value = array_shift($object_array);
        $fields = array();
        $con = $this->open();
        foreach ($object_array as $field => $val) {
            $fields[] = "$field = '" . mysqli_real_escape_string($con, $val) . "'";
        }

        $query = "UPDATE `$object` SET " . join(', ', $fields) . " WHERE $key = '$value'";
        if (mysqli_query($con, $query)) {
            $this->close($con);
            return 1;
        } else {
            return 0;
        }
    }

    function baseUrl($suffix = '') {
        $protocol = strpos($_SERVER['SERVER_SIGNATURE'], '443') !== false ? 'https://' : 'http://';
        $web_root = $protocol . $_SERVER['HTTP_HOST'] . "/" . "payroll/admin/";
        $suffix = ltrim($suffix, '/');
        return $web_root . trim($suffix);
    }

    function SiteBaseUrl($suffix = '') {
        $protocol = strpos($_SERVER['SERVER_SIGNATURE'], '443') !== false ? 'https://' : 'http://';
        $web_root = $protocol . $_SERVER['HTTP_HOST'] . "/" . "payroll/";
        $suffix = ltrim($suffix, '/');
        return $web_root . trim($suffix);
    }

    function authenticate() {

        if (!isset($_SESSION['emp_code']) || (trim($_SESSION['emp_code']) == '')) {
            $err = 'Login Session Expired Please Login';
            session_write_close();
            return 1;
        } else {
            return 0;
        }
    }

    function authenticate1() {

        if (!isset($_SESSION['c_email']) || (trim($_SESSION['c_email']) == '')) {
            $err = 'Login Session Expired Please Login';
            session_write_close();
            return 1;
        } else {
            return 0;
        }
    }

    function authenticate2() {

        if (!isset($_SESSION['std_email']) || (trim($_SESSION['std_email']) == '')) {
            $err = 'Login Session Expired Please Login';
            session_write_close();
            return 1;
        } else {
            return 0;
        }
    }

    function authenticate3() {

        if (!isset($_SESSION['email']) || (trim($_SESSION['email']) == '')) {
            $err = 'Login Session Expired Please Login';
            session_write_close();
            return 1;
        } else {
            return 0;
        }
    }

    function logout() {
        session_unset();
        session_destroy();
        return 1;
    }

    function logoutnew() {
        unset($_SESSION['admin_email']);
        unset($_SESSION['c_email']);
        unset($_SESSION['std_email']);
        unset($_SESSION['email']);
        session_unset();
        session_destroy();
        return 1;
    }

    function AutoComplete($name, $id, $data_text_field, $controller_name, $value, $style = '', $class = '') {
        $auto_complete_render = "";
        $auto_complete_render .= '<input id="' . $id . '" name="' . $name . '" style="' . $style . '" class="' . $class . '" value="' . $value . '" />';
        $auto_complete_render .= '<script type="text/javascript">';
        $auto_complete_render .= '$(document).ready(function(){';
        $auto_complete_render.= '$("#' . $id . '").kendoAutoComplete({
                                    dataSource   : new kendo.data.DataSource({
                                        serverFiltering: true,
                                        transport      : {
                                            read: "../../controller/' . $controller_name . '"
                                        },
                                        schema         : {
                                            data: "data"
                                        }
                                    }),
                                    minLength    : 1,
                                    dataTextField: "' . $data_text_field . '",
                                    placeholder  : "' . $name . '"
                                   });

                                        });</script>';
        return $auto_complete_render;
    }

    function DateTimePicker($name, $id, $value, $style = '', $class = '') {
        $datetime_render = '';
        $datetime_render .= '<input id="' . $id . '" name="' . $name . '" style="' . $style . '" class="' . $class . '" value="' . $value . '" />';
        $datetime_render .= '<script type="text/javascript">';
        $datetime_render .= '$(document).ready(function(){';
        $datetime_render .= '$("#' . $id . '").kendoDatePicker();';
        $datetime_render .= '});</script>';
        return $datetime_render;
    }

    //
    function hasPermissionView($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT perview FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                //$object[]=array();
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $perview = $obj->perview;
                }
                return $perview;
            }
        }
    }

    //Allows create button :: such ad 'Add Employee'
    function hasPermissionCreate($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT percreate FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                //$object[]=array();
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $percreate = $obj->percreate;
                }
                $this->close($con);
                return $percreate;
            }
        }
    }

    //Allows create button :: such ad 'Delete Item'
    function hasPermissionDelete($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT perdelete FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                //$object[]=array();
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $perdelete = $obj->perdelete;
                }
                $this->close($con);
                return $perdelete;
            }
        }
    }

    //Allows create button :: such ad 'Approve Leave Request'
    function hasPermissionApprove($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT perapprove FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                //$object[]=array();
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $perapprove = $obj->perview;
                }
                $this->close($con);
                return $perapprove;
            }
        }
    }

    //Allows cancel button :: such as 'Cancel a leave request'
    function hasPermissionCancel($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT percancel FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $percancel = $obj->perview;
                }
                $this->close($con);
                return $percancel;
            }
        }
    }

    function hasPermissionUpdate($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT perupdate FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $perupdate = $obj->perupdate;
                }
                $this->close($con);
                return $perupdate;
            }
        }
    }

    function hasPermissionExport($permission_id) {
        $count = 0;
        $con = $this->open();
        $query = "SELECT perexport FROM `module_permission` WHERE permission_id='$permission_id'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                while ($rows = mysqli_fetch_object($result)) {
                    $objects[] = $rows;
                }
                foreach ($objects as $obj) {
                    $perexport = $obj->perexport;
                }
                $this->close($con);
                return $perexport;
            }
        }
    }

    //Email without attachment
    function sent_mail_without_attatchment($mailto, $replyto, $subject, $message) {
        $from_mail = "rpac_erp@r-pac.com";
        $header = "From: RPAC ERP<" . $from_mail . ">\r\n";
        $header .= "Reply-To: " . $replyto . "\r\n";
        $uid = md5(uniqid(time()));
        $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
        $header .= "Content-type:text/html\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message . "\r\n\r\n";
        if (mail($mailto, $subject, $message, $header, '-f rpac_erp@r-pac.com')) {
            return 1; // or use booleans here if sent
        } else {
            return 2;
        }
    }

    //Email with attachment
    function mail_attachment($filename, $path, $mailto, $replyto, $subject, $message) {
        $file = $path . $filename;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $name = basename($file);
        $from_mail = "rpac_erp@r-pac.com";
        $header = "From: RPAC ERP<" . $from_mail . ">\r\n";
        $header .= "Reply-To: " . $replyto . "\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= "--" . $uid . "\r\n";
        $header .= "Content-type:text/html\r\n";   //; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message . "\r\n\r\n";
        $header .= "--" . $uid . "\r\n";

        $header .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
        $header .= $content . "\r\n\r\n";

        $header .= "--" . $uid . "--";

        if (mail($mailto, $subject, $message, $header, '-f rpac_erp@r-pac.com')) {
            echo 1; // or use booleans here
        } else {
            echo 2;
        }

        /*         * *
         * Function to get primary key of a table
         * By : Asma
         * Date : 16 March 15
         * * */

        function get_primary_key($object) {
            $con = $this->open();
            $query = "SELECT * FROM `$object` ";
            $result = mysqli_query($con, $query);
            if ($result) {
                $count = mysqli_num_rows($result);
                if ($count >= 1) {
                    $val = mysqli_fetch_assoc($result);
                    $this->close($con);
                    return $val['PF_id'];
                } else {
                    return 0;
                }
            } else {
                $this->close($con);
                return 0;
            }
        }

    }

}
