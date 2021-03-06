<?php
/**
 * Opine\Language\Model
 *
 * Copyright (c)2013, 2014 Ryan Mahoney, https://github.com/Opine-Org <ryan@virtuecenter.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Opine\Language;

class Model
{
    private $root;
    private $db;
    private $cacheFile;

    public function __construct($root, $db)
    {
        $this->root = $root;
        $this->db = $db;
        $this->cacheFile = $root.'/../var/cache/languages.json';
    }

    public function build()
    {
        $cache = $this->read();
        file_put_contents($this->cacheFile, json_encode($cache, JSON_PRETTY_PRINT));

        return json_encode($cache);
    }

    private function read()
    {
        $languages = iterator_to_array($this->db->collection('languages')->find());
        if (!is_array($languages) || count($languages) == 0) {
            return [];
        }
        $cache = [];
        foreach ($languages as $language) {
            $cache[$language['code_name']] = $language;
        }

        return $cache;
    }

    public function readDiskCache()
    {
        if (!file_exists($this->cacheFile)) {
            return [];
        }

        return json_decode(file_get_contents($this->cacheFile), true);
    }
}
