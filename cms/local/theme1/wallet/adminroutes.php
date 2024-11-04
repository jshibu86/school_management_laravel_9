<?php
/*
 * get countries data
 */
Route::post("get-wallet-data", "WalletController@getData")->name(
    "get_wallet_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-wallet/{action?}",
    "WalletController@statusChange"
)->name("wallet_action_from_admin");

Route::resource("wallet", "WalletController");

Route::get("e-payment-verify/{id?}", "WalletController@epaymentverify")->name(
    "epaymentverify"
);

Route::post("wallet-payment/{id?}", "WalletController@Paymentverify")->name(
    "Paymentverify"
);
