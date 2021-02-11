//mixins construct for cart in landing

const cartModule = {
  data: {
    cartList: [],
    currencyCart: currentCurrency,
    minimumDiscountCart: null,
    quantityDiscountCart: null,
    exchangeCart: null,
    minimunPurchaseCart: 0,
    disabledBtnVerifyCart: true,
    unitiesCart: [
			{name: 'Gr', id: 1},
			{name: 'Kg', id: 2},
			{name: 'Ml', id: 3},
			{name: 'L', id: 4},
			{name: 'Cm', id: 5}
		]
  },
  created: function() {
    this.initialConfigurationCart()
    this.loadCartStorage()
  },
  mounted: function() {
    this.disabledBtnVerifyCart = false
  },
  computed: {
    minimunCart: function() {
      return parseFloat(this.getPriceByCurrencyCart(this.minimunPurchaseCart, 2))
    },
    hasMinimumDiscountCart: function() {
      return this.minimumDiscountCart && this.getSubtotalUsdCard() >= this.minimumDiscountCart.minimum_purchase
    },
    quantityPurchaseDiscountCart: function() {
      let total = this.getSubtotalUsdCart()
      let percentage = this.quantityDiscountCart.percentage
      return total * (percentage / 100)
    },
  },
  methods: {
    loadCartStorage: function() {
      let inCartStorage = localStorage.getItem('cart')
      if(inCartStorage) {
        inCartStorage = JSON.parse(localStorage.getItem('cart'))
        vue_header.cart = inCartStorage.length
        this.cartList = inCartStorage
      } else {
        this.cartList = []
      }
      let subtotal = this.getSubtotalCart()
    },
    updateCartStorage: function() {
      localStorage.setItem('cart', JSON.stringify(this.cartList))
    },
    getTotalCart: function() {
      var subtotal = this.getSubtotalUsdCart()

      if(this.hasMinimumDiscountCart) {
        subtotal = subtotal - this.getMinimumDiscountCart()
      }

      if(this.quantityDiscountCart) {
        subtotal = subtotal - this.quantityPurchaseDiscountCart
      }

      return this.getPriceByCurrencyCart(subtotal, '2')
    },
    getSubtotalCart: function() {
      var total = 0;
      var that = this
      this.cartList.forEach(function(item, index) {
        let priceCart = that.$getPriceCart(item)
        let pricePriceByCurrency = that.getPriceByCurrencyCart(priceCart, item.producto.coin)
        total += parseFloat(pricePriceByCurrency.toFixed(2));
      });
      vue_header.subtotal = total;
      return total;
    },
    getSubtotalUsdCart: function() {
      var total = 0;
      var that = this;
      this.cartList.forEach(function(item, index) {
        let priceCart = that.$getPriceCart(item)
        total += priceCart;
      });
      // vue_header.subtotal = total;
      return total;
    },
    getMinimumDiscountCart: function() {
        if (this.minimumDiscountCart) {
          let total = this.getSubtotalUsdCart()
          let percentage = this.minimumDiscountCart.percentage
          return total * (percentage / 100)
        }
        return 0
    },
    getPriceByCurrencyCart: function(precio, coin) {
      var price = precio;
      if (coin == '1' && this.currencyCart == '2' && this.exchangeCart) {
        price = price / this.exchangeCart;
      }
      else if (coin == '2' && this.currencyCart == '1' && this.exchangeCart) {
        price = price * this.exchangeCart;
      }
      return price;
    },
    initialConfigurationCart: function() {
      axios.get(homeUrl + '/api/configuration-cart')
      .then(res => {
        if(res && res.data) {
          this.minimunPurchaseCart = res.data.minimunPurchase;
          this.exchangeCart = res.data.exchangeRate;
          this.minimumDiscountCart = res.data.minimumDiscount;
          this.quantityDiscountCart = res.data.quantityDiscount;
        }
      })
      .catch((error) => {
        console.log(error)
      });
    },
    addQuantityCartMaster: function(item) {
      if(
        item.amount.amount == item.cantidad ||
        item.cantidad == item.amount.max
      ) {
        return
      }

      item.cantidad++;
      this.updateProductCartMaster(item)
    },
    deleteProductInCartMaster: function(key, item) {
      setLoader();
      let newCart = []
      if(item.producto.isPromotion) {
        const promotion = this.promotions.find(element => element.id == item.producto.promotion.id)
        if(promotion) {
          const ids = promotion.products.map(element => {
            return element.product_id
          });
          this.cartList.map(element => {
            const toDelete = ids.find(id => {
              return id == element.amount_id
            })
            if(!toDelete){
              newCart.push(element)
            }
          });
          this.cartList = newCart
        }
      } else {
        var newArray = this.cartList.filter(function(item, k) {
          return key != k
        })
        newCart = newArray
        this.cartList = newArray
      }
      localStorage.setItem('cart', JSON.stringify(newCart))
      vue_header.cart = this.cartList.length
      quitLoader();
    },
    updateProductCartMaster: function(producto) {
      if(!producto.cantidad) {
        return
      }
      this.updateCartStorage()
    },
    checkquantityCart: function(event, key) {
      const value = event.target.value
      if(value <= 0) {
        const item = this.cart[key]
        item.cantidad = 1
        this.cartList[key] = item
      }
    },
    verifyCart: function(url) {
      if(this.getTotalCart() < this.minimunCart) {
        this.messageMinimunCart()
        return
      }

      const errorMinItem = this.cartList.find(function(item) {
        return item.cantidad < item.amount.min;
      })

      if(errorMinItem) {
        let productName = errorMinItem.producto.name
        let min = errorMinItem.amount.min
        if(errorMinItem.producto.variable) {
          productName = productName + ' ' + errorMinItem.amount.presentation + this.getUnitCart(errorMinItem.amount.unit);
        }

        swal('','No se puede procesar la compra porque el mínimo de compra de ' + productName + ' es de: ' + min + ' unidades','warning');
        return
      }

      window.location = url
    },
    getUnitCart: function(unit) {
      return this.unitiesCart.find(function(u) { return u.id == unit }).name
    },
    messageMinimunCart: function() {
      const restante = (this.minimunCart - this.getTotalCart()).toFixed(2)
      let faltanteText = ''

      if(this.currencyCart == 1) {
        faltanteText = this.$options.filters.VES(restante)
      } else {
        faltanteText = this.$options.filters.USD(restante)
      }

      swal('', 'Agregue ' + faltanteText + ' para llegar al mínimo de compra', 'warning')
    },
    getUnitCart: function(unit) {
      return this.unitiesCart.find(function(u) { return u.id == unit }).name
    },
  },
  watch:{
    cartList: function(val, oldVal) {
      this.updateCartStorage()
    }
  }
}
