<?php

namespace Khbd\LaravelWso2IdentityApiUser\Idps;

use Khbd\LaravelWso2IdentityApiUser\Interfaces\IDPInterface;
use Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp\Wso2IdpUsers;
use Illuminate\Http\Request;

class Wso2idp implements IDPInterface
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    protected $user_id;
    /**
     * @var bool
     */
    protected $is_success;

    /**
     * @var int
     */
    protected $response_code;

    /**
     * @var mixed
     */
    protected $message;


    /**
     * @var mixed
     */
    protected $onlyBody;

    /**
     * @var object
     */
    protected $asObject;

    /**
     * @var json
     */
    protected $asJson;

    /**
     * @var object
     */
    protected $data;

    /**
     * @var object | array
     */
    protected $response;



    /**
     * @param $settings
     *
     * @throws \Exception
     */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
    }

    public function userInfo($userID)
    {
        $AT = new Wso2IdpUsers($this->settings->base_url, $this->settings->username, $this->settings->password, $this->settings->idp_log );
        $this->response = $AT->userInfo($userID);
        return $this;
    }

    public function findUsers($userID)
    {
        $AT = new Wso2IdpUsers($this->settings->base_url, $this->settings->username, $this->settings->password, $this->settings->idp_log );
        $this->response = $AT->findUsers($userID);
        return $this;
    }

    /**
     * @param $recipient
     * @param $message
     * @param null $params
     *
     * @return object
     */
    public function create($userInfo)
    {
        $AT = new Wso2IdpUsers($this->settings->base_url, $this->settings->username, $this->settings->password, $this->settings->idp_log );
        $this->response = $AT->create($userInfo);
        return $this;
    }


    /**
     * Update user
     * @param array $userInformation
     * @return mixed|void
     */
    public function update(array $userInformation)
    {
        $AT = new Wso2IdpUsers($this->settings->base_url, $this->settings->username, $this->settings->password, $this->settings->idp_log );
        $this->response = $AT->update($userInformation);
        return $this;
    }


    public function delete( $userInformation = null)
    {
        $AT = new Wso2IdpUsers($this->settings->base_url, $this->settings->username, $this->settings->password, $this->settings->idp_log );
        $this->response = $AT->delete($userInformation);
        return $this;
    }


    /**
     * set response type
     * @return $this
     */
    public function get()
    {
        if($this->onlyBody){
            return $this->response['data'];
        }
        return $this->response;
    }
    /**
     * set response type
     * @return $this
     */
    public function asObject()
    {
      if($this->onlyBody){
          return (object) $this->response['data'];
      }
        return (object) $this->response;
    }

    /**
     * set response type
     * @return $this
     */
    public function asJson()
    {
       if($this->onlyBody){
           return json_encode ($this->response['data']);
       }
        return (object) $this->response;
    }

    /**
     * set pertial response
     * @return $this
     */
    public function onlyBody()
    {
        $this->onlyBody = true;
        return $this;
    }

    /**
     * initialize the is_success parameter.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
       return $this->response['status'];
    }

    /**
     * assign the message ID as received on the response,auto generate if not available.
     *
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->response['message'];
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->response['code'];
    }

    /**
     * @return mixed|string
     */
    public function getUserID()
    {
        return $this->user_id;
    }

    public function fixNumber($number){
       $validCheckPattern = "/^(?:\+88|01)?(?:\d{11}|\d{13})$/";
       if(preg_match($validCheckPattern, $number)){
           if(preg_match('/^(?:01)\d+$/', $number)){
               $number = '+88' . $number;
           }

           return $number;
       }

       return false;
    }
}
