<template>
    <div class="col s12">
        <div class="row">
            <div class="col s12">
                <a href="#!" class="btn btn-back" @click="$emit('back')">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label for=""> Volver</label>
                    </div>
                </a>
            </div>
        </div>
         <div class="container-fluid">
            <div class="row container-form">
                <div class="col s12">
                    <form action="" @submit.prevent="actualizar">
                        <div class="col s6 center-align" >
                            <label for="name" class="label-impegno">Propietario de la cuenta</label>
                            <input type="text" name="name" id="name" v-model="form.name" class="browser-default input-impegno">
                        </div>
                       <div class="col s6 center-align">
                            <label for="title" class="label-impegno">Banco</label>
                            <select name="" id="" v-model="form.bank_id" class="browser-default">
                                <option :value="c.id" v-for="(c, i) in banks" :key="i">{{ c.name }}</option>
                            </select>
                        </div>
                        <div class="col s6 center-align" v-if="form.bank_id == 1">
                            <label for="title" class="label-impegno">Correo</label>
                            <input type="email" name="title" v-model="form.email" class="browser-default input-impegno">
                        </div>
                        <div class="col s6 center-align"  v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Idenficación</label>
                            <input type="text" name="title" id="title" v-model="form.identification"  class="browser-default input-impegno">
                        </div>
                        <div class="col s6 center-align"  v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Número de cuenta</label>
                            <input type="text" name="title" id="title" v-model="form.number" class="browser-default input-impegno">                        
                        </div>
                        <div class="col s6 center-align" v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Tipo de cuenta</label>
                            <select name="" id="" v-model="form.type" class="browser-default">
                                <option value="1">Corriente</option>
                                <option value="2">Ahorro</option>
                            </select>
                        </div>

                        <div class="col s6 center-align" v-if="form.bank_id != 1">
                            <label for="telefono" class="label-impegno">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" v-model="form.phone" class="browser-default input-impegno">                        
                        </div>

                        <div class="col s12 center-align"><br><br>
                            <button class="btn btn-success" :disabled="sending">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .container-fluid{
        width: 90%;
        margin: auto;
    }
    .container-form{
        background-color: #fff;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding: 1rem .75rem;
    }
</style>


<script>
export default {
    props: {
        setform: {
            type: Object,
            default: {}
        },
        banks: {
            type: Array,
            default: []
        },
        accounts: {
            type: Array,
            default: []
        }
    },
    data () {
        return {
            sending: false,
            form: {
                id: 0,
                name: '',
                bank_id: '',
                number: '',
                type: 1,
                identification: '',
                phone: '',
                _method: 'PUT'
            }
        }
    },
    methods: {
        actualizar () {
        this.sending = true
           axios.post(`admin/banks/${this.form.id}`, this.form)
            .then(res => {
                this.sending = false

                swal('', 'Se actualizó la cuenta bancaria correctamente', 'success')
                setTimeout(() => {
                    location.reload()
                }, 900)
            })
            .catch(err => {
                this.sending = false

                if (err.response.status == 422){
                    swal("", err.response.data.error, "error");
                    return
                }

                swal('', 'Algo ha ocurrido', 'error')
            })
        }
    },
    mounted () {
        Object.getOwnPropertyNames(this.setform).forEach(name => {
            this.form[name] = this.setform[name]
        }) 
    }
}
</script>
