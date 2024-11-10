/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Invoice create init Js File
*/

function new_link() {
  var count = $("tr.product").length;
  count++;
  var tr1 = document.createElement("tr");
  tr1.id = count;
  tr1.className = "product";

  const row = `
              <tr id="${count}" class="product">
                  <th scope="row" class="product-id align-items-center">${count}</th>
                  <td class="text-start">
                      <div class="mb-2">
                          <input type="text" class="form-control bg-light border-0"
                              placeholder="Task Title" name="task_title" required />
                          <div class="invalid-feedback">
                              Please enter a product title
                          </div>
                      </div>
                  </td>
                  <td>
                      <select class="form-select" data-choices data-choices-search-false
                           name="task_status">
                          <option value="todo" selected>Todo</option>
                          <option value="processing">Processing</option>
                          <option value="done">Done</option>
                      </select>
                  </td>
                  <td>
                      <input type="text" class="form-control bg-light border-0"
                          placeholder="h:m" name="task_eta" required />
                  </td>
                  <td>
                      <input type="text" class="form-control bg-light border-0"
                          placeholder="h:m" name="task_time_spend" required />
                  </td>
                  <td class="product-removal text-center">
                      <a href="javascript:void(0)" class="btn btn-success">Delete</a>
                  </td>
              </tr>`;

  tr1.innerHTML = document.getElementById("newForm").innerHTML + row;

  document.getElementById("newlink").appendChild(tr1);
  var genericExamples = document.querySelectorAll("[data-trigger]");
  Array.from(genericExamples).forEach(function (genericExamp) {
    var element = genericExamp;
    new Choices(element, {
      placeholderValue: "This is a placeholder set in the config",
      searchPlaceholderValue: "This is a search placeholder",
    });
  });
}

$tasksEl = $("[name='content']");
function inputTasksValues($this) {
  const $taskRow = $this.closest("tr.product"); // Cache the closest row
  const taskId = $taskRow.attr("id") - 1; // Get the task ID
  const fieldName = $this.attr("name"); // Get the field name
  const fieldValue = $this.val(); // Get the field value
  let tasksArr = [];

  if ($tasksEl.val()) {
    tasksArr = JSON.parse($tasksEl.val());
    if (!tasksArr[taskId]) {
      tasksArr.push({});
    }
    tasksArr[taskId][fieldName] = fieldValue;
  } else {
    const obj = {};
    obj[fieldName] = fieldValue;
    tasksArr.push(obj);
  }

  $tasksEl.val(JSON.stringify(tasksArr));
}

$(document).on(
  "keyup",
  "[name='task_title'], [name='task_eta'], [name='task_time_spend']",
  function () {
    const $this = $(this);
    inputTasksValues($this);
  }
);
$(document).on("change", "[name='task_status']", function () {
  const $this = $(this);
  inputTasksValues($this);
});

remove();
/* Set rates + misc */
var taxRate = 0.125;
var shippingRate = 65.0;
var discountRate = 0.15;

function remove() {
  $(document).on("click", "tbody tr td.product-removal a", function () {
    const $this = $(this);
    const row = $this.closest("tr");
    const rowIndex = row.index();
    if ($tasksEl.val()) {
      tasksArr = JSON.parse($tasksEl.val());
      let updatedTasks = tasksArr
        .slice(0, rowIndex)
        .concat(tasksArr.slice(rowIndex + 1));
      $tasksEl.val(JSON.stringify(updatedTasks));
      row.remove();
      resetRow();
    }
  });
}

function resetRow() {
  console.log("Resetting row...");
  $("tr.product").each(function (index, el) {
    var incid = index + 1;
    $(el).attr("id", incid);
  });
  console.log($tasksEl.val());
}

/* Recalculate cart */
function recalculateCart() {
  var subtotal = 0;

  Array.from(document.getElementsByClassName("product")).forEach(function (
    item
  ) {
    Array.from(item.getElementsByClassName("product-line-price")).forEach(
      function (e) {
        if (e.value) {
          subtotal += parseFloat(e.value.slice(1));
        }
      }
    );
  });

  /* Calculate totals */
  var tax = subtotal * taxRate;
  var discount = subtotal * discountRate;

  var shipping = subtotal > 0 ? shippingRate : 0;
  var total = subtotal + tax + shipping - discount;

  document.getElementById("cart-subtotal").value =
    paymentSign + subtotal.toFixed(2);
  document.getElementById("cart-tax").value = paymentSign + tax.toFixed(2);
  document.getElementById("cart-shipping").value =
    paymentSign + shipping.toFixed(2);
  document.getElementById("cart-total").value = paymentSign + total.toFixed(2);
  document.getElementById("cart-discount").value =
    paymentSign + discount.toFixed(2);
  document.getElementById("totalamountInput").value =
    paymentSign + total.toFixed(2);
  document.getElementById("amountTotalPay").value =
    paymentSign + total.toFixed(2);
}

/* Update quantity */
function updateQuantity(amount, itemQuntity, priceselection) {
  var linePrice = amount * itemQuntity;
  /* Update line price display and recalc cart totals */
  linePrice = linePrice.toFixed(2);
  priceselection.value = paymentSign + linePrice;

  // recalculateCart();
}
