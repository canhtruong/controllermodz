/*
 * Copyright © 2019 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
Number.prototype.paddingLeft = function (paddingValue) {
  return String(paddingValue + this).slice(-paddingValue.length);
};

EstimatedDeliveryDate = {
  data: {},
  elapsed_time: 0,
  stockStatus: true,
  countdown: {
    update: function (decrement) {
      require(["jquery", "mage/translate"], function (jQuery) {
        jQuery(".edd_countdown").each(function () {
          let countdown = jQuery(this);
          if (countdown) {
            let current_countdown =
              parseInt(countdown.attr("countdown")) -
              EstimatedDeliveryDate.elapsed_time;
            if (decrement) {
              EstimatedDeliveryDate.elapsed_time += 1;
            }
            if (current_countdown <= 0) {
              countdown.html("");
              return;
            }
            let d = Math.floor(current_countdown / 86400);
            let h = Math.floor((current_countdown % 86400) / 3600);
            let m = Math.floor(((current_countdown % 86400) % 3600) / 60);
            let s = ((current_countdown % 86400) % 3600) % 60;
            if (d + h + m + s == 0) {
              document.location.reload();
            }
            let timer = new Array();
            if (window.format >= 1) {
              let dayWord =
                d > 1 ? jQuery.mage.__("d") : jQuery.mage.__("d");
              let day = d + " " + dayWord;

              if (window.type != "1") {
                if (d > 0) {
                  timer.push(day);
                }
              } else {
                timer.push(
                  '<div class="knob-container"><input value="' +
                    d +
                    '" type="text" id="countdown-day" data-width="60" data-height="60" data-max="' +
                    d +
                    '" data-thickness=".2"  data-fgcolor="green"  data-bgcolor="white" data-min="0" class="knob" data-readonly="true" /> <label>' +
                    dayWord +
                    "</label></div>"
                );
              }
            }
            if (window.format >= 2) {
              let hourWord =
                h > 1 ? jQuery.mage.__("h") : jQuery.mage.__("h");
              let hour = h + " " + hourWord;

              if (window.type != "1") {
                if (h > 0) {
                  timer.push(hour);
                }
              } else {
                timer.push(
                  '<div class="knob-container"><input value="' +
                    h +
                    '" type="text" id="countdown-hour" data-width="60" data-height="60" data-max="24" data-thickness=".2"  data-fgcolor="green"  data-bgcolor="white" data-min="0" class="knob" data-readonly="true" /> <label>' +
                    hourWord +
                    "</label></div>"
                );
              }
            }
            if (window.format >= 3) {
              let minWord =
                m > 1 ? jQuery.mage.__("m") : jQuery.mage.__("m");
              let min = m + " " + minWord;

              if (window.type != "1") {
                if (m > 0) {
                  timer.push(min);
                }
              } else {
                timer.push(
                  '<div class="knob-container"><input value="' +
                    m +
                    '" type="text" id="countdown-min" data-width="60" data-height="60" data-max="59" data-thickness=".2"  data-fgcolor="green"  data-bgcolor="white" data-min="0" class="knob" data-readonly="true" /> <label>' +
                    minWord +
                    "</label></div>"
                );
              }
            }
            if (window.format >= 4) {
              let secWord =
                s > 1 ? jQuery.mage.__("s") : jQuery.mage.__("s");
              let sec = s + " " + secWord;

              if (window.type != "1") {
                if (s > 0) {
                  timer.push(sec);
                }
              } else {
                timer.push(
                  '<div class="knob-container"><input value="' +
                    s +
                    '" type="text" id="countdown-sec" data-width="60" data-height="60" data-max="59" data-thickness=".2"  data-fgcolor="green"  data-bgcolor="white" data-min="0" class="knob" data-readonly="true" /> <label>' +
                    secWord +
                    "</label></div>"
                );
              }
            }

            if (window.type == "1") {
              countdown.html(timer.join(""));
              jQuery(".knob").knob();
            } else {
              countdown.html(timer.join().replace(/,/g, ", "));
            }
          }
        });
      });
    },
    start: function (decrement) {
      if (typeof timer != "undefined") {
        clearInterval(timer);
      }

      timer = setInterval(function () {
        EstimatedDeliveryDate.countdown.update(decrement);
      }, 1000);
    },
  },
  message: {
    updateFPC: function () {
      jQuery.ajax({
        url: EstimatedDeliveryDate.updateFPC_url,
        data: { id: EstimatedDeliveryDate.product_id },
        method: "post",
        global: false,
        complete: function (response) {
          let data = jQuery.parseJSON(response.responseText);
          EstimatedDeliveryDate.data = data;
          if (EstimatedDeliveryDate.debug == "0") {
            // jQuery(".estimatedDeliveryDate").html(data.message);
            EstimatedDeliveryDate.message.updateStockStatus(
              jQuery("#qty").val(),
              true
            );
          }
        },
      });
    },

    updateStockStatus: function (qty, force = false) {
      var saleable = true;
      var backorders = false;
      var message;

      if (qty > EstimatedDeliveryDate.data.threshold) {
        backorders = true;
      }
      if (
        qty > EstimatedDeliveryDate.data.threshold &&
        EstimatedDeliveryDate.data.out_of_stock == ""
      ) {
        saleable = false;
      }

      let updateRequired =
        saleable != EstimatedDeliveryDate.stockStatus || force;
      if (updateRequired) {
        if (backorders) {
          message = EstimatedDeliveryDate.data.out_of_stock;
        } else {
          message = EstimatedDeliveryDate.data.in_stock;
        }
        EstimatedDeliveryDate.stockStatus = saleable;

        if (!saleable) {
          message = jQuery.mage.__(
            "<div>The requested qty is not available</div>"
          );
          jQuery("#product-addtocart-button").prop("disabled", true);
          jQuery(".estimatedDeliveryDate")
            .removeClass("success")
            .addClass("error");
        } else {
          jQuery("#product-addtocart-button").prop("disabled", false);
          jQuery(".estimatedDeliveryDate")
            .removeClass("error")
            .addClass("success");
        }

        // jQuery(".estimatedDeliveryDate").effect("fade", "slow").effect("fade", "slow");
        if (typeof message === "string") {
          jQuery(".estimatedDeliveryDate").html(message.replace(/\\/g, ""));
          let script = message.replace(/.*<script>/g, "");
          script = script.replace(/<\/script>[sS]*/g, "");
          script = script.replace(/\\/g, "");
          if (!script.startsWith("<") && script != message) {
            eval(script);
          }
        }

        EstimatedDeliveryDate.countdown.update(false);
      }
    },

    update: function (type) {
      let attr = [];
      let selection = true;
      let options = new Array();

      if (type == "bundle") {
        selection = true;
        jQuery("." + type).each(function (i, input) {
          let idChunk = input.id.split("-");
          let index = idChunk.pop();
          let group = idChunk.pop();

          if (
            jQuery(input).is("input[type=radio]") ||
            jQuery(input).is("input[type=hidden]")
          ) {
            if (input.checked) {
              EstimatedDeliveryDate.data.children["_" + group][
                "_" + index
              ].selected_qty =
                jQuery("INPUT[name='bundle_option_qty[" + group + "]']").val() *
                jQuery("#qty").val();
              options.push(
                EstimatedDeliveryDate.data.children["_" + group]["_" + index]
              );
            }
          }
          if (jQuery(input).is("select") && jQuery(input).val() != "") {
            EstimatedDeliveryDate.data.children["_" + index][
              "_" + jQuery(input).val()
            ].selected_qty =
              jQuery("INPUT[name='bundle_option_qty[" + index + "]']").val() *
              jQuery("#qty").val();
            options.push(
              EstimatedDeliveryDate.data.children["_" + index][
                "_" + jQuery(input).val()
              ]
            );
          }
        });
        let message = null;
        let to = 0;
        var saleable = true;
        jQuery.each(options, function (i, option) {
          if (
            option.selected_qty > option.threshold &&
            option.out_of_stock == ""
          ) {
            saleable = false;
          }
          if (option.selected_qty > option.threshold) {
            if (option.to_out_of_stock > to) {
              to = option.to_out_of_stock;
              message = option.out_of_stock;
            }
          } else {
            if (option.to_in_stock > to) {
              to = option.to_in_stock;
              message = option.in_stock;
            }
          }
        });
        if (!saleable) {
          message = jQuery.mage.__(
            "<div>The requested qty is not available</div>"
          );
          jQuery("#product-addtocart-button").prop("disabled", true);
          jQuery(".estimatedDeliveryDate")
            .removeClass("success")
            .addClass("error");
        } else {
          jQuery("#product-addtocart-button").prop("disabled", false);
          jQuery(".estimatedDeliveryDate")
            .addClass("success")
            .removeClass("error");
        }

        if (typeof message === "string") {
          jQuery(".estimatedDeliveryDate").html(message.replace(/\\/g, ""));
          EstimatedDeliveryDate.countdown.update(false);
          let script = message.replace(/.*<script>/g, "");
          script = script.replace(/<\/script>[sS]*/g, "");
          script = script.replace(/\\/g, "");
          if (!script.startsWith("<") && script != message) {
            eval(script);
          }
        }
      } else {
        if (type == "swatch-attribute" || typeof type == "undefined") {
          jQuery("#product-options-wrapper .swatch-attribute").each(
            function () {
              let id = null;
              let value = null;
              if (jQuery(this).find(".swatch-option.selected").length < 1) {
                id = jQuery(this).attr("data-attribute-id");
                if (id == null) id = jQuery(this).attr("attribute-id");
                value = jQuery(this).attr("data-option-selected");
                if (value == null) value = jQuery(this).attr("option-selected");
                if (id != null) {
                  attr.push({
                    id: id,
                    value: value,
                  });
                } else {
                  selection = false;
                }
              } else {
                id = jQuery(this).attr("data-attribute-id");
                if (id == null) id = jQuery(this).attr("attribute-id");
                value = jQuery(this)
                  .find(".swatch-option.selected")
                  .attr("data-option-id");
                if (value == null)
                  value = jQuery(this)
                    .find(".swatch-option.selected")
                    .attr("option-id");
                attr.push({
                  id: id,
                  value: value,
                });
              }
            }
          );
        } else {
          jQuery(".super-attribute-select").each(function () {
            let attr_id = jQuery(this).attr("attribute-id");
            if (attr_id == null)
              attr_id = jQuery(this).attr("data-attribute-id");
            if (attr_id == null) attr_id = jQuery(this).data("attribute-id");
            if (typeof attr_id === "undefined") {
              attr_id = jQuery(this).attr("id").replace("attribute", "");
            }
            let option_id = jQuery(this).attr("option-selected");
            if (option_id == null)
              option_id = jQuery(this).attr("data-option-selected");
            if (option_id == null)
              option_id = jQuery(this).data("option-selected");
            if (typeof option_id === "undefined" && jQuery(this).val() !== "") {
              option_id = jQuery(this).val();
            }
            if (option_id !== null && jQuery(this).val() !== "") {
              attr.push({ id: attr_id, value: option_id });
              console.log(attr);
            } else {
              selection = false;
            }
          });
        }

        if (selection) {
          jQuery.each(EstimatedDeliveryDate.data.children, function (i, e) {
            let found = true;
            jQuery.each(attr, function (y, a) {
              if (eval("e.attribute" + a.id) !== a.value) {
                found = false;
              }
            });

            if (found) {
              EstimatedDeliveryDate.data.in_stock = e.in_stock;
              EstimatedDeliveryDate.data.out_of_stock = e.out_of_stock;
              EstimatedDeliveryDate.data.threshold = e.threshold;

              EstimatedDeliveryDate.message.updateStockStatus(
                jQuery("#qty").val(),
                true
              );
            }
          });
        } else {
          EstimatedDeliveryDate.data.in_stock =
            EstimatedDeliveryDate.data.default;
          EstimatedDeliveryDate.data.out_of_stock =
            EstimatedDeliveryDate.data.default;
          EstimatedDeliveryDate.data.threshold = Infinity;
          EstimatedDeliveryDate.message.updateStockStatus(
            jQuery("#qty").val(),
            true
          );
        }
      }
    },
  },
  options: {
    update: function () {
      let lt = new Array();

      jQuery(".product-custom-option").each(function (i, s) {
        if (jQuery(s).hasClass("multiselect")) {
          let id = jQuery(s).attr("id").replace("select_", "");

          jQuery(s)
            .select("OPTION")
            .each(function (i, opt) {
              if (jQuery(s).val() != null) {
                jQuery.each(jQuery(s).val(), function (i, value) {
                  lt.push({ id: id, value: value });
                });
              }
            });
        } else if (
          jQuery(s).hasClass("radio") ||
          jQuery(s).hasClass("checkbox")
        ) {
          if (jQuery(s).prop("checked")) {
            id = jQuery(s).attr("id").replace("options_", "");
            id = id.split("_");
            id = id[0];
            let value = jQuery(s).val();
            lt.push({ id: id, value: value });
          }
        } else {
          if (jQuery(s).val() > 0) {
            id = jQuery(s).attr("id").replace("select_", "");
            let value = jQuery(s).val();
            lt.push({ id: id, value: value });
          }
        }
      });

      let prev_from = 0;
      if (typeof from != "undefined") {
        prev_from = from;
      }
      let prev_to = 0;
      if (typeof to != "undefined") {
        prev_to = to;
      }

      from = 0;
      to = 0;
      jQuery.each(lt, function (i, leadtime) {
        let data = edd_options[leadtime.id][leadtime.value];

        if (data != null) {
          let part = data;
          if (("" + data).indexOf("-") != -1) {
            part = data.split("-");
          } else {
            part = [data, data];
          }

          if (edd_options_method == "0") {
            if (!isNaN(part[0])) {
              from += parseInt(part[0]);
            }
            if (!isNaN(part[1])) {
              to += parseInt(part[1]);
            }
          } else {
            if (!isNaN(part[0]) && part[0] > from) {
              from = parseInt(part[0]);
            }
            if (!isNaN(part[1]) && part[1] > to) {
              to = parseInt(part[1]);
            }
          }
        }
      });

      if (prev_from != from || prev_to != to) {
        jQuery(".estimatedDeliveryDate")
          .html(jQuery.mage.__("loading..."))
          .addClass("loading");
        jQuery(".estimatedDeliveryDate")[0].scrollIntoView({
          behavior: "smooth", // or "auto" or "instant"
          block: "start", // or "end"
        });

        jQuery.ajax({
          url: edd_options_url,
          data: { id: edd_options_id, from: from, to: to },
          method: "post",
          global: false,
          complete: function (response) {
            let message = jQuery.parseJSON(response.responseText).message;
            jQuery(".estimatedDeliveryDate")
              .html(message)
              .removeClass("loading");
          },
        });
      }
    },
  },
};

require([
  "jquery",
  "mage/translate",
  "Magento_Swatches/js/swatch-renderer",
  "Magento_ConfigurableProduct/js/configurable",
], function (jQuery) {
  if (
    typeof EstimatedDeliveryDate.data.exclude_children == "undefined" ||
    EstimatedDeliveryDate.data.exclude_children != 1
  ) {
    // listen to the configurable attributes selection only if the "Use same delivery date for all children" attribute is not set to yes
    // jQuery(document).ready(function () {
    //     jQuery('.super-attribute-select').change(function () {
    //         EstimatedDeliveryDate.message.update("super-attribute-select");
    //     });
    // });
    jQuery(document).on("click", ".swatch-option", function () {
      setTimeout(function () {
        EstimatedDeliveryDate.message.update("swatch-attribute");
      }, 100);
    });

    jQuery(document).ready(function () {
      jQuery(".bundle.option").click(function () {
        setTimeout(function () {
          EstimatedDeliveryDate.message.update("bundle");
        }, 100);
      });
    });
  }

  jQuery(document).ready(function () {
    jQuery(".input-text.qty").change(function () {
      if (jQuery(this).parents(".bundle-options-container").length) {
        EstimatedDeliveryDate.message.update("bundle");
      } else {
        EstimatedDeliveryDate.message.update();
        EstimatedDeliveryDate.message.updateStockStatus(
          jQuery(this).val(),
          true
        );
      }
    });
  });

  function waitFor(elt, callback) {
    let initializer = null;
    initializer = setInterval(function () {
      if (jQuery(elt).length > 0) {
        callback();
        clearInterval(initializer);
      }
    }, 200);
  }

  waitFor(".product-custom-option", EstimatedDeliveryDate.options.update);
  waitFor(".estimatedDeliveryDate", EstimatedDeliveryDate.message.updateFPC);
});

require(["jquery"], function (jQuery) {
  jQuery(document).on("click", ".product-custom-option", function () {
    EstimatedDeliveryDate.options.update();
  });
});
