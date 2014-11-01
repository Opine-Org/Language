<?php
/**
 * Opine\Language
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
use Exception;

class Service {
    private $languages = [];
    private $language = NULL;
    private $model;

    public function __construct ($model) {
        $this->model = $model;
    }

    public function pathEvaluate ($path) {
        if (!is_string($path)) {
            throw new Exception('Invalid path format');
        }
        $pathTest = trim($path, '/');
        if (substr_count($pathTest, '/') == 0) {
            return $path;
        }
        $parts = explode('/', $pathTest);
        $language = array_shift($parts);
        if (!array_key_exists($language, $this->languages)) {
            return $path;
        }
        $this->language = $language;
        return '/' . implode('/', $parts);
    }

    public function set ($language) {
        $this->language = $language;
    }

    public function get () {
        return $this->language;
    }

    public function getDetails () {
        if ($this->language === NULL) {
            return false;
        }
        return $this->languages[$this->language];
    }

    public function cacheSet ($languages) {
        if (empty($languages)) {
            $this->languages = $this->model->readDiskCache();
            return;
        }
        $this->languages = $languages;
    }

    public function cacheClear () {
        $this->languages = [];
    }

    public function languages () {
        return $this->languages;
    }
}