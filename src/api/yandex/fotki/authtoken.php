<?php
/*
 * Copyright (c) AIG
 * aignospam at gmail.com
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY AUTHOR AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

namespace API\YANDEX\FOTKI;

class AuthToken
{
  private $_token;

  function __construct() {
    $this->_token = null;
  }

  function __construct($login, $password, $encrypt_cmd) {
    $this->get($login, $password, $encrypt_cmd);
  }

  function get($login, $password, $encrypt_cmd) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'http://auth.mobile.yandex.ru/yamrsa/key/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($ch);
    
    curl_close($ch);

    $xml = simplexml_load_string($result);

    if (empty($xml->key) || empty($xml->request_id)) {
      throw new Exception("Unable to find key/request_id");
    }

    $credentials = "<credentials login='$login' password='$password'/>";

    $key = $xml->key;
    $request_id = $xml->request_id;

    $credentials_crypted = trim(`$encrypt_cmd "$key" "$credentials"`);

    $fields = array();
    $fields[] = "request_id=" . urlencode($request_id);
    $fields[] = "credentials=" . urlencode($credentials_crypted);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'http://auth.mobile.yandex.ru/yamrsa/token/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    
    curl_close($ch);

    $xml = simplexml_load_string($result);

    if (empty($xml->token)) { 
      throw new Exception("Unable to find token");
    }

    $this->_token = $xml->token;
  }

  function token() { return $this->_token; }
}
?>