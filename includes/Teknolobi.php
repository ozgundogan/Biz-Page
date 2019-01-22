
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Teknolobi
{
    //Parameters
    public $sqlType = "mysqli"; // Options: "mysql", "mysqli" , "mssql", "pgsql"
    public $sqlServer = "127.0.0.1"; // Options: SQL Server name/IP
    public $sqlUsername = "root"; // Sql username
    public $sqlPassword = "";
    public $sqlDatabase = "huris";
    public $sqlPort = "3306"; // 3306 for mysql, 1433 for MSSQL, 5432 for pgsql
    public $sqlCharset = "UTF8"; //utf8 for mysql, UTF-8 for mssql
    public $sqlUsePDO = false;
    public $Debug = false;
    public $ShowErrors = true;
    public $Version = "1.0";
    public $FloodGuard = true;
    public $Plugins_MailChimp = array('key' => 'f73a20f0d4ce415d004d888be9bd44bb-us10', 'list' => 'f31ce43719');
    public $ErrorLevels = array(E_ALL); //For all errors: E_ALL


    public $Views = array();
    public $Connection = false;
    public $Exception = array();
    public $PHPexception;
    public $startmicrotime;
    public $starttime;
    public $userip;
    public $Custom;
    public $File;
    public $Image;
    public $Translator;
    public $MailChimp;
    public $DebugReturn;

    function __construct()
    {
        $this->startmicrotime = microtime(true);
        $this->starttime = date('U');
        $this->userip = $this->get_client_ip();
        set_error_handler(array($this, "myErrorHandler"));
        register_shutdown_function(array($this, "shutdownHandler"));
    }

    function myErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if (in_array($errno, $this->ErrorLevels) || in_array(E_ALL, $this->ErrorLevels)) {
            $GLOBALS['PHPexception'][] = array($errno, $errfile, $errline, $errstr, $this->FriendlyErrorType($errno));
        }
    }


    function FriendlyErrorType($type)
    {
        switch ($type) {
            case E_ALL: // 32767 //
                return 'E_ALL';
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return "";
    }

    function micro()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    function Translate($text, $lngSource, $lngTarget, $ishtml = false)
    {
        if (empty($this->Translator)) {
            $this->Translator = new Translator("trnsl.1.1.20171110T074401Z.16f4cb1f0547b971.51f7413f91f6ffec16cfe49e478637c3c358cb8b");
        }

        $translation = $this->Translator->translate($text, $lngSource . "-" . $lngTarget, $ishtml);
        return $translation;
    }

    function Subscribe($email, $name = '')
    {
        if (empty($this->MailChimp)) {
            $this->MailChimp = new MailChimp($this->Plugins_MailChimp['key']);
        }
        $result = $this->MailChimp->post("lists/" . $this->Plugins_MailChimp['list'] . "/members", [
            'email_address' => $email,
            'merge_fields' => array('FNAME' => $name, 'LNAME' => ''),
            'status' => 'subscribed',
        ]);
        if (!empty($result['id'])) {
            $arrResult = array('response' => 'success');
        } else {
            $arrResult = array('response' => 'error', 'message' => $result['detail']);
        }
        return $arrResult;
    }

    function FloodGuard()
    {
        $logs = file_get_contents(dirname(__FILE__) . '/logs/blocked.log');
        $logs = explode("\n", $logs);
        $newlog = "";
        $found = false;
        $foundtime = "";
        for ($i = 0; $i < count($logs); $i++) {
            $tmpd = explode(' - ', $logs[$i]);
            if (count($tmpd) > 2) {
                if ($tmpd[1] > (date('U') - 3600)) {
                    if ($tmpd[2] == $this->userip) {
                        $found = true;
                        $foundtime = $tmpd[1] + 3600;
                    }
                    $newlog = $newlog . $logs[$i] . "\n";
                }
            }
        }
        file_put_contents(dirname(__FILE__) . '/logs/blocked.log', $newlog);
        if ($found == true) {
            $this->CreateView('guard', 'guard.tpl');
            $this->Views['guard']->assign('locktime', date('y-m-d H:i:s', $foundtime));
            $this->Views['guard']->parse('main.blocked');
            $this->Views['guard']->parse('main');
            $this->Views['guard']->out('main');
            exit();
        }
        $ActionCount = 5;
        if ($_SESSION['guardian']['blocked'] == true) {
            $ActionCount = 3;
        }
        if ($_SESSION['guardian']['blockcount'] == 20) {
            file_put_contents(dirname(__FILE__) . '/logs/blocked.log', date('d-m-y H:i:s') . ' - ' . date('U') . ' - ' . $this->userip . "\n", FILE_APPEND);

            $this->CreateView('guard', 'guard.tpl');
            $this->Views['guard']->parse('main');
            $this->Views['guard']->out('main');
            $_SESSION['guardian']['blockcount'] = 0;
            exit();
        }
        if ($_SESSION['guardian']['last_session_request'] > ($this->micro() - 2)) {
            if (empty($_SESSION['guardian']['last_request_count'])) {
                $_SESSION['guardian']['last_request_count'] = 1;
            } elseif ($_SESSION['guardian']['last_request_count'] < $ActionCount) {
                $_SESSION['guardian']['last_request_count'] = $_SESSION['guardian']['last_request_count'] + 1;
            } elseif ($_SESSION['guardian']['last_request_count'] >= $ActionCount) {
                $_SESSION['guardian']['last_session_request'] = $this->micro();
                $_SESSION['guardian']['blocked'] = true;
                if (empty($_SESSION['guardian']['blockedtime'])) $_SESSION['guardian']['blockedtime'] = date('U');
                if ($_SESSION['guardian']['blockedtime'] > date('U') + 600) {
                    $_SESSION['guardian']['blockedtime'] = date('U');
                    $_SESSION['guardian']['blockcount'] = 0;
                }
                if (empty($_SESSION['guardian']['blockcount'])) {
                    $_SESSION['guardian']['blockcount'] = 1;
                } else {
                    $_SESSION['guardian']['blockcount'] = $_SESSION['guardian']['blockcount'] + 1;
                }
                $this->CreateView('guard', 'guard.tpl');
                $this->Views['guard']->parse('main');
                $this->Views['guard']->out('main');
                exit();
            }
        } else {
            $_SESSION['guardian']['blocked'] = false;
            $_SESSION['guardian']['last_request_count'] = 1;
            $_SESSION['guardian']['last_session_request'] = $this->micro();
        }
    }

    function shutdownHandler() //will be called when php script ends.
    {
        $lasterror = error_get_last();
        switch ($lasterror['type']) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_PARSE:
                $error = "[SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];
                print_r($error);
                exit();
        }
    }
    function StartCustom($reset = false)
    {
        if (empty($this->Custom) || $reset == true) {
            $this->Custom = new CustomOperations($this);
        }
    }

    function RefreshCustom()
    {
        $this->Custom->Refresh($this);
    }

    function StartFile()
    {
        $this->File = new FileOperations();
    }

    function StartImage()
    {
        $this->Image = new ImageOperations();
    }

    public function Value($key)
    {
        return eval('$this->sqlType;');
    }

    // View işlemleri
    function CreateView($name, $filename)
    {
        $this->Views[$name] = new View($filename);
    }

    function check_login()
    {

    }

    function login()
    {

    }

    function logout()
    {

    }
    //DATA
    //v1
    /*
     * DATA Connection functions
     *
     */
    function data_connect()
    {
        if ($this->sqlUsePDO == true) {
            $this->Connection = new PDO($this->sqlType . ':host=' . $this->sqlServer . ';dbname=' . $this->sqlDatabase . ';port=' . $this->sqlPort . ';charset=' . $this->sqlCharset, $this->sqlUsername, $this->sqlPassword);
        } else {
            switch ($this->sqlType) {
                case 'mysql':
                    $this->Connection = @mysql_connect($this->sqlServer . ':' . $this->sqlPort, $this->sqlUsername, $this->sqlPassword, $this->sqlDatabase) or $this->Exception[] = "<strong>Database Connection Error:</strong> " . mysql_error();
                    @mysql_select_db($this->sqlDatabase, $this->Connection);
                    @$this->data_run("SET NAMES '" . $this->sqlCharset . "'");
                    @$this->data_run("SET CHARACTER SET " . $this->sqlCharset);
                    break;
                case 'mysqli':
                    $this->Connection = @mysqli_connect($this->sqlServer, $this->sqlUsername, $this->sqlPassword, $this->sqlDatabase, $this->sqlPort);
                    if (!$this->Connection) {
                        $this->Exception[] = "<strong>Database Connection Error:</strong> " . mysqli_connect_errno();
                    } else {
                        @$this->data_run("SET NAMES '" . $this->sqlCharset . "'");
                        @$this->data_run("SET CHARACTER SET " . $this->sqlCharset);
                    }
                    break;
                case 'mssql':
                    $this->Connection = @sqlsrv_connect($this->sqlServer, array("Database" => $this->sqlDatabase . "," . $this->sqlPort, "UID" => $this->sqlUsername, "PWD" => $this->sqlPassword, "CharacterSet" => $this->sqlCharset));
                    if ($this->Connection === false) {
                        $this->Exception[] = "<strong>Database Connection Error:</strong> " . serialize(sqlsrv_errors());
                    }
                    break;
                case 'pgsql':
                    try {
                        $this->Connection = @pg_connect("host='" . $this->sqlServer . "' dbname='" . $this->sqlDatabase . "' user='" . $this->sqlUsername . "' password='" . $this->sqlPassword . "' port='" . $this->sqlPort . "' options='--client_encoding=" . $this->sqlCharset . "'");
                    } Catch (Exception $e) {
                        $this->Exception[] = "<strong>Database Connection Error:</strong> " . $e->getMessage();
                    }
                    break;
            }
        }


    }

    function data_get($query)
    {
        $return = "";
        if ($this->sqlUsePDO == true) {
            try {
                $return = $this->Connection->query($query);
            } catch (PDOException $ex) {
                $this->Exception[] = "<strong>Database Query Error:</strong> " . $ex->getMessage();
            }
        } else {
            switch ($this->sqlType) {
                case 'mysql':
                    $return = @mysql_query($query) or $this->Exception[] = "Database Query Error: " . mysql_error();
                    break;
                case 'mysqli':
                    $return = @mysqli_query($this->Connection, $query);
                    if (!$return) {
                        $this->Exception[] = "<strong>Database Query Error:</strong> " . mysqli_error();
                    }
                    break;
                case 'mssql':
                    $return = @sqlsrv_query($this->Connection, $query) or $this->Exception[] = "<strong>Database Query Error:</strong> " . sqlsrv_errors($this->Connection);
                    break;
                case 'pgsql':
                    $return = pg_query($this->Connection, $query) or $this->Exception[] = "<strong>Database Query Error:</strong> " . pg_result_error($this->Connection);
                    break;
            }
        }

        return $return;
    }

    function data_run($query)
    {
        $return = "";
        if ($this->sqlUsePDO == true) {
            try {
                $return = $this->Connection->exec($query);
            } catch (PDOException $ex) {
                $this->Exception[] = "<strong>Database Query Error:</strong> " . $ex->getMessage();
            }
        } else {
            switch ($this->sqlType) {
                case 'mysql':
                    $return = @mysql_query($query) or $this->Exception[] = "Database Query Error: " . mysql_error();
                    break;
                case 'mysqli':
                    $return = @mysqli_query($this->Connection, $query);
                    if (!$return) {
                        $this->Exception[] = "<strong>Database Query Error:</strong> " . mysqli_error();
                    }
                    break;
                case 'mssql':
                    $return = @sqlsrv_query($this->Connection, $query) or $this->Exception[] = "<strong>Database Query Error:</strong> " . sqlsrv_errors($this->Connection);
                    break;
                case 'pgsql':
                    $return = pg_query($this->Connection, $query) or $this->Exception[] = "<strong>Database Query Error:</strong> " . pg_result_error($this->Connection);
                    break;
            }
        }

        return $return;
    }


    function data_fetch_array($sql)
    {
        $return = "";
        if ($this->sqlUsePDO == true) {
            $return = $sql->fetch(PDO::FETCH_ASSOC);
        } else {
            switch ($this->sqlType) {
                case 'mysql':
                    $return = mysql_fetch_array($sql);
                    break;
                case 'mysqli':
                    $return = mysqli_fetch_array($sql);
                    break;
                case 'mssql':
                    $return = sqlsrv_fetch_array($sql);
                    break;
                case 'pgsql':
                    $return = pg_fetch_array($sql);
                    break;
            }
        }

        return $return;
    }


    function data_num_rows($sql)
    {
        $return = "";
        if ($this->sqlUsePDO == true) {
            $return = $sql->rowCount();
        } else {
            switch ($this->sqlType) {
                case 'mysql':
                    $return = mysql_num_rows($sql);
                    break;
                case 'mysqli':
                    $return = mysqli_num_rows($sql);
                    break;
                case 'mssql':
                    $return = sqlsrv_num_rows($sql);
                    break;
                case 'pgsql':
                    $return = pg_num_rows($sql);
                    break;
            }
        }
        return $return;
    }

    function data_insert_id($sql)
    {
        $return = "";
        if ($this->sqlUsePDO == true) {
            $return = $this->Connection->lastInsertId();
        } else {
            switch ($this->sqlType) {
                case 'mysql':
                    $return = mysql_insert_id();
                    break;
                case 'mysqli':
                    $return = mysqli_insert_id($sql);
                    break;
                case 'mssql':
                    $return = sqlsrv_num_rows($sql);
                    break;
                case 'pgsql':
                    $return = pg_num_rows($sql);
                    break;
            }
        }
        return $return;
    }

    //DATA

    function SEO_text($vp_string)
    {
        $vp_string = trim($vp_string);
        $vp_string = html_entity_decode($vp_string);
        $vp_string = strip_tags($vp_string);
        $vp_string = str_replace(array("Ç", "ç", "Ş", "ş", "Ğ", "ğ", "ı", "Ö", "ö", "Ü", "ü", "İ"), array("c", "c", "s", "s", "g", "g", "i", "o", "o", "u", "u", "i"), $vp_string);
        $vp_string = preg_replace('~[^ a-zA-Z0-9_]~', ' ', $vp_string);
        $vp_string = preg_replace('~ ~', '-', $vp_string);
        $vp_string = preg_replace('~-+~', '-', $vp_string);
        return $vp_string;
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $ipaddress = 'Local IP detected';
        } else {
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if (isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if (isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if (isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    function hash($len = 15, $isupper = false)
    {
        $val = bin2hex(mcrypt_create_iv($len, MCRYPT_DEV_URANDOM));
        if ($isupper == true) {
            $val = strtoupper($val);
        }
        return $val;
    }

    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'yil',
            'm' => 'ay',
            'w' => 'hafta',
            'd' => 'gun',
            'h' => 'saat',
            'i' => 'dakika',
            's' => 'saniye',
        );
        $stringName = array(
            'y' => 'Yıl',
            'm' => 'Ay',
            'w' => 'Hafta',
            'd' => 'Gün',
            'h' => 'Saat',
            'i' => 'Dakika',
            's' => 'Saniye',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $stringName[$k];
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . " " . 'Önce' : " " . 'Sonra';
    }

    function money($mny)
    {
        return number_format($mny, 2, ",", ".");
    }

    function aygetir($ay)
    {
        if (strlen($ay) > 2) {
            $ay = explode("-", $ay);
            $ay = $ay[1];
        }
        $ay = (int)$ay;
        $aylar = array(
            1 => "Ocak",
            2 => "Şubat",
            3 => "Mart",
            4 => "Nisan",
            5 => "Mayıs",
            6 => "Haziran",
            7 => "Temmuz",
            8 => "Ağustos",
            9 => "Eylül",
            10 => "Ekim",
            11 => "Kasım",
            12 => "Aralık"
        );
        return $aylar[$ay];
    }

    function tr_strtolower($metin)
    {
        $metin = str_replace(array('Ğ', 'Ü', 'Ş', 'I', 'İ', 'Ö', 'Ç'), array('ğ', 'ü', 'ş', 'ı', 'i', 'ö', 'ç'), $metin);
        return mb_strtolower($metin, 'utf-8');
    }

    function tr_strtoupper($metin)
    {
        $metin = str_replace(array('ğ', 'ü', 'ş', 'ı', 'i', 'ö', 'ç'), array('Ğ', 'Ü', 'Ş', 'I', 'İ', 'Ö', 'Ç'), $metin);
        return mb_strtoupper($metin, 'utf-8');
    }

    function tr_ucfirst($metin)
    {
        $ilk = mb_substr($metin, 0, 1, 'utf-8');
        $kalan = mb_substr($metin, 1, strlen($metin), 'utf-8');
        return tr_strtoupper($ilk) . tr_strtolower($kalan);
    }

    function kucult($metin)
    {
        $metin = preg_split("/( |-)/", $metin);
        $metinek = array();
        for ($i = 0; $i < count($metin); $i++) {
            $metinek[] = $this->tr_ucfirst($metin[$i]);
        }
        return implode(" ", $metinek);
    }

    function compresscss($code)
    {
        $code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code);
        $code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $code);
        $code = str_replace('{ ', '{', $code);
        $code = str_replace(' }', '}', $code);
        $code = str_replace('; ', ';', $code);

        return $code;
    }

    /*
     * Sorting multi-dimensional arrays by index
     * Usage:
     * multi_array_sort($Multi_array, array('field1' => array(SORT_DESC, SORT_NUMERIC), 'field3' => array(SORT_DESC, SORT_NUMERIC)), true)
     */
    function multi_array_sort($data, $sortCriteria, $caseInSensitive = true)
    {
        if (!is_array($data) || !is_array($sortCriteria))
            return false;
        $args = array();
        $i = 0;
        foreach ($sortCriteria as $sortColumn => $sortAttributes) {
            $colList = array();
            foreach ($data as $key => $row) {
                $convertToLower = $caseInSensitive && (in_array(SORT_STRING, $sortAttributes) || in_array(SORT_REGULAR, $sortAttributes));
                $rowData = $convertToLower ? strtolower($row[$sortColumn]) : $row[$sortColumn];
                $colLists[$sortColumn][$key] = $rowData;
            }
            $args[] = &$colLists[$sortColumn];

            foreach ($sortAttributes as $sortAttribute) {
                $tmp[$i] = $sortAttribute;
                $args[] = &$tmp[$i];
                $i++;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return end($args);
    }

    function arrayToNested($a, $level = 'NULL')
    {
        $r = '';
        foreach ($a as $i) {
            if ($i['parent_id'] == $level) {
                $r = $r . "<li>" . $i['title'] . arrayToNested($a, $i['id']) . "</li>";
            }
        }
        return ($r == '' ? '' : "<ol>" . $r . "</ol>");
    }

    function StartPage()
    {
        if ($this->FloodGuard == true) {
            $this->FloodGuard();
        }
    }

    function EndPage()
    {
        if ($this->Debug == true) {
            $this->Debug();
        } elseif ($this->ShowErrors == true && count($this->Exception) > 0) {
            $this->Debug();
        }
    }

    function Debug()
    {
        $elapsed = microtime(true) - $this->startmicrotime;
        $endtime = date('U');

        $this->CreateView('debug', 'debug.tpl');
        $this->Views['debug']->assign('start', date('d-m-y H:i:s', $this->starttime));
        $this->Views['debug']->assign('end', date('d-m-y H:i:s', $endtime));
        $this->Views['debug']->assign('elapsedsecond', $endtime - $this->starttime);
        $this->Views['debug']->assign('elapsed', $elapsed);
        $this->Views['debug']->assign('userip', $this->userip);
        $this->Views['debug']->assign('version', $this->Version);
        $this->Views['debug']->assign('phpversion', phpversion());
        $this->Views['debug']->assign('dbserver', $this->sqlServer);
        $this->Views['debug']->assign('dbname', $this->sqlDatabase);
        $this->Views['debug']->assign('dbusername', $this->sqlUsername);
        $this->Views['debug']->assign('dbcharset', $this->sqlCharset);
        $alllevels = array();
        for ($i = 0; $i < count($this->ErrorLevels); $i++) {
            $alllevels[] = $this->FriendlyErrorType($this->ErrorLevels[$i]);
        }
        $this->Views['debug']->assign('errorlevels', implode(',', $alllevels));
        ob_start();
        phpinfo();
        $phpinfo = ob_get_contents();
        ob_end_clean();
        $this->Views['debug']->assign('phpinfo', $phpinfo);
        $loadedviews = array();
        foreach ($this->Views as $key => $val) {
            if ($key == "debug") continue;
            $loadedviews[] = $key;
        }
        if (count($loadedviews) > 0) {
            $this->Views['debug']->assign('views', implode(',', $loadedviews));
        } else {
            $this->Views['debug']->assign('views', 'No views loaded.');
        }
        $this->Views['debug']->assign('dbprovider', $this->sqlType . ($this->sqlUsePDO == false ? '' : ' (PDO)'));
        if (!$this->Connection) {
            $this->Views['debug']->assign('dbstatus', 'Disconnected');
        } else {
            $this->Views['debug']->assign('dbstatus', 'Connected');
        }
        $files = scandir(dirname(__FILE__));
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i] == '.' || $files[$i] == "..") continue;
            if (is_dir(dirname(__FILE__) . "/" . $files[$i])) continue;

            $this->Views['debug']->assign('filename', $files[$i]);
            $this->Views['debug']->assign('filetime', date("d-m-y H:i:s", filemtime(dirname(__FILE__) . "/" . $files[$i])));
            if(stristr($files[$i],'Teknolobi')) {
                $this->Views['debug']->parse('main.tfilelist');
            } else {
                $this->Views['debug']->parse('main.nfilelist');
            }


        }
        if (count($this->Exception) > 0) {
            $k = 0;
            foreach ($this->Exception as $error) {
                $k++;
                $this->Views['debug']->assign('errornumber', "<strong>" . $k . "- </strong>");
                $this->Views['debug']->assign('errormessage', $error);
                $this->Views['debug']->parse('main.message');
            }
        } else {
            $this->Views['debug']->assign('errormessage', 'No errors found.');
            $this->Views['debug']->parse('main.message');
        }
        if (count($GLOBALS['PHPexception']) > 0) {
            $k = 0;
            foreach ($GLOBALS['PHPexception'] as $error) {
                $k++;
                $this->Views['debug']->assign('errorid', $k);
                $this->Views['debug']->assign('phpnum', $error[0]);
                $this->Views['debug']->assign('phpfile', $error[1]);
                $this->Views['debug']->assign('phpline', $error[2]);
                $this->Views['debug']->assign('phperror', $error[3]);
                $this->Views['debug']->assign('phptype', $error[4]);
                $this->Views['debug']->parse('main.phpmessage');
            }
        } else {
            $this->Views['debug']->parse('main.nophpmessage');
        }
        if (!empty($this->DebugReturn)) {
            //print_r(debug_backtrace(), true).
            $this->Views['debug']->assign('debugreturn', print_r($this->DebugReturn, true));
            $this->Views['debug']->parse('main.debugreturn');
        }

        $this->Views['debug']->parse('main');
        $this->Views['debug']->out('main');
        exit();
    }
}

require("Teknolobi.View.php");
require("Teknolobi.Custom.php");
require("Teknolobi.Image.php");
require("Teknolobi.File.php");
require("Teknolobi.Payment.php");
require("plugins/Yandex/Translate/Translator.php");
require("plugins/Yandex/Translate/Translation.php");
require("plugins/MailChimp/MailChimp.php");
?>
