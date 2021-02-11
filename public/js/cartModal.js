var vue_cart_modal = new Vue({
    el: '#cartModal',
    mixins: [cartModule], //mixin script load in layout master
    data: {
      newProductAddToCart: false
    },
    methods: {
      addQuantityCart: function(item) {
        this.addQuantityCartMaster(item); //method mixin
        if (vue_cart_page) {
          vue_cart_page.changeProductCartInModal = !vue_cart_page.changeProductCartInModal
        }
      },
      updateProductCart: function(product) {
        this.updateProductCartMaster(product) //method mixin
        if (vue_cart_page) {
          vue_cart_page.changeProductCartInModal = !vue_cart_page.changeProductCartInModal
        }
      },
      deleteProductInCart: function(key, item) {
        this.deleteProductInCartMaster(key, item) //method mixin
        if (vue_cart_page) {
          vue_cart_page.changeProductCartInModal = !vue_cart_page.changeProductCartInModal
        }
      }
    },
    watch: {
      newProductAddToCart: function(val, oldVal) {

        let inCartStorage = localStorage.getItem('cart')
          if(inCartStorage) {
            this.cart = JSON.parse(localStorage.getItem('cart'))
          } else {
            this.cart = []
          }
          
        this.loadCartStorage()
      }
    }
});
