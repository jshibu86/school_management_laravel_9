<?php
/*
 * get countries data
 */
Route::post("get-inventory-data", "InventoryController@getData")->name(
    "get_inventory_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-inventory/{action?}",
    "InventoryController@statusChange"
)->name("inventory_action_from_admin");
/*
 * resource controller
 */
Route::resource("inventory", "InventoryController");

Route::get("inventrycategory", "InventoryController@InventoryCategory")->name(
    "InventoryCategory"
);

Route::get("inventryproduct", "InventoryController@InventoryProduct")->name(
    "InventoryProduct"
);

Route::get("inventrypurchase", "InventoryController@InventoryPurchase")->name(
    "InventoryPurchase"
);
