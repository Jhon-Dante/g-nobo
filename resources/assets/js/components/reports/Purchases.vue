<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Reportes de ventas</h1>
      </div>
    </div>
    <div class="row">
      <div class="col m3 s12">
        <label for="">Tipo</label>
        <select v-model="type" class="browser-default">
          <option value="daily">Diario</option>
          <option value="monthly">Mensual</option>
          <option value="yearly">Anual</option>
        </select>
      </div>
      <div class="col m3 s12">
        <label for="">Desde</label>
        <input
          type="text"
          class="datepicker-from browser-default input-impegno"
          name="from"
          placeholder="Desde"
        />
      </div>
      <div class="col m3 s12">
        <label for="">Hasta</label>
        <input
          type="text"
          class="datepicker-to browser-default input-impegno"
          name="to"
          placeholder="Hasta"
        />
      </div>
      <div class="col m3 s12">
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
            <line-chart
              :chart-data="purchasesChart"
              :styles="myStyles"
              :options="options"
            ></line-chart>
          </card-content>
        </card-main>
      </div>
    </div>

    <div class="row">
      <div class="col s12">
        <card-main>
          <card-content>
            <table-byte
              :set-table="purchases"
              :export-file="true"
              :export-url="'admin/reports/purchases-report/excel'"
              :export-title="'Reporte-de-ventas'"
              :export-pdf-url="'admin/reports/purchases-report/pdf'"
              :export-extradata="{
                report_type: currentType,
                from: from,
                to: to,
              }"
            >
              <table-row slot="table-head" slot-scope="{ item }">
                <table-head>Fecha</table-head>
                <table-head>Ventas brutas $</table-head>
                <table-head>Ventas brutas bs</table-head>
                <table-head>Utilidad bruta</table-head>
                <table-head>% Utilidad</table-head>
                <table-head>Ventas Netas $</table-head>
                <table-head>Ventas Netas bs</table-head>
                <table-head>Utilidad Neta</table-head>
              </table-row>
              <table-row slot="table-row" slot-scope="{ item }">
                <table-cell>
                  <span v-if="currentType === 'daily'">{{
                    item.label | date
                  }}</span>
                  <span v-else-if="currentType === 'monthly'">{{
                    MONTHS[item.label]
                  }}</span>
                  <span v-else>{{ item.label }}</span>
                </table-cell>
                <table-cell>{{ item.purchases | USD }}</table-cell>
                <table-cell>{{ item.purchases_bs | VES }}</table-cell>
                <table-cell>{{ item.utility | USD }} </table-cell>
                <table-cell>{{ item.utility_percentage }}% </table-cell>
                <table-cell>{{ item.purchases_neta | USD }}</table-cell>
                <table-cell>{{ item.purchases_neta_bs | VES }}</table-cell>
                <table-cell>{{ item.utility_neta | USD }} </table-cell>
              </table-row>
            </table-byte>
          </card-content>
        </card-main>
      </div>
    </div>
  </section>
</template>

<script>
import LineChart from "./LineChart.js";
import Loading from "./Loading";
import { MONTHS } from "../../constants";

const parseData = (results, type, title = "Ventas") => {
  const labels = results.map(({ label }) => {
    switch (type) {
      case "daily":
        return moment(label).format("DD-MM-YYYY");
        break;
      case "monthly":
        return MONTHS[label];
        break;
      case "yearly":
        return label;
        break;
    }
  });
  const data = results.map(({ purchases }) => purchases);
  const dataBs = results.map(({ purchases_bs }) => purchases_bs);
  console.log('>>: results', results)
  const utiliyPercentages = results.map(
    ({ utility_percentage }) => utility_percentage
  );
  return {
    labels, // Dates
    datasets: [
      {
        label: title,
        backgroundColor: "rgba(239, 77, 37, 0.7)",
        data,
        dataBs,
        utiliyPercentages,
      },
    ],
  };
};
export default {
  name: "purchases-report",
  components: {
    LineChart,
    Loading,
  },
  data() {
    return {
      MONTHS,
      isLoading: false,
      currentType: "daily",
      type: "daily",
      from: new Date(),
      to: new Date(),
      fromError: false,
      toError: false,
      purchasesChart: {
        labels: [], // Dates
        datasets: [
          {
            label: "Ventas",
            data: [],
          },
        ],
      },
      purchases: [],
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
              const { dataBs, utiliyPercentages } = currentDataset;
              const { value, xLabel, yLabel, index } = tooltipItems;
              const bs = this.$options.filters.VES(dataBs[index]);
              const usd = this.$options.filters.USD(value);
              return `Ventas: ${usd} - ${bs} / Utilidad: ${utiliyPercentages[index]}%`;
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
        .get(`admin/reports/purchases/${type}/${from}/${to}`)
        .then((res) => {
          this.purchasesChart = parseData(res.data, this.type);
          this.currentType = type;
          this.purchases = [...res.data];
          this.dataInBs = res.data.map(({ valueBs }) => valueBs);
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
        i18n: pickDateI18n,
        minYear: minYear,
        maxYear: maxYear,
        defaultDate: new Date(),
        maxDate: new Date(),
      });
      const instanceEnd = M.Datepicker.init(toDate, {
        onSelect: this.toDate,
        onClose: this.verify,
        i18n: pickDateI18n,
        minYear: minYear,
        maxYear: maxYear,
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
