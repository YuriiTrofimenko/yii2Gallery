<?php

namespace app\widgets;

class AbramFileInput extends \kartik\file\FileInput {

    /**
     * @var array initialize the FileInput widget
     */
    public function init()
    {
        parent::init();
        $this->_msgCat = 'fileinput';
        $this->initI18N(__DIR__);
        $this->initLanguage();
        $this->registerAssets();
        if ($this->pluginLoading) {
            Html::addCssClass($this->options, 'file-loading');
        }
        $input = $this->getInput('fileInput');
        $script = 'document.getElementById("' . $this->options['id'] . '").className.replace(/\bfile-loading\b/,"");';
        if ($this->showMessage) {
            $validation = ArrayHelper::getValue($this->pluginOptions, 'showPreview', true) ?
                Yii::t('fileinput', 'file preview and multiple file upload') :
                Yii::t('fileinput', 'multiple file upload');
            $message = '<strong>' . Yii::t('fileinput', 'Note:') . '</strong> ' .
                Yii::t(
                    'fileinput',
                    'Your browser does not support {validation}. Try an alternative or more recent browser to access these features.',
                    ['validation' => $validation]
                );
            $content = Html::tag('div', $message, $this->messageOptions) . "<script>{$script};</script>";
            $input .= "\n" . $this->validateIE($content);
        }
        echo $input;
    }

    /**
     * Validates and returns content based on IE browser version validation
     *
     * @param string $content
     * @param string $validation
     *
     * @return string
     */
    protected function validateIE($content, $validation = 'lt IE 10')
    {
        return "<!--[if {$validation}]><br>{$content}<![endif]-->";
    }

    /**
     * Registers the asset bundle and locale
     */
    public function registerAssetBundle()
    {
        $view = $this->getView();
        if ($this->resizeImages) {
            CanvasBlobAsset::register($view);
            $this->pluginOptions['resizeImage'] = true;
        }
        $theme = ArrayHelper::getValue($this->pluginOptions, 'theme');
        if (!empty($theme) && in_array($theme, self::$_themes)) {
            AbramFileInputThemeAsset::register($view)->addTheme($theme);
        }
        if ($this->sortThumbs) {
            SortableAsset::register($view);
        }
        if ($this->purifyHtml) {
            DomPurifyAsset::register($view);
            $this->pluginOptions['purifyHtml'] = true;
        }
        AbramFileInputAsset::register($view)->addLanguage($this->language, '', 'js/locales');
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $this->registerAssetBundle();
        $this->registerPlugin($this->pluginName);
    }
}