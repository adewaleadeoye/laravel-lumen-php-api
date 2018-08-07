<?php

namespace App\Http\Controllers;

use App\Http\ConnectClient;
use App\NHTSA;
use Validator;

class ApiController extends Controller
{
    /**
     * Processes route requests 
     *
     * @param string $model_year
     * @param string $manufacturer
     * @param string $model
     * @param string $withRating
     * @return array
     */
    public function respond($model_year,$manufacturer,$model, $withRating=''){

        $uri_part = NHTSA::CAR_MODEL_YEAR.'/'.$model_year.'/'.NHTSA::CAR_MAKE.'/'.$manufacturer.'/'.NHTSA::CAR_MODEL.'/'.$model.'?format=json';
        
        $res = $this->externalApi($uri_part);

        if(!$res){
            return $this->messageContent();
        }

        if($res->Count == 0){
            return $this->messageContent();
        }

        $car_results=array();

        if($withRating==='true'){
            foreach($res->Results as $results){
                $uri_part = NHTSA::VEHICLE_ID.'/'.$results->VehicleId.'?format=json';
                $car_res = $this->externalApi($uri_part);
                if(!$car_res){
                    continue;
                }
                $car_results[]=['CrashRating'=>$car_res->Results[0]->OverallRating,'Description'=>$results->VehicleDescription,'VehicleId'=>$results->VehicleId];
            }
        }else{
            foreach($res->Results as $results){
                $car_results[]=['Description'=>$results->VehicleDescription,'VehicleId'=>$results->VehicleId];
            }
        }

        return $this->messageContent($res->Count,$car_results);
    }

    /**
     * Handles connection to external api through client connect class
     *
     * @param string $uri_part
     * @return mixed boolean|array
     */
    private function externalApi($uri_part){
        $connect = new ConnectClient();
        $con = $connect->connect(NHTSA::BASE_URL.$uri_part);
        
        if(!$con){
            return false;
        }

        if($con->getStatusCode() != 200){
            return false;
        }

        return json_decode($con->getBody());
    }

    /**
     * Validate json post body
     *
     * @param array $data
     * @return boolean
     */
    protected function validatePostJsonBody($data){
        $validator = Validator::make($data, [
            'modelYear' => 'required',
            'manufacturer' => 'required',
            'model'=>'required'
        ]);
        
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    /**
     * Returns array
     *
     * @param integer $count
     * @param array $car_results
     * @return array
     */
    protected function messageContent($count=0,$car_results=[]){
        return ['count'=>$count,'car_results'=>$car_results];
    }

    /**
     * Returns Json response
     *
     * @param array $data
     * @return object
     */
    protected function jsonResponse($data){
        return response()->json(['Count' => $data['count'],'Results'=>$data['car_results']]);
    }

}
