<?php
/**
 * Copyright 2012 pixeltricks GmbH
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
 * @package Silvercart
 * @subpackage Products
 */

/**
 * Adds some X-Selling specific functions to a product.
 *
 * @package Silvercart
 * @subpackage Products
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @copyright 2012 pixeltricks GmbH
 * @since 13.03.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */
class SilvercartMarketingCrossSellingProduct extends DataObjectDecorator {
    
    /**
     * List of alternative products.
     *
     * @var DataObjectSet
     */
    protected $alternativeProducts = null;

    /**
     * Adds som extra data model fields
     *
     * @return array
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 28.02.2013
     */
    public function extraStatics() {
        return array(
            'db' => array(
                'ShowAlternativeProductsBefore' => 'Boolean(0)',
            ),
            'has_many' => array(
                'AlternativeSourceProducts' => 'SilvercartMarketingCrossSellingProductBridge.Source',
                'AlternativeTargetProducts' => 'SilvercartMarketingCrossSellingProductBridge.Target',
            ),
            'belongs_many_many' => array(
                'SilvercartMarketingCrossSellingWidgets' => 'SilvercartMarketingCrossSellingWidget',
            ),
        );
    }
    
    /**
     * Updates the CMS fields
     *
     * @param FieldSet &$fields Fields to update
     * 
     * @return void
     *
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 28.02.2013
     */
    public function updateCMSFields(FieldSet &$fields) {
        $owner = $this->owner;
        
        $fields->removeByName('SilvercartMarketingCrossSellingWidgets');
        $fields->removeByName('AlternativeSourceProducts');
        $fields->removeByName('AlternativeTargetProducts');
        
        $showAlternativeProductsBefore = new CheckboxField('ShowAlternativeProductsBefore', $this->owner->fieldLabel('ShowAlternativeProductsBefore'));
        $alternativeSourceProducts = new SilvercartBridgeTextAutoCompleteField(
                $owner,
                'AlternativeSourceProducts',
                $owner->fieldLabel('AlternativeSourceProducts'),
                'SilvercartProduct.ProductNumberShop'
        );
        $alternativeTargetProducts = new SilvercartBridgeTextAutoCompleteField(
                $owner,
                'AlternativeTargetProducts',
                $owner->fieldLabel('AlternativeTargetProducts'),
                'SilvercartProduct.ProductNumberShop'
        );
        
        $xSellingGroup = new SilvercartFieldGroup('CrossSellingGroup', $this->owner->fieldLabel('CrossSellingGroup'), $fields);
        $xSellingGroup->pushAndBreak($showAlternativeProductsBefore);
        $xSellingGroup->push($alternativeSourceProducts);
        $xSellingGroup->push($alternativeTargetProducts);
        
        $CSGTitleField = $fields->dataFieldByName('CSGTitle');
        if ($CSGTitleField instanceof FormField) {
            $xSellingGroup->breakAndPush($CSGTitleField);
        }
        
        $fields->addFieldToTab('Root.Main', $xSellingGroup);
    }
    
    /**
     * Updates the field labels
     *
     * @param array &$labels Labels to update
     * 
     * @return void
     *
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 28.02.2013
     */
    public function updateFieldLabels(&$labels) {
        $labels = array_merge(
                $labels,
                array(
                    'ShowAlternativeProductsBefore'             => _t('SilvercartMarketingCrossSellingProduct.SHOWALTERNATIVEPRODUCTSBEFORE'),
                    'AlternativeSourceProducts'                 => _t('SilvercartMarketingCrossSellingProduct.ALTERNATIVESOURCEPRODUCTS'),
                    'AlternativeTargetProducts'                 => _t('SilvercartMarketingCrossSellingProduct.ALTERNATIVETARGETPRODUCTS'),
                    'SilvercartMarketingCrossSellingWidgets'    => _t('SilvercartMarketingCrossSellingWidget.PLURALNAME'),
                    'CrossSellingGroup'                         => _t('SilvercartMarketingCrossSellingProduct.CROSSSELLINGGROUP'),
                )
        );
    }
    
    /**
     * Adds the alternative product markup to the given reference parameter.
     * 
     * @param string &$beforeProductHtmlInjections Reference to add HTML code to
     * 
     * @return void
     *
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 04.03.2013
     */
    public function updateBeforeProductHtmlInjections(&$beforeProductHtmlInjections) {
        if ($this->owner->ShowAlternativeProductsBefore) {
            $beforeProductHtmlInjections .= $this->owner->renderWith('SilvercartAlternativeProducts');
        }
    }
    
    /**
     * Adds the alternative product markup to the given reference parameter.
     * 
     * @param string &$afterProductHtmlInjections Reference to add HTML code to
     * 
     * @return void
     *
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 04.03.2013
     */
    public function updateAfterProductHtmlInjections(&$afterProductHtmlInjections) {
        if (!$this->owner->ShowAlternativeProductsBefore) {
            $afterProductHtmlInjections .= $this->owner->renderWith('SilvercartAlternativeProducts');
        }
    }


    /**
     * Returns a list of alternative products
     * 
     * @return DataObjectSet
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 04.03.2013
     */
    public function AlternativeProducts() {
        if (is_null($this->alternativeProducts)) {
            $this->alternativeProducts = DataObject::get(
                    'SilvercartProduct',
                    sprintf(
                            '"SilvercartProduct"."ID" IN (SELECT TargetID FROM SilvercartMarketingCrossSellingProductBridge WHERE SourceID = %d)',
                            $this->owner->ID
                    )
            );
        }
        return $this->alternativeProducts;
    }
    
}

/**
 * A bridge object to build an additional many_many relation to a product itself.
 *
 * @package Silvercart
 * @subpackage Products
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @copyright 2013 pixeltricks GmbH
 * @since 04.03.2013
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */
class SilvercartMarketingCrossSellingProductBridge extends DataObject {
    
    /**
     * Has one attributes.
     *
     * @var array
     */
    public static $has_one = array(
        'Source'    => 'SilvercartProduct',
        'Target'    => 'SilvercartProduct',
    );
    
}