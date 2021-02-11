const date = {}

function convertToLatinFormat(date) {
    return moment(date).format('DD-MM-YYYY') 
}

date.install = function (Vue) {
    Vue.filter('date', (val) => {
        return convertToLatinFormat(val)
    })
}

export default date
