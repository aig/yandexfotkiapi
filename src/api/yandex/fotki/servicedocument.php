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
require_once('albumcollection.php');
require_once('photocollection.php');

class ServiceDocument extends AtomEntry
{
  private $_album_collection_url;
  private $_photo_collection_url;

  private $_album_collection;
  private $_photo_collection;

  function __construct() {
    $this->_album_collection = null;
    $this->_photo_collection = null;
    $this->_album_collection_url = null;
    $this->_photo_collection_url = null;

    parent::__construct();
  }

  function parseXML() {
    $this->setEmptyNamespace('app');

    $this->_album_collection_url = $this->getXPathValue('//app:collection[@id="album-list"]/@href');
    $this->_photo_collection_url = $this->getXPathValue('//app:collection[@id="photo-list"]/@href');

    $this->_album_collection = null;
    $this->_photo_collection = null;
  }

  function getAlbumCollection() {
    if (!is_object($this->_album_collection)) {
      $this->_album_collection = new AlbumCollection();
      $this->_album_collection->parseURL($this->_album_collection_url . 'published/');
    }

    return $this->_album_collection;
  }

  function getPhotoCollection() {
    if (is_object($this->_photo_collection)) {
      $this->_photo_collection = new PhotoCollection();
      $this->_photo_collection->parseURL($this->_photo_collection_url . 'published/');
    }
  }
}
?>