var vue_sidebar = new Vue({
    el: '#sidebar',
    data: {
        cart: countCart,
        isOpen: false,
        subtotal: 0,
        isCartOpen: false,
        categoriesFilters: [],
        category_selected: null
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
            if(window.innerWidth > 1024) {
                return
            }
            $('.shop-cart-container').animate({
                right: this.isCartOpen ? '-425px' : '0px'
            },250);
            this.isCartOpen = !this.isCartOpen
        },
        entrar: function() {
            vue_header.filtro()
            $('#loginModal').modal('show')
        },
        selectCategorie: function(categoryId) {
            this.category_selected = categoryId == this.category_selected ? null : categoryId
        },
        filter: function(category, subcategory) {
            window.location = homeUrl + '?category_id=' + category + '&' + 'subcategory_id=' + subcategory
        },
        showPro: function() {
            window.location = homeUrl + '?showPro=1'
        }
    },
    mounted: function() {
        axios.post(homeUrl + '/tienda/filters')
            .then(function(res) {
                vue_sidebar.categoriesFilters = res.data.filters.map(function(category) {
                    category.open = false
                    return category
                })
            }).catch(function(err) {
                console.log('err', err)
            })
    }
});