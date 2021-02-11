Vue.filter('number',function(value) {
	if (value == '')
		return '';
	return formatMoney(parseFloat(value));
});

Vue.filter('VES',function(value) {
	if (value == '')
		return '';
	return formatMoney(parseFloat(value)) + ' Bs.';
});

Vue.filter('USD',function(value) {
	if (value == '')
		return '$0'
	if(value == 0) 
		return '$0'
	return '$ ' + formatMoney(parseFloat(value));
});

Vue.filter('date',function(value) {
	if (value == '')
		return '';
	return moment(value).format('DD/MM/YYYY');
});

Vue.filter('datetime',function(value) {
	if (value == '')
		return '';
	return moment(value).format('DD/MM/YYYY LT');
});

Vue.filter('metodo',function(value) {
	if (value == '')
		return '';
	let respuesta = "";
	value = parseInt(value);
	switch (value) {
		case 1:
			respuesta = "Transferencia";
			break;
		case 2:
			respuesta = "Pago Movil";
			break;
		case 3:
			respuesta = 'Zelle';
			break;
		case 4:
			respuesta = 'Paypal';
			break;
		case 5:
			respuesta = 'Efectivo';
			break;
		case 6:
			respuesta = 'Stripe';
			break;
	}
	return respuesta;
});

function formatMoney(n, c, d, t) {
  var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};