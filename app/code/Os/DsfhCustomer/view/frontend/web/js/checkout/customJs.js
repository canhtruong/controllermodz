define(
    [
        'ko',
        'jquery',
        'uiComponent',
        'mage/url'
    ],
    function (ko, $, Component,url) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Os_DsfhCustomer/checkout/customCheckbox'
            },
            initObservable: function () {
                var out = {};
                var str = window.location.search.replace("?", "");
                var str_check = str.replace("suggested_order","");
                var defaultvl = "";
                if(str.length == str_check.length){ } else {
                    defaultvl = "VMI-";
                }
                this._super()
                    .observe({
                        CheckVals: ko.observable(false),
                        customcheckboxdf: ko.observable(defaultvl)
                    });
                var checkVal=0;
                var requestId=0;
                var OrgId=0;
                self = this;
                this.CheckVals.subscribe(function (newValue) {
                    checkVal = document.getElementById("order-customCheckbox").value;
                    var firstchars = checkVal.substr(0, 4);
                    if(defaultvl === "VMI-"){
                        if(firstchars.toUpperCase() === defaultvl){
                            var linkUrls  = url.build('dsfhcustomer/checkout/saveInQuote');
                            jQuery(".checkout-billing-address .action-update").click();
                            $.ajax({
                                showLoader: true,
                                url: linkUrls,
                                data: {checkVal : checkVal, requestId : requestId, OrgId : OrgId},
                                type: "POST",
                                dataType: 'json'
                            }).done(function (data) {
                                console.log('success');
                            });
                        } else {
                            document.getElementById("order-customCheckbox").value = defaultvl;
                            alert("Please fill data for Customer PO number");
                        }
                    } else {
                        var linkUrls  = url.build('dsfhcustomer/checkout/saveInQuote');
                        jQuery(".checkout-billing-address .action-update").click();
                        $.ajax({
                            showLoader: true,
                            url: linkUrls,
                            data: {checkVal : checkVal, requestId : requestId, OrgId : OrgId},
                            type: "POST",
                            dataType: 'json'
                        }).done(function (data) {
                            console.log('success');
                        });
                    }
                    
                });
                return this;
            }
        });
    }
);