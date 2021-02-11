const img = {}

function getUrlImage(img) {
    return urlBase + img
}

img.install = function (Vue) {
    Vue.filter('img', (val) => {
        return getUrlImage(val)
    })
}

export default img