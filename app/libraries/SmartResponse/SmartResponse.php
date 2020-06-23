<?php
namespace Librerias\SmartResponse;
use Librerias\SmartResponse\HttpStatusCode;

//Clase que permite generar respuestas estandarizadas
class SmartResponse extends HttpStatusCode{
    private $statusCode;
    private $success = false;
    private $error = "";
    private $errorMessage = "";
    private $data = [];
    private $paginationNext = "";
    private $paginationPrevious = "";
    private $paginationSelf = "";

    public function __construct($success = true, $statusCode = self::HTTP_OK){
        $this->success = $success;
        $this->statusCode = $statusCode;
    }

    public function __get($name){
        return $this->$name;
    }

    public function onSuccess($statusCode = self::HTTP_OK){
        $this->success = true;
        $this->statusCode = $statusCode;
    }

    public function onError($error, $message, $statusCode = self::HTTP_INTERNAL_SERVER_ERROR){
        $this->success = false;
        $this->error = strtoupper($error);
        $this->error_message = $message;
        if($statusCode < 400 || $statusCode >= 600){
            $this->statusCode = $this->HTTP_INTERNAL_SERVER_ERROR;
        }
        else{
            $this->statusCode = $statusCode;
        }
    }

    public function toJsonString(){
        $json = $this->toJson();
        return json_encode($json);
    }

    public function toJson(){
        $json = new \stdClass();
        $json->success = $this->success;
        
        $json->statusCode = $this->statusCode;
        if ( $this->success == false ) {
            $json->error = new \stdClass();
            $json->error->title = $this->error;
            $json->error->message = $this->error_message;
        }
        else{
            if($this->statusCode < 200 || $this->statusCode > 226){
                //status no fue establecido, se establece 200 por default
                $this->statusCode = self::HTTP_OK;
                $json->status = $this->statusCode;
            }
            
            if($this->statusCode == 201 || $this->statusCode == 204){
                //No agregar data
                //no agregar pagination
                $json->data = $this->data;
            }
            else{
                $json->pagination['self'] = $this->paginationSelf;
                $json->pagination['next'] = $this->paginationNext;
                $json->pagination['prev'] = $this->paginationPrevious;
                
                
                $json->data = $this->data;
            }
           
        }

        return $json;
    }

    public function getParametersFromPaginationToken($paginationToken){
        if( isset($paginationToken) && empty($paginationToken) == false){
            $b64 = base64_decode($paginationToken);
            $params = unserialize($b64);
            return $params;
        }
        else
            return null;
    }

    public function setPagination($params, $rowCount){
        try{
            if( isset($params->limit) ){
                //****** PAGINATION
                $this->setPaginationSelf($params);
                
                $nextPage = clone $params;
                $nextPage->pagination += $params->limit;
                if( $rowCount >= $params->limit){
                    $this->setPaginationNext($nextPage);
                }
               
                $prevPage = clone $params;
                $prevPage->pagination -= $params->limit;
                if($prevPage->pagination >= 0 ){
                    $this->setPaginationPrev($prevPage);
                }
            }
        }
        catch(Exception $err){} 
    }

    public function setPaginationNext($params){
        $this->paginationNext = base64_encode(  serialize($params) );
    }
    public function setPaginationSelf($params){
        $this->paginationSelf = base64_encode(  serialize($params) );
    }
    public function setPaginationPrev($params){
        $this->paginationPrevious = base64_encode(  serialize($params) );
    }

    public function addData($key, $data){
        $this->data[$key] = $data;
    }

    public function setStatusCode($httpStatusCode){
        $this->statusCode = $httpStatusCode;
    }
    public function setSuccess($success){
        $this->success = $success;
    }

}
?>
