
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require("materialize-css");

window.Vue = require('vue');
window.moment = require("moment");

// Filters
import date from './filters/date'
import dateTime from './filters/date-time';
import money from './filters/money'
import amountInLetters from './filters/amount-in-letters'
import asset from './filters/asset'
import image from './filters/image'
import store from './store'
import 'vue-search-select/dist/VueSearchSelect.css'
import { ModelSelect } from 'vue-search-select'

Vue.use(date)
Vue.use(dateTime)
Vue.use(money)
Vue.use(amountInLetters)
Vue.use(asset)
Vue.use(image)

import swal from "sweetalert";

window.showLoading = function () {
    $("#prepage-loader").fadeIn('fast');
};

window.quiLoading = function() {
  $("#prepage-loader").fadeOut("fast", () => {
      $("#prepage-loader").hide();
  });
};

window.pickDateI18n = {
    nextMonth: 'Mes siguiente',
    previousMonth: 'Mes anterior',
    // Months and weekdays
    months: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
    monthsShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
    weekdays: [ 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado' ],
    weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab' ],
    // Materialize modified
    weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
    // Today and clear
    today: 'Hoy',
    clear: 'Limpiar',
    done: 'Aceptar'
}

window.minYear = 2000;
window.maxYear = moment().format('YYYY')

// Format Number
Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
Vue.component('model-select', ModelSelect);

// Input masked
import MaskedInput from 'vue-text-mask'
Vue.component('masked-input', MaskedInput);

// Cards
Vue.component("card-main", require("./components/card/CardMain.vue"));
Vue.component("card-content", require("./components/card/CardContent.vue"));
Vue.component("card-title", require("./components/card/CardTitle.vue"));

// Inputs
Vue.component("input-autocomplete", require("./components/inputs/InputAutocomplete"));
Vue.component("input-file", require("./components/inputs/InputFile"));
Vue.component("input-document", require("./components/inputs/InputDocument"));

// Table
Vue.component('new-table-byte', require("./components/table/NewTable"));
Vue.component('table-byte', require("./components/table/Table"));
Vue.component("table-row", require("./components/table/TableRow"));
Vue.component('table-head', require("./components/table/TableHead"));
Vue.component("table-cell", require("./components/table/TableCell"));

// Modal
Vue.component("byte-modal", require("./components/modal/Modal"));

// Login
Vue.component('login-form', require('./components/login/FormLogin.vue'));

// Size
Vue.component('size-index', require('./components/sizes/SizeIndex'));

// Category
Vue.component('categoy-index', require('./components/categories/CategoryIndex'));

// Collection
Vue.component('collection-index', require('./components/collection/CollectionIndex'));

// Design
Vue.component('design-index', require('./components/designs/DesignIndex'));

// Product
Vue.component('product-index', require('./components/products/ProductIndex'));

//Replenishments
Vue.component('replenishments-index', require('./components/replenishments/index'));

// Exchange Rate
Vue.component('change-index', require('./components/exchange_rate/ChangeIndex'));
Vue.component('change-form', require('./components/exchange_rate/ChangeForm'));

// Us
Vue.component('us-index', require('./components/us/UsIndex'));

// Allies
Vue.component('allies-index', require('./components/allies/AlliesIndex'));

//Blogs
Vue.component('blog-index', require('./components/blog/BlogIndex'));
Vue.component('blog-create', require('./components/blog/BlogCreate'));
Vue.component('blog-edit', require('./components/blog/BlogEdit'));

//Wholesalers
Vue.component('wholesaler-index', require('./components/wholesalers/WholesalerIndex'));
Vue.component('wholesaler-edit', require('./components/wholesalers/WholesalerEdit'));

// Profile
Vue.component('profile-form', require('./components/profile/ProfileForm'));

// Purchase
Vue.component('purchase-index', require('./components/purchase/PurchaseIndex'));

// Report
Vue.component('purchases-report', require('./components/reports/Purchases'));
Vue.component('orders-report', require('./components/reports/Orders'));
Vue.component('products-report', require('./components/reports/Products'));

// Clients
Vue.component('client-index', require('./components/clients/ClientIndex'));
Vue.component('client-edit', require('./components/clients/ClientEdit'));

// Banners
Vue.component('banners-index', require('./components/banners/BannerIndex'));
Vue.component('banners-create', require('./components/banners/BannerCreate'));

// Bank Accounts
Vue.component('bank-index', require('./components/banks/BankIndex'))

Vue.component('social-index', require('./components/social/SocialIndex'));
Vue.component('social-edit',  require('./components/social/SocialEdit'));

Vue.component('term-index',  require('./components/terms/TermIndex'));
Vue.component('municipality-index',  require('./components/municipalities/Index'));
Vue.component('municipality-form',  require('./components/municipalities/Form'));
Vue.component('municipality-edit',  require('./components/municipalities/Edit'));

Vue.component('estado-index', require('./components/estados/Index'));
Vue.component('offer-index', require('./components/offers/Index'));
Vue.component('offer-edit', require('./components/offers/Edit'));
Vue.component('offer-create', require('./components/offers/Form'));

Vue.component('discount-index', require('./components/discounts/Index'));
Vue.component('discount-edit', require('./components/discounts/Edit'));
Vue.component('discount-create', require('./components/discounts/Form'));

Vue.component('taxe-index', require('./components/taxes/Index'));
Vue.component('taxe-edit', require('./components/taxes/Edit'));
Vue.component('taxe-create', require('./components/taxes/Form'));

// Shipping Fees
Vue.component('shipping-fees-index', require('./components/shipping-fees/ShippingFeesIndex'));

// Promotions
Vue.component('promotions-index', require('./components/promotions/PromotionIndex'));
Vue.component('promotions-create', require('./components/promotions/PromotionForm'));
Vue.component('promotions-edit', require('./components/promotions/PromotionEdit'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store
});
