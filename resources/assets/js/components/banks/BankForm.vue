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
                    <form action="" @submit.prevent="_store">
                        <div class="col s6 center-align">
                            <label for="name" class="label-impegno">Propietario de la cuenta</label>
                            <input type="text" name="name" id="name" maxlength="20" v-model="form.name" class="browser-default input-impegno">
                        </div>
                       <div class="col s6 center-align">
                            <label for="title" class="label-impegno">Banco</label>
                            <select name="" id="" v-model="form.bank_id" class="browser-default">
                                <option :value="c.id" v-for="(c, i) in banks" :key="i">{{ c.name }}</option>
                            </select>
                        </div>
                        <div class="col s6 center-align" v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Idenficación</label>
                            <input type="text" name="title" maxlength="20" v-model="form.identification"  class="browser-default input-impegno">
                        </div>
                        <div class="col s6 center-align" v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Número de cuenta</label>
                            <input type="number" name="title" v-model="form.number" class="browser-default input-impegno">
                        </div>
                        <div class="col s6 center-align" v-if="form.bank_id == 1">
                            <label for="title" class="label-impegno">Correo</label>
                            <input type="email" name="title" v-model="form.email" class="browser-default input-impegno">
                        </div>
                        <div class="col s6 center-align" v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Tipo de cuenta</label>
                            <select name="" id="" v-model="form.type" class="browser-default">
                                <option value="1">Corriente</option>
                                <option value="2">Ahorro</option>
                            </select>
                        </div>
                         <div class="col s6 center-align" v-if="form.bank_id != 1">
                            <label for="title" class="label-impegno">Teléfono</label>
                            <input type="text" name="title" maxlength="20" v-model="form.phone"  class="browser-default input-impegno">
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
                name: '',
                bank_id: '',
                number: '',
                type: 1,
                identification: '',
                phone: '',
                email: ''
            }
        }
    },
    methods: {
       _store () {
            if(this.form.bank_id != 1 && this.form.number.length < 20) {
                swal('', 'El número de cuenta no debe ser menor a 20 digitos', 'warning')
                return
            }
            this.sending = true
            axios.post('admin/banks', this.form)
                .then(res => {
                    swal('', 'Se registro la cuenta bancaria correctamente', 'success')
                    setTimeout(() => {
                        location.reload()
                    }, 900)
                })
                .catch(err => {
                    if (err.response.status == 422){
                        swal("", err.response.data.error, "error");
                        return
                    }
                    swal('', 'Algo ha ocurrido', 'error')
                }).finally(() => {
                    this.sending = false
                })
       }
    },
    mounted () {
      console.log('asd')
    }
}
</script>
