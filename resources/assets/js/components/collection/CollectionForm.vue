<template id="template-collection-form">

    <section class="row">

        <div class="row">
            <div class="col s12">
                <a href="#!" class="btn btn-back" @click="$parent.options = 0">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label>Volver</label>
                    </div>
                </a>
            </div>
        </div>

        <card-main class="row"> 
            <card-content>

                <form @submit.prevent="send" class="col s12">
                    <div class="row">
                        <div class="col s12 m6">
                            <label for="name" class="label-impegno">Nombre (Español)</label>
                            <input type="text" name="name" id="name" v-model="form.name" class="browser-default input-impegno">                            
                        </div>

                        <div class="col s12 m6">
                            <label for="name_english" class="label-impegno">Nombre (Ingles)</label>
                            <input type="text" name="name_english" id="name_english" v-model="form.name_english" class="browser-default input-impegno">                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 container-btn-add">
                            <a href="#!" class="btn-add" @click="_addDesign()">
                                <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </a>
                            <div class="btn-add-text">
                                Agregar Diseño
                            </div>
                        </div>

                        <div class="col s12 nota" v-if="form.id > 0">
                            <span class="nota__title">Nota: </span> Los diseños con productos no pueden ser eliminados.
                        </div>

                        <div class="col s12 center-align" :key="index" v-for="(design, index) in form.designs">
                            
                            <div class="row flex">

                                <div class="col s5">
                                    <label for="name" class="label-impegno">Diseño (Español)</label>
                                    <input type="text" name="name" id="name" v-model="design.name" maxlength="50" class="browser-default input-impegno">
                                </div>

                                <div class="col s5">
                                    <label for="name" class="label-impegno">Diseño (Ingles)</label>
                                    <input type="text" name="name" id="name" v-model="design.name_english" maxlength="50" class="browser-default input-impegno">
                                </div>

                                <div class="col s2 btn-delete-margin_top">
                                    <a href="#!" class="btn-action" @click="_delete(index, design.id)" v-if="design.enabled == true || design.enabled == null">
                                        <img :src="'img/icons/ico-eliminar.png' | asset" alt="" class="img-responsive">
                                    </a>

                                    <span v-else>Tiene productos</span>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 center-align">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>

            </card-content>
        </card-main>
    </section>
</template>

<style lang="scss">
    .btn-delete-margin_top{
        margin-top: 2.2rem;
    }
</style>

<script>
export default {
    template: "#template-collection-form",

    data () {
        return {
            form: {
                id: 0,
                name: '',
                name_english: '',
                designs: [],
                deletes: []
            }
        }
    },
    props: {
        'set-form': {
            type: Object,
            default () {
                return {}
            }
        }
    },
    methods: {
        send () {
            if(this.form.id == 0){
                this.store()
            }else{
                this.update();
            }
        },

        _addDesign() {
            this.form.designs.push({ name: "", name_english: "", id: 0})
        },

        _delete(index, id){
            this.form.designs.splice(index, 1);

            this.deletes.push(id)
        },

        store (){
            axios.post('admin/collections', this.form)
                .then(res => {
                    swal({
                        title: '',
                        text: 'Se registro la colección exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: "success"
                    }, () => {
                        window.location = urlBase + "admin/collections";
                    })
                    
                })
                .catch(err => {
                    let message = "Disculpe, ha ocurrido un error";
                    if(err.response.status === 422){
                        message = err.response.data.error;
                    }
                    swal('', message, 'error');
                });
        },

        update (){
            this.form._method = "PUT";
            axios.post(`admin/collections/${this.form.id}`, this.form)
                .then(res => {
                    swal({
                        title: '',
                        text: 'Se edito la colección exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: "success"
                    }, () => {
                        window.location = urlBase + "admin/collections";
                    })
                    
                })
                .catch(err => {
                    let message = "Disculpe, ha ocurrido un error";
                    if(err.response.status === 422){
                        message = err.response.data.error;
                    }
                    swal('', message, 'error');
                });
        }
    },

    mounted () {
        if(Object.entries(this.setForm).length > 0){
            this.form.name = this.setForm.name;
            this.form.name_english = this.setForm.name_english;
            this.form.id = this.setForm.id;

            this.setForm.designs.forEach((s, i) => {
                this.form.designs.push(s);
                this.form.designs[i].enabled = s.products_count === 0 ? true : false;
            })
        }

        document.querySelectorAll('label').forEach(el => {
            el.classList.add('active');
        });
    }
}
</script>