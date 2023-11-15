<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ridivi extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_company', 'username', 'password', 'iban', 'active'

    ];
    public static function getRidiviKey($username, $password)
    {

        $data = array(
            'option' => 'getKey',
            'userName' => $username,
            'password' => $password,

        );
        $jsonData = json_encode($data);

        $header = array(
            'Content-Type: application/json; charset=utf-8'
        );
        if (Team::find(1)->proof == 1) {
            $curl = curl_init("https://api.dev-ridivi.com/v4/");
        } else {
            $curl = curl_init("https://api.ridivipay.com");
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);


        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($respuesta, true);
    }
    public static function getRidiviUser($key)
    {

        $data = array(
            'key' => $key,
            'option' => 'getUser',
            'idNumber' => Auth::user()->currentTeam->id_card,

        );
        $jsonData = json_encode($data);

        $header = array(
            'Content-Type: application/json; charset=utf-8'
        );
        if (Auth::user()->currentTeam->proof == 1) {
            $curl = curl_init("https://api.dev-ridivi.com/v4/");
        } else {
            $curl = curl_init("https://api.ridivipay.com");
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);


        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($respuesta, true);
    }
    public function payNewCompany()
    {
        DB::beginTransaction();
        try {
            $result = Team::find(1);
            $ridivi = $this->getRidiviKey($result->ridivi_username, $result->ridivi_pass);
            if ($ridivi["error"]) {
            }
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Datos Agregados con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
    }

    //links de pagos 
    public static function getToken($key, $secret)
    {
        $data = array(
            'key' => $key,
            'secret' => $secret,

        );
        $jsonData = json_encode($data);

        $header = array(
            'Content-Type: application/json; charset=utf-8'
        );
        if (Team::find(1)->proof == '1') {
            $curl = curl_init("https://apiv5.dev-ridivi.com/v5/auth/token");
        } else {
            $curl = curl_init("https://api.admin.ridivipay.com/v5/auth/token");
        }


        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);


        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($respuesta, true);
    }
    public static function getLinkPay($token, $name, $lastname, $idcard, $type, $phone, $email, $amount, $detail)
    {
        $data = array(
            "customerIdNumber" => null,
            "customerTypeIdNumber" => null,
            "userClientName" => $name,
            "userClientLastName" => $lastname,
            "userClientIdNumber" => $idcard,
            "userClientTypeNumber" => $type,
            "userClientPhone" => $phone,
            "userClientEmail" => $email,
            "amountPay" => $amount,
            "detailPay" => $detail,
            "dueDatePay" => "",
            "currencyPay" => "CRC",
            "expirationTimePay" => 1440,
            "sendMailNotification" => false,
            "invoiceNumber" => '90000'

        );
        $jsonData = json_encode($data);
        $header = array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $token
        );
        if (Team::find(1)->proof == '1') {
            $curl = curl_init("https://apiv5.dev-ridivi.com/v5/linkpay/getLinkPay");
        } else {
            $curl = curl_init("https://api.admin.ridivipay.com/v5/linkpay/getLinkPay");
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        return json_decode($respuesta, true);
    }
    public static function getDataLinkPay($token,$payKey)
    {
        $data = array(
            "payKey" => $payKey,
        );
        $jsonData = json_encode($data);
        $header = array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $token
        );
        if (Team::find(1)->proof == '1') {
            $curl = curl_init("https://apiv5.dev-ridivi.com/v5/linkpay/getDataLinkPay");
        } else {
            $curl = curl_init("https://api.admin.ridivipay.com/v5/linkpay/getDataLinkPay");
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        return json_decode($respuesta, true);
    }
    public static function proccessPay($token, $payKey, $acount, $idcard)
    {
        $data = array(
            'payKey' => $payKey,
            'externalAccount' => $acount,
            'externalId' => $idcard,

        );
        $jsonData = json_encode($data);

        $header = array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $token
        );
        if (Team::find(1)->proof == '1') {
            $curl = curl_init("https://apiv5.dev-ridivi.com/v5/linkpay/processLinkPay");
        } else {
            $curl = curl_init("https://api.admin.ridivipay.com/v5/linkpay/processLinkPay");
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);


        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        return json_decode($respuesta, true);
    }
}
