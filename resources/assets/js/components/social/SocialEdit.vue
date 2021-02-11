<template id="#template-social-edit">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Editar Información de Contacto</h1>
            </div>
        </div>
    <!-- <div class="col s12"> -->
        <div class="row">
            <div class="col s12">
                <a :href="url+'/admin/social'" class="btn btn-back">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label for=""> Volver</label>
                    </div>
                </a>
            </div>
        </div>
        <card-main class="row">
            <card-content>
                <form action="" @submit.prevent="_edit">
                    <div class="col s6 center-align">
                        <label class="label-impegno">Facebook</label>   
                        <input type="text" maxlength="50" name="facebook" id="facebook" class="browser-default input-impegno" v-model="setForm.facebook">
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Instagram</label>
                       <input type="text" maxlength="50" name="instagram" id="instagram" class="browser-default input-impegno" v-model="setForm.instagram">
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Twitter</label>
                       <input type="text" maxlength="50" name="twitter" id="twitter" class="browser-default input-impegno" v-model="setForm.twitter">
                    </div>
                    <!-- <div class="col s6 center-align">
                        <label class="label-impegno">Youtube</label>
                        <input type="text" maxlength="30" name="youtube" id="youtube" class="browser-default input-impegno" v-model="setForm.youtube">
                    </div> -->
                    <!-- <div class="col s6 center-align">
                       <label class="label-impegno">Slogan</label>
                       <input type="text" maxlength="30" name="slogan" id="slogan" class="browser-default input-impegno" v-model="setForm.slogan">
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Slogan (Inglés)</label>
                       <input type="text" maxlength="30" name="slogan_english" id="slogan_english" class="browser-default input-impegno" v-model="setForm.english_slogan">
                    </div> -->
                    <div class="col s6 center-align">
                       <label class="label-impegno">Dirección</label>
                       <input type="text" name="address" id="address" class="browser-default input-impegno" v-model="setForm.address">
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Teléfono</label>
                       <input type="number" name="phone" id="phone" class="browser-default input-impegno" v-model="setForm.phone">
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Correo</label>
                       <input type="text" name="email" id="email" class="browser-default input-impegno" v-model="setForm.email">
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Horario ATC</label>
                        <input
                          type="text"
                          v-model="setForm.horario_atc"
                          class="browser-default input-impegno"
                          name="horario_atc"
                          placeholder="Horario ATC"
                        />
                    </div>
                    <div class="col s6 center-align">
                       <label class="label-impegno">Horario de Entrega</label>
                        <input
                          type="text"
                          v-model="setForm.horario_entrega"
                          class="datepicker-start browser-default input-impegno"
                          name="horario_entrega"
                          placeholder="Horario de Entrega"
                        />
                    </div>

                    <div class="col s12 center-align content-image mt-1">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </form>
            </card-content>
        </card-main>
    </section>
</template>

<style>
    .mt-1 {
        margin-top: 1rem;
    }
</style>

<script>
export default {
    template: "#template-social-edit",
    
    data() {
        return {
            setForm: {}
        }
    },
    methods: {
        _edit() {
            let formdata = new FormData
            formdata.append('facebook', this.setForm.facebook)
            formdata.append('instagram', this.setForm.instagram)
            formdata.append('twitter', this.setForm.twitter)
            formdata.append('youtube', this.setForm.youtube)
            formdata.append('slogan', this.setForm.slogan)
            formdata.append('slogan_english', this.setForm.english_slogan)
            formdata.append('address', this.setForm.address)
            formdata.append('phone', this.setForm.phone)
            formdata.append('email', this.setForm.email)
            formdata.append('horario_atc', this.setForm.horario_atc)
            formdata.append('horario_entrega', this.setForm.horario_entrega)
            formdata.append('_method', 'PUT');

            axios.post("admin/social/"+this.setForm.id, formdata)
                .then( resp => {
                    swal({
                        title: '',
                        text: 'Se ha actualizado su información con éxito',
                        timer: 2000,
                        showConfirmButton: false,
                        type: 'success'
                    });
                }).catch( err => {
                    let message  = "Disculpe, ha ocurrido un error";
                    if (err.response.status == 422) {
                        message = err.response.data.error;
                    }
                    swal("", message, "error");
                }
            )
        }
    },
    props: {
        url:{
            type: String,
            default: ""
        },
        posts: {
            type: Object,
            default() {
                return {}
            }
        }
    },
    mounted() {
        this.setForm = this.posts
    }
}
</script>

