const asset = {}

function getUrlAsset(asset) {
    return urlBase + asset
}

asset.install = function (Vue) {
    Vue.filter('asset', (val) => {
        return getUrlAsset(val)
    })
}

export default asset
