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

require_once('atom.php');
require_once('icollection.php');

abstract class AtomFeed extends Atom implements ICollection
{
  private $_title;
  private $_list;

  function __construct() {
    $this->_title = null;
    $this->_list = null;
  }

  abstract public function createAtomEntry($xml);

  function parseURL($url) {
    $this->_list = array();

    do {
      $this->fetchXML($url);

      $this->setEmptyNamespace('atom');

      foreach ($this->getXPath('//atom:entry') as $entry) {
        $entry_object = $this->createAtomEntry($entry);
        $entry_object->parseXML();
        $this->_list[] = $entry_object;
      }

      $url = $this->getXPathValue('//atom:link[@rel="next"]/@href');

    } while (!empty($url));

    $this->_title = $this->getXPathValue('//atom:title');
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getList() {
    return $this->_list;
  }
}
?>