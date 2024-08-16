jQuery(document).ready(function ($) {
    let container = document.getElementById("product-editor-table");

    $.ajax({
        url: WooCommerceProductEditor.ajax_url,
        method: "POST",
        data: {
            action: "get_products",
            nonce: WooCommerceProductEditor.nonce,
        },
        success: function (response) {
            if (response.success) {
                new Handsontable(container, {
                    data: response.data,
                    colHeaders: ["ID", "Name", "Price", "Stock"],
                    columns: [
                        { data: "ID", readOnly: true },
                        { data: "name" },
                        { data: "price", type: "numeric" },
                        { data: "stock", type: "numeric" },
                    ],
                    licenseKey: "non-commercial-and-evaluation", // Required if using a free version
                    afterChange: function (changes, source) {
                        if (source !== "loadData") {
                            changes.forEach(function (change) {
                                let [row, prop, oldVal, newVal] = change;

                                if (oldVal !== newVal) {
                                    $.ajax({
                                        url: WooCommerceProductEditor.ajax_url,
                                        method: "POST",
                                        data: {
                                            action: "update_product",
                                            nonce: WooCommerceProductEditor.nonce,
                                            product_id: this.getDataAtRowProp(
                                                row,
                                                "ID"
                                            ),
                                            field: prop,
                                            value: newVal,
                                        },
                                        success: function (response) {
                                            if (!response.success) {
                                                alert(
                                                    "Failed to update product."
                                                );
                                            }
                                        },
                                    });
                                }
                            });
                        }
                    },
                });
            }
        },
    });
});
