<?php

class DigitaloceanApi
{

    const API_URL = 'https://api.digitalocean.com/v2/';

    private $apiKey = "";

    public function __construct($api_key)
    {
        $this->apiKey = $api_key;
    }

    public function getPostResults($action, array $post_data = null)
    {
        return json_decode($this->makePostConnection(self::API_URL . $action, $post_data));
    }

    public function getPutResults($action, array $put_data = null)
    {
        return json_decode($this->makePutConnection(self::API_URL . $action, $put_data));
    }

    public function getGetResults($action)
    {
        return json_decode($this->makeGetConnection(self::API_URL . $action));
    }

    public function getlongGetResults($action)
    {
        $pageone = json_decode($this->makeGetConnection(self::API_URL . $action . "?page=1"));
        $get_all_results = null;

        if (isset($pageone->meta)) {
            $total_results = $pageone->meta->total;
            $get_all_results = json_decode(
                $this->makeGetConnection(self::API_URL . $action . "?per_page={$total_results}")
            );
        }
        return $get_all_results;
    }

    public function getDeleteResults($action)
    {
        return json_decode($this->makeDeleteConnection(self::API_URL . $action));
    }

    public function makeTestConnection()
    {
        $getResult = json_decode($this->makeGetConnection(self::API_URL . "sizes"));
        if (isset($getResult->sizes) && !empty($getResult->sizes) && count($getResult->sizes) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function makeGetConnection($action)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ));
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function makePostConnection($action, array $post_data = null)
    {
        $result_p = json_encode($post_data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ));
        curl_setopt($ch, CURLOPT_POST, count($result_p));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $result_p);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function makeDeleteConnection($action)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ));
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function makePutConnection($action, array $put_data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ));
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
