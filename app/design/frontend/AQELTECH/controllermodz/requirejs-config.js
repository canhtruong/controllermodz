var config = {
  map: {
    "*": {
      theme: "js/theme"
    },
  },

  config: {
    mixins: {
      "Magento_Checkout/js/view/shipping": {
        "Magento_Checkout/js/view/shipping-mixin": true,
      },
    },
  },

  deps: ["theme"],
};
