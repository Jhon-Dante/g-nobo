<template id="template-blog-edit">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Editar Blog</h1>
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
                <form action="" @submit.prevent="_edit">
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
                                    <input-file :btn="false" :file="file.file !== '' ? `${url}img/blogs/${file.file}` : ''"  :image="true" v-on:file="_setFile(file.id, index, $event)" :disabled="file.disabled"></input-file>
                                    <button type="button" class="file__claer" @click="_sliceItem(file.id, index)" :disabled="file.disabled"></button>
                                    <div class="progress" :id="'progress-' + index">
                                        <div class="determinate" :style="`width: ${file.uploadPercentage}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 center-align content-image">
                        <button type="submit" class="btn btn-success" :disabled="sending">Actualizar</button>
                    </div>
                </form>
            </card-content>
        </card-main>
    </section>
</template>

<style>
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
    template: "#template-blog-edit",

    data(){
        return {
            form: {
                main: {
                    file: ""
                },
                title: "",
                title_english: "",
                description: "",
                description_english: "",
                images: [],
                image: ""
            },
            url: urlBase,
            elements: 0
        }
    },

    props: {
        'set-images': {
            type: Array,
            default() {
                return [];
            }
        },

        'set-form': {
            type: Object,
            default(){
                return {}
            }
        }
    },

    methods: {
        _setFile(id, i, file) {
            if(file.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            let progressElement = document.querySelector(`#progress-${i}`)
            progressElement.classList.add('progress-active')

            let formData = new FormData;
            formData.append('id', id);
            formData.append('file', file.file);
            formData.append('blog_id', this.form.id);
            axios.post("admin/blogs/update-image", formData, {
                onUploadProgress: function( progressEvent ) {
                    this.sending = true
                    this.form.images[i].uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                    this.form.images[i].disabled = true
                }.bind(this)
            })
            .then( resp => {
                this.sending = false
                this._quitProgress(progressElement, i)

                if (id == 0) {
                    this.form.images[i].id = resp.data.id
                    this.form.images[i].file = resp.data.file
                } 
            })
            .catch( err => {
                 this.sending = false
                let message  = "Disculpe, ha ocurrido un error";
                if (err.response.status == 422) {
                    message = err.response.data.error;
                }
                swal("", message, "error");
                this._quitProgress(progressElement, i)
            })
        },
          _quitProgress(progressElement, x) {
            progressElement.classList.remove('progress-active')
            setTimeout(() => {
                this.form.images[x].uploadPercentage = 0
                this.form.images[x].disabled = false
            }, 500)
        },
        _addImage() {
            let item = this.form.images.find(e => {
                return e.id == 0
            })

            if(item) {
                return
            }

            this.form.images.push({file: "", id: 0, uploadPercentage: 0,  disabled: false});
            this.images = this.form.images;
            this.elements += 1;

        },
        _edit(){
            
            let formData = new FormData;
            
            formData.append('title', this.form.title);
            formData.append('title_english', this.form.title_english);
            formData.append('description', this.form.description);
            formData.append('description_english', this.form.description_english);
            formData.append('_method', 'PUT');
            formData.append('id', this.form.id);
            
            axios.post("admin/blogs/"+this.form.id, formData)
                .then( resp => {
                        swal({
                            title: '',
                            text: 'Se ha actualizado el blog con éxito',
                            timer: 2000,
                            showConfirmButton: false,
                            type: 'success'
                        },
                        ()=>{
                            window.location = urlBase+"admin/blogs";
                        });
                    }
                ).catch( err => {
                        let message  = "Disculpe, ha ocurrido un error";
                        if (err.response.status == 422) {
                            message = err.response.data.error;
                        }
                        swal("", message, "error");
                    }
                )
        },
        _sliceItem (id, i) {
            let countImages = 0
            this.form.images.forEach(img => {
                if(img.id != 0 && !img.deleted_at) {
                    countImages++
                }
            })
            if(countImages == 1 && id != 0) {
                swal('', 'Debe existir al menos una imagen en el post', 'error')
                return
            }

            let parent = document.querySelector(".gallery__items");
            let child = document.querySelector(`#file-${id}`);
            this.form.images[i].disabled = true
            if (id != 0) {
                axios.post('admin/blogs/delete-image', {id: id})
                .then(resp => {

                    parent.removeChild(child);
                    console.log(i)
                    this.form.images[i].deleted_at = true
                })
                .catch(err => {
                    let message = "Disculpe, ha ocurrido un error";
                    if (err.response.status == 422){
                        message = err.response.data.error;
                    }
                    this.form.images[i].disabled = false

                    swal("", message, "error");
                })
            } else {
                this.form.images[i].deleted_at = true
                parent.removeChild(child)
            }
        },

    },
    
    mounted() {
        this.form.title = this.setForm.title;
        this.form.title_english = this.setForm.title_english;
        this.form.description = this.setForm.description;
        this.form.description_english = this.setForm.description_english;
        this.form.id = this.setForm.id;
        this.setImages.forEach((img)=>{
            Vue.set(img, 'uploadPercentage', 0) 
            Vue.set(img, 'disabled', false) 
            this.form.images.push(img);
        })
    }
}
</script>

