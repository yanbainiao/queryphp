<?php
/********************************************************************
*   FileName: class.msn.php                                            *
*   by changwei, 2010-4-13                                            *
*   Contact MSN:  changwei0112@hotmail.com                            *
*    获取MSN好友Email列表                                            *
*                                                                     *
=====================================================================
*                                                                     *
*   PHP配置环境要求                                                    *
*   支持socket、ssl、curl，开启php.ini文件中的以下地方                *
*    ;extension=php_sockets.dll                                         *   
*    ;extension=php_curl.dll                                         *
*    ;extension=php_openssl.dll                                         *
*                                                                     *
=====================================================================
*                                                                     *
*    使用方法  DEMO                                                    *
*    require('class.msn.php');   //引用MSN好友列表类                    *
*    $msn = new MsnFriend();                                            *
*    $list = $msn->GetList('changwei0112@hotmail.com','123123');        *
*    print_r($list);               //打印结果                            *
*                                                                    *
********************************************************************/
Class MsnFriend {
    /**
    * 获取MSN列表
    **/
    function GetList($username,$password) {
        global $sbconn,$debug,$trid;
        ob_start();
        $username = trim($username);
        $password = trim($password);
        if(empty($username) || empty($password)){
            die('need name and password');
        }
        $debug = 0;
        $trid  = 0;
        $proto = "MSNP10";
        $sbconn = fsockopen("messenger.hotmail.com",1863) or die("Can't connect to MSN server");
        flush();
        $this->data_out("VER $trid $proto CVR0");
        $this->data_in();
        $this->data_out("CVR $trid 0x0409 winnt 5.1 i386 MSNMSGR 8.0.0812 MSMSGS $username");
        $this->data_in();
        $this->data_out("USR $trid TWN I $username");
        $temp = $this->data_in();
        if (!stristr($temp,":")) {
            if (substr($temp,0,3)==601){
                die();
            } else {
            fclose($sbconn);
                die();
            }
        }
        @fclose($sbconn);
        $temp_array = explode(" ",$temp);
        $temp_array = explode(":",$temp_array[3]);
        flush();
        $sbconn = fsockopen($temp_array[0],$temp_array[1]) or die("error -_-#");
        $this->data_out("VER $trid $proto CVR0");
        $this->data_in();
        flush();
        $this->data_out("CVR $trid 0x0409 winnt 5.1 i386 MSNMSGR 8.0.0812 MSMSGS $username");
        $this->data_in();
        $this->data_out("USR $trid TWN I $username");
        $temp = $this->data_in();
        $temp_array = explode(" ",$temp);
        flush();
        $TOKENSTRING = trim(end($temp_array));
        flush();
        $nexus_socket = fsockopen("ssl://nexus.passport.com",443);
        fputs($nexus_socket,"GET /rdr/pprdr.asp HTTP/1.0\r\n\r\n");
        while ($temp != "\r\n"){
            $temp = fgets($nexus_socket,1024);
            if (substr($temp,0,12)=="PassportURLs"){
                $urls = substr($temp,14);
            }
        }
        $temp_array = explode(",",$urls);
        $temp = $temp_array[1];
        $temp = substr($temp,8);
        $temp_array = explode("/",$temp);
        @fclose($nexus_socket);
        $ssl_conn = fsockopen("ssl://".$temp_array[0],443);
        fputs($ssl_conn,"GET /{$temp_array[1]} HTTP/1.1\r\n");
        fputs($ssl_conn,"Authorization: Passport1.4 OrgVerb=GET,OrgURL=http%3A%2F%2Fmessenger%2Emsn%2Ecom,sign-in=".urlencode($username).",pwd=$password,$TOKENSTRING\r\n");
        fputs($ssl_conn,"User-Agent: MSMSGS\r\n");
        fputs($ssl_conn,"Host: {$temp_array[0]}\r\n");
        fputs($ssl_conn,"Connection: Keep-Alive\r\n");
        fputs($ssl_conn,"Cache-Control: no-cache\r\n\r\n");
        $temp = fgets($ssl_conn,512);
        if (rtrim($temp) == "HTTP/1.1 302 Found"){
            flush();
            while ($temp != "\r\n") {
                $temp = fgets($ssl_conn,256);
                if (substr($temp,0,9)=="Location:"){
                    $temp_array = explode(":",$temp);
                    $temp_array = explode("/",trim(end($temp_array)));
                    break;
                }
            }
            @fclose($ssl_conn);
            $ssl_conn = fsockopen("ssl://".$temp_array[2],443);
            fputs($ssl_conn,"GET /{$temp_array[3]} HTTP/1.1\r\n");
            fputs($ssl_conn,"Authorization: Passport1.4 OrgVerb=GET,OrgURL=http%3A%2F%2Fmessenger%2Emsn%2Ecom,sign-in=".urlencode($username).",pwd=$password,$TOKENSTRING\r\n");
            fputs($ssl_conn,"User-Agent: MSMSGS\r\n");
            fputs($ssl_conn,"Host: {$temp_array[2]}\r\n");
            fputs($ssl_conn,"Connection: Keep-Alive\r\n");
            fputs($ssl_conn,"Cache-Control: no-cache\r\n\r\n");
        } elseif (rtrim($temp)=="HTTP/1.1 401 Unauthorized"){
            @fclose($ssl_conn);
            die();
        } else {
            if (rtrim($temp) != "HTTP/1.1 200 OK"){
                flush();
                die();
            }
        }

        while ($temp != "\r\n"){
            $temp = fgets($ssl_conn,1024);
            if (substr($temp,0,19)=="Authentication-Info"){
                $auth_info = $temp;
                $temp = fgets($ssl_conn,1024);
                if (substr($temp,0,14)!="Content-Length"){
                $auth_info.= fgets($ssl_conn,1024);
                }
                break;
            }
        }
        @fclose($ssl_conn);
        $temp_array = explode("'",$auth_info);
        flush();
        $this->data_out("USR $trid TWN S {$temp_array[1]}");
        flush();
        $temp=$this->data_in();
        flush();
        $time_since_initmsg = time();
        while(!strstr($temp,"ABCHMigrated") && is_string(trim($temp))){
            if (substr($temp,0,3)=="sid"){
                $sid = trim(substr($temp,5));
            }
            if (substr($temp,0,2)=="kv"){
                $kv = trim(substr($temp,4));
            }
            if (substr($temp,0,7)=="MSPAuth"){
                $mspauth = trim(substr($temp,9));
                flush();
            }
            $temp = $this->data_in();
        }
        $temp = $this->data_in();
        flush();
        $this->data_out("SYN $trid 0 0");
        flush();
        stream_set_timeout($sbconn,0,125000);
        for($i=0;$i<160;$i++) {
            $temp = $this->data_in();
            switch (substr($temp, 0, 3)) {
                case "LST":
                    $temp_array = explode(" ",$temp);
                    $un = substr($temp_array[1], 2);
                    $nn = substr($temp_array[2], 2);
                    $nn1 = substr($temp_array[2], 0, 1);
                    if($nn1 == "F") {
                        echo "$nn\n";
                    }
                    echo $temp."<br/>";
                    break;
                default:
                    break;
            }
        }
        @fclose($sbconn);
        $t = ob_get_contents();
        ob_end_clean();
        preg_match_all('/N\=([^\=]+) /i',$t,$res);
        return $res[1];
        //echo implode('</br>',array_unique($res[1]));
    }
    function data_out($data){
        global $sbconn,$debug,$trid;
        fputs($sbconn,$data."\r\n");
        $trid++;
    }
    function data_in(){
        global $sbconn,$debug;
        $temp = fgets($sbconn,256);
        return $temp;
    }
}
?>
