<?php
/*
 * get countries data
 */
Route::post("get-shop-data", "ShopController@getData")->name(
    "get_shop_data_from_admin"
);

Route::post("get-supplier-data", "SupplierController@getData")->name(
    "get_supplier_data_from_admin"
);

Route::post("get-purchase-data", "PurchaseOrderController@getData")->name(
    "get_purchase_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-shop/{action?}",
    "ShopController@statusChange"
)->name("shop_action_from_admin");

Route::any(
    "do-status-change-for-supplier/{action?}",
    "SupplierController@statusChange"
)->name("supplier_action_from_admin");
/*
 * resource controller
 */
Route::resource("shop", "ShopController");

Route::resource("cart", "CartController");
Route::resource("purchase", "PurchaseOrderController");
Route::resource("supplier", "SupplierController");

// purchase report

Route::get("purchase-report", "PurchaseOrderController@purchaseReport")->name(
    "purchasereport"
);
Route::post(
    "purchase-report-get",
    "PurchaseOrderController@getreportdata"
)->name("getreportdata");

// order module

Route::resource("order", "OrderController");

Route::post("get-order-data", "OrderController@getData")->name(
    "get_order_data"
);

Route::any(
    "do-status-change-for-order/{action?}",
    "OrderController@statusChange"
)->name("order_action_from_admin");

// endordermodule

Route::get("add-to-cart/{id?}", "CartController@addtocart")->name(
    "cart.addtocart"
);

Route::get("get-mini-cart/{id?}", "CartController@minicart")->name(
    "cart.minicart"
);

Route::get(
    "mini-cart-product-remove/{id?}",
    "CartController@Productremove"
)->name("cart.Productremove");

Route::get("cart-product-update/{id?}", "CartController@updatecart")->name(
    "cart.updatecart"
);
