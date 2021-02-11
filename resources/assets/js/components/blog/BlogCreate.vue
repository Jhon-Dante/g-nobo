<template id="template-blog-create">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Crear Blog</h1>
            </div>
        </div>
    <!-- <div class="col s12"> -->
        <div class="row">
            <div class="col s12">
                <a :href="url+'admin/blogs'" class="btn btn-back">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label for=""> Volver</label>
                    </div>
                </a>
            </div>
        </div>
        <card-main class="row">
            <card-content>
                <form action="" @submit.prevent="_store">
                    <div class="col s6 center-align">
                        <label for="title" class="label-impegno">Título (Español)</label>
                        <input type="text" name="title" id="title" v-model="form.title" class="browser-default input-impegno">
                    </div>
                    <div class="col s6 center-align">
                        <label for="title" class="label-impegno">Título (Inglés)</label>
                        <input type="text" name="title_english" id="title_english" v-model="form.title_english" class="browser-default input-impegno">
                    </div>
                    <div class="col s6 center-align">
                        <label for="" class="label-impegno">Descripción (Español)</label>
                        <textarea type="text" name="description" id="description" v-model="form.description" class="browser-default textarea-wara"></textarea>
                    </div>
                    <div class="col s6 center-align">
                        <label for="" class="label-impegno">Descripción (Inglés)</label>
                        <textarea type="text" name="description_english" id="description_english" v-model="form.description_english" class="browser-default textarea-wara"></textarea>
                    </div>
                    <!-- <div class="col s6 center-align offset-m3 content-image">
                        <label for="" class="label-impegno">Subir Imágen</label>
                        <input-file :btn="false" image v-on:file="_setFile"></input-file>
                    </div> -->
                    <div id="test3" class="col s12">
                        <div class="row container-form">
                            <div class="row gallery__items">
                                <div class="col s12 container-btn-add">
                                    <button type="button" class="btn-add" @click="_addImage()">
                                        <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                                    </button>
                                    <div class="btn-add-text">
                                        Agregar imágenes
                                    </div>                            
                                </div>
                                <div class="col l4 m6 s6 items__file" :key="index" v-for="(file, index) in form.images" :id="`file-${file.id}`">
                                    <input-file :btn="false" :image="true" v-on:file="_setFile(index, $event)"></input-file>
                                    <button class="file__claer" @click="_sliceItem(file.id, index)"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 center-align content-image">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </card-content>
        </card-main>
    </section>
    <!-- </div> -->
</template>

<style lang="scss">

</style>


<script>
export default {
    template: "#template-blog-create",
    
    data() {
        return {
            form: {
                title: "",
                title_english: "",
                description: "",
                description_english: "",
                images: [],
            },
            url: urlBase
        }
    },
    methods: {
        // _setFile(file){
        //     this.form.image = file.file;
        // },
        _setFile(i, file) {
            if(file.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            if (i == null) {
                this.image = file.file
                this.form.main = file.file
            }else {
                this.form.images[i].file = file.file
                this.files = this.form.images
            }            
        },

        _addImage() {
            this.ids = this.form.images.length > 1 ? this.ids + 1 : this.ids
            this.form.images.push({file: "", id: this.ids})
            this.images = this.form.images
        },

        _sliceItem (id, i) {            
            if(id > 0){
                this.images = this.form.images.filter((el) => {
                    return (el.id != id)
                })
            } else {
                this.images.splice(i, 1)
            }
            let parent = document.querySelector(".gallery__items")
            let child = document.querySelector(`#file-${id}`)
            parent.removeChild(child)
        },

        _store(){
            let formData = new FormData(); 
            formData.append("title",this.form.title);
            formData.append("title_english",this.form.title_english);
            formData.append("description",this.form.description);
            formData.append("description_english", this.form.description_english);
            // formData.append("images",this.form.images);
            showLoading()

            this.form.images.forEach((image, index) => {
                formData.append("image"+index, image.file)
            });
            formData.append("count", this.form.images.length);
            axios.post("admin/blogs",formData, {
                onUploadProgress: function( progressEvent ) {
                    
                    let percent = document.querySelector('#percent')
                    let p = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                    if(p < 100) {
                        percent.innerHTML = `${p}%`
                    }
                }.bind(this)
            }).then(resp=>{
                quiLoading()
                swal({
                    title: '',
                    text: 'Se ha creado el blog con éxito',
                    timer: 2000,
                    showConfirmButton: false,
                    type: 'success'
                },()=>{
                    window.location = urlBase+"admin/blogs";
                });
            }).catch(err=>{
                quiLoading()
                let message = "Disculpe, ha ocurrido un error";
                if (err.response.status == 422){
                    message = err.response.data.error;
                }
                swal("", message, "error");
            }).then(all => {
                button.removeAttribute('disabled')
            })
        }
    },

    mounted(){
        this.form.images.push({file: "", id: 1});
    }
}
</script>

