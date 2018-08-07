<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class EndPointsController extends ApiController
{

    /**
     * Handles requests from both post and get routes
     *
     * @param Request $request
     * @param string $model_year
     * @param string $manufacturer
     * @param string $model
     * @return object
     */
    public function showVehicles(Request $request,$model_year='',$manufacturer='',$model=''){

        if($request->isMethod('post')) { //handle request if its a call to post route
            //Validate post body
           if(!$this->validatePostJsonBody($request->all())){
                $data = $this->messageContent();
                return $this->jsonResponse($data);
           }
            
            $model_year = $request->json()->get('modelYear');
            $manufacturer = $request->json()->get('manufacturer');
            $model = $request->json()->get('model');
        }
        
        $data = $this->respond($model_year,$manufacturer,$model,$request->get('withRating'));

        return $this->jsonResponse($data);
    }

}
