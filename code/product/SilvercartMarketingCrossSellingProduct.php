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
 * Attribute set to collect attributes
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
     * Adds som extra data model fields
     *
     * @return array
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 14.03.2012
     */
    public function extraStatics() {
        return array(
            'belongs_many_many'  => array(
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
     * @since 16.03.2012
     */
    public function updateCMSFields(FieldSet &$fields) {
        $fields->removeByName('SilvercartMarketingCrossSellingWidgets');
    }
    
    /**
     * Updates the field labels
     *
     * @param array &$labels Labels to update
     * 
     * @return void
     *
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 16.03.2012
     */
    public function updateFieldLabels(&$labels) {
        $labels = array_merge(
                $labels,
                array(
                    'SilvercartMarketingCrossSellingWidgets' => _t('SilvercartMarketingCrossSellingWidget.PLURALNAME'),
                )
        );
    }
    
}