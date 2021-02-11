<template id="template-allies-update">
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
                <div class="row">
                    <div class="col s12 m6 l6 center-align">
                        <label for="nombre" class="label-impegno">Nombre (Español)</label>
                        <input type="text" name="nombre" id="nombre" v-model="form.name" class="browser-default input-impegno">
                    </div>
                    <div class="col s12 m6 l6 center-align">
                        <label for="facebook" class="label-impegno">Facebook</label>
                        <input type="text" name="facebook" id="facebook" v-model="form.facebook" class="browser-default input-impegno">
                    </div>
                    <div class="col s12 m6 l6 center-align">
                        <label for="twitter" class="label-impegno">Twitter</label>
                        <input type="text" name="twitter" id="twitter" v-model="form.twitter" class="browser-default input-impegno">
                    </div>
                    <div class="col s12 m6 l6 center-align">
                        <label for="instagram" class="label-impegno">Instagram</label>
                        <input type="text" name="instagram" id="instagram" v-model="form.instagram" class="browser-default input-impegno">
                    </div>
                    <div class="col s12 center-align">
                        <small>Formato de redes sociales: <code>https://www.facebook.com</code></small><br>
                    </div>
                    <div class="col s12 m6 l6 center-align">
                        <label for="direccion" class="label-impegno">Descripción</label>
                        <textarea name="direccion" id="direccion" v-model="form.address" class="browser-default input-impegno"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="row gallery__items">
                        <div class="col s12 container-btn-add">
                            <button class="btn-add" @click="_addItem()">
                                <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar imagen
                            </div>                            
                        </div>
                        <div class="col l4 m6 s6 items__file" :key="index" v-for="(file, index) in items" :id="`file-${file.id}`">
                            <input-file :btn="false" :file="file.file !== '' ? `${urlBase + 'img/aliados/' + file.file}` : ''" :image="true" v-on:file="_setFile(file.id, index, $event)" :disabled="file.disabled"></input-file>
                            <button class="file__claer" @click="_sliceItem(file.id, index)" v-if="items.length > 1" :disabled="file.disabled"></button>
                            <div class="progress" :id="'progress-' + index">
                                <div class="determinate" :style="`width: ${file.uploadPercentage}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 center-align">
                        <a href="#!" class="btn btn-success" @click="_edit($event)" :disabled="sending">Actualizar</a>
                    </div>                    
                </div> 
            </card-content>
        </card-main>
    </div>    
</template>

<style lang="scss">
    .progress {
        opacity: 0;
        transition: all ease-in-out 0.35s;
    }
    .progress-active {
        opacity: 1;
    }
</style>

<script>
export default {
    template: "#template-allies-update",

    props: {
        data: {
            type: Object,
            default: {}
        }
    },

    created () {
        this.form = this.data
        this.items = this.form.fotos
        this.form.files = this.items
        this.items.forEach(e => {
            Vue.set(e, 'uploadPercentage', 0)
            Vue.set(e, 'disabled', false)
        })
    },

    data () {
        return {
            form: {
                files: []
            },
            sending: false,
            items: [],
            files: [],
            file: '',
            ids: 0,
            elements: 0,
            urlBase: urlBase,
            config: { onUploadProgress: progressEvent => console.log(progressEvent) },
            uploadPercentage: 0,
        }
    },

    methods: {
        _back() {
            //this.$emit('back', 0)
            window.location = urlBase + "admin/allies";
        },

        _setFile(i, x, e) {
            if(e.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            let progressElement = document.querySelector(`#progress-${x}`)
            progressElement.classList.add('progress-active')
            let formData = new FormData()
            this.sending = true

            formData.append('id',  i)
            formData.append('file', e.file)
            formData.append('aliado_id', this.form.id)
            axios.post('admin/allies/update-image', formData, {
                onUploadProgress: function( progressEvent ) {
                    this.sending = true
                    this.items[x].disabled = true
                    this.items[x].uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                }.bind(this)
            })
            .then(resp => {
                this.sending = false
                this.items[x].id = resp.data.id
                this.items[x].file = resp.data.file
                progressElement.classList.remove('progress-active')
                this._quitProgress(progressElement, x)
               
            })
            .catch(err => {
                this._quitProgress(progressElement, x)
                this._showAlert("Disculpa, ha ocurrido un error", "error")
            })
        },
         _quitProgress(progressElement, x) {
            progressElement.classList.remove('progress-active')
            setTimeout(() => {
                this.items[x].uploadPercentage = 0
                this.items[x].disabled = false
            }, 500)
        },

        _addItem() {
             let item = this.form.files.find(e => {
                return e.id == 0
            })

            if(item) {
                return
            }

            this.items.push({file: "", id: 0, uploadPercentage: 0, disabled: false})
            this.form.files = this.items
            this.elements = this.elements + 1
        },

        _sliceItem (id, i) {
            let countImages = 0
            this.form.files[i].disabled = true
            this.form.files.forEach(img => {
                if(img.id != 0 && !img.deleted_at) {
                    countImages++
                }
            })
            if(countImages == 1 && id != 0) {
                swal('', 'Debe existir al menos una imagen en para el aliado', 'error')
                return
            }

            console.log(countImages, id)
            let parent = document.querySelector(".gallery__items")
            let child = document.querySelector(`#file-${id}`)            
            
            if (id != 0) {
                axios.post('admin/allies/delete-images', {id: id})
                .then(resp => {
                    parent.removeChild(child)
                    this.elements = this.elements - 1;
                    this.form.files[i].deleted_at = true
                })
                .catch(err => {
                    this.form.files[i].disabled = false
                    this._showAlert("Disculpa, ha ocurrido un error", "error")
                })
            } else {
                parent.removeChild(child);
                this.elements = this.elements - 1;
            }
        },
        
        _edit(e) {
            let button = e.target
            button.setAttribute('disabled', true)
            axios.put(`admin/allies/${this.form.id}`, this.form)
            .then(resp => {                
                this._showAlert("Aliado actualizado exitosamente", "success")
                 setTimeout(() => {
                    location.reload()
                }, 500)
            })
            .catch(err => {
                let message = "Disculpe, ha ocurrido un error";
                if(err.response.status == 422){
                    message = err.response.data.error;
                }
                this._showAlert(message, 'error'); 

            })
            .then(all => {
                button.removeAttribute('disabled')
            })
        },

        _showAlert(text, type) {
            swal({
                title: "",
                text: text,
                timer: 2000,
                showConfirmButton: false,
                type: type
            })
        },
    },

    mounted() {        
        this.ids = this.items[this.items.length - 1].id
       
        setTimeout(() => {
            this.elements = Array.from(document.querySelectorAll('.items__file')).length
        }, 150);
    }
}
</script>
