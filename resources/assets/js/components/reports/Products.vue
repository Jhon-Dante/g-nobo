<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Reportes de productos mas vendidos</h1>
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
            <div class="parent-chart-container">
              <bar-chart
                :chart-data="productsChart"
                :css-classes="chartContainterClass"
                :options="options"
              ></bar-chart>
            </div>
          </card-content>
        </card-main>
      </div>
    </div>

    <div class="row">
      <div class="col s12">
        <card-main>
          <card-content>
            <table-byte
              :set-table="products"
              :export-file="true"
              :export-url="'admin/reports/best-sellers/excel'"
              :export-pdf-url="'admin/reports/best-sellers/pdf'"
              :export-title="'Reporte-de-productos-mas-vendidos'"
              :export-extradata="{ from: from, to: to }"
            >
              <table-row slot="table-head" slot-scope="{ item }">
                <table-head>Nombre de producto</table-head>
                <table-head>Unidades vendidas</table-head>
              </table-row>
              <table-row slot="table-row" slot-scope="{ item }">
                <table-cell>{{ item.presentation_formatted }}</table-cell>
                <table-cell>{{ item.purchases_number }}</table-cell>
              </table-row>
            </table-byte>
          </card-content>
        </card-main>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .initial-chart-container {
    width: 20vw;
    position: relative;
  }
  .data-chart-container {
    width: 80vw;
    height: 40vh;
    position: relative;
    overflow: auto;
  }
  .parent-chart-container {
    overflow-x: scroll;
    width: 100%;
  }
</style>

<script>
import BarChart from "./BarChart.js";
import Loading from "./Loading";
import { MONTHS } from "../../constants";

const parseData = (results, type) => {
  const labels = results.map(({ name }) => name);
  const data = results.map(({ purchases_number }) => purchases_number);
  return {
    labels, // Dates
    datasets: [
      {
        label: "Cantidad productos",
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
      productsChart: {
        labels: [], // Dates
        datasets: [
          {
            label: "Cantidad productos",
            data: [],
          },
        ],
      },
      products: [],
      dataInBs: [],
      chartContainterClass: "initial-chart-container",
      options: {
        responsive: true,
        // tooltips: {
        //   enabled: true,
        //   callbacks: {
        //     label: (tooltipItems, data) => {
        //       const currentDataset = data.datasets[0];
        //       const { results } = currentDataset;
        //       const { value, index } = tooltipItems;
        //       const { pending, processing } = results[index];
        //       return `Pedidos: ${value}.  Pendientes: ${pending}. Procesados: ${processing}`;
        //     },
        //   },
        // },
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
        .get(`admin/reports/products/${from}/${to}`)
        .then((res) => {
          if(res.data.length > 0) {
            this.chartContainterClass = 'data-chart-container';
          }
          this.productsChart = parseData(res.data);
          this.products = res.data;
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
