var vue_header = new Vue({
    el: '#vue_header',
    data: {
        cart: countCart,
        isOpen: false,
        subtotal: 0,
        isCartOpen: false,
        isVisible: false,
        suggestions: [],
        categories: [],
        subcategories: [],
        timer: null,
        query: '',
        currency: currencySession, //currencySession in layout master defined
        loadQuery: false,
        queryLoaded: false
    },
    methods: {
        filtro: function() {
            if(this.isOpen) {
                this.close()
            }else {
                this.open()
            }

            this.isOpen = !this.isOpen
        },
        close: function() {
            this.isOpen = false;
        },
        open: function() {
            $('.filtro').fadeIn();
            $('.filtro-container').animate({
                left: '0px'
            },250);
        },
        close: function() {
            $('.filtro').fadeOut();
            $('.filtro-container').animate({
                left: '-500px'
            },250);
        },
        toggleCart: function() {
            /*
            if(window.innerWidth > 1024) {
                return
            }*/
            $('.shop-cart-container').animate({
                right: this.isCartOpen ? '-425px' : '0px'
            },250);
            this.isCartOpen = !this.isCartOpen
            $('#cartModal').modal('show');
        },
        responsiveHeader: function() {
            this.isVisible = !this.isVisible
        },
        autocomplete: function(event) {
            clearTimeout(this.timer);
            this.loadQuery = true;
            const self = this;
            this.suggestions = [];
            if (this.query.length > 0) {
                this.timer = setTimeout(function() {
                    axios.post('tienda/autocomplete',{
                        search: self.query
                    }).then(function(res) {
                        if (res.data) {
                            self.suggestions = res.data.suggestions;
                            if(res.data.categories){
                                self.categories = res.data.categories;
                            }
                            if(res.data.subCategories){
                                self.subcategories = res.data.subCategories
                            }
                        }
                    }).catch(function(err) {
                        console.log(err);
                    }).finally(function() {
                        self.loadQuery = false;
                        self.queryLoaded = true;
                    });
                },1000);
            }
            else {
                this.suggestions = [];
                this.loadQuery = false;
            }
        },
        quitSugestions: function() {
            this.suggestions = []
            this.queryLoaded = false
            this.loadQuery = false
        }
    }
});
