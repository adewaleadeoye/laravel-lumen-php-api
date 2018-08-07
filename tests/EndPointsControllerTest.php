<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EndPointControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * 
     * @test
     */
    public function get_request_returns_json_with_expected_structure()
    {
        $this->GET('/vehicles/2015/Toyota/Yaris')
            ->seeStatusCode(200)
            ->shouldReturnJson()
            ->seeJsonStructure([
                'Count',
                'Results'
            ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     * 
     * @test
     */
    public function get_request_returns_json__with_expected_structure_when_rating_equals_true()
    {
        $this->GET('/vehicles/2015/Toyota/Yaris?withRating=true')
            ->seeStatusCode(200)
            ->shouldReturnJson()
             ->seeJsonStructure([
                'Count',
                'Results'
            ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     * 
     * @test
     */
    public function post_json_request_returns_json_with_expected_structure()
    {
        $this->json('POST', '/vehicles',["modelYear"=>2015,"manufacturer"=>"Audi", "model"=> "A3"])
            ->seeStatusCode(200)
            ->shouldReturnJson()
            ->seeJsonStructure([
                'Count',
                'Results'
            ]);
    }
    /**
     *
     * @return void
     * 
     * @test
     */
    public function invalid_post_json_request_returns_zero_based_json_with_expected_structure()
    {
        $this->json('POST', '/vehicles',["manufacturer"=>"Honda", "model"=> "Accord"])
            ->seeStatusCode(200)
            ->shouldReturnJson()
             ->seeJsonEquals([
                'Count'=>0,
                'Results' => []
            ]);
    }
}
