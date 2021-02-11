<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Reportes de pedidos</h1>
      </div>
    </div>
    <div class="row">
      <div class="col m4 s12">
        <label for="">Desde</label>
        <input
          type="text"
          class="datepicker-from browser-default input-impegno"
          name="from"
          placeholder="Desde"
        />
      </div>
      <div class="col m4 s12">
        <label for="">Hasta</label>
        <input
          type="text"
          class="datepicker-to browser-default input-impegno"
          name="to"
          placeholder="Hasta"
        />
      </div>
      <div class="col m4 s12">
        <br />
        <button href="#!" class="btn btn-success" @click="filter">
          Filtrar
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col s12">
        <card-main>
          <card-content>
            <bar-chart
              :chart-data="ordersChart"
              :styles="myStyles"
              :options="options"
            ></bar-chart>
          </card-content>
        </card-main>
      </div>
    </div>

    <div class="row">
      <div class="col s12">
        <card-main>
          <card-content>
            <table-byte
              :set-table="orders"
              :export-file="true"
              :export-url="'admin/reports/orders/excel'"
              :export-title="'Reporte-de-pedidos'"
              :export-pdf-url="'admin/reports/orders/pdf'"
              :export-extradata="{ from: from, to: to }"
            >
              <table-row slot="table-head" slot-scope="{ item }">
                <table-head>Fecha</table-head>
                <table-head># Pedidos</table-head>
                <table-head>Pendientes</table-head>
                <table-head>Aprobados</table-head>
                <table-head>Completados</table-head>
              </table-row>
              <table-row slot="table-row" slot-scope="{ item }">
                <table-cell>
                  <span>{{ item.label | date }}</span>
                </table-cell>
                <table-cell>{{ item.orders }}</table-cell>
                <table-cell>{{ item.pending }}</table-cell>
                <table-cell>{{ item.processing }} </table-cell>
                <table-cell>{{ item.completed }} </table-cell>
              </table-row>
            </table-byte>
          </card-content>
        </card-main>
      </div>
    </div>
  </section>
</template>

<script>
import BarChart from "./BarChart.js";
import Loading from "./Loading";
import { MONTHS } from "../../constants";

const parseData = (results, type) => {
  const labels = results.map(({ label }) => Vue.filter("date")(label));
  const data = results.map(({ orders }) => orders);
  return {
    labels, // Dates
    datasets: [
      {
        label: "Pedidos",
        backgroundColor: "rgba(239, 77, 37, 0.7)",
        data,
        results,
      },
    ],
  };
};

export default {
  name: "purchases-report",
  components: {
    BarChart,
    Loading,
  },
  data() {
    return {
      MONTHS,
      isLoading: false,
      type: "daily",
      from: new Date(),
      to: new Date(),
      fromError: false,
      toError: false,
      ordersChart: {
        labels: [], // Dates
        datasets: [
          {
            label: "Pedidos",
            data: [],
          },
        ],
      },
      orders: [],
      dataInBs: [],
      myStyles: {
        width: "500px",
      },
      options: {
        responsive: true,
        tooltips: {
          enabled: true,
          callbacks: {
            label: (tooltipItems, data) => {
              const currentDataset = data.datasets[0];
              const { results } = currentDataset;
              const { value, index } = tooltipItems;
              const { pending, processing, completed } = results[index];
              return `Pedidos: ${value}.  Pendientes: ${pending}. Aprobados: ${processing}. Completados: ${completed}`;
            },
          },
        },
      },
    };
  },
  mounted() {
    this.filter();
    this.initDatepickers();
  },
  methods: {
    filter() {
      const { type } = this;
      const from = moment(this.from).format("YYYY-MM-DD");
      const to = moment(this.to).format("YYYY-MM-DD");
      showLoading();
      axios
        .get(`admin/reports/orders/${from}/${to}`)
        .then((res) => {
          console.log(res.data);
          this.ordersChart = parseData(res.data);
          this.orders = res.data;
        })
        .catch((err) => {
          console.log("err", err);
          swal("", "Disculpa, ha ocurrido un error", "error");
        })
        .finally(() => {
          quiLoading();
        });
    },
    fromDate(date) {
      this.fromError = false;
      if (this.to && moment(date).isAfter(moment(this.to))) {
        swal(
          "",
          "La fecha de inicio no puede ser superior a la de finalización,  por favor vuelva a seleccionarla"
        );
        this.fromError = true;
        return;
      }
      this.from = date;
    },
    toDate(date) {
      this.toError = false;
      if (this.from && moment(date).isBefore(moment(this.from))) {
        swal(
          "",
          "La fecha de finalización no puede ser antes la de inicio, por favor vuelva a seleccionarla"
        );
        this.toError = true;
        return;
      }
      this.to = date;
    },
    verify() {
      if (this.fromError) {
        document.querySelector(".datepicker-from").value = "";
        this.from = "";
      }

      if (this.toError) {
        document.querySelector(".datepicker-to").value = "";
        this.to = "";
      }
    },
    initDatepickers() {
      const tabs = document.querySelector("#tabs-swipe-demo");
      const instanceTab = M.Tabs.init(tabs, {});
      const fromDate = document.querySelector(".datepicker-from");
      const toDate = document.querySelector(".datepicker-to");
      const instanceStart = M.Datepicker.init(fromDate, {
        onSelect: this.fromDate,
        onClose: this.verify,
        minYear: minYear,
        maxYear: maxYear,
        i18n: pickDateI18n,
        defaultDate: new Date(),
        maxDate: new Date(),
      });
      const instanceEnd = M.Datepicker.init(toDate, {
        onSelect: this.toDate,
        onClose: this.verify,
        minYear: minYear,
        maxYear: maxYear,
        i18n: pickDateI18n,
        defaultDate: new Date(),
        maxDate: new Date(),
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.report {
  &__title {
    font-size: 1.8rem;
    display: flex;
    align-items: center;
  }
}
</style>
