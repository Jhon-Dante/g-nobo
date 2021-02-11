<div id="cartModal" class="modal right fade cart-modal"
  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document"
    style="position: absolute;top: 0;right: 0;margin-top: 0;">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          <span class="font-bold total-products" v-cloak>
            @{{cartList.length}} Producto(s)
          </span>
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="list-cart">
            <template v-for="(item, index) in cartList">
              <div class="shop-cart-item">
                <div class="shop-cart-item-quit">
                  <button v-on:click="deleteProductInCart(index, item)">
                    x
                  </button>
                </div>
                <div class="shop-cart-item-photo"
                  :style="{ backgroundImage: 'url(' + item.image + ')' }"></div>
                <div class="shop-cart-item-data">
                  <div class="shop-cart-item-data-name" v-cloak>
                    @if (\App::getLocale() == 'es')
                      @{{ item.producto.name }}
                    @else
                      @{{ item.producto.name_english }}
                    @endif
                  </div>
                  <div class="shop-cart-item-data-unitary">
                    <span class="through" v-if="item.producto.offer && currencyCart == 1">
                      @{{ getPriceByCurrencyCart(item.amount.price, item.producto.coin) | VES }}</span>

                    <span v-if="currencyCart == 1" v-cloak>
                      <span class="font-bold" v-if="item.producto.offer">
                        -@{{ item.producto.offer.percentage }}%
                      </span>
                      @{{ getPriceByCurrencyCart($getPriceByAmount(item.producto, item.amount), item.producto.coin) | VES }}
                    </span>

                    <span class="through" v-if="item.producto.offer && currencyCart == 2">
                      @{{ getPriceByCurrencyCart(item.amount.price, item.producto.coin) | USD }}</span>

                    <span v-if="currencyCart == 2" v-cloak>
                      <span class="font-bold" v-if="item.producto.offer">
                        -@{{ item.producto.offer.percentage }}%
                      </span>
                      @{{ getPriceByCurrencyCart($getPriceByAmount(item.producto, item.amount), item.producto.coin) | USD }}
                    </span>

                    <span v-if="item.producto.variable" v-cloak> -
                      @{{ item.amount.presentation }} @{{ getUnitCart(item.amount.unit) }}
                    </span>
                    <div class="shop-cart-item-data-taxe" style="display:none">
                      @{{ item.producto.taxe ? item.producto.taxe.name : 'Exento de IVA' }}
                    </div>
                  </div>
                  <div class="shop-cart-item-data-buttons">
                    <div class="shop-cart-item-data-controls"
                      :class="{ 'shop-cart-item-data-controls--error': item.amount.amount < item.cantidad }">
                      <button class="shop-cart-item-data-controls--add"
                        v-on:click="
                        item.cantidad > 1 && (!item.producto.isPromotion || item.producto.isPromotion && item.cantidad > item.producto.promotion.amount)
                          ? item.cantidad--
                          : null;
                        updateProductCart(item)">-</button>
                      <input type="number" v-model="item.cantidad"
                      max="99" width="20" min="1" v-on:blur="updateProductCart(item)"
                      v-on:keyup="checkquantityCart($event, index)">
                      <button class="shop-cart-item-data-controls--sub"
                        v-on:click="addQuantityCart(item)">+</button>
                    </div>
                    <span v-if="currencyCart == 1" class="shop-cart-item-data-price" v-cloak>
                      @{{ getPriceByCurrencyCart($getPriceCart(item), item.producto.coin) | VES }}
                    </span>
                    <span v-if="currencyCart == 2" class="shop-cart-item-data-price" v-cloak>
                      @{{ getPriceByCurrencyCart($getPriceCart(item), item.producto.coin) | USD }}
                    </span>

                  </div>
                  <span class="shop-cart-item-data-discount"
                    v-if="item.producto.discount && item.cantidad >= item.producto.discount.quantity_product && item.discountAviable && !item.producto.isPromotion"
                  >
                    @{{ item.producto.discount.quantity_product }} x @{{ item.producto.discount.percentage }}% dcto.
                  </span>
                  <span class="shop-cart-item-data-discount"
                    v-if="item.producto.isPromotion">
                    @{{ item.producto.promotion.amount }} x @{{ item.producto.promotion.discount_percentage }}% dcto.
                    <br>
                  </span>
                  <span v-show="item.amount.amount < item.cantidad && !item.isPromotion" class="shop-cart-item-data-error">Cantidad no disponible</span>
                </div>
              </div>
            </template>
            <div v-if="cartList.length == 0">
              No hay productos en el carrito.
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-12">
            <div class="d-flex flex-row-reverse shop-cart-subtotal">
              <span>
                <span class="font-bold shop-cart-subtotal-text">Subtotal</span>
                <span v-if="currencyCart == 1" class="shop-cart-subtotal-price" v-cloak>
                  @{{ getSubtotalCart() | VES }}
                </span>
                <span v-if="currencyCart == 2" class="shop-cart-subtotal-price" v-cloak>
                  @{{ getSubtotalCart() | USD }}
                </span>
              </span>
            </div>
            <div class="d-flex flex-row-reverse shop-cart-discount"
              v-if="hasMinimumDiscountCart" v-cloak>
              <span>
                <span class="font-bold shop-cart-discount-text" v-cloak>
                  -@{{ minimumDiscountCart.percentage }}% @{{ minimumDiscountCart.name }}:
                  <span v-if="currencyCart == 1" class="shop-cart-subtotal-price" v-cloak>
                    -@{{ getPriceByCurrencyCart(getMinimumDiscountCart(), 2) | VES }}
                  </span>
                  <span v-if="currencyCart == 2" class="shop-cart-subtotal-price" v-cloak>
                    -@{{ getMinimumDiscountCart() | USD }}
                  </span>
                </span>
              </span>
            </div>
            <div class="d-flex flex-row-reverse shop-cart-discount"
              v-if="quantityDiscountCart && getSubtotalUsdCart() > 0" v-cloak>
              <span>
                <span class="font-bold shop-cart-discount-text" v-cloak>
                  -@{{ quantityDiscountCart.percentage }}% @{{ quantityDiscountCart.name }}:
                  <span v-if="currencyCart == 1" class="shop-cart-subtotal-price" v-cloak>
                    -@{{ getPriceByCurrencyCart(quantityPurchaseDiscount, 2) | VES }}
                  </span>
                  <span v-if="currencyCart == 2" class="shop-cart-subtotal-price" v-cloak>
                    -@{{ quantityPurchaseDiscount | USD }}
                  </span>
                </span>
              </span>
            </div>
            <div class="d-flex flex-row-reverse shop-cart-subtotal"
              v-if="hasMinimumDiscountCart || quantityDiscountCart && getSubtotalUsdCart() > 0">
              <span>
                <span class="font-bold shop-cart-subtotal-text">Subtotal descuento:</span>
                <span v-if="currencyCart == 1" class="shop-cart-subtotal-price" v-cloak>
                  @{{ getTotalCart() | VES }}
                </span>
                <span v-if="currencyCart == 2" class="shop-cart-subtotal-price" v-cloak>
                  @{{ getTotalCart() | USD }}
                </span>
              </span>
            </div>
            <span
              class="d-flex flex-row-reverse text-danger text-sm pr-3 shop-cart-minimum"
              v-if="currencyCart == 1 && getTotalCart() < minimunCart" v-cloak>
              @{{ minimunCart | VES }} mínimo de compra
            </span>
            <span
            class="d-flex flex-row-reverse text-danger text-sm pr-3 shop-cart-minimum"
              v-if="currencyCart == 2 && getTotalCart() < minimunCart" v-cloak>
              @{{ minimunCart | USD }} mínimo de compra
            </span>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
          <div class="col-12">
            @guest
              <a class="btn btn-primary mt-2" href="#"
              data-target="#loginModal" data-toggle="modal">Continuar</a>
            @else
            <a href="#!" :disabled="disabledBtnVerifyCart"
              class="btn btn-success bt-cart mt-2"
              v-on:click="verifyCart('{{ url('verificacion') }}')">
              Continuar
            </a>
            @endguest
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
