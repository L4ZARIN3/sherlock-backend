<?php

namespace App\Http\Controllers\dbs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BigDataCorpController extends Controller
{
    public function req($chave, $dataset, $type)
    {
        $JSON = '{
            "Datasets": "'.$dataset.'",
            "q": "' . $chave . '",
            "AccessToken": "'.env('BIG_DATA_CORP_KEY').'"
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bigboost.bigdatacorp.com.br/'.$type);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXY, ENV('PROXY'));
        curl_setopt($ch, CURLOPT_PROXYPORT, ENV('PROXY_PORT'));
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, ENV('PROXY_CRED'));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array(
            "Content-Type: application/json",
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON);
        return json_decode(curl_exec($ch), true);
    }

    public function cpf(Request $request)
    {

        $dataset = 'basic_data,phones_extended,emails_extended,vehicles,addresses_extended,occupation_data';
        $chave = 'doc{' . $request->cpf . '}';
        $type = 'peoplev2';
        $retorno = $this->req($chave, $dataset, $type);
        
        if($retorno['Status']['basic_data'][0]['Code'] == '-114'){
            if($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca nao retornou nenhum resultado.'], 422);
            }
            exit();
        }

        if ($request->type == 'json') {
            return $retorno;
        }else{
            return response()->json(['status' => 'error', 'msg' => 'Informe o type do response.'], 422);
        }
    }

    public function nome(Request $request)
    {

        $dataset = 'basic_data,phones_extended,emails_extended,vehicles,addresses_extended,occupation_data';
        $chave = 'name{' . $request->nome . '}';
        if (!empty($request->nascimento)) {
            $chave .=  ',birthdate{' . $request->nascimento . '},dateformat{dd/MM/yyyy}';
        }
        if(!empty($request->mae)){
            $chave .=  ',mothername{'.$request->mae.'}';
        }

        $type = 'peoplev2';
        $retorno = $this->req($chave, $dataset, $type);

        if($retorno['Status']['doc_finder'][0]['Code'] == '-130'){
            if ($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca retornou muitos resultados, adicione mais parametros a sua pesquisa.'], 422);
            }
            exit();
        }

        if(!array_key_exists('0', $retorno['Result']) ){
            if($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca nao retornou nenhum resultado.'], 422);
            }
            exit();
        }

        if($request->type == 'json'){
            return $retorno;
        }else{
            return response()->json(['status' => 'error', 'msg' => 'Informe o type do response.'], 422);
        }

    }

    public function telefone(Request $request)
    {
        $dataset = 'basic_data,phones_extended,emails_extended,vehicles,addresses_extended,occupation_data';
        $chave = 'phone{'.$request->telefone.'}';
        $type = 'peoplev2';

        $retorno = $this->req($chave, $dataset, $type);

        if(!array_key_exists('0', $retorno['Result'])){
            if($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca nao retornou nenhum resultado.'], 422);
            }
            exit();
        }

        if($request->type == 'json') {
            return $retorno;
        }else{
            return response()->json(['status' => 'error', 'msg' => 'Informe o type do response.'], 422);
        }
    }

    public function email(Request $request)
    {
        $dataset = 'basic_data,phones_extended,emails_extended,vehicles,addresses_extended,occupation_data';
        $chave = 'email{'.$request->email.'}';
        $type = 'peoplev2';
        $retorno = $this->req($chave, $dataset, $type);

        if(!array_key_exists('0', $retorno['Result']) ){
            if($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca nao retornou nenhum resultado.'], 422);
            }
            exit();
        }


        if($request->type == 'json') {
            return $retorno;
        }else{
            return response()->json(['status' => 'error', 'msg' => 'Informe o type do response.'], 422);
        }
    }


    public function placa(Request $request)
    {
        $dataset = 'license_plates';
        $chave = 'licenseplate{'.$request->placa.'}';
        $type = 'vehicles';
        $retorno = $this->req($chave, $dataset, $type);

        if(!array_key_exists('0', $retorno['Result'][0]['LicensePlateData']['VehicleInfo'])){
            if($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca nao retornou nenhum resultado.'], 422);
            }

            exit();
        }


        if($retorno['Status']['license_plates'][0]['Code'] == '-145'){
            if($request->type == 'json') {
                return response()->json(['status' => 'error', 'msg' => 'Busca nao retornou nenhum resultado.'], 422);
            }

            exit();
        }


        if($request->type == 'json') {
            return $retorno;
        }else{
            return response()->json(['status' => 'error', 'msg' => 'Informe o type do response.'], 422);
        }
    }
}
