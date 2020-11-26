<?php

class DoneJobe extends ParsingSite {
    private $pages = [];
    private $editData;
    private $editLink;
    private $dataBody;
    private $editDate;
    private $photoData;
    private $photoDataLink;


    protected function createParsing(){
        for ($i = 0; $i < $this->getLenght(); $i++) {
            $this->editData = $this->parseTitle($i);
            $this->editDate = $this->parseData($i);
            $this->editLink = $this->parseLink($i);
            $this->dataBody = $this->parseBody($this->editLink);
            $this->photoData = $this->parsePhoto($i);
            $this->photoDataLink = $this->downloadPhoto($this->photoData);
            $this->putData($this->editData, $this->editDate, $this->dataBody, $this->photoDataLink);
        }
    }

    public function putSomeData(){
        $this->setParameters();
        $this->getDataArray();
        $this->createParsing();
    }

    public function getSomeData($a){
        return $this->getDataMain($a);
    }
}