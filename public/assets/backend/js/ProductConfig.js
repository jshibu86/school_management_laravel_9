import AcademicConfig from "./AcademicConfig.js";
import ExamConfig from "./ExamConfig.js";

export default class ProductConfig {
    static ProductConfiginit(notify_script, type = null, mode = null) {
        if (type == "shop") {
            $('select[name="category_id"]').on("change", function () {
                let cat_id = $(this).val();

                ProductConfig.getBrands(cat_id);
            });

            $('select[name="supplier_id"]').on("change", function () {
                var supplier_id = $(this).val();
                if (supplier_id == "new") {
                    $("#supplier_name").val("");
                    $("#supplier_email").val("");
                    $("#supplier_mobile").val("");
                    $(".supplier_address").val("");
                    $(".hint").show();
                    $(".supplier_row").show();
                } else {
                    var url =
                        window.supplier_info + "?supplier_id=" + supplier_id;
                    if (url) {
                        axios
                            .get(url)
                            .then((response) => {
                                if (response.data.supplier_info) {
                                    $("#supplier_name").val(
                                        response.data.supplier_info
                                            .supplier_name
                                    );
                                    $("#supplier_email").val(
                                        response.data.supplier_info
                                            .supplier_email
                                    );
                                    $("#supplier_mobile").val(
                                        response.data.supplier_info
                                            .supplier_mobile
                                    );
                                    $(".supplier_address").val(
                                        response.data.supplier_info
                                            .supplier_address
                                    );
                                    $(".hint").hide();
                                    $(".supplier_row").show();
                                } else {
                                    notify_script(
                                        "Error",
                                        "No Supplier found",
                                        "error",
                                        true
                                    );
                                }
                            })
                            .catch((error) => {
                                let status = error.response;
                                console.log(status);
                                notify_script("Error", status, "error", true);
                            });
                    }
                }
            });
        }
        if (type == "purchase") {
            $('select[name="category_id"]').on("change", function () {
                let cat_id = $(this).val();

                ProductConfig.getBrands(cat_id);

                ProductConfig.getProducts(cat_id, null, notify_script, mode);
            });
            $('select[name="class_id"]').on("change", function () {
                AcademicConfig.getSection($(this).val(), notify_script);
            });

            $('select[name="product_id"]').on("change", function () {
                ProductConfig.CheckProductQuantity(
                    $(this).val(),
                    notify_script
                );
            });

            $('select[name="brand_id"]').on("change", function () {
                let brand_id = $(this).val();

                let cat_id = $('select[name="category_id"]').val();

                ProductConfig.getProducts(
                    cat_id,
                    brand_id,
                    notify_script,
                    mode
                );
            });
        }

        // purchase report module

        $('select[name="report_type"]').on("change", function () {
            let type = $(this).val();

            $(".startdate").val(" ");
            $(".enddate").val(" ");
            $(".month").val(" ");
            $(".day").val(" ");
            $(".year").val(" ");

            if (type == "daily") {
                $(".monthly").hide();
                $(".yearly").hide();
                $(".daily").show();
                $(".weekly").hide();
            } else if (type == "monthly") {
                $(".monthly").show();
                $(".yearly").hide();
                $(".daily").hide();
                $(".weekly").hide();
            } else if (type == "yearly") {
                $(".monthly").hide();
                $(".yearly").show();
                $(".daily").hide();
                $(".weekly").hide();
            } else if (type == "weekly") {
                $(".monthly").hide();
                $(".yearly").hide();
                $(".daily").hide();
                $(".weekly").show();
            }
        });
    }

    static getProducts(cat_id, brand_id, notify_script, mode) {
        let getUrl =
            window.producturl +
            "?cat=" +
            cat_id +
            "&brand=" +
            brand_id +
            "&type=" +
            mode;
        if (cat_id || brand_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="product_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select product...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="product_id"]')
                            .empty()
                            .select2({ placeholder: "select product..." });
                        notify_script(
                            "Error",
                            "No products found",
                            "error",
                            true
                        );
                    }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="product_id"]')
                .empty()
                .select2({ placeholder: "Pick a product..." });
        }
    }

    static getBrands(cat_id) {
        let getUrl = window.brandurl + "?cat=" + cat_id;
        if (cat_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="brand_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select brand...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="brand_id"]')
                            .empty()
                            .select2({ placeholder: "select brand..." });
                    }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="brand_id"]')
                .empty()
                .select2({ placeholder: "Pick a brand..." });
        }
    }
    static Openmodel(id) {
        let getUrl = window.getproduct + "?product_id=" + id;
        if (id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.viewfile);
                        $("#view__homeworks").modal("show");
                        //new PerfectScrollbar(".dashboard-social-list");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    console.log(status);
                });
        }
    }
    static Addtocart(id, qty) {
        let getUrl = window.addtocart + "?id=" + id + "&quantity=" + qty;
        if (id) {
            axios
                .get(getUrl)
                .then((response) => {
                    ProductConfig.Minicart();
                    Snackbar.show({
                        text: "Added to your Cart",
                        pos: "top-center",
                    });
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                });
        } else {
            // clear section list dropdown
            Snackbar.show({
                text: "Whoops !! Something Went Wrong , Try Again",
                pos: "top-center",
            });
        }
    }
    static productremove(id, type = null) {
        let getUrl = window.cartproductremove + "?rowid=" + id;
        if (id) {
            axios
                .get(getUrl)
                .then((response) => {
                    ProductConfig.Minicart();
                    ProductConfig.Loadcart();
                    $("#parsed").val(response.data.parsed);
                    console.log(window.location.href);

                    if (response.data.cartcount == 0) {
                        if (window.location.href.indexOf("order/create") > -1) {
                            window.location.reload();
                        }
                    }

                    // if (type) {
                    //     if (response.data.cartcount == 0) {
                    //         window.location.reload();
                    //     }
                    // }
                    Snackbar.show({
                        text: "Product Removed from your Cart",
                        pos: "top-center",
                    });
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                });
        } else {
            // clear section list dropdown
            Snackbar.show({
                text: "Whoops !! Something Went Wrong , Try Again",
                pos: "top-center",
            });
        }
    }

    static Minicart() {
        let getUrl = window.minicart;
        axios
            .get(getUrl)
            .then((response) => {
                $('span[id="cart-count"]').text(response.data.cartqty);

                if (response.data.cartqty > 0) {
                    $(".checkout-cart").show();
                    document.querySelector(
                        ".cart-message"
                    ).textContent = `Checkout : ${response.data.carttotal} ₦`;
                } else {
                    $(".checkout-cart").hide();
                }

                var minicart = "";

                $(".cart-item-list").html(response.data.viewfile);
            })
            .catch((error) => {
                let status = error;
                console.log(status);
            });
    }

    static Loadcart() {
        let getUrl = window.checkoutcart;
        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    $(".order-cart").html("");
                    $(".order-cart").html(response.data.viewfile);
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                });
        } else {
            // clear section list dropdown
            Snackbar.show({
                text: "Whoops !! Something Went Wrong , Try Again",
                pos: "top-center",
            });
        }
    }
    static updateCart(id, qty) {
        let getUrl = window.updatecart + "?id=" + id + "&qty=" + qty;

        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    ProductConfig.Minicart();
                    ProductConfig.Loadcart();

                    $("#parsed").val(response.data.parsed);
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                });
        } else {
            // clear section list dropdown
            Snackbar.show({
                text: "Whoops !! Something Went Wrong , Try Again",
                pos: "top-center",
            });
        }
    }
    static testing() {
        console.log("from product");
    }

    static CheckProductQuantity(id, notify_script = null) {
        let getUrl = window.checkquantity + "?id=" + id;

        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    $("#stockavailable").val(response?.data?.product_qty);
                    console.log(response?.data);
                    $("#salequantity").val(response?.data?.product_qty);
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                });
        } else {
            // clear section list dropdown
            notify_script("Error", "Something Wrong", "error", true);
        }
    }
}
