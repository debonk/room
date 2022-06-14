# room > gk ballroom software

2.7.9	14/06/2022
Modify Modul: Product List: Add Print feature for product list
Modul: Localisation > Venue
	Table Rename: Ceremony > Venue
	ALTER TABLE `oc_venue` CHANGE `ceremony_id` `venue_id` INT(11) NOT NULL AUTO_INCREMENT;
	ALTER TABLE `oc_venue` ADD `slots` TINYTEXT NOT NULL AFTER `code`;
	ALTER TABLE `oc_order` CHANGE `ceremony_id` `venue_id` INT(11) NOT NULL;

Modify: Sale > Order List: Add Venue and Username filter features
Remove Modul: Localisation > Ceremony

2.7.8	09/06/2022
Modul: Dashboard > Yearly
Bug Fixed: Catalog > Vendor: Pagination always reset to page 1
Bug Fixed: Sale > Order: Add order not save slot prefix
Bug Fixed: Sale > Order > Monthly view: Some order not shown in monthly calendar
Bug Fixed: Sale > Order > Yearly view: Some order not shown in yearly calendar
Modify: Sale > Order: Add Date Start/End Filter
Modify: Report > Order: Give a better report

2.7.7	08/06/2022
Security Issue: Add rel="noopener noreferrer" on <a> with target="_blank".

2.7.6	08/03/2022
Modify: Sale > Order: Order customer must be registered.
Modify: Sale > Order > Add History: Validate PO and vendor transaction before completing order.
Bug Fixed: Sale > Order > Edit Order: Vendor PO product not linked to order after edited. Validate PO before edit order.
Bug Fixed: Report > Sale > Commission: Order link not loaded properly.

2.7.5	07/02/2022
Modify: Sale > Order > Customer: Order can be paid by multiple customer (i.e order yg dipindahtangankan).
Bug Fixed: Forgotten password not working

2.7.4
Bug Fixed: Config SSL not set on store_id = 0.

2.7.3	19/01/2022
Layout: order_agreement, vendor_agreement, admission: Show letter head on media print.

2.7.2
- Sale/Order Info: Memperbaiki sistem pemilihan vendor.
