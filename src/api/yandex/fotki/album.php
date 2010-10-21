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

require_once('atomentry.php');

class Album extends AtomEntry
{
  private $_self_url;
  private $_edit_url;
  private $_photos_url;

  private $_photo_collection;

  function __construct($xml = null) {
    parent::__construct($xml);

    $this->_self_url = null;
    $this->_edit_url = null;
    $this->_photos_url = null;
    $this->_photo_collection = null;
  }

  function parseXML() {
    parent::parseXML();

    $this->_self_url = $this->getXPathValue('atom:link[@rel="self"]/@href');
    $this->_edit_url = $this->getXPathValue('atom:link[@rel="edit"]/@href');
    $this->_photos_url = $this->getXPathValue('atom:link[@rel="photos"]/@href');
  }

  function getPhotoCollection() {
    if (!is_object($this->_photo_collection)) {
      $this->_photo_collection = new PhotoCollection();
      $this->_photo_collection->parseURL($this->_photos_url);
    }

    return $this->_photo_collection;
  }
}
?>