<?php
namespace Opine;

use PHPUnit_Framework_TestCase;
use Opine\Config\Service as Config;
use Opine\Container\Service as Container;

class LanguageTest extends PHPUnit_Framework_TestCase {
    private $language;
    private $languageModel;
    private $languages;

    public function setup () {
        $this->root = __DIR__ . '/../public';
        $config = new Config($this->root);
        $config->cacheSet();
        $container = Container::instance($this->root, $config, $this->root . '/../container.yml');
        $this->language = $container->language;
        $this->languageModel = $container->languageModel;
        $this->languages = [
            'en' => [
                'code_name' => 'en',
                'name' => 'English',
                'charset' => 'UTF-8',
                '_id' => '1'
            ],
            'ar' => [
                'code_name' => 'ar',
                'name' => 'Arabic',
                'charset' => 'UTF-8',
                '_id' => '2'
            ]
        ];
    }

    public function testContainer () {
        $this->assertTrue(get_class($this->language) == 'Opine\Language\Service');
        $this->assertTrue(get_class($this->languageModel) == 'Opine\Language\Model');
    }

    public function testPathNoMatch () {
        $path = '/blogs';
        $this->assertTrue($path == $this->language->pathEvaluate($path));
    }

    public function testPathMatch () {
        $path = '/en/blogs';
        $this->language->cacheSet($this->languages);
        $newPath = $this->language->pathEvaluate($path);
        $this->assertTrue($newPath == '/blogs');
        $this->assertTrue($this->language->get() == 'en');
        $this->assertTrue($this->language->get() == 'en');
        $details = $this->language->getDetails();
        $this->assertTrue($details['name'] == 'English');
    }

    public function testBuild () {
        $cacheFile = $this->root . '/../cache/languages.json';
        $this->languageModel->build();
        $this->assertTrue(file_exists($cacheFile));
        $this->assertTrue(is_array(json_decode(file_get_contents($cacheFile), true)));
    }
}