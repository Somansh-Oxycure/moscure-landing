(function () {
  "use strict";

  function notify(title, text, icon) {
    if (typeof swal === "function") {
      swal({
        title: title,
        text: text,
        icon: icon,
        timer: 3000
      });
    } else {
      window.alert(title + "\n" + text);
    }
  }

  function initMoscurePage() {
    window.moscureCheckoutInitRan = true;
    var menuToggle = document.querySelector(".mobile-menu-toggle");
    var mainNav = document.getElementById("mainNav");

    if (menuToggle && mainNav) {
      menuToggle.addEventListener("click", function () {
        var isOpen = mainNav.classList.toggle("is-open");

        menuToggle.setAttribute("aria-expanded", String(isOpen));
        menuToggle.setAttribute("aria-label", isOpen ? "Close menu" : "Open menu");
      });

      mainNav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
          mainNav.classList.remove("is-open");
          menuToggle.setAttribute("aria-expanded", "false");
          menuToggle.setAttribute("aria-label", "Open menu");
        });
      });

      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
          mainNav.classList.remove("is-open");
          menuToggle.setAttribute("aria-expanded", "false");
          menuToggle.setAttribute("aria-label", "Open menu");
        }
      });
    }

    var orderForm = document.getElementById("contact-form");

    if (orderForm) {
      orderForm.addEventListener("submit", function (event) {
        event.preventDefault();

        var data = new FormData(orderForm);
        var submitButton = orderForm.querySelector("button[type='submit']");
        var submitText = submitButton ? submitButton.innerHTML : "";

        if (submitButton) {
          submitButton.disabled = true;
          submitButton.innerHTML = "<span>Sending...</span>";
        }

        fetch(orderForm.getAttribute("action") || "send-order.php", {
          method: "POST",
          body: data,
          headers: {
            "Accept": "application/json"
          }
        })
          .then(function (response) {
            return response.json().catch(function () {
              return {};
            }).then(function (payload) {
              if (!response.ok || payload.success === false) {
                throw new Error(payload.message || "We could not send your order details. Please try again.");
              }

              return payload;
            });
          })
          .then(function () {
            notify("Thank You!", "Your details were sent successfully.", "success");
            orderForm.reset();
          })
          .catch(function (error) {
            notify("Order Not Sent", error.message || "Please try again in a moment.", "error");
          })
          .finally(function () {
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.innerHTML = submitText;
            }
          });
      });
    }

    var checkoutModal = document.getElementById("moscureCheckoutModal");
    var checkoutForm = document.getElementById("moscureStepCheckout");

    if (checkoutModal && checkoutForm) {
      var checkoutState = {
        step: 1,
        productName: "Moscure IPO",
        productLabel: "Outdoor Unit",
        productImage: "assets/img/moscure/ipo-hero.webp",
        unitPrice: 12999,
        quantity: 1
      };
      var buyButtons = document.querySelectorAll(".moscure-buy-now");
      var closeButtons = checkoutModal.querySelectorAll("[data-checkout-close]");
      var stepPanels = checkoutModal.querySelectorAll("[data-step-panel]");
      var stepIndicators = checkoutModal.querySelectorAll("[data-step-indicator]");
      var quantityInput = document.getElementById("checkoutQuantity");
      var quantityHidden = document.getElementById("checkoutQuantityInput");
      var productHidden = document.getElementById("checkoutProductInput");
      var unitPriceHidden = document.getElementById("checkoutUnitPriceInput");
      var totalHidden = document.getElementById("checkoutTotalInput");
      var productLabel = document.getElementById("checkoutProductLabel");
      var productName = document.getElementById("checkoutProductName");
      var productImage = document.getElementById("checkoutProductImage");
      var quantityImage = document.getElementById("checkoutQuantityImage");
      var quantityProduct = document.getElementById("checkoutQuantityProduct");
      var unitPrice = document.getElementById("checkoutUnitPrice");
      var total = document.getElementById("checkoutTotal");
      var reviewProduct = document.getElementById("checkoutReviewProduct");
      var reviewQuantity = document.getElementById("checkoutReviewQuantity");
      var reviewAddress = document.getElementById("checkoutReviewAddress");
      var checkoutBack = document.getElementById("checkoutBack");
      var checkoutNext = document.getElementById("checkoutNext");
      var checkoutError = document.getElementById("checkoutError");
      var checkoutPincode = document.getElementById("checkoutPincode");
      var checkoutPhone = document.getElementById("checkoutPhone");
      var checkoutDeliveryEstimate = document.getElementById("checkoutDeliveryEstimate");
      var pincodeRanges = [];
      var pincodeRangesLoaded = false;
      var pincodeRangesFailed = false;
      var lastFocusedElement = null;

      function loadPincodeRanges() {
        fetch("assets/data/pincode-ranges.json", {
          headers: {
            "Accept": "application/json"
          }
        })
          .then(function (response) {
            if (!response.ok) {
              throw new Error("Could not load serviceable pincodes.");
            }

            return response.json();
          })
          .then(function (payload) {
            pincodeRanges = Array.isArray(payload.ranges) ? payload.ranges : [];
            pincodeRangesLoaded = true;
            pincodeRangesFailed = false;
            updateDeliveryEstimate();
          })
          .catch(function () {
            pincodeRanges = [];
            pincodeRangesLoaded = true;
            pincodeRangesFailed = true;
            updateDeliveryEstimate();
          });
      }

      function formatRupees(value) {
        return "\u20b9" + Number(value || 0).toLocaleString("en-IN");
      }

      function formatDeliveryDate(date) {
        return new Intl.DateTimeFormat("en-IN", {
          weekday: "short",
          day: "numeric",
          month: "long"
        }).format(date).replace(",", "");
      }

      function getDeliveryWindowText() {
        var today = new Date();
        var startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
        var endDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 2);

        return formatDeliveryDate(startDate) + " - " + formatDeliveryDate(endDate);
      }

      function getServiceablePincodeRange(pincode) {
        var value = Number(pincode);

        if (!/^[0-9]{6}$/.test(pincode) || Number.isNaN(value)) {
          return null;
        }

        return pincodeRanges.find(function (range) {
          return value >= Number(range.start) && value <= Number(range.end);
        }) || null;
      }

      function setDeliveryEstimate(message, status, html) {
        if (!checkoutDeliveryEstimate) {
          return;
        }

        if (html) {
          checkoutDeliveryEstimate.innerHTML = html;
        } else {
          checkoutDeliveryEstimate.textContent = message || "";
        }

        checkoutDeliveryEstimate.classList.toggle("is-success", status === "success");
        checkoutDeliveryEstimate.classList.toggle("is-error", status === "error");
      }

      function updateDeliveryEstimate() {
        if (!checkoutPincode) {
          return false;
        }

        var pincode = checkoutPincode.value.trim();

        checkoutPincode.setCustomValidity("");

        if (pincode === "") {
          setDeliveryEstimate("", "");
          return false;
        }

        if (!/^[0-9]{6}$/.test(pincode)) {
          setDeliveryEstimate("We are not available in your area.", "error");
          checkoutPincode.setCustomValidity("Enter a valid 6-digit serviceable pincode.");
          return false;
        }

        if (!pincodeRangesLoaded) {
          setDeliveryEstimate("Checking delivery availability...", "");
          checkoutPincode.setCustomValidity("Checking delivery availability. Please try again.");
          return false;
        }

        if (pincodeRangesFailed) {
          setDeliveryEstimate("Could not check delivery availability. Please refresh and try again.", "error");
          checkoutPincode.setCustomValidity("Could not check delivery availability. Please refresh and try again.");
          return false;
        }

        if (!getServiceablePincodeRange(pincode)) {
          setDeliveryEstimate("We are not available in your area.", "error");
          checkoutPincode.setCustomValidity("We are not available in your area.");
          return false;
        }

        setDeliveryEstimate("", "success", "<span><i class=\"fa-solid fa-truck-fast\" aria-hidden=\"true\"></i> Estimated delivery " + getDeliveryWindowText() + "</span><span><i class=\"fa-solid fa-box-open\" aria-hidden=\"true\"></i> Free shipping</span>");
        return true;
      }

      function cleanQuantity(value) {
        var parsed = parseInt(value, 10);

        if (Number.isNaN(parsed) || parsed < 1) {
          return 1;
        }

        return Math.min(parsed, 20);
      }

      function setError(message) {
        if (checkoutError) {
          checkoutError.textContent = message || "";
        }
      }

      function updateStepUi() {
        stepPanels.forEach(function (panel) {
          panel.classList.toggle("is-active", Number(panel.getAttribute("data-step-panel")) === checkoutState.step);
        });

        stepIndicators.forEach(function (indicator) {
          var stepNumber = Number(indicator.getAttribute("data-step-indicator"));

          indicator.classList.toggle("is-active", stepNumber === checkoutState.step);
          indicator.classList.toggle("is-complete", stepNumber < checkoutState.step);
        });

        if (checkoutBack) {
          checkoutBack.disabled = checkoutState.step === 1;
        }

        if (checkoutNext) {
          checkoutNext.innerHTML = checkoutState.step === 3
            ? "<span>Pay " + formatRupees(checkoutState.unitPrice * checkoutState.quantity) + "</span>"
            : "<span>Continue</span>";
        }
      }

      function updateReview() {
        var city = document.getElementById("checkoutCity");
        var state = document.getElementById("checkoutState");
        var pincode = document.getElementById("checkoutPincode");
        var addressBits = [];

        if (city && city.value.trim() !== "") {
          addressBits.push(city.value.trim());
        }

        if (state && state.value.trim() !== "") {
          addressBits.push(state.value.trim());
        }

        if (pincode && pincode.value.trim() !== "") {
          addressBits.push(pincode.value.trim());
        }

        if (reviewProduct) {
          reviewProduct.textContent = checkoutState.productName;
        }

        if (reviewQuantity) {
          reviewQuantity.textContent = String(checkoutState.quantity);
        }

        if (reviewAddress) {
          reviewAddress.textContent = addressBits.length ? addressBits.join(", ") : "Add address in step 2";
        }
      }

      function updateTotals() {
        var orderTotal = checkoutState.unitPrice * checkoutState.quantity;

        if (quantityInput) {
          quantityInput.value = checkoutState.quantity;
        }

        if (quantityHidden) {
          quantityHidden.value = checkoutState.quantity;
        }

        if (productHidden) {
          productHidden.value = checkoutState.productName;
        }

        if (unitPriceHidden) {
          unitPriceHidden.value = checkoutState.unitPrice;
        }

        if (totalHidden) {
          totalHidden.value = orderTotal;
        }

        if (productLabel) {
          productLabel.textContent = checkoutState.productLabel;
        }

        if (productName) {
          productName.textContent = checkoutState.productName;
        }

        if (productImage) {
          productImage.src = checkoutState.productImage;
          productImage.alt = checkoutState.productName + " product";
        }

        if (quantityImage) {
          quantityImage.src = checkoutState.productImage;
          quantityImage.alt = checkoutState.productName + " product";
        }

        if (quantityProduct) {
          quantityProduct.textContent = checkoutState.productName;
        }

        if (unitPrice) {
          unitPrice.textContent = formatRupees(checkoutState.unitPrice) + " each";
        }

        if (total) {
          total.textContent = formatRupees(orderTotal);
        }

        updateReview();
        updateStepUi();
      }

      function setStep(nextStep) {
        checkoutState.step = Math.min(Math.max(nextStep, 1), 3);
        setError("");
        updateReview();
        updateStepUi();
      }

      function validateAddressStep() {
        updateDeliveryEstimate();

        var fields = [
          "checkoutName",
          "checkoutPhone",
          "checkoutEmail",
          "checkoutAddress1",
          "checkoutCity",
          "checkoutState",
          "checkoutPincode"
        ];
        var firstInvalid = null;

        fields.forEach(function (fieldId) {
          var field = document.getElementById(fieldId);

          if (field && !field.checkValidity() && !firstInvalid) {
            firstInvalid = field;
          }
        });

        if (firstInvalid) {
          firstInvalid.reportValidity();
          firstInvalid.focus();
          setError(firstInvalid === checkoutPincode && checkoutPincode.validationMessage
            ? checkoutPincode.validationMessage
            : "Please complete the required delivery details.");
          return false;
        }

        return true;
      }

      function goToPayment() {
        var data = new FormData(checkoutForm);

        if (checkoutNext) {
          checkoutNext.disabled = true;
          checkoutNext.innerHTML = "<span>Opening Razorpay...</span>";
        }

        if (typeof Razorpay !== "function") {
          setError("Razorpay Checkout could not load. Please refresh and try again.");

          if (checkoutNext) {
            checkoutNext.disabled = false;
            updateStepUi();
          }

          return;
        }

        fetch("create-razorpay-order.php", {
          method: "POST",
          body: data,
          headers: {
            "Accept": "application/json"
          }
        })
          .then(function (response) {
            return response.json().catch(function () {
              return {};
            }).then(function (payload) {
              if (!response.ok || payload.success === false) {
                throw new Error(payload.message || "Could not start Razorpay payment.");
              }

              return payload;
            });
          })
          .then(function (payload) {
            var product = payload.product || {};
            var serverUnitPrice = Number(product.unit_price || checkoutState.unitPrice);
            var serverQuantity = Number(product.quantity || checkoutState.quantity);

            if (serverUnitPrice > 0) {
              checkoutState.unitPrice = serverUnitPrice;
            }

            if (serverQuantity > 0) {
              checkoutState.quantity = serverQuantity;
            }

            updateTotals();

            var razorpay = new Razorpay({
              key: payload.key,
              amount: payload.amount,
              currency: payload.currency || "INR",
              name: "Moscure",
              description: checkoutState.productName + " x " + checkoutState.quantity,
              image: "assets/img/moscure/favicon.png",
              order_id: payload.order_id,
              prefill: {
                name: document.getElementById("checkoutName").value.trim(),
                email: document.getElementById("checkoutEmail").value.trim(),
                contact: document.getElementById("checkoutPhone").value.trim()
              },
              notes: {
                product: checkoutState.productName,
                quantity: String(checkoutState.quantity),
                city: document.getElementById("checkoutCity").value.trim(),
                state: document.getElementById("checkoutState").value.trim(),
                pincode: document.getElementById("checkoutPincode").value.trim()
              },
              theme: {
                color: "#ed3142"
              },
              modal: {
                ondismiss: function () {
                  if (checkoutNext) {
                    checkoutNext.disabled = false;
                    updateStepUi();
                  }
                }
              },
              handler: function (response) {
                var verifyData = new FormData(checkoutForm);

                verifyData.append("razorpay_order_id", response.razorpay_order_id || "");
                verifyData.append("razorpay_payment_id", response.razorpay_payment_id || "");
                verifyData.append("razorpay_signature", response.razorpay_signature || "");

                fetch("verify-razorpay-payment.php", {
                  method: "POST",
                  body: verifyData,
                  headers: {
                    "Accept": "application/json"
                  }
                })
                  .then(function (verifyResponse) {
                    return verifyResponse.json().catch(function () {
                      return {};
                    }).then(function (verifyPayload) {
                      if (!verifyResponse.ok || verifyPayload.success === false) {
                        throw new Error(verifyPayload.message || "Payment verification failed.");
                      }

                      return verifyPayload;
                    });
                  })
                  .then(function (verifyPayload) {
                    var thankYouParams = new URLSearchParams();
                    var paymentId = verifyPayload.razorpay_payment_id || response.razorpay_payment_id || "";
                    var orderId = verifyPayload.razorpay_order_id || response.razorpay_order_id || "";

                    if (paymentId) {
                      thankYouParams.set("payment_id", paymentId);
                    }

                    if (orderId) {
                      thankYouParams.set("order_id", orderId);
                    }

                    if (verifyPayload.customer_mail_status) {
                      thankYouParams.set("mail", verifyPayload.customer_mail_status);
                    }

                    checkoutForm.reset();
                    checkoutState.step = 1;
                    updateTotals();
                    closeCheckout();
                    window.location.href = "thank-you.php" + (thankYouParams.toString() ? "?" + thankYouParams.toString() : "");
                  })
                  .catch(function (error) {
                    setError(error.message || "Payment verification failed. Please contact support.");
                    notify("Payment Verification Failed", error.message || "Please contact support.", "error");
                  })
                  .finally(function () {
                    if (checkoutNext) {
                      checkoutNext.disabled = false;
                      updateStepUi();
                    }
                  });
              }
            });

            razorpay.on("payment.failed", function (response) {
              var message = response.error && response.error.description
                ? response.error.description
                : "Payment failed. Please try again.";

              setError(message);
              notify("Payment Failed", message, "error");

              if (checkoutNext) {
                checkoutNext.disabled = false;
                updateStepUi();
              }
            });

            razorpay.open();
          })
          .catch(function (error) {
            setError(error.message || "Could not start Razorpay payment. Please try again.");

            if (checkoutNext) {
              checkoutNext.disabled = false;
              updateStepUi();
            }
          });
      }

      function openCheckout(button) {
        lastFocusedElement = document.activeElement;
        checkoutState.productName = button.getAttribute("data-product-name") || checkoutState.productName;
        checkoutState.productLabel = button.getAttribute("data-product-label") || checkoutState.productLabel;
        checkoutState.productImage = button.getAttribute("data-product-image") || checkoutState.productImage;
        checkoutState.unitPrice = Number(button.getAttribute("data-product-price")) || checkoutState.unitPrice;
        checkoutState.quantity = 1;
        checkoutState.step = 1;

        checkoutForm.reset();
        updateTotals();
        updateDeliveryEstimate();
        setError("");
        checkoutModal.classList.add("is-open");
        checkoutModal.setAttribute("aria-hidden", "false");
        document.body.classList.add("moscure-checkout-locked");
      }

      function closeCheckout() {
        checkoutModal.classList.remove("is-open");
        checkoutModal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("moscure-checkout-locked");

        if (checkoutNext) {
          checkoutNext.disabled = false;
        }

        if (lastFocusedElement && typeof lastFocusedElement.focus === "function") {
          lastFocusedElement.focus();
        }
      }

      window.openMoscureCheckoutFromButton = openCheckout;

      buyButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
          event.preventDefault();
          openCheckout(button);
        });
      });

      closeButtons.forEach(function (button) {
        button.addEventListener("click", closeCheckout);
      });

      checkoutModal.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
          closeCheckout();
        }
      });

      checkoutModal.querySelectorAll("[data-quantity-change]").forEach(function (button) {
        button.addEventListener("click", function () {
          checkoutState.quantity = cleanQuantity(checkoutState.quantity + Number(button.getAttribute("data-quantity-change")));
          updateTotals();
        });
      });

      if (quantityInput) {
        quantityInput.addEventListener("input", function () {
          checkoutState.quantity = cleanQuantity(quantityInput.value);
          updateTotals();
        });
      }

      if (checkoutPhone) {
        checkoutPhone.addEventListener("input", function () {
          checkoutPhone.value = checkoutPhone.value.replace(/\D/g, "").slice(0, 10);
        });
      }

      if (checkoutPincode) {
        checkoutPincode.addEventListener("input", function () {
          checkoutPincode.value = checkoutPincode.value.replace(/\D/g, "").slice(0, 6);
          updateDeliveryEstimate();
          updateReview();
        });

        checkoutPincode.addEventListener("blur", updateDeliveryEstimate);
      }

      checkoutForm.querySelectorAll("input, select").forEach(function (field) {
        field.addEventListener("input", updateReview);
        field.addEventListener("change", updateReview);
      });

      if (checkoutBack) {
        checkoutBack.addEventListener("click", function () {
          setStep(checkoutState.step - 1);
        });
      }

      if (checkoutNext) {
        checkoutNext.addEventListener("click", function () {
          if (checkoutState.step === 1) {
            checkoutState.quantity = cleanQuantity(quantityInput ? quantityInput.value : checkoutState.quantity);
            updateTotals();
            setStep(2);
            return;
          }

          if (checkoutState.step === 2) {
            if (validateAddressStep()) {
              setStep(3);
            }
            return;
          }

          if (validateAddressStep()) {
            goToPayment();
          }
        });
      }

      checkoutForm.addEventListener("submit", function (event) {
        event.preventDefault();
      });

      updateTotals();
      loadPincodeRanges();
    }

    document.addEventListener("click", function (event) {
      var buyButton = event.target.closest(".moscure-buy-now");

      if (buyButton && typeof window.openMoscureCheckoutFromButton === "function") {
        event.preventDefault();
        window.openMoscureCheckoutFromButton(buyButton);
      }
    });

    var subscribeForm = document.getElementById("subscribe-form");

    if (subscribeForm) {
      subscribeForm.addEventListener("submit", function (event) {
        event.preventDefault();
        subscribeForm.reset();
        notify("Thank You!", "You have been added to Moscure updates.", "success");
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initMoscurePage);
  } else {
    initMoscurePage();
  }
})();
