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
        'fillMethod'                => "Enum('randomGenerator,orderStatistics','randomGenerator')",
        'numberOfProducts'          => 'Int',
        'useListView'               => 'Boolean(0)',
        'WidgetTitle'               => 'VarChar(255)',
        'showOnProductGroupPages'   => 'Boolean(0)'
    );
    
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
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @copyright 2011 pixeltricks GmbH
     * @since 29.08.2011
     */
    public function fieldLabels($includerelations = true) {
        $fieldLabels = array_merge(
            parent::fieldLabels($includerelations),
            array(
                'fillMethod'    => _t('SilvercartMarketingCrossSellingWidget.FILL_METHOD'),
                'WidgetTitle'   => _t('SilvercartMarketingCrossSellingWidget.WIDGET_TITLE')
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
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        
        $fillMethods = singleton('SilvercartMarketingCrossSellingWidget')->dbObject('fillMethod')->enumValues();
        
        foreach ($fillMethods as $fillMethodKey => $fillMethodName) {
            $fillMethods[$fillMethodKey] = _t('SilvercartMarketingCrossSellingWidget.'.strtoupper($fillMethodName));
        }
        
        $fields->push(
            new TextField('WidgetTitle', _t('SilvercartMarketingCrossSellingWidget.WIDGET_TITLE'))
        );
        
        $fields->push(
            new CheckboxField('showOnProductGroupPages', _t('SilvercartMarketingCrossSellingWidget.SHOW_ON_PRODUCT_GROUP_PAGES'))
        );
        $fields->push(
            new CheckboxField('isContentView', _t('SilvercartMarketingCrossSellingWidget.IS_CONTENT_VIEW'))
        );
        $fields->push(
            new CheckboxField('useListView', _t('SilvercartMarketingCrossSellingWidget.USE_LISTVIEW'))
        );
        $fields->push(
            new TextField('numberOfProducts', _t('SilvercartMarketingCrossSellingWidget.NUMBER_OF_PRODUCTS'))
        );
        $fields->push(
            new OptionsetField(
                'fillMethod',
                _t('SilvercartMarketingCrossSellingWidget.CHOOSE_FILL_METHOD'),
                $fillMethods
            )
        );
        
        return $fields;
    }
    
    /**
     * We set checkbox field values here to false if they are not in the post
     * data array.
     *
     * @return void
     *
     * @param array $data The post data array
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 28.08.2011
     */
    function populateFromPostData($data) {
        if (!array_key_exists('isContentView', $data)) {
            $this->isContentView = 0;
        }
        if (!array_key_exists('useListView', $data)) {
            $this->useListView = 0;
        }
        if (!array_key_exists('showOnProductGroupPages', $data)) {
            $this->showOnProductGroupPages = 0;
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
     * Returns a DataObjectSet of products.
     *
     * @return DataObjectSet
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
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
                return $this->selectElementyByOrderStatistics($controller);
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
     * @param Controller $controller The current controller
     * 
     * @return mixed DataObjectSet|boolean false
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 29.08.2011
     */
    protected function selectElementyByOrderStatistics($controller) {
        
    }
}