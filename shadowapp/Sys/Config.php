<?php

namespace Shadowapp\Sys;

Class Config
{

    protected $_basePath;
    protected $_configData;
    protected $fileConfig;

    public function __construct($configFile = 'config.ini')
    {
        $this->_basePath = dirname(dirname(dirname(__FILE__)));

        if ($configFile != 'config.ini' && file_exists($configFile)) {

            if (substr($configFile, strrpos($configFile, '.') + 1) == 'ini') {
                $this->_configData = $configFile;
            } else {
                die('Wrong Config File,  extension must be .ini');
            }
        } else {

            $this->_configData = $this->_basePath . '/config.ini';
        }
    }

    protected function getFileConfigInfo()
    {
        $listOfConfigFiles = array_values(array_filter(array_map(function($configFile) {
                            if (!in_array($configFile, ['.', '..'])) {
                                $fileInfo = new \SplFileInfo($configFile);
                                if ($fileInfo->getExtension() != 'php') {
                                    return;
                                }

                                return $fileInfo->getBasename('.php');
                            }
                        }, scandir(CONFIG_DIR))));


        return $this->setConfigData($listOfConfigFiles);
    }

    private function setConfigData(array $listOfConfigFiles)
    {
        if (!count($listOfConfigFiles)) {
            return [];
        }

        $stack = [];

        foreach ($listOfConfigFiles as $configFile) {
            $placeholder = require_once CONFIG_DIR . $configFile . '.php';

            if (!is_array($placeholder)) {
                continue;
            }


            $stack[$configFile] = $placeholder;
        }

        return $stack;
    }

    public function get()
    {
        if (!file_exists($this->_configData)) {
            $message = $this->_configData . ' not found';
            throw new \Shadowapp\Sys\Exceptions\ConfigurationFileNotFoundException($message);
        }

        return (object) parse_ini_file($this->_configData);
    }

    public function getFromFile(string $search)
    {
        return shcol($search, $this->getFileConfigInfo(),[]);
    }

}
