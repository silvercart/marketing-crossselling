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
 * German (Germany) language pack
 *
 * @package SilvercartMarketingCrossSelling
 * @subpackage i18n
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @ignore
 */
i18n::include_locale_file('silvercart_marketing_cross_selling', 'en_US');

global $lang;

if (array_key_exists('de_DE', $lang) && is_array($lang['de_DE'])) {
    $lang['de_DE'] = array_merge($lang['en_US'], $lang['de_DE']);
} else {
    $lang['de_DE'] = $lang['en_US'];
}

$lang['de_DE']['SilvercartMarketingCrossSellingProduct']['ALTERNATIVESOURCEPRODUCTS']       = 'Folgende Produkte als Alternativ-Produkte verwenden';
$lang['de_DE']['SilvercartMarketingCrossSellingProduct']['ALTERNATIVETARGETPRODUCTS']       = 'Dieses Produkt als Alternativ-Produkt für folgende verwenden';
$lang['de_DE']['SilvercartMarketingCrossSellingProduct']['CROSSSELLINGGROUP']               = 'Cross-Selling';
$lang['de_DE']['SilvercartMarketingCrossSellingProduct']['SHOWALTERNATIVEPRODUCTSBEFORE']   = 'Alternativ-Produkte <u>über</u> den Produkt-Details anzeigen (ansonsten erfolgt die Anzeige unter den Details)';

$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['CHOOSE_FILL_METHOD']           = 'Wählen Sie die Art, wie Produkte für das Widget ausgesucht werden sollen';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['CMSTITLE']                     = 'Widget für die Anzeige von Cross-Selling Produkten';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['CUSTOM_TEMPLATE_NAME']         = 'Name des eigenen Templates (ohne .ss Endung)';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['DESCRIPTION']                  = 'Stellt die ausgewählten Produkte als Widget dar.';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['FILL_METHOD']                  = 'Selektionsmethode für die Produkte';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['IS_CONTENT_VIEW']              = 'Normale Produktansicht statt Widgetansicht verwenden';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['NUMBER_OF_PRODUCTS']           = 'Anzahl der Produkte, die angezeigt werden sollen';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['OTHERPRODUCTGROUP']            = 'Produkte aus der nachfolgend definierten Warengruppe verwenden';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['TITLE']                        = 'Cross-Selling Produkte';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['ORDERSTATISTICS']              = 'Bestellstatistiken nutzen, um Produkte anzuzeigen, die zusammen mit dem aktuellen Produkt gekauft wurden';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['RANDOMGENERATOR']              = 'Zufallsgenerator nutzen (Produkte werden aus gleicher Warengruppe zufällig ausgewählt)';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['RELATEDPRODUCTS']              = 'Produkte direkt verknüpfen (Produkte werden über das nachfolgende Textfeld mit Autovervollständigung für Artikelnummern gepflegt)';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['SHOW_ON_PRODUCT_GROUP_PAGES']  = 'Auch auf Warengruppenseiten anzeigen';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['USE_LISTVIEW']                 = 'Listendarstellung verwenden';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['WIDGET_TITLE']                 = 'Titel für das Widget';
$lang['de_DE']['SilvercartMarketingCrossSellingWidget']['USE_CUSTOM_TEMPLATES']         = 'Eigenes Template für die Anzeige verwenden';

$lang['de_DE']['SilvercartMarketingCrossSellingWidgetLanguage']['PLURALNAME']           = _t('Silvercart.TRANSLATIONS');
$lang['de_DE']['SilvercartMarketingCrossSellingWidgetLanguage']['SINGULARNAME']         = _t('Silvercart.TRANSLATION');