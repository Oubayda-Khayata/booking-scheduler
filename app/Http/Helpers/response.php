<?php

if(!function_exists('json')){
    function json($status='',$message='',$data='',$code=200){

        $response['status'] = $status;
        $response['message'] = $message;
        $response['data'] =$data;

        return response()->json($response, $code);
    }
}
