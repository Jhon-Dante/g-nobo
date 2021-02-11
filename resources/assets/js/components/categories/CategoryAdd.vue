<template id="template-category-add">
    <div class="col s12">
        <div class="row">
            <div class="col s12">
                <a href="#!" class="btn btn-back" @click="_back()">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label for=""> Volver</label>
                </div>
                </a>
            </div>
        </div>

        <card-main>
            <card-content>
                <div class="container">
                    <form class="row" @submit.prevent="_submit">

                        <div class="col s12 m6 l6">
                            <label for="name" class="label-impegno">Categoría (Español)</label>
                            <input type="text" name="name" id="name" v-model="form.name" maxlength="35" class="browser-default input-impegno">
                        </div>

                        <!--<div class="col s12 m6 l6">
                            <label for="name_english" class="label-impegno">Categoría (Ingles)</label>
                            <input type="text" name="name_english" id="name_english" v-model="form.name_english" maxlength="35" class="browser-default input-impegno">
                        </div> -->
                        <!-- <div class="col s12">
                            <fieldset>
                                <legend>Disponible para estos </legend>
                                <div class="col s12">
                                    <p class="not-mb">
                                        <input type="checkbox" id="filter-all" class="with-gap" @click="_selectAllFilters()">
                                        <label for="filter-all">Todo</label>
                                    </p>
                                </div>
                                <div class="col s12 m3" v-for="(filter, i) in filters" :key="'filter-' + i">
                                    <p>
                                        <input type="checkbox" :id="`filter-${i}`" class="with-gap" :value="filter.id" v-model="form.filters">
                                        <label :for="`filter-${i}`">{{ filter.name }}</label>
                                    </p>
                                </div>
                            </fieldset>
                        </div> -->
                        <!-- <div class="col s12">
                            <fieldset>
                                <legend>Tallas</legend>
                                <div class="col s12">
                                    <p class="not-mb">
                                        <input type="checkbox" id="size-all" class="with-gap" @click="_selectAll()">
                                        <label for="size-all">Todo</label>
                                    </p>
                                </div>
                                <div class="col s12 m3" v-for="(size, i) in sizes" :key="'size-' + i">
                                    <p>
                                        <input type="checkbox" :id="`size-${i}`" class="with-gap" :value="size.id" v-model="form.sizes">
                                        <label :for="`size-${i}`">{{ size.name }}</label>
                                    </p>
                                </div>
                            </fieldset>                            
                        </div> -->

                        <div class="row">
                            <div class="col s12 container-btn-add">
                                <a href="#!" class="btn-add" @click="_addSubcategory()">
                                    <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                                </a>
                                <div class="btn-add-text">
                                    Agregar Subcategoría
                                </div>
                            </div>

                            <div class="col s12 nota" v-if="form.id > 0">
                                <span class="nota__title">Nota: </span> Las subcategorías con productos no pueden ser eliminadas.
                            </div>

                            <div class="col s12 center-align" :key="index" v-for="(subcategory, index) in form.subcategories">
                                
                                <div class="row flex">
                                    <div class="co s1" v-if="subcategory.id">
                                        ID: <b>{{ subcategory.id }}</b>
                                    </div>
                                    <div class="col s5">
                                        <label for="name" class="label-impegno">Subcategoría (Español)</label>
                                        <input type="text" name="name" id="name" v-model="subcategory.name" maxlength="50" class="browser-default input-impegno">
                                    </div>

                                    <!--<div class="col s5">
                                        <label for="name" class="label-impegno">Subcategoría (Ingles)</label>
                                        <input type="text" name="name" id="name" v-model="subcategory.name_english" maxlength="50" class="browser-default input-impegno">
                                    </div> -->

                                    <div class="col s1 margin-botton">

                                        <a href="#!" class="btn-action" @click="_delete(index)" v-if="subcategory.enabled == true || subcategory.enabled == null">
                                            <img :src="'img/icons/ico-eliminar.png' | asset" alt="" class="img-responsive">
                                        </a>

                                        <span v-else>Tiene productos</span>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                        
                        <div class="col s12 center-align">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </card-content>
        </card-main>
    </div>
</template>

<style lang="scss">
    .container-btn-add{
        margin: 1rem 0;
    }

    .flex{
        display: flex;
        align-items: flex-end;
    }

    .btn-floating i{
        color: #fff
    }

    .not-mb{
        margin-bottom: 0 !important;
    }

    .nota{
        font-size: .8rem;
        margin: .8rem 0;

        &__title {
            font-weight: bold;
        }
    }
    fieldset{
        margin: 1rem 0;
        border-radius: .6rem;
        legend{
            padding: 0 10px;
        }
    }
    .margin-botton{
        margin-bottom: .8rem;
    }
</style>

<script>
export default {
    template: "#template-category-add",

    props: {
        sizes: {
            type: Array,
            default () {
                return []
            }
        },
        filters: {
            type: Array,
            default () {
                return []
            }
        },
        'set-form': {
            type: Object,
            default(){
                return {}
            }
        }
    },

    data () {
        return {
            form: {
                id: 0,
                name: "",
                name_english: "",
                sizes: [],
                filters: [],
                subcategories: []
            }
        }
    },

    methods: {
        _back() {
            this.$emit('back', 0)
        },

        _addSubcategory() {
            this.form.subcategories.push({ name: "", name_english: "", id: 0})
        },

        _delete(index){
            this.form.subcategories.splice(index, 1);
        },

        _selectAll(){
            this.form.sizes = [];
            // if(event.target.checked){
                this.sizes.forEach(val => {
                    this.form.sizes.push(val.id);
                });
            // }            
        },

        _selectAllFilters() {            
            this.form.filters = [];
            // if (event.target.checked) {
                this.filters.forEach(val => {
                    this.form.filters.push(val.id)
                })
            // }
        },

        _submit(){
            showLoading();
            if(this.form.id == 0 || this.form.id == undefined){
                this._store();
            }else{
                this.update();
            }
        },

        _store(){
            axios.post(`admin/categories`, this.form)
                .then(res => {
                    quiLoading();
                    swal({
                        title: '',
                        text: 'Se registro la categoría exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: "success"
                    }, () => {
                        window.location = urlBase + "admin/categories";
                    })
                })
                .catch(err => {
                    quiLoading();
                    let message = "Disculpe, ha ocurrido un error";
                    if(err.response.status === 422){
                        message = err.response.data.error;
                    }
                    swal('', message, 'error');
                });
        },

        update(){
            this.form._method = "PATCH";

            axios.post(`admin/categories/${this.form.id}`, this.form)
                .then(res => {
                    quiLoading();
                    swal({
                        title: '',
                        text: 'Se edito la categoría exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: "success"
                    }, () => {
                        window.location = `${urlBase}admin/categories`;
                    });
                })
                .catch(err => {
                    quiLoading();
                    let message = "Disculpe, ha ocurrido un error";
                    if(err.response.status === 422){
                        message = err.response.data.error;
                    }
                    swal('', message, 'error');
                });
        }
    },
    created(){
        if(Object.entries(this.setForm).length > 0){
            this.form.id = this.setForm.id
            this.form.name = this.setForm.name;
            this.form.name_english = this.setForm.name_english;
            
            this.setForm.sizes.forEach(s => {
                this.form.sizes.push(s.id);
            });

            this.setForm.filters.forEach(f => {
                this.form.filters.push(f.id);
            });

            this.setForm.subcategories.forEach((s, i) => {
                this.form.subcategories.push(s);
                this.form.subcategories[i].enabled = s.products_count === 0 ? true : false;
            })
        }
    },
    mounted(){
        if(Object.entries(this.setForm).length > 0){
            if(this.setForm.sizes.length == this.form.sizes.length){
                document.querySelector("#size-all").setAttribute('checked', true);
            }
        }
        this._selectAll()
        this._selectAllFilters()
    }
}
</script>

