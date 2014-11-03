<?php
namespace Selenium\Helper;

use Symfony\Component\Yaml\Yaml;

class File extends AbstractHelper
{
    /**
     * Loads YAML file and returns parsed data
     *
     * @param string $fullFileName Full file name (including path)
     *
     * @return array
     */
    public function loadYamlFile($fullFileName)
    {
        $data = array();
        if ($fullFileName && file_exists($fullFileName)) {
            $data = Yaml::parse($fullFileName);
        }
        return ($data) ? $data : array();
    }

    /**
     * Loads multiple YAML files and returns merged data
     *
     * @param string $globExpr File names glob pattern
     *
     * @return array
     */
    public function loadYamlFiles($globExpr)
    {
        $data = array();
        $files = glob($globExpr);
        if (!empty($files)) {
            foreach ($files as $file) {
                $fileData = $this->loadYamlFile($file);
                if (!empty($fileData)) {
                    $data = array_replace_recursive($data, $fileData);
                }
            }
        }
        return $data;
    }
}
