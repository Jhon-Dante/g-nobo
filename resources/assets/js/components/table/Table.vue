<template id="template-table">
  <div class="row">
    <div
      class="col s12 m6 input-field input-group-right input-search-table"
      :class="exportCustomClass"
      v-if="exportFile"
    >
      <button type="button" class="btn btn-success" @click="_exportFile()">
        <span class="v-align-up cursor">Exportar</span>
      </button>
      <button
        type="button"
        class="btn btn-success"
        @click="_exportPDF()"
        v-if="exportPdfUrl"
      >
        <span class="v-align-up cursor">Exportar PDF</span>
      </button>
      <button
        type="button"
        class="btn btn-success"
        style="margin-left: 10px;"
        @click="$refs['inputFile'].click()"
        v-if="importUrl"
      >
        <span class="v-align-up cursor">Carga masiva</span>
      </button>
      <input
        type="file"
        ref="inputFile"
        class
        name="inputFile"
        id="inputFile"
        style="display:none;"
        @change="_importFile"
      />
    </div>
    <div class="col m6 s12">
      <div
        class="col s12 m10 offset-m2 input-field input-search-table"
        v-show="filters.length > 0"
      >
        <i class="material-icons prefix">search</i>
        <input type="text" @keyup="_filter" v-model="filter" />
      </div>
    </div>
    <table class="bordered highlight responsive-table table-impegno">
      <thead>
        <slot name="table-head"></slot>
        <tr v-if="thead.length > 0">
          <th v-for="(th, index) in thead" :key="`th-` + index">
            {{ th.name }}
          </th>
        </tr>
      </thead>
      <tbody>
        <slot
          name="table-row"
          v-for="(row, i) in bodyTableRender"
          :item="row"
          :index="i"
        ></slot>
        <slot name="empty-rows" v-if="rows.length === 0"></slot>
      </tbody>
      <tfoot>
        <slot name="table-foot"></slot>
      </tfoot>
    </table>

    <!-- Paginador -->
    <div class="col s12 center-align" v-show="pages.length > 1">
      <ul class="pagination">
        <!-- <li :class="currentPage==1?'hidden_li':''" v-on:click="currentPage = currentPage-1">   
                    <a href="#!"><i class="mdi-navigation-chevron-left"></i></a>
        </li>-->
        <li id="multileft" v-on:click="arrows(-999)">
          <span class="fa fa-angle-double-left custom"><<&nbsp;&nbsp;</span>
        </li>
        <li id="left" v-on:click="arrows(-1)">
          <span class="mdi-navigation-chevron-left"><&nbsp;&nbsp;</span>
        </li>

        <li
          v-for="(pag, pages_index) in pages"
          :class="currentPage == pag ? 'active' : ''"
          v-on:click="changePage(pag)"
          :key="'li-pag' + pages_index"
          v-if="showPage(currentPage, pag, pages.length)"
        >
          <a href="#!">{{ pag }}</a>
        </li>
        <li v-on:click="arrows(+1)">
          <span>&nbsp;&nbsp;></span>
        </li>
        <li v-on:click="arrows(999)">
          <span>&nbsp;&nbsp;>></span>
        </li>

        <!-- <li :class="currentPage==lastPage || pages.length == 1?'hidden_li':''" v-on:click="currentPage = currentPage+1"><a href="#!">
                    <i class="mdi-navigation-chevron-right"></i></a>
        </li>-->
      </ul>
    </div>

    <!-- <div class="col s12 m6 offset-m3" v-if="rows.length !== 0">
            <ul class="pagination">
                <li :class="pagination.first_page.class">
                    <a :href="pagination.first_page.path"><i class="material-icons">chevron_left</i></a>
                </li>

                <li v-for="num in pagination.pages" :key="'pag-' + num" :class="pagination.current_page == num ? 'active' : 'waves-effect'">
                    <a :href="`${pagination.path}?page=${num}`">{{ num }}</a>
                </li>

                <li :class="pagination.last_page.class">
                    <a :href="pagination.last_page.path"><i class="material-icons">chevron_right</i></a>
                </li>
            </ul>
    </div>-->
  </div>
</template>

<style>
.pagination {
  text-align: center;
}

table .btn-floating i {
  color: #fff;
}

.pagination li span {
  font-size: 1.4rem !important;
  cursor: pointer;
  vertical-align: middle;
}
</style>

<script>
export default {
  template: "#template-table",
  props: {
    forPage: {
      type: Number,
      default: 10,
    },
    "set-table": {
      type: Array,
      default() {
        return [];
      },
    },
    id: {
      type: String,
      default: "table-byte-" + Math.floor(Math.random() * (1000 - 1)) + 1,
    },
    filters: {
      type: Array,
      default() {
        return [];
      },
    },
    "external-filter": {
      type: String,
    },
    "export-file": {
      type: Boolean,
      default: false,
    },
    "export-url": {
      type: String,
      default: "",
    },
    "export-pdf-url": {
      type: String,
      default: "",
    },
    "import-url": {
      type: String,
      default: "",
    },
    "export-title": {
      type: String,
      default: "Reporte",
    },
    "export-pdf-title": {
      type: String,
      default: "Reporte",
    },
    "export-extradata": {
      type: Object,
      default() {
        return {};
      },
    },
    "export-pdf-extrada": {
      type: Object,
      default() {
        return {};
      },
    },
    "export-icon": {
      type: String,
      default: "",
    },
    "export-custom-class": {
      type: String,
      default: "m6",
    },
  },
  data() {
    return {
      rows: [],
      thead: [],
      filter: "",
      pagination: {
        first_page: "",
        last_page: "",
      },
      currentPage: 1, // Pagina por defecto
      lastPage: "", // Última página
      pagesArray: [], // Array que contendra las paginas a mostrar
      itemsTotal: [], // Array que contendra los datos
      tempTable: [],
    };
  },
  watch: {
    externalFilter(value) {
      let result = [];
      let alls = this.setTable;
      this.filter = value;
      this.filters.forEach((f, k) => {
        let flag = false;
        if (f.indexOf(".") > -1) {
          // Busco puntos en el filtro, para saber que se trata de una busquede de un objeto
          let propieties = f.split(".");

          alls.forEach((r) => {
            let value = "";
            for (let i = 0; i < propieties.length; i++) {
              if (value == "") {
                value = r[propieties[i]];
              } else {
                if (i == propieties.length - 1) {
                  if (
                    value[propieties[i]]
                      .toString()
                      .toLowerCase()
                      .indexOf(this.externalFilter.toString().toLowerCase()) >=
                    0
                  ) {
                    if (!result.includes(r)) {
                      result.push(r);
                    }
                  }
                } else {
                  value = value[propieties[i]];
                }
              }
            }
          });
        } else {
          alls.forEach((r) => {
            if (
              r[f]
                .toString()
                .toLowerCase()
                .indexOf(this.externalFilter.toString().toLowerCase()) >= 0
            ) {
              if (!result.includes(r)) {
                result.push(r);
              }
            }
          });
        }
      });

      this.rows = result;
    },
    setTable(newRows, oldRows){
      if(newRows!= oldRows){
        this.rows = newRows
        const needItemNumber = (parseInt(this.forPage) * parseInt(this.currentPage-1)) +1
        if((newRows.length <= needItemNumber) && this.currentPage != 1){
          this.currentPage = 1
        }
        if(this.filter)
          this._filter()
      }
    }
  },
  computed: {
    /**
     * Muestro la pagina actual
     */
    isActive(val) {
      if (val == this.currentPage) {
        return true;
      }
    },

    /**
     * Muestro la cantidad de paginas posibles
     */
    pages() {
      this.pagesArray = [];
      let totalItems = this.rows.length;
      const needItemNumber = (parseInt(this.forPage) * parseInt(this.currentPage-1)) +1
      if((this.rows.length < needItemNumber) && this.currentPage != 1){
        this.currentPage = this.currentPage - 1
      }
      if (totalItems == 0) {
        this.pagesArray.push(1);
        return this.pagesArray;
      } else {
        this.pagesArray = [];
        this.lastPage = Math.ceil(totalItems / this.forPage);

        if (this.lastPage == 0) {
          this.pagesArray.push(1);
        } else {
          for (let index = 1; index <= this.lastPage; index++) {
            this.pagesArray.push(index);
          }
        }
      }
      return this.pagesArray;
    },

    /**
     * Muestro la cantidad de registros por paginas
     */
    bodyTableRender() {
      if (this.filter == "") {
        this.rows = this.setTable;
      }

      if (this.currentPage == 1) {
        return this.rows.slice(0, this.forPage);
      } else {
        if (this.filter != "" && this.currentPage > this.lastPage) {
          this.currentPage = 1;
        }

        let since =
          parseInt(parseInt(this.currentPage) * parseInt(this.forPage)) -
          this.forPage;
        let until = parseInt(
          parseInt(this.currentPage) * parseInt(this.forPage)
        );
        return this.rows.slice(since, until);
      }
    },
  },
  methods: {
    _importFile(e) {
      let file = e.target.files[0];
      const [name, extension] = file.name.split(".");
      if (extension != "xlsx" && extension != "xls") {
        swal("", "Formato inválido, debe ser excel .xlsx", "warning");
        return;
      }
      showLoading();

      let formData = new FormData();
      formData.append("file", file);
      axios
        .post(this.importUrl, formData)
        .then((res) => {
          swal("", "Se han importando los datos exitosamente", "success");
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        })
        .catch((err) => {
          swal(
            "",
            "Algunos productos no se registraron por no tener el formato de carga correcto",
            "error"
          );
          console.log("error", err);
        })
        .finally(() => {
          quiLoading();
        });
    },
    _exportPDF() {
      const options = {
        responseType: "blob",
      };

      showLoading();
      axios
        .post(
          this.exportPdfUrl,
          {
            data: this.rows,
            ...this.exportExtradata,
          },
          options
        )
        .then((response) => {
          const url = window.URL.createObjectURL(
            new Blob([response.data], { type: "application/pdf" })
          );
          const link = document.createElement("a");
          const ext = ".pdf";
          const filename =
            this.exportTitle + "_" + moment().format("DD-MM-YYYY") + ext;
          link.href = url;
          link.setAttribute("download", `${filename}`);
          document.body.appendChild(link);
          link.click();
        })
        .catch((err) => {
          let message = "Disculpe, ha ocurrido un error";
          console.log(err);
          if (err.response.status === 422) {
            message = err.response.data;
          }

          swal("", message.error, "error");
        })
        .finally(() => {
          quiLoading();
        });
    },
    _exportFile() {
      const options = {
        responseType: "blob",
      };

      showLoading();
      axios
        .post(
          this.exportUrl,
          {
            data: this.rows,
            type: "application/vnd.ms-excel",
            ...this.exportExtradata,
          },
          options
        )
        .then((response) => {
          const url = window.URL.createObjectURL(
            new Blob([response.data], { type: "application/vnd.ms-excel" })
          );

          const link = document.createElement("a");
          const ext = ".xls";
          const filename =
            this.exportTitle + "_" + moment().format("DD-MM-YYYY") + ext;

          link.href = url;
          link.setAttribute("download", `${filename}`);

          document.body.appendChild(link);

          link.click();
        })
        .catch((err) => {
          let message = "Disculpe, ha ocurrido un error";
          console.log(err);
          if (err.response.status === 422) {
            message = err.response.data;
          }

          swal("", message.error, "error");
        })
        .finally(() => {
          quiLoading();
        });
    },
    /**
     * Función que realiza el cambio de página
     */
    arrows(calc) {
      var sign = Math.sign(calc);
      const nextQuantity = (((this.currentPage-1) * this.forPage) + (this.forPage+1))
      if (sign == -1) {
        if (this.currentPage == 1) {
          return false;
        }
        calc = calc * -1;

        if (calc == 999) {
          this.currentPage = 1;
        } else {
          this.currentPage = this.currentPage - calc;
        }
      } else {
        if (calc == 999) {
          var diferencia = this.pagesArray.length - this.currentPage;
          this.currentPage = this.pagesArray.length;
        } else {
          if(this.setTable.length >= nextQuantity)
            this.currentPage = this.currentPage + calc;
        }
      }
    },

    changePage: function(page) {
      this.currentPage = page;
    },

    showPage(currentPage, page, length) {
      let lastPage = length; // 11
      let maxPage = currentPage + 5;
      let minPage = currentPage - 5;
      let ultime = lastPage - 5;

      if (lastPage == currentPage && page >= minPage) {
        return true;
      } else if (
        (maxPage >= page || currentPage == page) &&
        page >= currentPage
      ) {
        return true;
      } else if (currentPage >= ultime && page >= ultime) {
        let last = minPage + 5;

        if (page >= last) {
          return false;
        }

        return true;
      }

      return false;
    },

    _setPagination(object) {
      this.pagination = {
        pages: object.last_page,
        first_page: {
          class: object.current_page == 1 ? "disabled" : "waves-effect",
          path:
            object.current_page == 1
              ? "#!"
              : `${object.path}?page=${object.first_page}`,
        },
        last_page: {
          class:
            object.current_page == object.last_page
              ? "disabled"
              : "waves-effect",
          path:
            object.current_page == object.last_page
              ? "#!"
              : `${object.path}?page=${object.last_page}`,
        },
        path: object.path,
        current_page: object.current_page,
      };
    },
    _filter() {
      let result = [];
      let alls = this.setTable;

      this.filters.forEach((f, k) => {
        let flag = false;
        if (f.indexOf(".") > -1) {
          // Busco puntos en el filtro, para saber que se trata de una busquede de un objeto
          let propieties = f.split(".");

          alls.forEach((r) => {
            let value = "";
            for (let i = 0; i < propieties.length; i++) {
              if (value == "") {
                value = r[propieties[i]];
              } else {
                if (i == propieties.length - 1) {
                  if (
                    value[propieties[i]]
                      .toString()
                      .toLowerCase()
                      .indexOf(this.filter.toLowerCase()) >= 0
                  ) {
                    if (!result.includes(r)) {
                      result.push(r);
                    }
                  }
                } else {
                  value = value[propieties[i]];
                }
              }
            }
          });
        } else {
          alls.forEach((r) => {
            if (
              r[f]
                .toString()
                .toLowerCase()
                .indexOf(this.filter.toLowerCase()) >= 0
            ) {
              if (!result.includes(r)) {
                result.push(r);
              }
            }
          });
        }
      });

      this.rows = result;
    },
    /**
     *  Método para setear la posicion del objeto dentro del array
     */
    setPos: function(since, until) {
      for (let index = since; index <= until; index++) {
        if (this.itemsTotal[index] != undefined) {
          this.itemsTotal[index].positionJson = index;
        }
      }
    },
    deleteTextFilter: function(){
      this.filter = ''
    }
  },
  mounted() {
    this.$parent.$on('deleteFilter', this.deleteTextFilter);
    this.tempTable = this.setTable;
    this.rows = this.setTable;
    // this._setPagination(this.setTable);
  },
};
</script>
