<?php
/*******************************************************************************
* Copyright (c) 2013-2015 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Zak James (zak.james@gmail.com) - Initial implementation
*    Denis Roy (Eclipse Foundation)
*******************************************************************************/


# This is based on work done in the GitHub webhook
# https://github.com/eclipse/eclipse-webhook/blob/master/lib/restclient.php


class RestClient
{
  
  function __construct() {
  }

  private $userAgent = "Eclipse.org-REST-client";
  /**
   * Send a POST requst using cURL
   * @param string $url to request
   * @param array $post values to send
   * @param array $options for cURL
   * @return string 
   */
  protected function curl_post($url, $post = NULL, array $options = array()) {
      $defaults = array(
          CURLOPT_POST => 1,
          CURLOPT_HEADER => 0,
          CURLOPT_HTTPHEADER => array(
            "Authorization: token A",
            "User-Agent: " . $this->userAgent
          ),
          CURLOPT_URL => $url,
          CURLOPT_FRESH_CONNECT => 1,
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_FORBID_REUSE => 1,
          CURLOPT_TIMEOUT => 4,
          CURLOPT_POSTFIELDS => $post
      );

      $ch = curl_init();
      curl_setopt_array($ch, ($options + $defaults));
      if( ! $result = curl_exec($ch)) {
      	#TODO: handle error
      }
      curl_close($ch);
      return $result;
  }

  /**
   * Send a GET requst using cURL
   * @param string $url to request
   * @param array $get values to send
   * @param array $options for cURL
   * @return string
   */
  protected function curl_get($url, array $get = NULL, array $options = array()) {
      $defaults = array(
          CURLOPT_URL => $url,//. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
          //CURLOPT_HEADER => 1,
          CURLOPT_HTTPHEADER => array(
            "Authorization: token ".GITHUB_TOKEN,
            "User-Agent: " . $this->userAgent,
            "Content-Length: 0"
          ),
          CURLOPT_HEADER => TRUE,
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_TIMEOUT => 4
      );

      $ch = curl_init();
      curl_setopt_array($ch, ($options + $defaults));
      $result = curl_exec($ch);
      $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if(! $result) {
          if ($code < 400) {
            return "{\"http_code\": $code}";
          }
      }
      //getting headers, so we need offset to content
      $headerLength = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
      $header = substr($result, 0, $headerLength);
      $body = substr($result, $headerLength);
      if(strlen($body) <= 0) {
      	$body = "{\"http_code\": $code}";
      }
      curl_close($ch);

      return $body;
  }
  
  public function buildURL(array $components = NULL) {
    $path = implode('/', $components);
    return $this->endPoint .'/'. $path;
  }

  /* http convenience functions
   */
  public function get($url) {
    $json = ($this->curl_get($url));
    return json_decode(urldecode($json));
  }

  public function put($url) {
    $extra_headers = array(
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POSTFIELDS => ""
    );
    $json = ($this->curl_get($url, NULL, $extra_headers));
    return json_decode(stripslashes($json));
  }

  public function delete($url) {
    $extra_headers = array(CURLOPT_CUSTOMREQUEST => 'DELETE');
    $json = ($this->curl_get($url, NULL, $extra_headers));
    return json_decode(stripslashes($json));
  }

  /**
   *
   * @param string $url
   * @param array $data key-value array of POST parameters
   * @return JSON-decoded payload
   */
  public function post($url, $data) {
    $json = ($this->curl_post($url, $data));
    return json_decode(stripslashes($json));
  }
  public function patch($url, $data) {
    $extra_headers = array(CURLOPT_CUSTOMREQUEST => 'PATCH');
    $json = ($this->curl_post($url, json_encode($data), $extra_headers));
    return json_decode(stripslashes($json));
  }
}