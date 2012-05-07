<?php
/**
 * Copyright 2011 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * SilverCart is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SilverCart is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SilverCart.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package SilvercartMarketingCrossSelling
 * @subpackage Widgets
 */

/**
 * Provides a widget with cross selling products.
 * 
 * @package SilvercartMarketingCrossSelling
 * @subpackage Widgets
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 29.08.2011
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2011 pixeltricks GmbH
 */
class SilvercartMarketingCrossSellingWidget extends SilvercartWidget {
    
    /**
     * Attributes.
     *
     * @var array
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    public static $db = array(
        'isContentView'             => 'Boolean(0)',
        'fillMethod'                => "Enum('randomGenerator,orderStatistics,otherProductGroup','randomGenerator')",
        'numberOfProducts'          => 'Int',
        'useListView'               => 'Boolean(0)',
        'WidgetTitle'               => 'VarChar(255)',
        'showOnProductGroupPages'   => 'Boolean(0)',
        'useCustomTemplate'         => 'Boolean(0)',
        'customTemplateName'        => 'VarChar(255)'
    );
    
    /**
     * 1:1 or 1:n relationships.
     *
     * @var array
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 30.08.2011
     */
    public static $has_one = array(
        'SilvercartProductGroupPage' => 'SilvercartProductGroupPage'
    );
    
    /**
     * 1:n relationships.
     *
     * @var array
     */
    public static $has_many = array(
        'SilvercartMarketingCrossSellingWidgetLanguages' => 'SilvercartMarketingCrossSellingWidgetLanguage'
    );
    
    /**
     * Casted properties
     *
     * @var array
     */
    public static $casting = array(
        'WidgetTitle' => 'VarChar(255)'
    );
    
    /**
     * Getter for the widgets title depending on the current locale
     *
     * @return string 
     */
    public function getWidgetTitle() {
        return $this->getLanguageFieldValue('WidgetTitle');
    }
    
    /**
     * Returns the title of this widget.
     * 
     * @return string
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    public function Title() {
        return _t('SilvercartMarketingCrossSellingWidget.TITLE');
    }
    
    /**
     * Returns the title of this widget for display in the WidgetArea GUI.
     * 
     * @return string
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    public function CMSTitle() {
        return _t('SilvercartMarketingCrossSellingWidget.CMSTITLE');
    }
    
    /**
     * Returns the description of what this template does for display in the
     * WidgetArea GUI.
     * 
     * @return string
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    public function Description() {
        return _t('SilvercartMarketingCrossSellingWidget.DESCRIPTION');
    }
    
    /**
     * Field labels for display in tables.
     *
     * @param boolean $includerelations A boolean value to indicate if the labels returned include relation fields
     *
     * @return array
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>, Sebastian Diel <sdiel@pixeltricks.de>
     * @since 07.05.2012
     */
    public function fieldLabels($includerelations = true) {
        $fieldLabels = array_merge(
            parent::fieldLabels($includerelations),
            array(
                'fillMethod'                                        => _t('SilvercartMarketingCrossSellingWidget.FILL_METHOD'),
                'WidgetTitle'                                       => _t('SilvercartMarketingCrossSellingWidget.WIDGET_TITLE'),
                'showOnProductGroupPages'                           => _t('SilvercartMarketingCrossSellingWidget.SHOW_ON_PRODUCT_GROUP_PAGES'),
                'useCustomTemplate'                                 => _t('SilvercartMarketingCrossSellingWidget.USE_CUSTOM_TEMPLATES'),
                'customTemplateName'                                => _t('SilvercartMarketingCrossSellingWidget.CUSTOM_TEMPLATE_NAME'),
                'isContentView'                                     => _t('SilvercartMarketingCrossSellingWidget.IS_CONTENT_VIEW'),
                'useListView'                                       => _t('SilvercartMarketingCrossSellingWidget.USE_LISTVIEW'),
                'numberOfProducts'                                  => _t('SilvercartMarketingCrossSellingWidget.NUMBER_OF_PRODUCTS'),
                'SilvercartProductGroupPage'                        => _t('SilvercartProductGroupPage.SINGULARNAME'),
                'SilvercartMarketingCrossSellingWidgetLanguages'    => _t('SilvercartMarketingCrossSellingWidgetLanguage.PLURALNAME'),
            )
        );

        $this->extend('updateFieldLabels', $fieldLabels);
        return $fieldLabels;
    }
    
    /**
     * Define CMS Fields for this widget.
     *
     * @return FieldSet
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>, Sebastian Diel <sdiel@pixeltricks.de>
     * @since 07.05.2012
     */
    public function getCMSFields() {
        $fields = new FieldSet();
        
        
        $rootTabSet         = new TabSet('RootTabSet');
        $mainTab            = new Tab('Root',               _t('Silvercart.CONTENT'));
        $translationsTab    = new Tab('TranslationsTab',    _t('SilvercartConfig.TRANSLATIONS'));
        
        $fillMethods = singleton('SilvercartMarketingCrossSellingWidget')->dbObject('fillMethod')->enumValues();
        
        foreach ($fillMethods as $fillMethodKey => $fillMethodName) {
            $fillMethods[$fillMethodKey] = _t('SilvercartMarketingCrossSellingWidget.'.strtoupper($fillMethodName));
        }
        
        $showOnProductGroupPagesField   = new CheckboxField('showOnProductGroupPages',  $this->fieldLabel('showOnProductGroupPages'));
        $useCustomTemplateField         = new CheckboxField('useCustomTemplate',        $this->fieldLabel('useCustomTemplate'));
        $customTemplateNameField        = new TextField('customTemplateName',           $this->fieldLabel('customTemplateName'));
        $isContentViewField             = new CheckboxField('isContentView',            $this->fieldLabel('isContentView'));
        $useListViewField               = new CheckboxField('useListView',              $this->fieldLabel('useListView'));
        $numberOfProductsField          = new TextField('numberOfProducts',             $this->fieldLabel('numberOfProducts'));
        $fillMethodField                = new OptionsetField(
                'fillMethod',
                $this->fieldLabel('fillMethod'),
                $fillMethods
        );
        $silvercartProductGroupPage     = new GroupedDropdownField(
                'SilvercartProductGroupPageID',
                $this->fieldLabel('SilvercartProductGroupPage'),
                SilvercartProductGroupHolder_Controller::getRecursiveProductGroupsForGroupedDropdownAsArray()
        );
        $translationsTableField         = new ComplexTableField($this, 'SilvercartMarketingCrossSellingWidgetLanguages', 'SilvercartMarketingCrossSellingWidgetLanguage');
        
        $languageFields = SilvercartLanguageHelper::prepareCMSFields($this->getLanguage());
        foreach ($languageFields as $languageField) {
            $mainTab->push($languageField);
        }
        
        $fields->push($rootTabSet);
        $rootTabSet->push($mainTab);
        $rootTabSet->push($translationsTab);
        
        $mainTab->push($showOnProductGroupPagesField);
        $mainTab->push($useCustomTemplateField);
        $mainTab->push($customTemplateNameField);
        $mainTab->push($isContentViewField);
        $mainTab->push($useListViewField);
        $mainTab->push($numberOfProductsField);
        $mainTab->push($fillMethodField);
        $mainTab->push($silvercartProductGroupPage);
        
        $translationsTab->push($translationsTableField);
        
        $this->extend('updateCMSFields', $fields);
        
        return $fields;
    }
    
    /**
     * We set checkbox field values here to false if they are not in the post
     * data array.
     *
     * @param array $data The post data array
     *
     * @return void
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>, Sebastian Diel <sdiel@pixeltricks.de>
     * @since 07.05.2012
     */
    public function populateFromPostData($data) {
        if (!array_key_exists('isContentView', $data)) {
            $this->isContentView = 0;
        }
        if (!array_key_exists('useListView', $data)) {
            $this->useListView = 0;
        }
        if (!array_key_exists('showOnProductGroupPages', $data)) {
            $this->showOnProductGroupPages = 0;
        }
        if (!array_key_exists('useCustomTemplate', $data)) {
            $this->useCustomTemplate = 0;
        }
        
        parent::populateFromPostData($data);
    }
}

/**
 * Provides a widget with cross selling products.
 * 
 * @package SilvercartMarketingCrossSelling
 * @subpackage Widgets
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 29.08.2011
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2011 pixeltricks GmbH
 */
class SilvercartMarketingCrossSellingWidget_Controller extends SilvercartWidget_Controller {
    
    /**
     * Incdicates wether a custom template should be used for rendering.
     *
     * @return boolean
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 30.08.2011
     */
    public function isCustomView() {
        $isCustomView = false;
        
        if ($this->useCustomTemplate) {
            $isCustomView = true;
        }
        
        return $isCustomView;
    }
    
    /**
     * Returns an HTML string with the contents rendered with the custom
     * template.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 30.08.2011
     */
    public function CustomView() {
        $elements = $this->Elements();
        $output   = '';
        
        if ($elements) {
            $output .= $this->customise($elements)->renderWith($this->customTemplateName);
        }
        
        return $output;
    }
    
    /**
     * Returns a DataObjectSet of products.
     *
     * @return DataObjectSet
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>, Sebastian Diel <sdiel@pixeltricks.de>
     * @since 07.05.2012
     */
    public function Elements() {
        $controller = Controller::curr();
        
        if (!$controller instanceof SilvercartProductGroupPage_Controller) {
            return false;
        }
        
        if (!$controller->isProductDetailView() &&
            !$this->showOnProductGroupPages) {
            
            return false;
        }
        
        switch ($this->fillMethod) {
            case 'randomGenerator':
                return $this->selectElementyByRandomGenerator($controller);
                break;
            case 'orderStatistics':
                return $this->selectElementyByOrderStatistics();
                break;
            case 'otherProductGroup':
                return $this->selectElementFromOtherProductGroup();
                break;
        }
    }
    
    /**
     * Returns a random set of products from the same product group we're
     * currently in.
     *
     * @param Controller $controller The current controller
     * 
     * @return mixed DataObjectSet|boolean false
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    protected function selectElementyByRandomGenerator($controller) {
        $resultSet = false;
        $resultSet = $controller->getRandomProducts($this->numberOfProducts);

        return $resultSet;
    }
    
    /**
     * Returns a random set of products that have been bought together with
     * the displayed product.
     * 
     * @return mixed DataObjectSet|boolean false
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    protected function selectElementyByOrderStatistics() {
        
    }
    
    /**
     * Returns a set of products from another product group.
     *
     * @return mixed DataObjectSet|false
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>, Sebastian Diel <sdiel@pixeltricks.de>
     * @since 07.05.2012
     */
    protected function selectElementFromOtherProductGroup() {
        $resultSet = false;
        
        if ($this->SilvercartProductGroupPageID > 0) {
            $productGroupPage = ModelAsController::controller_for($this->SilvercartProductGroupPage());
            $resultSet        = $productGroupPage->getRandomProducts($this->numberOfProducts);
        }

        return $resultSet;
    }
}