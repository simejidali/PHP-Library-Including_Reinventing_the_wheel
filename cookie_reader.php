<?php

// *************************************************
// Title: cookie_reader.php
// Summary: CURLで取得したCookieを扱いやすくする
// Author: C-take
// License: MIT
// *************************************************

class CookieReader{
    private $dic_cookie = array();

    //コンストラクタ
    public function __construct($cookie_file){
        $cookie_text = file_get_contents($cookie_file);

        $pattern = '/^([^\t\n]|)*\t([^\t\n]|)*\t([^\t\n]|)*\t([^\t\n]|)*\t([^\t\n]|)*\t([^\t\n]|)*\t([^\t\n]|)*$/m';
        preg_match_all($pattern,$cookie_text,$matches, PREG_OFFSET_CAPTURE);
        foreach($matches as $match){
            if(0 < strlen($match[0][0])){
            $tmpAry = explode("\t",$match[0][0]);
                if(count($tmpAry) == 7){
                    $this->$dic_cookie[] = array(
                                    'domain' => $tmpAry[0],
                                    'httponly'=>$tmpAry[1],
                                    'path' => $tmpAry[2],
                                    'secure' => $tmpAry[3],
                                    'expires'=> $tmpAry[4],
                                    'name'=> $tmpAry[5],
                                    'content'=> $tmpAry[6]
                                );
                }
            }
        }
    }
    
    // 指定した名前のクッキーを取得する
    public function Get($cookie_name){
        return $this->searchCookie($cookie_name);
    }
    
    // 指定した名前のクッキーのコンテンツを取得する
    public function GetContent($cookie_name){
        $cookie_record = $this->searchCookie($cookie_name);
        return $cookie_record['content'];
    }

    // クッキーの検索
    private function searchCookie($cookie_name){
        $result = array();
        if(gettype ($this->$dic_cookie) != "array"){
              return $result;
        }
        foreach($this->$dic_cookie as $record){
            if($record['name'] == $cookie_name){
                $result = $record;
            }
        }
        return $result;
    }


}




?>