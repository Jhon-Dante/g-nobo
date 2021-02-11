const dateTime = {}

function convertToLatinFormat(dateTime) {
    return moment(dateTime).format('DD-MM-YYYY HH:mm:ss') 
}

dateTime.install = function (Vue) {
    Vue.filter('dateTime', (val) => {
        return convertToLatinFormat(val)
    })
}

export default dateTime
