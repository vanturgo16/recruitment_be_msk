<?php
namespace App\Traits;
use App\Models\MstRules;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

trait ApiRegionalTrait
{
    public function getTokenRegional()
    {
        $email = MstRules::where('rule_name', 'Email Auth Regional')->first()->rule_value;        
        $password = MstRules::where('rule_name', 'Password Auth Regional')->first()->rule_value;
        $url = MstRules::where('rule_name', 'API Auth Regional')->first()->rule_value;

        $response = Http::post($url, [
            'email' => decrypt($email),
            'password' => decrypt($password),
        ]);
        $data = $response['data'];
        $token = $data['token'];

        return $token;
    }

    public function getProvinceRegional()
    {
        $token = $this->getTokenRegional();
        $ruleApiProvince = MstRules::where('rule_name', 'API List Province')->first()->rule_value;

        $getProvince = Http::withToken($token)->get($ruleApiProvince);
        $provinces = $getProvince['data'];

        return $provinces;
    }

    public function getCity($id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List City
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API List City')->first()->rule_value;
        $data = json_encode(['province_id' => $id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $datas = $response->data;

        return json_encode($datas);
    }

    public function getDistrict($id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List District
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API List District')->first()->rule_value;
        $data = json_encode(['city_id' => $id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $datas = $response->data;

        return json_encode($datas);
    }

    public function getSubDistrict($id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List District
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API List Sub District')->first()->rule_value;
        $data = json_encode(['district_id' => $id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $datas = $response->data;

        return json_encode($datas);
    }
}
