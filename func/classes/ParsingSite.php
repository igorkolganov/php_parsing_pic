<?php

class ParsingSite extends ConnectDB {
    private $varData;
    private $url;
    private $pages;
    private $data;
    private $linksPhoto = [];
    private $dataToReplace;
    private $dataWithReplace;
    private $dateToReplace;
    private $editLink;
    private $list = [];
    private $arrData = [];



    public function __construct($url, $pages){
        $this->url = $url;
        $this->pages = $pages;
        $this->dataToReplace = ['<span class="item__title rm-cm-item-text">', '</span>'];
        $this->dataWithReplace = ['', ''];
        $this->dateToReplace = ['<span class="item__category">', '</span>'];
    }

    protected function setParameters(){
        $this->varData = curl_init();
        curl_setopt($this->varData, CURLOPT_URL, $this->url);
        curl_setopt($this->varData, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->varData, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->varData, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->varData, CURLOPT_HEADER, false);
        curl_setopt($this->varData, CURLOPT_ENCODING, '');
        curl_setopt($this->varData, CURLOPT_USERAGENT, 'spider_man');
        curl_setopt($this->varData, CURLOPT_AUTOREFERER, 'spider_man');
        curl_setopt($this->varData, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($this->varData, CURLOPT_TIMEOUT, 120);
        curl_setopt($this->varData, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->varData, CURLOPT_POST, 1);
        curl_setopt($this->varData, CURLOPT_POSTFIELDS, $this->url);
        curl_setopt($this->varData, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->varData, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->varData, CURLOPT_VERBOSE, 1);
        $this->data = curl_exec($this->varData);
    }

    protected function getLenght(){
        return $this->pages;
    }

    protected function getDataArray(){
        $dom = new simple_html_dom();
        $html = str_get_html($this->data);
        $list = $html->find(".js-news-feed-item");
        for ($i = 0; $i < count($list); $i++){
            array_push($this->arrData, $list[$i]);
        }
    }

    protected function parseTitle($k){
        preg_match_all('#<span class="item__title rm-cm-item-text">(.+?)</span>#is' ,$this->arrData[$k], $matches);
        $editData = str_replace($this->dataToReplace, $this->dataWithReplace, $matches[0][0]);
        return preg_replace('/\s\s+/', '', $editData);
    }

    protected function parseData($k){
        preg_match_all('#<span class="item__category">(.+?)</span>#is' ,$this->arrData[$k], $matches_date);
        return $editDate = str_replace($this->dateToReplace, $this->dataWithReplace, $matches_date[0][0]);
    }

    protected function parseBody($url){
        $varData = curl_init();
        curl_setopt($varData, CURLOPT_URL, $url);
        curl_setopt($varData, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($varData, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($varData, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($varData, CURLOPT_HEADER, false);
        curl_setopt($varData, CURLOPT_ENCODING, '');
        curl_setopt($varData, CURLOPT_USERAGENT, 'spider_man');
        curl_setopt($varData, CURLOPT_AUTOREFERER, 'spider_man');
        curl_setopt($varData, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($varData, CURLOPT_TIMEOUT, 120);
        curl_setopt($varData, CURLOPT_MAXREDIRS, 10);
        curl_setopt($varData, CURLOPT_POST, 1);
        curl_setopt($varData, CURLOPT_POSTFIELDS, $this->url);
        curl_setopt($varData, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($varData, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($varData, CURLOPT_VERBOSE, 1);
        $dataStrLink = curl_exec($varData);

        $dom = new simple_html_dom();
        $html = str_get_html($dataStrLink);
        $list = $html->find(".l-table");
        $dataBody = preg_replace( '/<(.+?)>/is', '', $list[0]);
        return preg_replace('/\s\s+/', '', $dataBody);
    }

    protected function parseLink($k){
        $dom = new simple_html_dom();
        $html = str_get_html($this->arrData[$k]);
        $list = $html->find(".item__link");
        for ($i = 0; $i < count($list); $i++){
            preg_match_all('/href="([^"]*)"/i' ,$list[$i], $matches_link);
            $data = substr($matches_link[0][0], 6);
            $data = substr($data, 0, -1);
            return $data;
        }
    }

    protected function parsePhoto($k){
        preg_match_all('#<span class="item__image-block">(.+?)</span>#is' ,$this->arrData[$k], $matches_date_photo);
        $dataL = substr($matches_date_photo[1][0], 99, 85);
        if ($dataL){
            return $dataL;
        }else{

        }
    }

    protected function downloadPhoto($link)
    {
        $upload_path = "img/download/";
        $user_filename = $link;
        $userfile_basename = pathinfo($user_filename, PATHINFO_FILENAME);
        $userfile_extension = pathinfo($user_filename, PATHINFO_EXTENSION);
        $server_filename = $userfile_basename . "." . $userfile_extension;
        $server_filepath = $upload_path . $server_filename;

        $i = 0;
        while (file_exists($server_filepath)) {
            $ms = explode(' ', microtime());
            $i++;
            $server_filepath = $upload_path . $ms[1] . "($i)" . "." . $userfile_extension;
        }
        if (copy($link, $server_filepath)) {
            $response['status'] = 'ok';
            return $server_filepath;
        }
    }

    protected function putData($editData, $editDate, $dataBody, $photoData){
        $this->insertData($editData, $editDate, $dataBody, $photoData);
    }

    protected function getDataMain($a){
        return $this->getData($a);
    }
}