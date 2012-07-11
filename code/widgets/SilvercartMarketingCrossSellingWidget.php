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
        'fillMethod'                => "Enum('randomGenerator,orderStatistics,otherProductGroup,relatedProducts','randomGenerator')",
        'numberOfProducts'          => 'Int',
        'GroupView'                 => 'VarChar(255)',
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
     * n:m relationships.
     *
     * @var array
     */
    public static $many_many = array(
        'SilvercartProducts' => 'SilvercartProduct'
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
            SilvercartWidgetTools::fieldLabelsForProductSliderWidget($this),
            array(
                'fillMethod'                                        => _t('SilvercartMarketingCrossSellingWidget.FILL_METHOD'),
                'WidgetTitle'                                       => _t('SilvercartMarketingCrossSellingWidget.WIDGET_TITLE'),
                'showOnProductGroupPages'                           => _t('SilvercartMarketingCrossSellingWidget.SHOW_ON_PRODUCT_GROUP_PAGES'),
                'useCustomTemplate'                                 => _t('SilvercartMarketingCrossSellingWidget.USE_CUSTOM_TEMPLATES'),
                'customTemplateName'                                => _t('SilvercartMarketingCrossSellingWidget.CUSTOM_TEMPLATE_NAME'),
                'numberOfProducts'                                  => _t('SilvercartMarketingCrossSellingWidget.NUMBER_OF_PRODUCTS'),
                'SilvercartProductGroupPage'                        => _t('SilvercartProductGroupPage.SINGULARNAME'),
                'SilvercartMarketingCrossSellingWidgetLanguages'    => _t('SilvercartMarketingCrossSellingWidgetLanguage.PLURALNAME'),
                'SilvercartProducts'                                => _t('SilvercartProduct.PLURALNAME'),
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
     * @since 20.06.2012
     */
    public function getCMSFields() {
        $productGroupHolder = SilvercartTools::PageByIdentifierCode('SilvercartProductGroupHolder');
        $fillMethods        = singleton('SilvercartMarketingCrossSellingWidget')->dbObject('fillMethod')->enumValues();
        foreach ($fillMethods as $fillMethodKey => $fillMethodName) {
            $fillMethods[$fillMethodKey] = _t('SilvercartMarketingCrossSellingWidget.'.strtoupper($fillMethodName));
        }
        $fields             = new FieldSet();
        $rootTabSet         = new TabSet('RootTabSet');
        $mainTab            = new Tab('Root',               _t('Silvercart.CONTENT'));
        $translationsTab    = new Tab('TranslationsTab',    _t('SilvercartConfig.TRANSLATIONS'));
        
        $showOnProductGroupPagesField   = new CheckboxField('showOnProductGroupPages',  $this->fieldLabel('showOnProductGroupPages'));
        $useCustomTemplateField         = new CheckboxField('useCustomTemplate',        $this->fieldLabel('useCustomTemplate'));
        $customTemplateNameField        = new TextField('customTemplateName',           $this->fieldLabel('customTemplateName'));
        $isContentViewField             = new CheckboxField('isContentView',            $this->fieldLabel('isContentView'));
        $numberOfProductsField          = new TextField('numberOfProducts',             $this->fieldLabel('numberOfProducts'));
        $groupViewField                 = SilvercartGroupViewHandler::getGroupViewDropdownField('GroupView', $this->fieldLabel('GroupView'), $this->GroupView);
        $fillMethodField                = new OptionsetField(
                'fillMethod',
                $this->fieldLabel('fillMethod'),
                $fillMethods
        );
        $silvercartProductGroupDropdown = new TreeDropdownField(
                'SilvercartProductGroupPageID',
                $this->fieldLabel('SilvercartProductGroupPage'),
                'SiteTree'
        );
        $silvercartProductGroupDropdown->setTreeBaseID($productGroupHolder->ID);
        $translationsTableField         = new ComplexTableField($this, 'SilvercartMarketingCrossSellingWidgetLanguages', 'SilvercartMarketingCrossSellingWidgetLanguage');
        
        $languageFields = SilvercartLanguageHelper::prepareCMSFields($this->getLanguage(true));
        foreach ($languageFields as $languageField) {
            $mainTab->push($languageField);
        }
        $silvercartProducts             = new SilvercartManyManyTextAutoCompleteField(
                $this,
                'SilvercartProducts',
                $this->fieldLabel('SilvercartProducts'),
                'SilvercartProduct.ProductNumberShop'
        );
        
        $fields->push($rootTabSet);
        $rootTabSet->push($mainTab);
        $rootTabSet->push($translationsTab);
        
        $mainTab->push($showOnProductGroupPagesField);
        $mainTab->push($useCustomTemplateField);
        $mainTab->push($customTemplateNameField);
        $mainTab->push($isContentViewField);
        $mainTab->push($groupViewField);
        $mainTab->push($numberOfProductsField);
        $mainTab->push($fillMethodField);
        $mainTab->push($silvercartProductGroupDropdown);
        $mainTab->push($silvercartProducts);
        
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
        if (!array_key_exists('GroupView', $data)) {
            $this->GroupView = SilvercartGroupViewHandler::getDefaultGroupView();
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
     * Set of active related products
     *
     * @var DataObjectSet
     */
    protected $relatedProducts = null;
    
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
     * @since 28.06.2012
     */
    public function Elements() {
        $controller = Controller::curr();
        $elements   = new DataObjectSet();
        
        if (!$this->showOnProductGroupPages &&
            (!$controller->hasMethod('isProductDetailView') ||
             !$controller->isProductDetailView())) {
            $elements = false;
        } else {
            switch ($this->fillMethod) {
                case 'randomGenerator':
                    $elements = $this->selectElementyByRandomGenerator($controller);
                    break;
                case 'orderStatistics':
                    $elements = $this->selectElementyByOrderStatistics();
                    break;
                case 'otherProductGroup':
                    if ($controller->ID === $this->SilvercartProductGroupPage()->ID) {
                        $elements = false;
                    } else {
                        $elements = $this->selectElementFromOtherProductGroup();
                    }
                    break;
                case 'relatedProducts':
                    $elements = $this->selectElementFromRelatedProducts();
                    break;
            }
        }
        return $elements;
    }
    
    /**
     * Returns the content for non slider widgets
     *
     * @return string
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.05.2012
     */
    public function ElementsContent() {
        return $this->customise(array(
            'Elements' => $this->Elements(),
        ))->renderWith(SilvercartWidgetTools::getGroupViewTemplateName($this));
    }
    
    /**
     * overloads the parents Content() method;
     * fill only if there are elements
     *
     * @return mixed $content 
     * 
     * @author Roland Lehmann <rlehmann@pixeltricks.de>
     * @since 09.07.2012
     */
    public function Content() {
        $content = false;
        if ($this->ElementsContent()) {
            $content = parent::Content();
        }
        return $content;
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

    /**
     * Returns the related products
     *
     * @return ComponentSet
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 28.06.2012
     */
    protected function selectElementFromRelatedProducts() {
        if (is_null($this->relatedProducts)) {
            $this->relatedProducts = new DataObjectSet();
            foreach ($this->SilvercartProducts() as $product) {
                if ($product->isActive) {
                    $this->relatedProducts->push($product);
                }
            }
        }
        return $this->relatedProducts;
    }
    
    /**
     * Creates the cache key for this widget.
     *
     * @return string
     *
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 03.07.2012
     */
    public function WidgetCacheKey() {
        $key = SilvercartWidgetTools::ProductWidgetCacheKey($this);
        return $key;
    }
}