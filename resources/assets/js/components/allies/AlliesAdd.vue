<template id="template-allies-add">
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
                        <label for="name" class="label-impegno">Nombre (Español)</label>
                        <input type="text" name="name" id="name" v-model="form.name" class="browser-default input-impegno">
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
                    <div class="col s12 m12 l12 center-align">
                        <label for="address" class="label-impegno">Descripción</label>
                        <textarea name="address" id="address" v-model="form.address" class="browser-default input-impegno"></textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class="row gallery__items">
                        <div class="col s12 container-btn-add">
                            <button type="button" class="btn-add" @click="_addItem()">
                                <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar imágenes
                            </div>                            
                        </div>
                        <div class="col l4 m6 s6 items__file" :key="index" v-for="(file, index) in form.files" :id="`file-${file.id}`">
                            <input-file :btn="false" :image="true" v-on:file="_setFile(index, $event)"></input-file>
                            <button class="file__claer" @click="_sliceItem(file.id, index)"></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 center-align">
                        <a href="#!" class="btn btn-success" @click="_store($event)">Guardar</a>
                    </div>
                </div>
            </card-content>
        </card-main>
    </div>
</template>

<style lang="scss">
    textarea{
        resize: none; 
        height: 10rem !important;
    }
    .items__file{
        position: relative;
    }
</style>

<script>
export default {
    template: "#template-allies-add",

    data () {
        return {
            form: {
                name: "",
                facebook: "",
                twitter: "",
                instagram: "",
                address: "",
                files: []
            },
        }
    },

    methods: {
        _back() {
            window.location  = urlBase + "admin/allies";
        },
        
        _setFile (i, file) {
            if(file.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            if (i == null) {
                this.file = file.file
                this.form.main = file.file
            }else {
                this.form.files[i].file = file.file
                this.files = this.form.files
            }
        },

        _addItem() {
            this.ids = this.form.files.length > 1 ? this.ids + 1 : this.ids
            this.form.files.push({file: "",  id: this.ids})
            this.files = this.form.files
        },

        _sliceItem(id, i) {            
           this.files = this.form.files.filter((el) => {
                return (el.id != id)
            })
            let parent = document.querySelector(".gallery__items")
            let child = document.querySelector(`#file-${id}`)            
            parent.removeChild(child)
        },

        _store(e) {
            let button = e.target;
            if (this.form.files.length == 0) {
                this._showAlert("Disculpa debes cargar al menos una archivo", "error");
                return false;
            }
            button.setAttribute('disabled', true)
            
            let formData = new FormData();
            formData.append("name",this.form.name);
            formData.append("facebook",this.form.facebook);
            formData.append("twitter",this.form.twitter);
            formData.append("instagram", this.form.instagram);
            formData.append("address", this.form.address);

            let count = 0
            this.form.files.forEach((file, index) => {
                if(file.file != '') {
                    formData.append("image"+index, file.file);
                    count++
                }
            });
            showLoading()

            formData.append("count", count);
            axios.post('admin/allies', formData, {
                onUploadProgress: function( progressEvent ) {
                    
                    let percent = document.querySelector('#percent')
                    let p = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                    if(p < 100) {
                        percent.innerHTML = `${p}%`
                    }
                }.bind(this)
            })
            .then( resp => {
                quiLoading()

                this._showAlert("Aliado almacenado exitosamente", "success");
                setTimeout(() => {
                    location.reload()
                }, 500)
            })
            .catch( err => {
                quiLoading()

                let message = "Disculpe, ha ocurrido un error";
                if(err.response.status == 422){
                    message = err.response.data.error;
                }
                this._showAlert(message, 'error');
            })
            .then( all => {
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
        this.ids = this.form.files.length;
        this.form.files.push({file: "", id: 1});
    }
}
</script>

