let getPrice = {};

function calcOffer(price, percentage) {
  var percentageOffer = (percentage / 100) * price;
  return price - percentageOffer;
}

getPrice.install = function(Vue) {
  Vue.prototype.$getPrice = function(product, withOffer) {
    if (typeof withOffer === "undefined") {
      var withOffer = false;
    }
    var priceBase;
    if (product.variable) {
      priceBase = product.amounts.find(function(amount) {
        return amount.id == product.amount_id;
      }).price;
    } else {
      priceBase = product.amounts[0].price;
    }

    let price = priceBase;
    if (withOffer && product.offer) {
      price = calcOffer(priceBase, product.offer.percentage);
    }

    return price;
  };
};

let getPriceByAmount = {};
let getPriceByAmountFuntion = function(product, amount) {
  let priceBase = amount.price;
  let price = (priceBase = priceBase);
  if (product.offer) {
    price = calcOffer(priceBase, product.offer.percentage);
  }

  return price;
};

getPriceByAmount.install = function(Vue) {
  Vue.prototype.$getPriceByAmount = getPriceByAmountFuntion;
};

let diffDaysOffer = {};

diffDaysOffer.install = function(Vue) {
  Vue.prototype.$diffDaysOffer = function(endDate) {
    let diffDays = moment(endDate).diff(moment(), "days");
    return diffDays == 0 ? 1 : diffDays;
  };
};

let getPriceCart = {};

getPriceCart.install = function(Vue) {
  Vue.prototype.$getPriceCart = function(item) {
    let discount = item.producto.discount;
    let quantity = item.cantidad;
    let priceBase = getPriceByAmountFuntion(item.producto, item.amount);
    let price = priceBase * item.cantidad;

    if (
      discount &&
      quantity >= discount.quantity_product &&
      (item.discountAviable || item.producto.isPromotion)
    ) {
      // Si tiene descuento
      let amountBaseDiscount = priceBase * discount.quantity_product;
      let amountDiscount = (discount.percentage / 100) * amountBaseDiscount;
      price = price - amountDiscount;
    }

    return price;
  };
};

Vue.use(getPriceCart);
Vue.use(diffDaysOffer);
Vue.use(getPrice);
Vue.use(getPriceByAmount);
