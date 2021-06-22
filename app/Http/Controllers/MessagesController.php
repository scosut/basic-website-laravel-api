<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Message;

class MessagesController extends Controller
{
  public function index() {
    $messages = Message::all();

    if (count($messages) > 0) {
      return response()->json(["success" => true, "data" => $messages]);
    }
    else {
      return response()->json(["success" => false, "data" => []]);
    }
}
  
  public function store(Request $request) {
    $data = json_decode($request->getContent(), true);    
	  $data = filter_var_array($data, FILTER_SANITIZE_STRING);

    // validate inputs
    $validator = Validator::make($data,
        [
          "name"    => "required",
          "email"   => "required|email",
          "message" => "required"
        ]
    );

    // if validation fails
    if($validator->fails()) {
      return response()->json(["success" => false, "errors" => $validator->errors(), "message" => "failed validation", "data" => $data]);
    }

    $message = Message::create($data);
    
    if(!is_null($message)) {            
      return response()->json(["success" => true, "errors" => [], "message" => "message created"]);
    }    
    else {
      return response()->json(["success" => false, "errors" => [], "message" => "message not created"]);
    }
  }
}
