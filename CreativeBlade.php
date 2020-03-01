<?php
namespace CreativeBlade;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;

class CreativeBlade
{
    private $blade;
    private $viewPath;
    private $cachePath;
    private $preference_file = '';

    public function __construct($view, $cache)
    {
        $this->viewPath = $view;
        $this->cachePath = $cache;
        $this->preference_file = $this->cachePath . '/blade_preferences.txt';
        $this->blade = new BladeCompiler(new Filesystem(), $this->cachePath);
    }

    public function loadBladeView($view, $data = array())
    {
        extract($data);
        $bladeViewPath = $this->viewPath . '/' . $view . '.blade.php';

        if (trim($this->getBladeFileTimestamp()) !== trim($this->generatePreferenceFileTimestamp($bladeViewPath))) {

            $this->blade->compile($bladeViewPath);
        }
        $compiledViewPath = $this->blade->getCompiledPath($bladeViewPath);
        if (!file_exists($compiledViewPath)) {
            $this->blade->compile($bladeViewPath);
        }
        $preference = $this->generatePreferenceFileTimestamp($bladeViewPath) . ',' . $this->generatePreferenceFileTimestamp($compiledViewPath);
        $this->updateBladePreferenceFile($preference);
        include $compiledViewPath;
    }

    private function updateBladePreferenceFile($content)
    {
        if (!is_file($this->preference_file)) {
            $fp = fopen($this->preference_file, 'w');
            chmod($this->preference_file, 0777);
            fwrite($fp, $content);
            fclose($fp);
        } else {
            file_put_contents($this->preference_file, $content);
        }


    }

    function generatePreferenceFileTimestamp($file)
    {
        return filemtime($file);
    }

    function getBladeFileTimestamp()
    {
        if ($this->getPreferencesFileContent() !== '') {
            $content = $this->getPreferencesFileContent();
            $content = explode(',', $content)[0];
        } else {
            $content = '';
        }

        return $content;
    }

    function getCompiledFileTimestamp()
    {
        if ($this->getPreferencesFileContent() !== '') {
            $content = $this->getPreferencesFileContent();
            $content = explode(',', $content)[1];
        } else {
            $content = '';
        }

        return $content;
    }

    function getPreferencesFileContent()
    {
        if (is_file($this->preference_file)) {
            $fp = fopen($this->preference_file, 'r');
            $content = fread($fp, filesize($this->preference_file));
            fclose($fp);
            return $content;
        }
        return '';
    }
}
